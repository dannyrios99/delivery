<?php

namespace App\Imports;

use App\Models\VentasRappi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Carbon\Carbon;

class VentasRappiImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    /**
     * Limpia fechas en español (lun, mar...) a formato Y-m-d H:i:s
     */
    private function limpiarFecha($fecha)
    {
        if (!$fecha) return null;
        try {
            $fecha = strtolower($fecha);
            $mapa = [
                'lun' => 'Mon', 'mar' => 'Tue', 'mié' => 'Wed', 'mie' => 'Wed',
                'jue' => 'Thu', 'vie' => 'Fri', 'sáb' => 'Sat', 'sab' => 'Sat', 'dom' => 'Sun',
                'ene' => 'Jan', 'abr' => 'Apr', 'ago' => 'Aug', 'dic' => 'Dec'
            ];
            $fechaTraducida = str_replace(array_keys($mapa), array_values($mapa), $fecha);
            return Carbon::parse($fechaTraducida)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return null; 
        }
    }

    /**
     * NUEVO: Limpia números (Quita el signo $ y las comas de miles)
     * Convierte "29,468.75" -> "29468.75"
     */
    private function limpiarNumero($valor)
    {
        if (is_null($valor) || $valor === '') return 0;
        
        // Quitar cualquier cosa que no sea número, punto o signo menos
        // Esto elimina las comas (,) que causan error en DECIMAL
        $valorLimpio = str_replace([',', '$', ' '], '', $valor);
        
        return (float) $valorLimpio;
    }

    public function model(array $row)
    {
        return new VentasRappi([
            'fecha_creacion_orden' => $this->limpiarFecha($row['fecha_creacion_orden'] ?? null),
            'id_orden'             => $row['id_orden'] ?? null,
            'id_paidlot'           => $row['id_paidlot'] ?? null,
            'id_tienda'            => $row['id_tienda'] ?? null,
            'nombre_tienda'        => $row['nombre_tienda'] ?? null,
            'tipo_orden'           => $row['tipo_orden'] ?? null,
            'metodo_entrega'       => $row['metodo_entrega'] ?? null,
            'metodo_pago'          => $row['metodo_pago'] ?? null,
            'estado_orden'         => $row['estado_orden'] ?? null,
            'tiempo_preparacion'   => (int) ($row['tiempo_preparacion'] ?? 0),
            'prime'                => $row['prime'] ?? null,
            'retroactivo'          => $row['retroactivo'] ?? null,
            'porcentaje_cancelacion'          => $this->limpiarNumero($row['porcentaje_cancelacion'] ?? 0),
            'porcentaje_uso_plataforma'       => $this->limpiarNumero($row['porcentaje_uso_plataforma'] ?? 0),
            'porcentaje_uso_plataforma_prime' => $this->limpiarNumero($row['porcentaje_uso_plataforma_prime'] ?? 0),
            'tipo_transaccion'                => $row['tipo_transaccion'] ?? null,

            // APLICAR LIMPIEZA A TODOS LOS CAMPOS DE DINERO
            'impoconsumo_iva_informativo'            => $this->limpiarNumero($row['impoconsumo_iva_informativo'] ?? 0),
            'ventas_base_uso_plataforma_informativo' => $this->limpiarNumero($row['ventas_base_uso_plataforma_informativo'] ?? 0),
            'venta_bruta'                            => $this->limpiarNumero($row['venta_bruta'] ?? 0),
            'descuento_creditos'                     => $this->limpiarNumero($row['descuento_creditos'] ?? 0),
            'descuento_producto_aliado'              => $this->limpiarNumero($row['descuento_producto_aliado'] ?? 0),
            'descuentos_inversion_rappi_dar'         => $this->limpiarNumero($row['descuentos_inversion_rappi_dar'] ?? 0),
            'costo_domicilio_propinas'               => $this->limpiarNumero($row['costo_domicilio_propinas'] ?? 0),
            'meal_vouchers'                          => $this->limpiarNumero($row['meal_vouchers'] ?? 0),
            'total_pagado_repartidor_efectivo'       => $this->limpiarNumero($row['total_pagado_repartidor_efectivo'] ?? 0),
            'total_pagado_usuario_marketplace'       => $this->limpiarNumero($row['total_pagado_usuario_marketplace'] ?? 0),
            'descuento_domicilio_gratis'             => $this->limpiarNumero($row['descuento_domicilio_gratis'] ?? 0),
            'compensaciones'                         => $this->limpiarNumero($row['compensaciones'] ?? 0),
            'costo_canceladas'                       => $this->limpiarNumero($row['costo_canceladas'] ?? 0),
            'uso_alquiler_plataforma_rappi'          => $this->limpiarNumero($row['uso_alquiler_plataforma_rappi'] ?? 0),
            'descuento_inversion_rappi_sobre_uso_plat' => $this->limpiarNumero($row['descuento_inversion_rappi_sobre_uso_plat'] ?? 0),
            'uso_alquiler_plataforma_prime'          => $this->limpiarNumero($row['uso_alquiler_plataforma_prime'] ?? 0),
            'tarifa_integration'                     => $this->limpiarNumero($row['tarifa_integration'] ?? 0),
            'tarifa_demora'                          => $this->limpiarNumero($row['tarifa_demora'] ?? 0),
            'tarifa_transaccional'                   => $this->limpiarNumero($row['tarifa_transaccional'] ?? 0),
            'tarifa_activacion_marketplace'          => $this->limpiarNumero($row['tarifa_activacion_marketplace'] ?? 0),
            'contracargos'                           => $this->limpiarNumero($row['contracargos'] ?? 0),
            'tarifa_servicio_usuario'                => $this->limpiarNumero($row['tarifa_servicio_usuario'] ?? 0),
            'cuota_rappiads'                         => $this->limpiarNumero($row['cuota_rappiads'] ?? 0),
            'descuento_service_fee'                  => $this->limpiarNumero($row['descuento_service_fee'] ?? 0),
            'servicio_cargo'                         => $this->limpiarNumero($row['servicio_cargo'] ?? 0),
            'servicios_entrega_recoleccion_cargo'    => $this->limpiarNumero($row['servicios_entrega_recoleccion_cargo'] ?? 0),
            'descuento_pago_anticipado'              => $this->limpiarNumero($row['descuento_pago_anticipado'] ?? 0),
            'subtotal_antes_impuestos'               => $this->limpiarNumero($row['subtotal_antes_impuestos'] ?? 0),
            'iva_uso_plataforma'                     => $this->limpiarNumero($row['iva_uso_plataforma'] ?? 0),
            'descuento_inversion_rappi_sobre_iva'    => $this->limpiarNumero($row['descuento_inversion_rappi_sobre_iva'] ?? 0),
            'iva_campanas'                           => $this->limpiarNumero($row['iva_campanas'] ?? 0),
            'retefuente_uso_plataforma'              => $this->limpiarNumero($row['retefuente_uso_plataforma'] ?? 0),
            'descuento_inversion_rappi_sobre_retefuente' => $this->limpiarNumero($row['descuento_inversion_rappi_sobre_retefuente'] ?? 0),
            'retefuente_tarjetas'                    => $this->limpiarNumero($row['retefuente_tarjetas'] ?? 0),
            'retefuente_campanas'                    => $this->limpiarNumero($row['retefuente_campanas'] ?? 0),
            'reteica_tarjetas'                       => $this->limpiarNumero($row['reteica_tarjetas'] ?? 0),
            'iva_rappi_ads'                          => $this->limpiarNumero($row['iva_rappi_ads'] ?? 0),
            'retefuente_rappi_ads'                   => $this->limpiarNumero($row['retefuente_rappi_ads'] ?? 0),
            'iva_descuento_service_fee'              => $this->limpiarNumero($row['iva_descuento_service_fee'] ?? 0),
            'retefuente_descuento_service_fee'       => $this->limpiarNumero($row['retefuente_descuento_service_fee'] ?? 0),
            'iva_servicio_cargo'                     => $this->limpiarNumero($row['iva_servicio_cargo'] ?? 0),
            'retefuente_servicio_cargo'              => $this->limpiarNumero($row['retefuente_servicio_cargo'] ?? 0),
            'valor_ajustes_manuales'                 => $this->limpiarNumero($row['valor_ajustes_manuales'] ?? 0),
            'deuda_periodos_anteriores'              => $this->limpiarNumero($row['deuda_periodos_anteriores'] ?? 0),
            'cuota_prestamo'                         => $this->limpiarNumero($row['cuota_prestamo'] ?? 0),
            'cashback_rappi_creditos_aliado'         => $this->limpiarNumero($row['cashback_rappi_creditos_aliado'] ?? 0),
            'challenge_rappi_creditos_aliado'        => $this->limpiarNumero($row['challenge_rappi_creditos_aliado'] ?? 0),
            'gasto_bancario'                         => $this->limpiarNumero($row['gasto_bancario'] ?? 0),
            'iva_gasto_bancario'                     => $this->limpiarNumero($row['iva_gasto_bancario'] ?? 0),
            'retefuente_gasto_bancario'              => $this->limpiarNumero($row['retefuente_gasto_bancario'] ?? 0),
            'valor_neto'                             => $this->limpiarNumero($row['valor_neto'] ?? 0),
            'valor_a_transferir'                     => $this->limpiarNumero($row['valor_a_transferir'] ?? 0),
            
            'id_relacionado'         => $row['id_relacionado'] ?? null,
            'id_paidlot_retroactivo' => $row['id_paidlot_retroactivo'] ?? null,
            'razon_ajuste_rads'      => $row['razon_ajuste_rads'] ?? null,
            'descripcion_comentarios'=> $row['descripcion_comentarios'] ?? null,
        ]);
    }

    // REDUCIMOS EL TAMAÑO PARA EVITAR EL ERROR 1390
    public function chunkSize(): int
    {
        return 500; 
    }

    public function batchSize(): int
    {
        return 500; 
    }
}