<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\MapaEmbebido;
use Illuminate\Http\Request;

class MapaEmbebidoController extends Controller
{
    /**
     * Mostrar mapas embebidos por sucursal
     */
public function index()
{
    $sucursales = Sucursal::leftJoin(
            'mapas_embebidos',
            'sucursales.id',
            '=',
            'mapas_embebidos.sucursal_id'
        )
        ->select('sucursales.*')
        ->with('mapaEmbebido')
        ->orderByRaw('mapas_embebidos.id IS NULL') // 👈 clave
        ->orderBy('sucursales.nombre')
        ->get();

    return view('mapas.index', compact('sucursales'));
}


    /**
     * Mostrar mapa embebido de una sucursal específica
     */
    public function show($sucursalId)
    {
        $mapa = MapaEmbebido::where('sucursal_id', $sucursalId)
            ->with('sucursal')
            ->firstOrFail();

        return view('mapas.show', compact('mapa'));
    }

    public function store(Request $request)
{
    $request->validate([
        'sucursal_id'   => 'required|exists:sucursales,id',
        'google_map_id' => 'required|string'
    ]);

    MapaEmbebido::updateOrCreate(
        ['sucursal_id' => $request->sucursal_id],
        ['google_map_id' => $request->google_map_id]
    );

    return back()->with('success', 'Mapa asignado correctamente');
}
}
