<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sucursal;
use App\Models\HorarioRappi;

class RappiController extends Controller
{
    /**
     * Mostrar la vista principal agrupada por sucursal
     */
    public function index(Request $request)
    {
        $query = Sucursal::with(['horariosRappi' => function ($q) {
            $q->orderBy('marca')->orderBy('apertura');
        }])->orderBy('nombre');

        // Switch activado → mostrar solo sucursales con horarios
        if ($request->has('with')) {
            $query->whereHas('horariosRappi');
        }

        $sucursales = $query->get();

        return view('horarios.rappi.index', compact('sucursales'));
    }



    /**
     * Crear un horario para una sucursal específica
     */
    public function store(Request $request, $sucursal_id)
    {
        $request->validate([
            'marca'     => 'required|string',
            'apertura'  => 'required',
            'cierre'    => 'required',
        ]);

        HorarioRappi::create([
            'sucursal_id' => $sucursal_id,
            'marca'       => $request->marca,
            'apertura'    => $request->apertura,
            'cierre'      => $request->cierre,

            'lunes'      => $request->has('lunes'),
            'martes'     => $request->has('martes'),
            'miercoles'  => $request->has('miercoles'),
            'jueves'     => $request->has('jueves'),
            'viernes'    => $request->has('viernes'),
            'sabado'     => $request->has('sabado'),
            'domingo'    => $request->has('domingo'),
        ]);

        return back()->with('success', 'Horario creado correctamente.');
    }


    /**
     * Actualizar un horario existente
     */
    public function update(Request $request, $id)
    {
        $horario = HorarioRappi::findOrFail($id);

        $request->validate([
            'marca'     => 'required|string',
            'apertura'  => 'required',
            'cierre'    => 'required',
        ]);

        $horario->update([
            'marca'       => $request->marca,
            'apertura'    => $request->apertura,
            'cierre'      => $request->cierre,

            'lunes'      => $request->has('lunes'),
            'martes'     => $request->has('martes'),
            'miercoles'  => $request->has('miercoles'),
            'jueves'     => $request->has('jueves'),
            'viernes'    => $request->has('viernes'),
            'sabado'     => $request->has('sabado'),
            'domingo'    => $request->has('domingo'),
        ]);

        return back()->with('success', 'Horario actualizado correctamente.');
    }


    /**
     * Eliminar un horario
     */
    public function destroy($id)
    {
        $horario = HorarioRappi::findOrFail($id);
        $horario->delete();

        return back()->with('success', 'Horario eliminado correctamente.');
    }
}
