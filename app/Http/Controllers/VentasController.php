<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function index(Request $request)
    {
        // Año seleccionado (o año actual si no se envía)
        $year = $request->get('year', date('Y'));

        // Estados finalizados reales
        $estadosFinales = ['Entregado', 'Reparto', 'Cerrado con novedad'];

        // MÉTRICAS PARA INOUT FILTRADAS POR AÑO
        $inout = DB::connection('inout')
            ->table('orders_hotamericas')
            ->selectRaw('
                COUNT(*) as total_ordenes,
                SUM(total) as total_vendido,
                AVG(total) as ticket_promedio
            ')
            ->whereIn('stateCurrent', $estadosFinales)
            ->whereYear('createdAt', $year)       // ← FILTRO POR AÑO
            ->first();

        // ESTRUCTURA DE PLATAFORMAS
        $plataformas = [
            [
                'slug' => 'inout',
                'nombre' => 'InOut Delivery',
                'descripcion' => 'Órdenes y ventas procesadas por InOut.',
                'total_ordenes' => $inout->total_ordenes ?? 0,
                'total_vendido' => $inout->total_vendido ?? 0,
                'ticket_promedio' => $inout->ticket_promedio ?? 0,
                'ruta' => route('ventas.inout') . '?year=' . $year,   // ← Mantiene el filtro al entrar
            ],

            // Rappi (próximamente)
            [
                'slug' => 'rappi',
                'nombre' => 'Rappi',
                'descripcion' => 'Integración pendiente.',
                'total_ordenes' => 0,
                'total_vendido' => 0,
                'ticket_promedio' => 0,
                'ruta' => '#',
            ],

            // Web/Ecommerce (próximamente)
            [
                'slug' => 'web',
                'nombre' => 'Web / Ecommerce',
                'descripcion' => 'Integración pendiente.',
                'total_ordenes' => 0,
                'total_vendido' => 0,
                'ticket_promedio' => 0,
                'ruta' => '#',
            ],
        ];

        // Retornar vista con año seleccionado
        return view('ventas.index', compact('plataformas', 'year'));
    }

}
