<?php

namespace App\Http\Controllers;

use App\Models\GastoArmi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ConsolidadoDomiciliosImport;

class GastosArmiController extends Controller
{
    /**
     * Vista principal - Gastos Armi
     */
    public function index()
    {
        $registros = GastoArmi::orderBy('sede')
            ->orderBy('rango_km')
            ->get();

        $totalMonto = $registros->sum('valor_final');

        return view('consolidados.gastos_armi', compact(
            'registros',
            'totalMonto'
        ));
    }

    /**
     * Importar Excel y generar Gastos Armi
     */
    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls',
        ]);

        $import = new ConsolidadoDomiciliosImport();
        $import->archivo = $request->file('archivo')->getClientOriginalName();

        Excel::import($import, $request->file('archivo'));

        return redirect()
            ->route('gastos-armi.index')
            ->with('success', 'Gastos Armi importados correctamente');
    }
}