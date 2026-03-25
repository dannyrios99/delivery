<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CompensacionesImport;
use App\Models\Compensacion;
use Illuminate\Support\Facades\Storage;


class CompensacionesController extends Controller
{

    public function index()
    {
        $compensaciones = Compensacion::orderBy('fecha', 'desc')->get();

        $totalMonto = Compensacion::sum('monto');

        return view('consolidados.compensaciones', [
            'compensaciones' => $compensaciones,
            'totalMonto'     => $totalMonto,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new CompensacionesImport, $request->file('archivo'));

        return back()->with('success', 'Compensaciones importadas correctamente.');
    }

    public function plantilla()
    {
        $path = storage_path('app/templates/plantilla_compensaciones_rappi.xlsx');

        return response()->download(
            $path,
            'plantilla_compensaciones_rappi.xlsx'
        );
    }
}
