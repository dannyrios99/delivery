<?php

namespace App\Http\Controllers;

use App\Models\HoraExtra;
use App\Models\ActividadSoporte;
use Illuminate\Http\Request;

class ActividadSoporteController extends Controller
{
    /**
     * Guardar actividad de soporte asociada a una hora extra
     */
    public function store(Request $request, HoraExtra $horaExtra)
    {
        $request->validate([
            'tipo_soporte' => 'required|string|max:100',
            'descripcion'  => 'required|string',
            'sistema'      => 'required|string|max:100'
        ]);

        ActividadSoporte::create([
            'hora_extra_id' => $horaExtra->id,
            'tipo_soporte'  => $request->tipo_soporte,
            'descripcion'   => $request->descripcion,
            'sistema'       => $request->sistema
        ]);

        return back()->with('success', 'Actividad de soporte registrada correctamente');
    }
}
