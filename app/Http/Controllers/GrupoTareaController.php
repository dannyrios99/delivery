<?php

namespace App\Http\Controllers;

use App\Models\GrupoTarea;
use Illuminate\Http\Request;

class GrupoTareaController extends Controller
{
    // Crear grupo de tareas (columna)
    public function store(Request $request)
    {
        try {
            $request->validate([
                'proyecto_id' => 'required|exists:proyectos,id',
                'nombre' => 'required|string|max:100',
            ]);

            GrupoTarea::create([
                'proyecto_id' => $request->proyecto_id,
                'nombre' => $request->nombre,
                'orden' => 0
            ]);

            return redirect()->back()
                ->with('success', 'Grupo creado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el grupo');
        }
    }

    // (Opcional) eliminar grupo
    public function destroy(GrupoTarea $grupo)
    {
        try {
            $grupo->delete();

            return redirect()->back()
                ->with('success', 'Grupo eliminado');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No se pudo eliminar el grupo');
        }
    }
}
