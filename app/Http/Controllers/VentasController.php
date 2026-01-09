<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', 'todos');
        $platform = $request->get('platform');

        // Las cards solo necesitan estructura. Los datos ahora vienen por AJAX.
        $plataformas = [
            [
                'slug' => 'inout',
                'nombre' => 'InOut Delivery',
                'descripcion' => 'Órdenes y ventas procesadas por InOut.',
                'year' => $year,
                'ruta' => route('ventas.inout'),
            ],
                [
                    'slug' => 'rappi',
                    'nombre' => 'Rappi',
                    'descripcion' => 'Órdenes y ventas procesadas por Rappi.',
                    'year' => 'todos',
                    'ruta' => route('ventas.rappi'),
                ],
                [
                    'slug' => 'didi',
                    'nombre' => 'Didi',
                    'descripcion' => 'Órdenes y ventas procesadas por Didi.',
                    'year' => 'todos',
                    'ruta' => route('ventas.didi'),
                ],
        ];

        return view('ventas.index', compact('plataformas'));
    }

    public function metricas(Request $request)
    {
        $platform = $request->get('platform');
        $year = $request->get('year', 'todos');

        // Creamos una llave de cache única: ej. metrics_rappi_2024
        $cacheKey = "metrics_{$platform}_{$year}";

        // Recordar los datos por 15 minutos para no saturar la BD
        $data = Cache::remember($cacheKey, now()->addMinutes(2), function () use ($platform, $year) {
            return $this->getPlatformData($platform, $year);
        });

        return response()->json($data);
    }

    /**
     * Lógica de extracción de datos separada para mayor orden
     */
    private function getPlatformData($platform, $year)
    {
        $default = [
            'total_ordenes' => 0,
            'total_vendido' => 0,
            'ticket_promedio' => 0,
        ];

        try {
            if ($platform === 'inout') {
                $query = DB::connection('inout')->table('orders_hotamericas')
                    ->selectRaw("COUNT(*) as total_ordenes, SUM(total) as total_vendido, AVG(total) as ticket_promedio")
                    ->whereIn('stateCurrent', ['Entregado', 'Reparto', 'Cerrado con novedad']);
                
                if ($year !== 'todos') $query->whereYear('createdAt', $year);
                return $query->first() ?? $default;
            }

            if ($platform === 'rappi') {
                $query = DB::table('ventas_rappi')
                    ->selectRaw("COUNT(DISTINCT id_orden) as total_ordenes, SUM(venta_bruta) as total_vendido, AVG(venta_bruta) as ticket_promedio")
                    ->whereNotIn('estado_orden', ['CANCELLED', 'CANCELADA']);

                if ($year !== 'todos') $query->whereYear('fecha_creacion_orden', $year);
                return $query->first() ?? $default;
            }

            if ($platform === 'didi') {
                $query = DB::table('didi_orders')
                    ->selectRaw("COUNT(DISTINCT order_id) as total_ordenes, SUM(billing_amount) as total_vendido, AVG(billing_amount) as ticket_promedio");

                if ($year !== 'todos') $query->whereYear('billing_time', $year);
                return $query->first() ?? $default;
            }
        } catch (\Exception $e) {
            \Log::error("Error en métricas {$platform}: " . $e->getMessage());
        }

        return $default;
    }

}
