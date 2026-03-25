<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoArmi extends Model
{
    protected $table = 'consolidado_domicilios'; 
    // 👉 la tabla puede cambiarse después, por ahora reutilizamos

    protected $fillable = [
        'sede',
        'rango_km',
        'numero_entregas',
        'valor_venta',
        'domicilio_hot',
        'domicilio_armi',
        'recargo_km',
        'recargo_nocturno',
        'recargo_domingo',
        'valor_final',
        'inversion_hot',
        'fecha_reporte',
        'archivo_origen',
    ];

    protected $casts = [
        'fecha_reporte' => 'date',

        'numero_entregas' => 'integer',

        'valor_venta' => 'decimal:2',
        'domicilio_hot' => 'decimal:2',
        'domicilio_armi' => 'decimal:2',
        'recargo_km' => 'decimal:2',
        'recargo_nocturno' => 'decimal:2',
        'recargo_domingo' => 'decimal:2',
        'valor_final' => 'decimal:2',
        'inversion_hot' => 'decimal:2',
    ];
}