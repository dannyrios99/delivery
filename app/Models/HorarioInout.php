<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioInOut extends Model
{
    protected $table = 'horarios_inout';

    protected $fillable = [
        'sucursal_id',
        'mapa',
        'apertura',
        'cierre',
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'sabado',
        'domingo',
        'festivo',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
