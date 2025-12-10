<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DidiOrdersTemplateExport implements FromArray, WithHeadings, WithEvents
{
    public function headings(): array
    {
        return [
            'Store ID',
            'Store Name',
            'Billing Type',
            'Billing Time',
            'Order ID',
            'Accepted/Refunded at',
            'Pickup No.',
            'Original Item Price',
            'Menu Promotion Expenses(including Menu Promotion Compensation)',
            'Menu Promotion Compensation',
            'Commission Rate',
            'Commission',
            'Free Delivery Event Expenses',
            'Free Delivery Event Compensation',
            'Trip Earnings',
            'IVA de la plataforma',
            'Deduction Amount',
            'Billing Amount',
            'Payment Method',
        ];
    }

    public function array(): array
    {
        // Puedes dejarlo vacío o meter una fila de ejemplo
        return [
            // ['Ejemplo Store', 'Ejemplo Name', ...],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // AUTO-AJUSTAR TODAS LAS COLUMNAS (A hasta S → 19 columnas)
                foreach (range('A', 'S') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Hacer el encabezado más legible
                $sheet->getStyle('A1:S1')->getFont()->setBold(true);
                $sheet->getStyle('A1:S1')->getAlignment()->setWrapText(true);
            },
        ];
    }
}
