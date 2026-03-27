<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\VentasRappi;
use App\Models\DidiOrder;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Estos datos son locales y rápidos de obtener
        $proyectosActivos = Proyecto::count(); 
        $tareasPendientes = \App\Models\Tarea::where('archivada', false)->count();

        $showGoogleCalendarModal = $user && !$user->hasGoogleCalendarConnected();

        return view('dashboard.dashboard', compact(
            'showGoogleCalendarModal',
            'proyectosActivos',
            'tareasPendientes'
        ));
    }

    /**
     * Obtener los KPIs de ventas de la SEMANA de forma asíncrona (Solo Inout).
     */
    public function getKpiStats()
    {
        set_time_limit(120);
        $hoy = Carbon::now()->toDateString();
        $cacheKey = "dashboard_kpis_weekly_{$hoy}";

        return Cache::remember($cacheKey, 300, function () { // 5 minutos
            $inicioSemana = Carbon::now()->subDays(6)->startOfDay();
            $finDia = Carbon::now()->endOfDay();

            $ventasInoutSemana = 0;
            $pedidosInoutSemana = 0;
            $pendientesInoutHoy = 0;
            $canceladosSemana = 0;

            try {
                // Ventas y Pedidos Semanales
                $statsSemana = DB::connection('inout')->table('orders_hotamericas')
                    ->whereIn('stateCurrent', ['Entregado', 'Reparto', 'Cerrado con novedad'])
                    ->whereBetween('createdAt', [$inicioSemana, $finDia])
                    ->selectRaw('SUM(total) as total_venta, COUNT(*) as total_pedidos')
                    ->first();

                $ventasInoutSemana = $statsSemana->total_venta ?? 0;
                $pedidosInoutSemana = $statsSemana->total_pedidos ?? 0;

                // Cancelados Semanales
                $canceladosSemana = DB::connection('inout')->table('orders_hotamericas')
                    ->where('stateCurrent', 'Cancelado')
                    ->whereBetween('createdAt', [$inicioSemana, $finDia])
                    ->count();

                // Pedidos en Reparto Hoy (Operativo)
                $pendientesInoutHoy = DB::connection('inout')->table('orders_hotamericas')
                    ->where('stateCurrent', 'Reparto')
                    ->whereDate('createdAt', Carbon::today())
                    ->count();

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error API Inout Dashboard Semanal: " . $e->getMessage());
            }

            return [
                'totalVentasSemana' => (float)$ventasInoutSemana,
                'totalPedidosSemana' => (int)$pedidosInoutSemana,
                'canceladosSemana' => (int)$canceladosSemana,
                'repartoHoy' => (int)$pendientesInoutHoy
            ];
        });
    }

    /**
     * Obtener insights semanales (Gráfico de volumen y Top productos).
     */
    public function getWeeklyInsights()
    {
        $hoy = Carbon::now()->toDateString();
        $cacheKey = "dashboard_weekly_insights_{$hoy}";

        return Cache::remember($cacheKey, 600, function () { // 10 minutos
            $inicioSemana = Carbon::now()->subDays(6)->startOfDay();
            $finDia = Carbon::now()->endOfDay();

            try {
                // 1. Volumen diario (Gráfico)
                $volumenDiario = DB::connection('inout')->table('orders_hotamericas')
                    ->selectRaw('DATE(createdAt) as fecha, COUNT(*) as total')
                    ->whereIn('stateCurrent', ['Entregado', 'Reparto', 'Cerrado con novedad'])
                    ->whereBetween('createdAt', [$inicioSemana, $finDia])
                    ->groupBy('fecha')
                    ->orderBy('fecha')
                    ->get();

                // 2. Top 5 productos de la semana
                $topProductos = DB::connection('inout')
                    ->table('orders_hotamericas as o')
                    ->join('items_hotamericas as i', 'o.id', '=', 'i.order_id')
                    ->selectRaw('i.product, SUM(i.amount) as total_vendido')
                    ->whereIn('o.stateCurrent', ['Entregado', 'Reparto', 'Cerrado con novedad'])
                    ->whereBetween('o.createdAt', [$inicioSemana, $finDia])
                    ->groupBy('i.product')
                    ->orderByDesc('total_vendido')
                    ->take(5)
                    ->get();

                return [
                    'volumenDiario' => $volumenDiario,
                    'topProductos' => $topProductos
                ];
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error API Weekly Insights: " . $e->getMessage());
                return ['error' => 'Error al cargar insights'];
            }
        });
    }

    /**
     * Obtener los últimos movimientos de Inout de forma asíncrona.
     */
    public function getMovimientosInout()
    {
        return Cache::remember('dashboard_recent_movements', 120, function () { // 2 minutos
            try {
                return DB::connection('inout')->table('orders_hotamericas')
                    ->select('createdAt', 'platform', 'total', 'stateCurrent')
                    ->orderBy('createdAt', 'desc')
                    ->take(5)
                    ->get();
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error API Inout Movimientos: " . $e->getMessage());
                return [];
            }
        });
    }
}
