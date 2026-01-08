<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Http\Request;

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
        $request->validate([
            'titulo' => 'required|string|max:255',
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);

        Tarea::create([
            'titulo' => $request->titulo,
            'proyecto_id' => $request->proyecto_id,
            'estado' => 'pendiente',
        ]);

        return redirect()->back()
            ->with('success', 'Tarea creada correctamente');
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
            'estado' => 'required|in:pendiente,en_progreso,hecho',
            'prioridad' => 'required|in:baja,media,alta',
            'fecha_limite' => 'nullable|date'
        ]);

        $tarea->update($request->all());

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea actualizada');
    }

    public function destroy(Tarea $tarea)
    {
        $tarea->delete();

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea eliminada');
    }

    // 🔥 Método extra: cambiar estado (Kanban / Ajax)
    public function cambiarEstado(Request $request, Tarea $tarea)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_progreso,hecho'
        ]);

        $tarea->update(['estado' => $request->estado]);

        return response()->json(['success' => true]);
    }
}
