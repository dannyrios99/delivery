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

                // Responsables (NUEVO)
                'responsables' => 'nullable|array',
                'responsables.*' => 'exists:users,id',

                // Checklist
                'checklist' => 'nullable|array',
                'checklist.*.texto' => 'required|string|max:255',
                'checklist.*.completado' => 'nullable|boolean',
            ]);

            DB::beginTransaction();

            // 1️⃣ Crear la tarea
            $tarea = Tarea::create([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'prioridad' => $request->prioridad,
                'fecha_limite' => $request->fecha_limite,
                'proyecto_id' => $request->proyecto_id,
                'grupo_id' => $request->grupo_id,
            ]);

            // 2️⃣ Guardar Responsables (NUEVO)
            if ($request->has('responsables')) {
                // sync() inserta los IDs en la tabla pivote tarea_user automáticamente
                $tarea->responsables()->sync($request->responsables);
            }

            // 3️⃣ Guardar checklist (si existe)
            if ($request->has('checklist')) {
                foreach ($request->checklist as $index => $item) {
                    if (empty($item['texto'])) continue; // Evitamos basura en la BD

                    TareaChecklist::create([
                        'tarea_id' => $tarea->id,
                        'texto' => $item['texto'],
                        'completado' => ($item['completado'] == "1") ? 1 : 0, // <--- CAMBIO AQUÍ
                        'orden' => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Tarea creada correctamente');

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error al crear tarea', [
                'error' => $e->getMessage(),
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
            DB::beginTransaction();

            // 1. Actualizar datos básicos
            $tarea->update([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'prioridad' => $request->prioridad,
                'fecha_limite' => $request->fecha_limite,
            ]);

            // 2. ACTUALIZAR RESPONSABLES (Añade esta parte)
            // sync() se encarga de borrar los que quitaste y agregar los nuevos en 'tarea_user'
            $responsables = $request->input('responsables', []); // Si viene vacío, limpia la tabla
            $tarea->responsables()->sync($responsables);

            // 3. Procesar checklist (Tu código original)
            if ($request->has('checklist')) {
                $idsRecibidos = collect($request->checklist)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                TareaChecklist::where('tarea_id', $tarea->id)
                    ->whereNotIn('id', $idsRecibidos)
                    ->delete();

                foreach ($request->checklist as $orden => $item) {
                    // REGLA DE ORO: Si no hay texto, ignoramos el ítem para que no de error
                    if (empty($item['texto'])) continue;

                    if (!empty($item['id'])) {
                        // ACTUALIZAR
                        TareaChecklist::where('id', $item['id'])->update([
                            'texto' => $item['texto'],
                            'completado' => ($item['completado'] == "1") ? 1 : 0, // <--- CAMBIO AQUÍ
                            'orden' => $orden,
                        ]);
                    } else {
                        // CREAR NUEVO
                        TareaChecklist::create([
                            'tarea_id' => $tarea->id,
                            'texto' => $item['texto'],
                            'completado' => ($item['completado'] == "1") ? 1 : 0, // <--- CAMBIO AQUÍ
                            'orden' => $orden,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Tarea actualizada correctamente');

        } catch (\Throwable $e) {
            DB::rollBack();
            // Log::error($e->getMessage()); // Útil para debugear si algo falla
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
