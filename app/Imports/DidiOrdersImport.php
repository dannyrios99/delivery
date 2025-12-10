<?php

namespace App\Imports;

use App\Models\DidiOrder;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class DidiOrdersImport implements ToModel, WithHeadingRow
{
public function model(array $row)
    {
        return new DidiOrder([
            'store_id'                           => $row['store_id'] ?? null,
            'store_name'                         => $row['store_name'] ?? null,
            'billing_type'                       => $row['billing_type'] ?? null,
            'billing_time'                       => $this->parseDate($row['billing_time'] ?? null),
            'order_id'                           => $row['order_id'] ?? null,
            'accepted_at'                        => $this->parseDate($row['acceptedrefunded_at'] ?? null),
            'pickup_no'                          => $row['pickup_no'] ?? null,
            'original_item_price'                => $row['original_item_price'] ?? 0,

            // columnas REALES del archivo
            'menu_promotion_expenses'            => $row['menu_promotion_expensesincluding_menu_promotion_compensation'] ?? 0,
            'menu_promotion_compensation'        => $row['menu_promotion_compensation'] ?? 0,

            'commission_rate'                    => $row['commission_rate'] ?? 0,
            'commission'                         => $row['commission'] ?? 0,

            'free_delivery_event_expenses'       => $row['free_delivery_event_expenses'] ?? 0,
            'free_delivery_event_compensation'   => $row['free_delivery_event_compensation'] ?? 0,

            'trip_earnings'                      => $row['trip_earnings'] ?? 0,
            'iva_plataforma'                     => $row['iva_de_la_plataforma'] ?? 0,
            'deduction_amount'                   => $row['deduction_amount'] ?? 0,
            'billing_amount'                     => $row['billing_amount'] ?? 0,
            'payment_method'                     => $row['payment_method'] ?? null,
        ]);
    }

private function parseDate($value)
{
    if (!$value) return null;

    // 1. Si viene como número Excel (ej: 45233.1234)
    if (is_numeric($value)) {
        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
        } catch (\Exception $e) {
            // continúa al siguiente formato
        }
    }

    // 2. Formato con segundos: 10/09/2023 22:16:00
    try {
        return Carbon::createFromFormat('d/m/Y H:i:s', $value);
    } catch (\Exception $e) {}

    // 3. Formato sin segundos: 10/09/2023 22:16
    try {
        return Carbon::createFromFormat('d/m/Y H:i', $value);
    } catch (\Exception $e) {}

    // 4. Como fallback general
    try {
        return Carbon::parse($value);
    } catch (\Exception $e) {
        return null;
    }
}

}
