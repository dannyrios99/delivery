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
            $validated = $request->validate([
                'proyecto_id' => 'required|exists:proyectos,id',
                'nombre'      => 'required|string|max:100',
            ]);

            $grupo = GrupoTarea::create([
                'proyecto_id' => $validated['proyecto_id'],
                'nombre'      => $validated['nombre'],
                'orden'       => 0
            ]);

            return response()->json([
                'success' => true,
                'grupo'   => [
                    'id'     => $grupo->id,
                    'nombre' => $grupo->nombre,
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'errors'  => $e->errors()
            ], 422);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al crear el grupo'
            ], 500);
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
    public function destroy($id)
    {
        try {
            $grupo = GrupoTarea::with('tareas')->findOrFail($id);

            // Si tienes cascada, esto puede ser solo delete()
            $grupo->delete();

            return response()->json([
                'success' => true
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false
            ], 500);
        }
    }


}
