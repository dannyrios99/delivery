<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string|null $store_id
 * @property string|null $store_name
 * @property string|null $billing_type
 * @property string|null $billing_time
 * @property string|null $order_id
 * @property string|null $accepted_at
 * @property string|null $pickup_no
 * @property string|null $original_item_price
 * @property string|null $menu_promotion_expenses
 * @property string|null $menu_promotion_compensation
 * @property string|null $commission_rate
 * @property string|null $commission
 * @property string|null $free_delivery_event_expenses
 * @property string|null $free_delivery_event_compensation
 * @property string|null $trip_earnings
 * @property string|null $iva_plataforma
 * @property string|null $deduction_amount
 * @property string|null $billing_amount
 * @property string|null $payment_method
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereBillingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereBillingTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereBillingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereCommissionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereDeductionAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereFreeDeliveryEventCompensation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereFreeDeliveryEventExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereIvaPlataforma($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereMenuPromotionCompensation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereMenuPromotionExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereOriginalItemPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder wherePickupNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereStoreName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereTripEarnings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DidiOrder whereUpdatedAt($value)
 */
	class DidiOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Proyecto|null $proyecto
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tarea> $tareas
 * @property-read int|null $tareas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoTarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoTarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoTarea query()
 */
	class GrupoTarea extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $sucursal_id
 * @property string $mapa
 * @property string $apertura
 * @property string $cierre
 * @property int|null $lunes
 * @property int|null $martes
 * @property int|null $miercoles
 * @property int|null $jueves
 * @property int|null $viernes
 * @property int|null $sabado
 * @property int|null $domingo
 * @property int|null $festivo
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Sucursal $sucursal
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereApertura($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereCierre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereDomingo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereFestivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereJueves($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereLunes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereMapa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereMartes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereMiercoles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereSabado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereSucursalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioInOut whereViernes($value)
 */
	class HorarioInOut extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $sucursal_id
 * @property string|null $marca
 * @property string $apertura
 * @property string $cierre
 * @property int|null $lunes
 * @property int|null $martes
 * @property int|null $miercoles
 * @property int|null $jueves
 * @property int|null $viernes
 * @property int|null $sabado
 * @property int|null $domingo
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Sucursal $sucursal
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereApertura($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereCierre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereDomingo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereJueves($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereLunes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereMarca($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereMartes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereMiercoles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereSabado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereSucursalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioRappi whereViernes($value)
 */
	class HorarioRappi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GrupoTarea> $grupos
 * @property-read int|null $grupos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tarea> $tareas
 * @property-read int|null $tareas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proyecto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proyecto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proyecto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proyecto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proyecto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proyecto whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proyecto whereUpdatedAt($value)
 */
	class Proyecto extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Sucursal|null $sucursal
 * @property-read \App\Models\User|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retiro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retiro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retiro query()
 */
	class Retiro extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property string|null $ciudad
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HorarioInOut> $horariosInout
 * @property-read int|null $horarios_inout_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HorarioRappi> $horariosRappi
 * @property-read int|null $horarios_rappi_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Retiro> $retiros
 * @property-read int|null $retiros_count
 * @property-read Sucursal|null $sucursal
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $usuarios
 * @property-read int|null $usuarios_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sucursal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sucursal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sucursal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sucursal whereCiudad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sucursal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sucursal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sucursal whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sucursal whereUpdatedAt($value)
 */
	class Sucursal extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $proyecto_id
 * @property string $titulo
 * @property string|null $descripcion
 * @property string|null $estado
 * @property string|null $prioridad
 * @property string|null $fecha_limite
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TareaChecklist> $checklist
 * @property-read int|null $checklist_count
 * @property-read \App\Models\GrupoTarea|null $grupo
 * @property-read \App\Models\Proyecto $proyecto
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarea query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarea whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarea whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarea whereFechaLimite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarea wherePrioridad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarea whereProyectoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarea whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarea whereUpdatedAt($value)
 */
	class Tarea extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Tarea|null $tarea
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TareaChecklist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TareaChecklist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TareaChecklist query()
 */
	class TareaChecklist extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $username
 * @property string|null $email
 * @property string $password
 * @property string $role
 * @property int|null $sucursal_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSucursalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property \Illuminate\Support\Carbon|null $fecha_creacion_orden
 * @property string|null $id_orden
 * @property string|null $id_paidlot
 * @property string|null $id_tienda
 * @property string|null $nombre_tienda
 * @property string|null $tipo_orden
 * @property string|null $metodo_entrega
 * @property string|null $metodo_pago
 * @property string|null $estado_orden
 * @property int|null $tiempo_preparacion
 * @property string|null $prime
 * @property string|null $retroactivo
 * @property numeric|null $porcentaje_cancelacion
 * @property numeric|null $porcentaje_uso_plataforma
 * @property numeric|null $porcentaje_uso_plataforma_prime
 * @property string|null $tipo_transaccion
 * @property numeric|null $impoconsumo_iva_informativo
 * @property numeric|null $ventas_base_uso_plataforma_informativo
 * @property numeric|null $venta_bruta
 * @property numeric|null $descuento_creditos
 * @property numeric|null $descuento_producto_aliado
 * @property numeric|null $descuentos_inversion_rappi_dar
 * @property numeric|null $costo_domicilio_propinas
 * @property numeric|null $meal_vouchers
 * @property numeric|null $total_pagado_repartidor_efectivo
 * @property numeric|null $total_pagado_usuario_marketplace
 * @property numeric|null $descuento_domicilio_gratis
 * @property numeric|null $compensaciones
 * @property numeric|null $costo_canceladas
 * @property numeric|null $uso_alquiler_plataforma_rappi
 * @property numeric|null $descuento_inversion_rappi_sobre_uso_plat
 * @property numeric|null $uso_alquiler_plataforma_prime
 * @property numeric|null $tarifa_integration
 * @property numeric|null $tarifa_demora
 * @property numeric|null $tarifa_transaccional
 * @property numeric|null $tarifa_activacion_marketplace
 * @property numeric|null $contracargos
 * @property numeric|null $tarifa_servicio_usuario
 * @property numeric|null $cuota_rappiads
 * @property numeric|null $descuento_service_fee
 * @property numeric|null $servicio_cargo
 * @property numeric|null $servicios_entrega_recoleccion_cargo
 * @property numeric|null $descuento_pago_anticipado
 * @property numeric|null $subtotal_antes_impuestos
 * @property numeric|null $iva_uso_plataforma
 * @property numeric|null $descuento_inversion_rappi_sobre_iva
 * @property numeric|null $iva_campanas
 * @property numeric|null $retefuente_uso_plataforma
 * @property numeric|null $descuento_inversion_rappi_sobre_retefuente
 * @property numeric|null $retefuente_tarjetas
 * @property numeric|null $retefuente_campanas
 * @property numeric|null $reteica_tarjetas
 * @property numeric|null $iva_rappi_ads
 * @property numeric|null $retefuente_rappi_ads
 * @property numeric|null $iva_descuento_service_fee
 * @property numeric|null $retefuente_descuento_service_fee
 * @property numeric|null $iva_servicio_cargo
 * @property numeric|null $retefuente_servicio_cargo
 * @property numeric|null $valor_ajustes_manuales
 * @property numeric|null $deuda_periodos_anteriores
 * @property numeric|null $cuota_prestamo
 * @property numeric|null $cashback_rappi_creditos_aliado
 * @property numeric|null $challenge_rappi_creditos_aliado
 * @property numeric|null $gasto_bancario
 * @property numeric|null $iva_gasto_bancario
 * @property numeric|null $retefuente_gasto_bancario
 * @property numeric|null $valor_neto
 * @property numeric|null $valor_a_transferir
 * @property string|null $id_relacionado
 * @property string|null $id_paidlot_retroactivo
 * @property string|null $razon_ajuste_rads
 * @property string|null $descripcion_comentarios
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereCashbackRappiCreditosAliado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereChallengeRappiCreditosAliado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereCompensaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereContracargos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereCostoCanceladas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereCostoDomicilioPropinas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereCuotaPrestamo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereCuotaRappiads($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereDescripcionComentarios($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereDescuentoCreditos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereDescuentoDomicilioGratis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereDescuentoInversionRappiSobreIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereDescuentoInversionRappiSobreRetefuente($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereDescuentoInversionRappiSobreUsoPlat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereDescuentoPagoAnticipado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereDescuentoProductoAliado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereDescuentoServiceFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereDescuentosInversionRappiDar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereDeudaPeriodosAnteriores($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereEstadoOrden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereFechaCreacionOrden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereGastoBancario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereIdOrden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereIdPaidlot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereIdPaidlotRetroactivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereIdRelacionado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereIdTienda($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereImpoconsumoIvaInformativo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereIvaCampanas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereIvaDescuentoServiceFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereIvaGastoBancario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereIvaRappiAds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereIvaServicioCargo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereIvaUsoPlataforma($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereMealVouchers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereMetodoEntrega($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereMetodoPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereNombreTienda($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi wherePorcentajeCancelacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi wherePorcentajeUsoPlataforma($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi wherePorcentajeUsoPlataformaPrime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi wherePrime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereRazonAjusteRads($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereRetefuenteCampanas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereRetefuenteDescuentoServiceFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereRetefuenteGastoBancario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereRetefuenteRappiAds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereRetefuenteServicioCargo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereRetefuenteTarjetas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereRetefuenteUsoPlataforma($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereReteicaTarjetas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereRetroactivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereServicioCargo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereServiciosEntregaRecoleccionCargo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereSubtotalAntesImpuestos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereTarifaActivacionMarketplace($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereTarifaDemora($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereTarifaIntegration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereTarifaServicioUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereTarifaTransaccional($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereTiempoPreparacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereTipoOrden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereTipoTransaccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereTotalPagadoRepartidorEfectivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereTotalPagadoUsuarioMarketplace($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereUsoAlquilerPlataformaPrime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereUsoAlquilerPlataformaRappi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereValorATransferir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereValorAjustesManuales($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereValorNeto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereVentaBruta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VentasRappi whereVentasBaseUsoPlataformaInformativo($value)
 */
	class VentasRappi extends \Eloquent {}
}

