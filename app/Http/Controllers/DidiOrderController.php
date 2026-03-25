<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\DidiOrdersImport;
use App\Exports\DidiOrdersTemplateExport;
use App\Models\DidiOrder;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DidiOrderController extends Controller
{
    public function index()
    {
        $orders = DidiOrder::orderBy('billing_time', 'desc')->get();
        return view('ventas.didi', compact('orders'));
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

    public function dashboard(Request $request)
    {
        // 📅 Fechas (último mes por defecto)
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->subMonth()->startOfDay();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        // Query base por rango seleccionado
        $query = DidiOrder::whereBetween('billing_time', [$startDate, $endDate]);

        // 🔢 Totales
        $earnings       = (clone $query)->sum('trip_earnings');
        $commissionRaw  = (clone $query)->sum('commission'); // NEGATIVO
        $commissionAbs  = abs($commissionRaw);
        $orders         = (clone $query)->count();

        // 💰 Total Ventas REAL
        $totalSalesReal = $earnings + $commissionAbs;

        // 📅 ÓRDENES por hora (ACUMULADO DEL RANGO)
        $dailySalesRaw = (clone $query)
            ->select(
                DB::raw('HOUR(billing_time) as hour'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->groupBy('hour')
            ->get()
            ->keyBy('hour');

        // Completar horas 0–23
        $dailySales = collect();

        for ($h = 0; $h < 24; $h++) {
            $dailySales->push([
                'hour' => $h,
                'total_orders' => $dailySalesRaw[$h]->total_orders ?? 0
            ]);
        }


        // 📈 2️⃣ Ventas de la SEMANA (en realidad: rango seleccionado)
        $weeklySales = (clone $query)
            ->select(
                DB::raw('DATE(billing_time) as date'),
                DB::raw('SUM(trip_earnings) + ABS(SUM(commission)) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 📊 3️⃣ Ventas del MES (mismo rango, otra visualización)
        $monthlySales = (clone $query)
            ->select(
                DB::raw('DATE(billing_time) as date'),
                DB::raw('SUM(trip_earnings) + ABS(SUM(commission)) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('ventas.didi-dashboard', [
            'startDate'        => $startDate,
            'endDate'          => $endDate,
            'totals'           => [
                'billing'  => $totalSalesReal,
                'earnings' => $earnings,
                'orders'   => $orders,
            ],
            'commissionTotal'  => $commissionRaw, // negativo para mostrar
            'dailySales'       => $dailySales,
            'weeklySales'      => $weeklySales,
            'monthlySales'     => $monthlySales,
        ]);
    }

}
