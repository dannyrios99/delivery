<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compensacion extends Model
{
    protected $table = 'compensaciones';

    protected $fillable = [
        'orden_id',
        'fecha',
        'razon',
        'paidlots_ids',
        'monto',
        'moneda',
        'productos',
        'productos_ids',
        'comentarios',
    ];
}
