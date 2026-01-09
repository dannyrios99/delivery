<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Proyecto;
use App\Models\User;
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

                // checklist
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

            // 2️⃣ Guardar checklist (si existe)
            if ($request->has('checklist')) {
                foreach ($request->checklist as $index => $item) {
                    TareaChecklist::create([
                        'tarea_id' => $tarea->id,
                        'texto' => $item['texto'],
                        'completado' => $item['completado'] ?? 0,
                        'orden' => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Tarea creada correctamente');

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Error al crear tarea con checklist', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'No se pudo crear la tarea');
        }
    }

    public function show(Tarea $tarea)
    {
        $tarea->load(['proyecto', 'usuario']);
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
    $request->validate([
        'titulo' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'prioridad' => 'nullable|in:baja,media,alta',
        'fecha_limite' => 'nullable|date',
    ]);

    $tarea->update([
        'titulo' => $request->titulo,
        'descripcion' => $request->descripcion,
        'prioridad' => $request->prioridad,
        'fecha_limite' => $request->fecha_limite,
    ]);

    return redirect()->back()
        ->with('success', 'Tarea actualizada correctamente');
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
