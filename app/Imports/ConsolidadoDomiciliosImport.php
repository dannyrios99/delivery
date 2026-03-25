<?php

namespace App\Imports;

use App\Models\GastoArmi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ConsolidadoDomiciliosImport implements ToCollection, WithHeadingRow
{
    public array $resultado = [];
    public ?string $archivo = null;

    /**
     * Rangos FIJOS de KM
     * (calculados desde distancia_real)
     */
    protected array $rangos = [
        '0-1'   => [0, 1],
        '1.1-2' => [1.1, 2],
        '2.1-3' => [2.1, 3],
        '3.1-4' => [3.1, 4],
        '4.1-5' => [4.1, 5],
        '5+'    => [5.1, INF],
    ];

    /**
     * Procesa todas las filas del Excel
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // =========================
            // DATOS BASE
            // =========================
            $sede = trim($row['name_store'] ?? '');
            $km   = (float) ($row['distancia_real'] ?? 0);

            if ($sede === '') {
                continue; // fila inválida
            }

            $rango = $this->resolverRango($km);

            $this->inicializar($sede, $rango);

            // =========================
            // ACUMULACIONES
            // =========================
            $this->resultado[$sede][$rango]['numero_entregas']++;

            $this->resultado[$sede][$rango]['valor_venta']
                += (float) ($row['valor_venta'] ?? 0);

            $this->resultado[$sede][$rango]['domicilio_hot']
                += (float) ($row['valor_domicilio_hot'] ?? 0);

            $this->resultado[$sede][$rango]['domicilio_armi']
                += (float) ($row['valor_armi'] ?? 0);

            $this->resultado[$sede][$rango]['recargo_km']
                += (float) ($row['recargo_km_extra'] ?? 0);

            $this->resultado[$sede][$rango]['recargo_nocturno']
                += (float) ($row['recargo_nocturno'] ?? 0);

            $this->resultado[$sede][$rango]['recargo_domingo']
                += (float) ($row['recargo_domingo'] ?? 0);

            $this->resultado[$sede][$rango]['valor_final']
                += (float) ($row['ppr_total_value'] ?? 0);
        }

        // =========================
        // CÁLCULOS FINALES + GUARDADO
        // =========================
        foreach ($this->resultado as $sede => $rangos) {
            foreach ($rangos as $rango => $data) {

                $data['inversion_hot'] =
                    $data['valor_final'] - $data['domicilio_hot'];

                GastoArmi::updateOrCreate(
                    [
                        'sede' => $sede,
                        'rango_km' => $rango,
                        'fecha_reporte' => now()->toDateString(),
                    ],
                    [
                        'numero_entregas' => $data['numero_entregas'],
                        'valor_venta' => $data['valor_venta'],
                        'domicilio_hot' => $data['domicilio_hot'],
                        'domicilio_armi' => $data['domicilio_armi'],
                        'recargo_km' => $data['recargo_km'],
                        'recargo_nocturno' => $data['recargo_nocturno'],
                        'recargo_domingo' => $data['recargo_domingo'],
                        'valor_final' => $data['valor_final'],
                        'inversion_hot' => $data['inversion_hot'],
                        'archivo_origen' => $this->archivo,
                    ]
                );
            }
        }
    }

    /**
     * Determina el rango de KM según distancia_real
     */
    private function resolverRango(float $km): string
    {
        foreach ($this->rangos as $label => [$min, $max]) {
            if ($km >= $min && $km <= $max) {
                return $label;
            }
        }

        return 'sin_rango';
    }

    /**
     * Inicializa la estructura si no existe
     */
    private function inicializar(string $sede, string $rango): void
    {
        if (!isset($this->resultado[$sede][$rango])) {
            $this->resultado[$sede][$rango] = [
                'numero_entregas' => 0,
                'valor_venta' => 0,
                'domicilio_hot' => 0,
                'domicilio_armi' => 0,
                'recargo_km' => 0,
                'recargo_nocturno' => 0,
                'recargo_domingo' => 0,
                'valor_final' => 0,
                'inversion_hot' => 0,
            ];
        }
    }
}