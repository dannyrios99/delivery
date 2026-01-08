<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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

        // Estructura por defecto
        $data = (object)[
            'total_ordenes' => 0,
            'total_vendido' => 0,
            'ticket_promedio' => 0,
        ];

        // ================================
        // 1. MÉTRICAS INOUT (BD externa)
        // ================================
        if ($platform === 'inout') {

            $estadosFinales = ['Entregado', 'Reparto', 'Cerrado con novedad'];

            try {
                $query = DB::connection('inout')
                    ->table('orders_hotamericas')
                    ->selectRaw("
                        COUNT(*) as total_ordenes,
                        SUM(total) as total_vendido,
                        AVG(total) as ticket_promedio
                    ")
                    ->whereIn('stateCurrent', $estadosFinales);

                if ($year !== 'todos') {
                    $query->whereYear('createdAt', $year);
                }

                $result = $query->first();

                if ($result) {
                    $data = $result;
                }

            } catch (\Exception $e) {
                // Si falla la conexión externa, devolvemos ceros sin romper la app
            }
        }

        // ================================
        // 2. MÉTRICAS RAPPI (tu BD local)
        //    Tabla sugerida: ventas_rappi
        // ================================
        if ($platform === 'rappi') {

            try {
                $query = DB::table('ventas_rappi')
                    ->selectRaw("
                        COUNT(DISTINCT id_orden) as total_ordenes,
                        SUM(venta_bruta) as total_vendido,
                        AVG(venta_bruta) as ticket_promedio
                    ")
                    ->whereNotIn('estado_orden', ['CANCELLED', 'CANCELADA']);

                if ($year !== 'todos') {
                    $query->whereYear('fecha_creacion_orden', $year);
                }

                $result = $query->first();

                if ($result) {
                    $data = $result;
                }

            } catch (\Exception $e) {
                // silencio intencional: no rompe la UI
            }
        }

        // ================================
        // 3. MÉTRICAS DIDI (tu BD local)
        //    Tabla sugerida: ventas_didi
        // ================================
        if ($platform === 'didi') {

            try {
                $query = DB::table('didi_orders')
                    ->selectRaw("
                        COUNT(DISTINCT order_id) as total_ordenes,
                        SUM(CAST(billing_amount AS DECIMAL(20,2))) as total_vendido,
                        AVG(CAST(billing_amount AS DECIMAL(20,2))) as ticket_promedio
                    ");

                if ($year !== 'todos') {
                    $query->whereYear('billing_time', $year);
                }

                $result = $query->first();

                if ($result) {
                    $data = $result;
                }

            } catch (\Exception $e) {
                // silencio intencional, no rompemos la UI
            }
        }

        return response()->json($data);
    }

}
