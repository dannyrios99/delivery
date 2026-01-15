<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    public function create()
    {
        return view('proyectos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Proyecto::create($request->only('nombre'));

        return redirect()->back()
            ->with('success', 'Proyecto creado correctamente');
    }


    public function show(Proyecto $proyecto)
    {
        $usuarios = User::all();

        // Añadimos 'grupos.tareas.comentarios.user'
        $proyecto->load([
            'grupos.tareas.responsables', 
            'grupos.tareas.checklist',
            'grupos.tareas.comentarios.user' // <--- NUEVO
        ]);
        
        // También lo añadimos aquí para la colección de tareas
        $tareas = $proyecto->tareas()->with([
            'responsables', 
            'checklist',
            'comentarios.user' // <--- NUEVO
        ])->get();

        return view('proyectos.show', compact('proyecto', 'tareas', 'usuarios'));
    }

    public function edit(Proyecto $proyecto)
    {
        return view('proyectos.edit', compact('proyecto'));
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $proyecto->update($request->only('nombre'));

        return redirect()->route('proyectos.index')
            ->with('success', 'Proyecto actualizado');
    }

    public function destroy(Proyecto $proyecto)
    {
        $proyecto->delete();

        return redirect()->route('proyectos.index')
            ->with('success', 'Proyecto eliminado');
    }
}
