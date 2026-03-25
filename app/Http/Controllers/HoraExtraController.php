<?php

namespace App\Http\Controllers;

use App\Models\HoraExtra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HoraExtraController extends Controller
{
    /**
     * Mostrar listado de horas extras
     */
    public function index()
    {
        $horasExtras = HoraExtra::orderBy('fecha', 'desc')->get();

        return view('horas_extras.index', compact('horasExtras'));
    }

    /**
     * Mostrar formulario para crear hora extra
     */
    public function create()
    {
        return view('horas_extras.create');
    }

    public function show(HoraExtra $horaExtra)
{
    // Cargar actividades de soporte
    $horaExtra->load('actividadesSoporte');

    return view('horas_extras.show', compact('horaExtra'));
}
    /**
     * Guardar hora extra
     */

public function update(Request $request, HoraExtra $horaExtra)
{
    $request->validate([
        'fecha' => 'required|date',
        'hora_inicio' => 'required',
        'hora_fin' => 'required',
        'observacion' => 'nullable|string'
    ]);

    $inicio = strtotime($request->hora_inicio);
    $fin = strtotime($request->hora_fin);

    if ($fin < $inicio) {
        $fin += 86400;
    }

    $totalHoras = round(($fin - $inicio) / 3600, 2);

    $horaExtra->update([
        'fecha' => $request->fecha,
        'hora_inicio' => $request->hora_inicio,
        'hora_fin' => $request->hora_fin,
        'total_horas' => $totalHoras,
        'area' => $request->area,
        'observacion' => $request->observacion
    ]);

    return redirect()->route('horas-extras.index')
        ->with('success', 'Hora extra actualizada correctamente');
}

}
