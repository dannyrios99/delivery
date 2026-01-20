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
    public function index()
    {
        $tareas = Tarea::with(['proyecto'])->get();
        return view('tareas.index', compact('tareas'));
    }

    public function create()
    {
        $proyectos = Proyecto::all();


        return view('tareas.create', compact('proyectos', 'usuarios'));
    }

    public function store(Request $request)
    {
        try {
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

            // 3️⃣ Guardar checklist
            if ($request->has('checklist')) {
                foreach ($request->checklist as $index => $item) {
                    if (empty($item['texto'])) continue;

                    TareaChecklist::create([
                        'tarea_id'   => $tarea->id,
                        'texto'      => $item['texto'],
                        'completado' => ($item['completado'] ?? 0) == "1" ? 1 : 0,
                        'orden'      => $index,
                    ]);
                }
            }

            DB::commit();

            // 🔄 CARGAR responsables (CLAVE)
            $tarea->load('responsables');

            // 🔔 Evento emails
            if ($tarea->responsables->isNotEmpty()) {
                event(new TareaAsignada($tarea, $tarea->responsables));
            }

            // 📅 Google Calendar
            foreach ($tarea->responsables as $user) {
                if ($user && $user->google_refresh_token) {
                    SyncTaskWithGoogleCalendar::dispatch($tarea->id, $user->id);
                }
            }

            return redirect()->back()
                ->with('success', 'Tarea creada correctamente');

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error al crear tarea', [
                'error'   => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'No se pudo crear la tarea');
        }
    }

    public function show(Tarea $tarea)
    {
        // Cargamos los comentarios y el usuario que comentó
        $tarea->load(['proyecto', 'responsables', 'comentarios.user', 'checklist']);
        return view('tareas.show', compact('tarea'));
    }

    public function edit(Tarea $tarea)
    {
        $proyectos = Proyecto::all();
        $usuarios = User::all();

        return view('tareas.edit', compact('tarea', 'proyectos', 'usuarios'));
    }

    public function update(Request $request, Tarea $tarea)
    {
        try {
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
                'fecha_limite' => $request->filled('fecha_limite')
                    ? $request->fecha_limite
                    : $tarea->fecha_limite,
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

            // 🔹 3. Detectar cambios
            $removedUsers = array_diff($oldResponsables, $newResponsables);
            $addedUsers   = array_diff($newResponsables, $oldResponsables);

            Log::info('CAMBIOS RESPONSABLES', [
                'removed' => $removedUsers,
                'added' => $addedUsers,
            ]);

            // 4️⃣ BORRAR eventos (responsables quitados)
            foreach ($removedUsers as $userId) {
                Log::info('DISPATCH DELETE EVENT', [
                    'tarea_id' => $tarea->id,
                    'user_id' => $userId,
                ]);

                DeleteGoogleCalendarEvent::dispatch($tarea->id, $userId);
            }

            // 5️⃣ CREAR eventos (responsables nuevos)
            foreach ($addedUsers as $userId) {
                Log::info('DISPATCH CREATE EVENT', [
                    'tarea_id' => $tarea->id,
                    'user_id' => $userId,
                ]);

                SyncTaskWithGoogleCalendar::dispatch($tarea->id, $userId);
            }

            // 🔄 ACTUALIZAR eventos para TODOS los responsables actuales
            $actualResponsables = $tarea->responsables()->pluck('users.id')->toArray();

            Log::info('RESPONSABLES PARA UPDATE GOOGLE', [
                'actual_responsables' => $actualResponsables,
            ]);

            foreach ($actualResponsables as $userId) {
                Log::info('DISPATCH UPDATE EVENT', [
                    'tarea_id' => $tarea->id,
                    'user_id' => $userId,
                ]);

                UpdateGoogleCalendarEvent::dispatch($tarea->id, $userId);
            }

            // 7️⃣ Checklist (tu código original)
            if ($request->has('checklist')) {
                Log::info('CHECKLIST RECIBIDO', [
                    'checklist' => $request->checklist,
                ]);

                $idsRecibidos = collect($request->checklist)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                TareaChecklist::where('tarea_id', $tarea->id)
                    ->whereNotIn('id', $idsRecibidos)
                    ->delete();

                foreach ($request->checklist as $orden => $item) {
                    if (empty($item['texto'])) continue;

                    if (!empty($item['id'])) {
                        TareaChecklist::where('id', $item['id'])->update([
                            'texto' => $item['texto'],
                            'completado' => ($item['completado'] == "1") ? 1 : 0,
                            'orden' => $orden,
                        ]);
                    } else {
                        TareaChecklist::create([
                            'tarea_id' => $tarea->id,
                            'texto' => $item['texto'],
                            'completado' => ($item['completado'] == "1") ? 1 : 0,
                            'orden' => $orden,
                        ]);
                    }
                }
            }

            DB::commit();

            Log::info('UPDATE FINALIZADO OK', [
                'tarea_id' => $tarea->id,
            ]);

            return redirect()->back()
                ->with('success', 'Tarea actualizada correctamente');

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('ERROR UPDATE TAREA', [
                'tarea_id' => $tarea->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'No se pudo actualizar la tarea');
        }
    }

    public function storeComentario(Request $request)
    {
        $request->validate([
            'tarea_id' => 'required|exists:tareas,id',
            'contenido' => 'required|string',
        ]);

        $comentario = ComentarioTarea::create([
            'tarea_id' => $request->tarea_id,
            'user_id' => auth()->id(),
            'contenido' => $request->contenido,
        ]);

        // Devolvemos el comentario con la relación del usuario para mostrar el nombre
        return response()->json([
            'success' => true,
            'nombre' => auth()->user()->name,
            'contenido' => $comentario->contenido,
            'fecha' => $comentario->created_at->diffForHumans()
        ]);
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

            $tarea->update([
                'grupo_id' => $request->grupo_id
            ]);

            return redirect()->back()
                ->with('success', 'Tarea movida correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No se pudo mover la tarea');
        }
    }

}
