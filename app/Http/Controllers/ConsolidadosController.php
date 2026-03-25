<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ConsolidadosController extends Controller
{
    public function index()
    {
        // ========================
        // INOUT
        // ========================
        $inout = $this->consolidadoInout();

        // ========================
        // RAPPI (placeholder)
        // ========================
        $rappi = [
            'canceladas' => ['cantidad' => 0, 'monto' => 0],
            'novedades'  => ['cantidad' => 0, 'monto' => 0],
            'impacto_total' => 0,
        ];

        // ========================
        // DIDI (placeholder)
        // ========================
        $didi = [
            'canceladas' => ['cantidad' => 0, 'monto' => 0],
            'novedades'  => ['cantidad' => 0, 'monto' => 0],
            'impacto_total' => 0,
        ];

        return view('consolidados.index', compact('inout', 'rappi', 'didi'));
    }

    private function consolidadoInout()
    {
        $from = now()->subDays(29)->toDateString();
        $to   = now()->toDateString();

        $base = DB::connection('inout')
            ->table('orders_hotamericas')
            ->whereBetween('createdAt', [
                $from . ' 00:00:00',
                $to   . ' 23:59:59'
            ]);

        $canceladas = $base->clone()
            ->where('stateCurrent', 'Cancelado')
            ->selectRaw('COUNT(*) as cantidad, SUM(total) as monto')
            ->first();

        $novedades = $base->clone()
            ->whereIn('stateCurrent', ['Cerrado con novedad', 'Novedad'])
            ->selectRaw('COUNT(*) as cantidad, SUM(total) as monto')
            ->first();

        return [
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
        ];
    }
}
