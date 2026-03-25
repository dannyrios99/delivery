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

    // Añadimos Request $request
    public function show(Request $request, Proyecto $proyecto)
    {
        $usuarios = User::all();

        // Filtro para las tareas dentro de los grupos
        $proyecto->load([
            'grupos.tareas' => function ($query) use ($request) {
                // SI el usuario quiere ver solo sus tareas:
                $query->where('archivada', false)
                      ->orderBy('updated_at', 'desc');
                      
                $query->when($request->ver == 'mis-tareas', function ($q) {
                    $q->whereHas('responsables', function ($r) {
                        $r->where('users.id', auth()->id());
                    });
                })
                // Cargamos las relaciones de la tarea de todos modos
                ->with(['responsables', 'checklist', 'comentarios.user']);
            }
        ]);

        // Opcional: Si usas la variable $tareas suelta en la vista, también la filtramos
        $tareas = $proyecto->tareas()
            ->where('archivada', false)
            ->when($request->ver == 'mis-tareas', function ($query) {
                $query->whereHas('responsables', function ($q) {
                    $q->where('users.id', auth()->id());
                });
            })
            ->with(['responsables', 'checklist', 'comentarios.user'])
            ->get();

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

    public function historial(Proyecto $proyecto)
    {
        $tareas = $proyecto->tareas()
            ->where('archivada', true)
            ->with(['grupo', 'responsables'])
            ->get();

        return view('proyectos.historial', compact('proyecto', 'tareas'));
    }
}
