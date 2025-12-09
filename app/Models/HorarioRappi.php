<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioRappi extends Model
{
    protected $table = 'horarios_rappi';

    protected $fillable = [
        'sucursal_id',
        'marca',
        'apertura',
        'cierre',
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'sabado',
        'domingo',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
