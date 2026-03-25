<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Proyecto;
use App\Models\User;
use App\Models\ComentarioTarea;
use Illuminate\Http\Request;
use App\Models\TareaChecklist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\TareaAsignada;
use App\Jobs\SyncTaskWithGoogleCalendar;
use App\Jobs\DeleteGoogleCalendarEvent;
use App\Jobs\UpdateGoogleCalendarEvent;

class TareaController extends Controller
{
    public function index(Request $request) // <-- Asegúrate de inyectar Request
    {
        $tareas = Tarea::with(['proyecto'])
            // Si en la URL viene ?ver=mis-tareas, filtramos por el usuario autenticado
            ->when($request->ver == 'mis-tareas', function ($query) {
                return $query->whereHas('responsables', function ($q) {
                    $q->where('users.id', auth()->id());
                });
            })
            ->get();

        return view('tareas.index', compact('tareas'));
    }

    public function show(Tarea $tarea)
    {
        // Añadimos 'comentarios.archivos' a la carga
        $tarea->load([
            'proyecto', 
            'responsables', 
            'comentarios.user', 
            'comentarios.archivos', // <--- ¡ESTA ES LA CLAVE!
            'checklist'
        ]);

        // Si tu modal se abre desde la misma página (index), 
        // asegúrate de que esto devuelva JSON si es una petición AJAX.
        if (request()->ajax()) {
            return response()->json($tarea);
        }

        return view('tareas.show', compact('tarea'));
    }

    public function create()
    {
        $proyectos = Proyecto::all();


        return view('tareas.create', compact('proyectos', 'usuarios'));
    }

    public function store(Request $request)
    {
        try {
            $request->merge([
                'fecha_limite' => $request->fecha_limite ?: null,
            ]);

            $request->validate([
                'titulo' => 'required|string|max:255',
                'proyecto_id' => 'required|exists:proyectos,id',
                'grupo_id' => 'required|exists:grupos_tareas,id',
                'descripcion' => 'nullable|string',
                'prioridad' => 'nullable|in:baja,media,alta',
                'fecha_limite' => 'nullable|date',
                'responsables' => 'nullable|array',
                'responsables.*' => 'exists:users,id',
                'checklist' => 'nullable|array',
                'checklist.*.texto' => 'required|string|max:255',
                'checklist.*.completado' => 'nullable|boolean',
                'checklist.*.archivo' => 'nullable|file|max:10240', // Validamos el archivo del checklist
            ]);

            DB::beginTransaction();

            // 1️⃣ Crear la tarea
            $tarea = Tarea::create([
                'titulo'        => $request->titulo,
                'descripcion'   => $request->descripcion,
                'prioridad'     => $request->prioridad,
                'fecha_limite'  => $request->fecha_limite,
                'proyecto_id'   => $request->proyecto_id,
                'grupo_id'      => $request->grupo_id,
            ]);

            // 2️⃣ Guardar responsables
            if ($request->filled('responsables')) {
                $tarea->responsables()->sync($request->responsables);
            }

            // 3️⃣ Guardar checklist y sus archivos
            if ($request->has('checklist')) {
                foreach ($request->checklist as $index => $item) {
                    if (empty($item['texto'])) continue;

                    $checkItem = TareaChecklist::create([
                        'tarea_id'   => $tarea->id,
                        'texto'      => $item['texto'],
                        'completado' => ($item['completado'] ?? 0) == "1" ? 1 : 0,
                        'orden'      => $index,
                    ]);

                    // 📎 Guardar archivo del item si existe
                    if ($request->hasFile("checklist.{$index}.archivo")) {
                        $file = $request->file("checklist.{$index}.archivo");
                        $nombreOriginal = $file->getClientOriginalName();
                        $nombreFinal = time() . '_check_' . $nombreOriginal;
                        $file->move(public_path('comentarios'), $nombreFinal);

                        $checkItem->archivos()->create([
                            'nombre_original' => $nombreOriginal,
                            'ruta' => 'comentarios/' . $nombreFinal,
                            'mime' => $file->getClientMimeType(),
                            'size' => $file->getSize(),
                        ]);
                    }
                }
            }

            DB::commit();

            // Cargas y Jobs...
            $tarea->load('responsables');
            if ($tarea->responsables->isNotEmpty()) {
                event(new TareaAsignada($tarea, $tarea->responsables));
            }
            foreach ($tarea->responsables as $user) {
                if ($user && $user->google_refresh_token) {
                    SyncTaskWithGoogleCalendar::dispatch($tarea->id, $user->id);
                }
            }

            return redirect()->back()->with('success', 'Tarea creada correctamente');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al crear tarea', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'No se pudo crear la tarea');
        }
    }

    public function update(Request $request, Tarea $tarea)
    {
        try {
            $request->merge([
                'fecha_limite' => $request->fecha_limite ?: null,
            ]);
            
            Log::info('UPDATE INICIO', [
                'tarea_id' => $tarea->id,
                'request_responsables' => $request->input('responsables'),
            ]);

            DB::beginTransaction();

            // 🔹 0. Responsables ANTES del cambio
            $oldResponsables = $tarea->responsables->pluck('id')->toArray();

            Log::info('RESPONSABLES ANTES', [
                'old_responsables' => $oldResponsables,
            ]);

            // 1️⃣ Actualizar datos básicos
            $tarea->update([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'prioridad' => $request->prioridad,
                'fecha_limite' => $request->fecha_limite, 
            ]);

            Log::info('TAREA ACTUALIZADA', [
                'titulo' => $tarea->titulo,
                'fecha_limite' => $tarea->fecha_limite,
            ]);

            // 2️⃣ Actualizar responsables
            $newResponsables = $request->input('responsables', []);
            $tarea->responsables()->sync($newResponsables);

            Log::info('RESPONSABLES DESPUÉS SYNC', [
                'new_responsables_request' => $newResponsables,
                'responsables_en_bd' => $tarea->responsables()->pluck('users.id')->toArray(),
            ]);

            // 🔹 3. Detectar cambios para Google Calendar
            $removedUsers = array_diff($oldResponsables, $newResponsables);
            $addedUsers   = array_diff($newResponsables, $oldResponsables);

            Log::info('CAMBIOS RESPONSABLES', [
                'removed' => $removedUsers,
                'added' => $addedUsers,
            ]);

            // 4️⃣ BORRAR eventos (Google Calendar)
            foreach ($removedUsers as $userId) {
                Log::info('DISPATCH DELETE EVENT', ['tarea_id' => $tarea->id, 'user_id' => $userId]);
                DeleteGoogleCalendarEvent::dispatch($tarea->id, $userId);
            }

            // 5️⃣ CREAR eventos (Google Calendar)
            foreach ($addedUsers as $userId) {
                Log::info('DISPATCH CREATE EVENT', ['tarea_id' => $tarea->id, 'user_id' => $userId]);
                SyncTaskWithGoogleCalendar::dispatch($tarea->id, $userId);
            }

            // 🔄 ACTUALIZAR eventos para TODOS los responsables actuales
            $actualResponsables = $tarea->responsables()->pluck('users.id')->toArray();
            foreach ($actualResponsables as $userId) {
                Log::info('DISPATCH UPDATE EVENT', ['tarea_id' => $tarea->id, 'user_id' => $userId]);
                UpdateGoogleCalendarEvent::dispatch($tarea->id, $userId);
            }

            // 7️⃣ Checklist (Actualizado para procesar archivos polimórficos)
            if ($request->has('checklist')) {
                Log::info('CHECKLIST RECIBIDO', [
                    'checklist' => $request->checklist,
                ]);

                $idsRecibidos = collect($request->checklist)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                // Eliminar ítems que ya no están en el formulario
                TareaChecklist::where('tarea_id', $tarea->id)
                    ->whereNotIn('id', $idsRecibidos)
                    ->delete();

                foreach ($request->checklist as $orden => $item) {
                    if (empty($item['texto'])) continue;

                    // A. Crear o Actualizar el ítem
                    if (!empty($item['id'])) {
                        $checkItem = TareaChecklist::findOrFail($item['id']);
                        $checkItem->update([
                            'texto' => $item['texto'],
                            'completado' => ($item['completado'] == "1") ? 1 : 0,
                            'orden' => $orden,
                        ]);
                    } else {
                        $checkItem = TareaChecklist::create([
                            'tarea_id' => $tarea->id,
                            'texto' => $item['texto'],
                            'completado' => ($item['completado'] == "1") ? 1 : 0,
                            'orden' => $orden,
                        ]);
                    }

                    // B. Procesar ARCHIVO polimórfico si existe para este ítem
                    if ($request->hasFile("checklist.{$orden}.archivo")) {
                        $file = $request->file("checklist.{$orden}.archivo");
                        
                        // 1. Extraer datos ANTES de mover (Soluciona el error SplFileInfo::getSize)
                        $nombreOriginal = $file->getClientOriginalName();
                        $mime = $file->getClientMimeType();
                        $size = $file->getSize();
                        $nombreFinal = time() . '_check_' . $nombreOriginal;

                        // 2. Mover a public/comentarios
                        $file->move(public_path('comentarios'), $nombreFinal);

                        // 3. Crear relación polimórfica (Limpiamos el anterior si existe)
                        $checkItem->archivos()->delete(); 
                        $checkItem->archivos()->create([
                            'nombre_original' => $nombreOriginal,
                            'ruta' => 'comentarios/' . $nombreFinal,
                            'mime' => $mime,
                            'size' => $size,
                        ]);
                        
                        Log::info('ARCHIVO ADJUNTO A ITEM CHECKLIST', ['item_id' => $checkItem->id]);
                    }
                }
            }

            DB::commit();

            Log::info('UPDATE FINALIZADO OK', [
                'tarea_id' => $tarea->id,
            ]);

            return redirect()->back()->with('success', 'Tarea actualizada correctamente');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ERROR UPDATE TAREA', [
                'tarea_id' => $tarea->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'No se pudo actualizar la tarea');
        }
    }

    public function edit(Tarea $tarea)
    {
        $proyectos = Proyecto::all();
        $usuarios = User::all();

        return view('tareas.edit', compact('tarea', 'proyectos', 'usuarios'));
    }

    public function storeComentario(Request $request)
    {
        $request->validate([
            'tarea_id' => 'required|exists:tareas,id',
            'contenido' => 'nullable|string',
            'archivo' => 'nullable|file|max:10240',
        ]);

        try {
            $comentario = ComentarioTarea::create([
                'tarea_id' => $request->tarea_id,
                'user_id' => auth()->id(),
                'contenido' => $request->contenido,
            ]);

            $archivoData = null;

            if ($request->hasFile('archivo')) {
                $file = $request->file('archivo');

                // 1. EXTRAER datos antes de moverlo (IMPORTANTE)
                $nombreOriginal = $file->getClientOriginalName();
                $mime = $file->getClientMimeType(); // Usar getClientMimeType es más seguro aquí
                $size = $file->getSize();
                $nombreFinal = time() . '_' . $nombreOriginal;

                // 2. MOVER el archivo a public/comentarios
                $file->move(public_path('comentarios'), $nombreFinal);
                $rutaParaBD = 'comentarios/' . $nombreFinal;

                // 3. GUARDAR en la base de datos
                $archivo = $comentario->archivos()->create([
                    'nombre_original' => $nombreOriginal,
                    'ruta' => $rutaParaBD,
                    'mime' => $mime,
                    'size' => $size,
                ]);

                $archivoData = [
                    'nombre' => $archivo->nombre_original,
                    'url' => asset($rutaParaBD),
                ];
            }

            return response()->json([
                'success' => true,
                'id' => $comentario->id,
                'nombre' => auth()->user()->name,
                'contenido' => $comentario->contenido,
                'archivo' => $archivoData,
            ]);

        } catch (\Exception $e) {
            // Esto te permitirá ver el error real en la consola de Network
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyComentario(ComentarioTarea $comentario)
    {
        // Seguridad: Solo el autor puede borrar su comentario
        if ($comentario->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $comentario->delete();

        return response()->json(['success' => true]);
    }

    public function destroy(Tarea $tarea)
    {
        try {
            // 1️⃣ Obtener responsables ANTES de borrar
            $responsables = $tarea->responsables()->pluck('users.id');

            // 2️⃣ Disparar Job para borrar eventos de Google
            foreach ($responsables as $userId) {
                DeleteGoogleCalendarEvent::dispatch($tarea->id, $userId);
            }

            // 3️⃣ Ahora sí, borrar la tarea
            $tarea->delete();

            return redirect()->back()
                ->with('success', 'Tarea eliminada correctamente');

        } catch (\Throwable $e) {

            Log::error('Error al eliminar tarea', [
                'tarea_id' => $tarea->id ?? null,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'No se pudo eliminar la tarea');
        }
    }

    // 🔥 Método extra: cambiar estado (Kanban / Ajax)
    public function cambiarEstado(Request $request, Tarea $tarea)
    {
        try {
            $request->validate([
                'estado' => 'required|in:pendiente,en_progreso,hecho'
            ]);

            $tarea->update([
                'estado' => $request->estado
            ]);

            return redirect()->back()
                ->with('success', 'Estado de la tarea actualizado');
        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', 'Ocurrió un error al actualizar el estado');
        }
    }

    public function mover(Request $request, Tarea $tarea)
    {
        try {
            $request->validate([
                'grupo_id' => 'required|exists:grupos_tareas,id'
            ]);

            $grupoDestino = \App\Models\GrupoTarea::find($request->grupo_id);
            
            // Detectar si el grupo destino es de finalización
            $esTerminado = $grupoDestino && in_array(strtolower(trim($grupoDestino->nombre)), ['terminado', 'hecho', 'completado', 'done']);

            $tarea->update([
                'grupo_id' => $request->grupo_id,
                // Si pasa a terminado, se archiva; si se devuelve, se desarchiva (opcional, pero de momento aseguremos el archivo)
                'archivada' => $esTerminado ? true : $tarea->archivada
            ]);

            $mensaje = $esTerminado 
                ? 'Tarea completada y archivada automáticamente' 
                : 'Tarea movida correctamente';

            return redirect()->back()->with('success', $mensaje);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo mover la tarea');
        }
    }

    public function archivar(Tarea $tarea)
    {
        try {
            $tarea->update(['archivada' => true]);
            return redirect()->back()->with('success', 'Tarea archivada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo archivar la tarea');
        }
    }

    public function restaurar(Tarea $tarea)
    {
        try {
            $tarea->update(['archivada' => false]);
            return redirect()->back()->with('success', 'Tarea restaurada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo restaurar la tarea');
        }
    }
}