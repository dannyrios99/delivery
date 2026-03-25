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
            ->selectRaw('
                pointSale as sucursal, 
                COUNT(*) as total,
                SUM(total) as total_venta
            ')
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
            // 9. Frecuencia clientes únicos por día
            // ===================================
            $frecuenciaClientes = $conn->clone()
                ->selectRaw('DATE(createdAt) as fecha, COUNT(DISTINCT userId) as total_clientes')
                ->whereIn('stateCurrent', $estadosFinales)
                ->whereBetween('createdAt', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();

        // ===================================
        // 10. Frecuencia clientes únicos por hora
        // ===================================
        $frecuenciaPorHora = $conn->clone()
            ->selectRaw('HOUR(createdAt) as hora, COUNT(DISTINCT userId) as total_clientes')
            ->whereIn('stateCurrent', $estadosFinales)
            ->whereBetween('createdAt', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupBy('hora')
            ->orderBy('hora')
            ->get();
            
        // ===================================
        // 11. Productos más vendidos por hora
        // ===================================
        
        $hour = $request->get('hour');
        
        $topProductosQuery = DB::connection('inout')
            ->table('orders_hotamericas as o')
            ->join('items_hotamericas as i', 'o.id', '=', 'i.order_id')
            ->selectRaw('
                i.product,
                SUM(i.amount) as total_vendido
            ')
            ->whereIn('o.stateCurrent', $estadosFinales)
            ->whereBetween('o.createdAt', [$from . ' 00:00:00', $to . ' 23:59:59']);
        
        // 🔥 Si viene hora seleccionada, filtramos
        if (!is_null($hour)) {
            $topProductosQuery->whereRaw('HOUR(o.createdAt) = ?', [$hour]);
        }
        
        // 🔥 Ejecutamos la consulta final manteniendo tu variable
        $topProductos = $topProductosQuery
            ->groupBy('i.product')
            ->orderByDesc('total_vendido')
            ->get();
            
            // ===================================
        // 12. Venta total del periodo
        // ===================================
        $ventaTotal = $conn->clone()
            ->selectRaw('SUM(total) as total')
            ->whereIn('stateCurrent', $estadosFinales)
            ->whereBetween('createdAt', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->first();
        
        
        // ===================================
        // 13. Ventas por canal (Call Center / Web)
        // ===================================
        $ventasPorCanal = $conn->clone()
            ->selectRaw('platform, SUM(total) as total')
            ->whereIn('stateCurrent', $estadosFinales)
            ->whereBetween('createdAt', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupBy('platform')
            ->get();
                // ===================================
                // RESPUESTA COMPLETA
                // ===================================
                return response()->json([
                
                    // ===================================
                    // Gráficas principales
                    // ===================================
                    'canal'      => $canal,
                    'sucursales' => $sucursales,
                    'formasPago' => $formasPago,
                    'entrega'    => $entrega,
                
                    // ===================================
                    // Histórico de órdenes
                    // ===================================
                    'historico'  => [
                        'diario'  => $historicoDiario,
                        'semanal' => $historicoSemanal,
                        'mensual' => $historicoMensual,
                
                        // ===================================
                        // Frecuencias
                        // ===================================
                        'frecuencia_clientes' => $frecuenciaClientes,
                        'frecuencia_hora'     => $frecuenciaPorHora,
                
                        // ===================================
                        // Productos
                        // ===================================
                        'productos_top' => $topProductos,
                    ],
                
                    // ===================================
                    // Cancelaciones
                    // ===================================
                    'canceladas' => [
                        'resumen'      => $totalCanceladas,
                        'por_sucursal' => $canceladasPorSucursal,
                    ],
                
                    // ===================================
                    // KPIs del dashboard
                    // ===================================
                    'kpis' => [
                        'venta_total'   => $ventaTotal,
                        'ventas_canal'  => $ventasPorCanal,
                    ],
                
                    // ===================================
                    // KPIs específicos (Call / Web)
                    // ===================================
                    'kpis_detalle' => [
                        'call' => $ventaCall ?? null,
                        'web'  => $ventaWeb ?? null,
                    ],
                
                ]);
            }

    public function diagnosticoCanceladasCompensadas(Request $request)
    {
        // Rango por defecto: últimos 30 días
        $from = $request->get('from', now()->subDays(29)->toDateString());
        $to   = $request->get('to', now()->toDateString());

        // ===============================
        // 1. Estados reales existentes
        // ===============================
        $estados = DB::connection('inout')
            ->table('orders_hotamericas')
            ->select('stateCurrent', DB::raw('COUNT(*) as total'))
            ->groupBy('stateCurrent')
            ->orderBy('total', 'desc')
            ->get();

        // =====================================
        // 2. Análisis "Cerrado con novedad"
        // =====================================
        $novedad = DB::connection('inout')
            ->table('orders_hotamericas')
            ->where('stateCurrent', 'Cerrado con novedad')
            ->whereBetween('createdAt', [
                $from . ' 00:00:00',
                $to   . ' 23:59:59'
            ])
            ->selectRaw('
                COUNT(*) as cantidad,
                SUM(total) as monto,
                MIN(total) as minimo,
                MAX(total) as maximo
            ')
            ->first();

        // =====================================
        // 3. Canceladas (control)
        // =====================================
        $canceladas = DB::connection('inout')
            ->table('orders_hotamericas')
            ->where('stateCurrent', 'Cancelado')
            ->whereBetween('createdAt', [
                $from . ' 00:00:00',
                $to   . ' 23:59:59'
            ])
            ->selectRaw('
                COUNT(*) as cantidad,
                SUM(total) as monto
            ')
            ->first();

        // ===============================
        // RESPUESTA
        // ===============================
        return response()->json([
            'rango' => [
                'from' => $from,
                'to'   => $to,
            ],
            'estados_reales' => $estados,
            'cerrado_con_novedad' => $novedad,
            'canceladas' => $canceladas,
        ]);
    }

    public function consolidadoExcepcionesInout(Request $request)
    {
        $from = $request->get('from', now()->subDays(29)->toDateString());
        $to   = $request->get('to', now()->toDateString());

        $base = DB::connection('inout')
            ->table('orders_hotamericas')
            ->whereBetween('createdAt', [
                $from . ' 00:00:00',
                $to   . ' 23:59:59'
            ]);

        // 🔴 Canceladas
        $canceladas = $base->clone()
            ->where('stateCurrent', 'Cancelado')
            ->selectRaw('COUNT(*) as cantidad, SUM(total) as monto')
            ->first();

        // 🟡 Con novedad (ajustes parciales)
        $novedades = $base->clone()
            ->whereIn('stateCurrent', ['Cerrado con novedad', 'Novedad'])
            ->selectRaw('COUNT(*) as cantidad, SUM(total) as monto')
            ->first();

        return response()->json([
            'rango' => compact('from', 'to'),
            'excepciones' => [
                'canceladas' => [
                    'cantidad' => $canceladas->cantidad ?? 0,
                    'monto'    => $canceladas->monto ?? 0,
                ],
                'novedades' => [
                    'cantidad' => $novedades->cantidad ?? 0,
                    'monto'    => $novedades->monto ?? 0,
                ],
                'impacto_total' =>
                    ($canceladas->monto ?? 0) + ($novedades->monto ?? 0),
            ],
        ]);
    }
}
