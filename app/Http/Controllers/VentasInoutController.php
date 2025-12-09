<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class VentasInoutController extends Controller
{
    // ============================
    // VISTA PRINCIPAL (solo tabla)
    // ============================
    public function index()
    {
        // NO TRAE DATA → DataTables la cargará por AJAX
        return view('ventas.inout');
    }

    // ======================================
    // DATA PARA DATATABLE — SERVER SIDE AJAX
    // ======================================
    public function inoutData()
    {
        $estados = ['Entregado','Reparto','Cerrado con novedad'];

        $query = DB::connection('inout')
            ->table('orders_hotamericas')
            ->select(
                'createdAt',
                'platform',
                'pointSaleCode',
                'pointSale',
                'business',
                'city',
                'type',
                'paymentMethod',
                'total',
                'stateCurrent'
            )
            ->whereIn('stateCurrent', $estados)
            ->orderBy('createdAt', 'DESC');

        return DataTables::of($query)
            ->editColumn('createdAt', fn($row) => Carbon::parse($row->createdAt)->format('Y-m-d H:i'))
            ->editColumn('type', fn($row) => ucfirst($row->type))
            ->editColumn('total', fn($row) => number_format($row->total, 0, ',', '.'))
            ->make(true);
    }

    // ============================
    // VISTA DE GRÁFICAS
    // ============================
    public function graficos()
    {
        $db = DB::connection('inout');
        $estados = ['Entregado', 'Reparto', 'Cerrado con novedad'];

        $desde14d = now()->subDays(14)->startOfDay();
        $desde6w  = now()->subWeeks(6)->startOfWeek();
        $desde6m  = now()->subMonths(6)->startOfMonth();

        // Distribución
        $distribucionPuntos = $db->table('orders_hotamericas')
            ->selectRaw('pointSaleCode, pointSale, COUNT(*) as total')
            ->whereIn('stateCurrent', $estados)
            ->where('createdAt', '>=', $desde14d)
            ->groupBy('pointSaleCode', 'pointSale')
            ->orderBy('total','DESC')
            ->limit(10)
            ->get();

        // Formas de pago
        $formasPago = $db->table('orders_hotamericas')
            ->selectRaw('paymentMethod, COUNT(*) as total')
            ->whereIn('stateCurrent', $estados)
            ->where('createdAt', '>=', $desde14d)
            ->groupBy('paymentMethod')
            ->orderBy('total','DESC')
            ->get();

        // Entrega
        $entrega = $db->table('orders_hotamericas')
            ->selectRaw('type, COUNT(*) as total')
            ->whereIn('stateCurrent', $estados)
            ->where('createdAt', '>=', $desde14d)
            ->groupBy('type')
            ->get();

        // Diarias
        $ordenesDiarias = $db->table('orders_hotamericas')
            ->selectRaw('DATE(createdAt) as fecha, COUNT(*) as total')
            ->whereIn('stateCurrent', $estados)
            ->where('createdAt', '>=', now()->subDays(14))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Semanales
        $ordenesSemanales = $db->table('orders_hotamericas')
            ->selectRaw('YEARWEEK(createdAt) as semana, COUNT(*) as total')
            ->whereIn('stateCurrent', $estados)
            ->where('createdAt', '>=', $desde6w)
            ->groupBy('semana')
            ->orderBy('semana')
            ->get();

        // Mensuales
        $ordenesMensuales = $db->table('orders_hotamericas')
            ->selectRaw('DATE_FORMAT(createdAt, "%Y-%m") as mes, COUNT(*) as total')
            ->whereIn('stateCurrent', $estados)
            ->where('createdAt', '>=', $desde6m)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Canceladas
        $canceladasTotal = $db->table('orders_hotamericas')
            ->where('stateCurrent', 'Cancelado')
            ->count();

        $canceladasPorPunto = $db->table('orders_hotamericas')
            ->selectRaw('pointSaleCode, pointSale, COUNT(*) as total')
            ->where('stateCurrent', 'Cancelado')
            ->groupBy('pointSaleCode','pointSale')
            ->orderBy('total','DESC')
            ->limit(10)
            ->get();

        return view('ventas.inout-graficos', compact(
            'distribucionPuntos',
            'formasPago',
            'entrega',
            'ordenesDiarias',
            'ordenesSemanales',
            'ordenesMensuales',
            'canceladasTotal',
            'canceladasPorPunto'
        ));
    }
}
