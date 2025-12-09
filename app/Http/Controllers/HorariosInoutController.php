<?php

namespace App\Http\Controllers;

use App\Models\HorarioInOut;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class HorariosInOutController extends Controller
{
    public function index(Request $request)
    {
        $query = Sucursal::with(['horariosInout' => function ($q) {
            $q->orderBy('mapa')->orderBy('apertura');
        }])->orderBy('nombre');

        // Si el switch está activado, filtrar por sucursales con horarios
        if ($request->has('with')) {
            $query->whereHas('horariosInout');
        }

        $sucursales = $query->get();

        return view('horarios.inout.index', compact('sucursales'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'sucursal_id' => 'required|exists:sucursales,id',
            'mapa' => 'required|string|max:50',
            'apertura' => 'required',
            'cierre' => 'required',
        ]);

        HorarioInOut::create([
            'sucursal_id' => $request->sucursal_id,
            'mapa' => $request->mapa,
            'apertura' => $request->apertura,
            'cierre' => $request->cierre,
            'lunes' => $request->filled('lunes'),
            'martes' => $request->filled('martes'),
            'miercoles' => $request->filled('miercoles'),
            'jueves' => $request->filled('jueves'),
            'viernes' => $request->filled('viernes'),
            'sabado' => $request->filled('sabado'),
            'domingo' => $request->filled('domingo'),
            'festivo' => $request->filled('festivo'),
        ]);

        return redirect()->back()->with('success', 'Horario creado correctamente.');
    }


    public function edit($id)
    {
        $horario = HorarioInOut::findOrFail($id);
        return view('horarios.inout.edit', compact('horario'));
    }

    public function update(Request $request, $id)
    {
        $horario = HorarioInOut::findOrFail($id);

        $horario->update([
            'mapa' => $request->mapa,
            'apertura' => $request->apertura,
            'cierre' => $request->cierre,
            'lunes' => $request->lunes ? 1 : 0,
            'martes' => $request->martes ? 1 : 0,
            'miercoles' => $request->miercoles ? 1 : 0,
            'jueves' => $request->jueves ? 1 : 0,
            'viernes' => $request->viernes ? 1 : 0,
            'sabado' => $request->sabado ? 1 : 0,
            'domingo' => $request->domingo ? 1 : 0,
            'festivo' => $request->festivo ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Horario actualizado correctamente');
    }
}
