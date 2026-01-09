<?php

namespace App\Http\Controllers;

use App\Models\GrupoTarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GrupoTareaController extends Controller
{
    // Crear grupo
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

    // Actualizar grupo
public function update(Request $request, GrupoTarea $grupos_tarea)
{
    try {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $grupos_tarea->nombre = $request->nombre;
        $grupos_tarea->save();

        return redirect()->back()
            ->with('success', 'Grupo actualizado correctamente');

    } catch (\Throwable $e) {

        // Log del error real (MUY IMPORTANTE)
        Log::error('Error al actualizar grupo de tareas', [
            'grupo_id' => $grupos_tarea->id ?? null,
            'request' => $request->all(),
            'error' => $e->getMessage(),
        ]);

        return redirect()->back()
            ->with('error', 'No se pudo actualizar el grupo');
    }
}
    // Eliminar grupo
public function destroy(GrupoTarea $grupos_tarea)
{
    try {
        $grupos_tarea->delete();

        return redirect()->back()
            ->with('success', 'Grupo eliminado');
    } catch (\Throwable $e) {
        return redirect()->back()
            ->with('error', 'No se pudo eliminar el grupo');
    }
}

}
