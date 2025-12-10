<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

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

    public function dashboard(Request $request)
    {
        // Por defecto, mostrar últimos 7 días
        $defaultTo   = now()->toDateString();
        $defaultFrom = now()->subDays(6)->toDateString();

        return view('ventas.inout-dashboard', [
            'defaultFrom' => $defaultFrom,
            'defaultTo'   => $defaultTo,
        ]);
    }

    public function graficas(Request $request)
    {
        $from = $request->get('from');
        $to   = $request->get('to');

        if (!$from || !$to) {
            return response()->json(['error' => 'Rango de fechas requerido'], 422);
        }

        // Estados finales reales de la tabla
        $estadosFinales = ['Entregado', 'Reparto', 'Cerrado con novedad'];

        // Conexión base
        $conn = DB::connection('inout')->table('orders_hotamericas');

        // ===================================
        // 1. Distribución por canal (platform)
        // ===================================
        $canal = $conn->clone()
            ->selectRaw('platform as canal, COUNT(*) as total')
            ->whereIn('stateCurrent', $estadosFinales)
            ->whereBetween('createdAt', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupBy('platform')
            ->get();

        // =======================================
        // 2. Órdenes por sucursal (pointSale)
        // =======================================
        $sucursales = $conn->clone()
            ->selectRaw('pointSale as sucursal, COUNT(*) as total')
            ->whereIn('stateCurrent', $estadosFinales)
            ->whereBetween('createdAt', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupBy('pointSale')
            ->orderBy('total', 'desc')
            ->get();

        // ===================================
        // 3. Forma de pago (paymentMethod)
        // ===================================
        $formasPago = $conn->clone()
            ->selectRaw('paymentMethod as forma_pago, COUNT(*) as total')
            ->whereIn('stateCurrent', $estadosFinales)
            ->whereBetween('createdAt', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupBy('paymentMethod')
            ->get();

        // ===================================
        // 4. Entrega (type)
        // ===================================
        $entrega = $conn->clone()
            ->selectRaw('type as tipo_entrega, COUNT(*) as total')
            ->whereIn('stateCurrent', $estadosFinales)
            ->whereBetween('createdAt', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupBy('type')
            ->get();

        // ===================================
        // 5. Histórico diario
        // ===================================
        $historicoDiario = $conn->clone()
            ->selectRaw('DATE(createdAt) as fecha, COUNT(*) as total')
            ->whereIn('stateCurrent', $estadosFinales)
            ->whereBetween('createdAt', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // ===================================
        // 6. Histórico semanal
        // ===================================
        $historicoSemanal = $conn->clone()
            ->selectRaw('YEAR(createdAt) as anio, WEEK(createdAt, 1) as semana, COUNT(*) as total')
            ->whereIn('stateCurrent', $estadosFinales)
            ->whereBetween('createdAt', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupBy('anio', 'semana')
            ->orderBy('anio')
            ->orderBy('semana')
            ->get();

        // ===================================
        // 7. Histórico mensual
        // ===================================
        $historicoMensual = $conn->clone()
            ->selectRaw('YEAR(createdAt) as anio, MONTH(createdAt) as mes, COUNT(*) as total')
            ->whereIn('stateCurrent', $estadosFinales)
            ->whereBetween('createdAt', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupBy('anio', 'mes')
            ->orderBy('anio')
            ->orderBy('mes')
            ->get();

        // ===================================
        // 8. Canceladas (stateCurrent = Cancelado)
        // ===================================
        $canceladosBase = DB::connection('inout')
            ->table('orders_hotamericas')
            ->where('stateCurrent', 'Cancelado')
            ->whereBetween('createdAt', [$from . ' 00:00:00', $to . ' 23:59:59']);

        $canceladasPorSucursal = $canceladosBase->clone()
            ->selectRaw('pointSale as sucursal, COUNT(*) as total')
            ->groupBy('pointSale')
            ->get();

        $totalCanceladas = $canceladosBase->clone()
            ->selectRaw('COUNT(*) as total_ordenes, SUM(total) as total_valor')
            ->first();

        // ===================================
        // RESPUESTA COMPLETA
        // ===================================
        return response()->json([
            'canal'      => $canal,
            'sucursales' => $sucursales,
            'formasPago' => $formasPago,
            'entrega'    => $entrega,
            'historico'  => [
                'diario'  => $historicoDiario,
                'semanal' => $historicoSemanal,
                'mensual' => $historicoMensual,
            ],
            'canceladas' => [
                'resumen'      => $totalCanceladas,
                'por_sucursal' => $canceladasPorSucursal,
            ],
        ]);
    }

}
