<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DidiOrder extends Model
{
    protected $table = 'didi_orders';

    protected $fillable = [
        'store_id',
        'store_name',
        'billing_type',
        'billing_time',
        'order_id',
        'accepted_at',
        'pickup_no',
        'original_item_price',
        'menu_promotion_expenses',
        'menu_promotion_compensation',
        'commission_rate',
        'commission',
        'free_delivery_event_expenses',
        'free_delivery_event_compensation',
        'trip_earnings',
        'iva_plataforma',
        'deduction_amount',
        'billing_amount',
        'payment_method',
    ];
}

