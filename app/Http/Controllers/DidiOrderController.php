<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\DidiOrdersImport;
use App\Exports\DidiOrdersTemplateExport;
use App\Models\DidiOrder;
use Maatwebsite\Excel\Facades\Excel;

class DidiOrderController extends Controller
{
    public function index()
    {
        $orders = DidiOrder::orderBy('billing_time', 'desc')->get();
        return view('ventas.didi-graficos', compact('orders'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new DidiOrdersImport, $request->file('file'));

        return back()->with('success', 'Archivo importado correctamente');
    }

    public function downloadTemplate()
{
    return Excel::download(new DidiOrdersTemplateExport, 'didi_import_template.xlsx');
}
}
