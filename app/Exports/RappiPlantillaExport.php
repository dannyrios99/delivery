<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RappiPlantillaExport implements WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'fecha_creacion_orden',
            'id_orden',
            'id_paidlot',
            'id_tienda',
            'nombre_tienda',
            'tipo_orden',
            'metodo_entrega',
            'metodo_pago',
            'estado_orden',
            'tiempo_preparacion',
            'prime',
            'retroactivo',
            'porcentaje_cancelacion',
            'porcentaje_uso_plataforma',
            'porcentaje_uso_plataforma_prime',
            'tipo_transaccion',
            'impoconsumo_iva_informativo',
            'ventas_base_uso_plataforma_informativo',
            'venta_bruta',
            'descuento_creditos',
            'descuento_producto_aliado',
            'descuentos_inversion_rappi_dar',
            'costo_domicilio_propinas',
            'meal_vouchers',
            'total_pagado_repartidor_efectivo',
            'total_pagado_usuario_marketplace',
            'descuento_domicilio_gratis',
            'compensaciones',
            'costo_canceladas',
            'uso_alquiler_plataforma_rappi',
            'descuento_inversion_rappi_sobre_uso_plat',
            'uso_alquiler_plataforma_prime',
            'tarifa_integration',
            'tarifa_demora',
            'tarifa_transaccional',
            'tarifa_activacion_marketplace',
            'contracargos',
            'tarifa_servicio_usuario',
            'cuota_rappiads',
            'descuento_service_fee',
            'servicio_cargo',
            'servicios_entrega_recoleccion_cargo',
            'descuento_pago_anticipado',
            'subtotal_antes_impuestos',
            'iva_uso_plataforma',
            'descuento_inversion_rappi_sobre_iva',
            'iva_campanas',
            'retefuente_uso_plataforma',
            'descuento_inversion_rappi_sobre_retefuente',
            'retefuente_tarjetas',
            'retefuente_campanas',
            'reteica_tarjetas',
            'iva_rappi_ads',
            'retefuente_rappi_ads',
            'iva_descuento_service_fee',
            'retefuente_descuento_service_fee',
            'iva_servicio_cargo',
            'retefuente_servicio_cargo',
            '', // Columna BG (Encabezado vacío)
            '',  // Columna BH (Encabezado vacío)
            'valor_ajustes_manuales',
            'deuda_periodos_anteriores',
            'cuota_prestamo',
            'cashback_rappi_creditos_aliado',
            'challenge_rappi_creditos_aliado',
            'gasto_bancario',
            'iva_gasto_bancario',
            'retefuente_gasto_bancario',
            'valor_neto',
            'valor_a_transferir',
            'id_relacionado',
            'id_paidlot_retroactivo',
            'razon_ajuste_rads',
            'descripcion_comentarios'
        ];
    }
}