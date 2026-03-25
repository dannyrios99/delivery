<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Compensacion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CompensacionesImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        if (empty($row['monto']) || empty($row['fecha'])) {
            return null;
        }

        try {
            $fecha = is_numeric($row['fecha'])
                ? \Carbon\Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha'])
                )
                : \Carbon\Carbon::parse($row['fecha']);
        } catch (\Exception $e) {
            return null;
        }

        $monto = str_replace(['$', ' ', ','], '', $row['monto']);

        if (!is_numeric($monto)) {
            return null;
        }

        return new Compensacion([
            'orden_id'      => $row['orden_id'] ?? null,
            'fecha'         => $fecha,
            'razon'         => $row['razon'] ?? null,
            'paidlots_ids'  => $row['paidlots_ids'] ?? null,
            'monto'         => (float) $monto,
            'moneda'        => 'COP',
            'productos'     => $row['productos'] ?? null,
            'productos_ids' => $row['productos_ids'] ?? null,
            'comentarios'   => $row['comentarios'] ?? null,
        ]);
    }

}
