<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapaEmbebido extends Model
{
    protected $table = 'mapas_embebidos';

    protected $fillable = [
        'sucursal_id',
        'google_map_id'
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}

