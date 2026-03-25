<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActividadSoporte extends Model
{
    protected $table = 'actividades_soporte';

    protected $fillable = [
        'hora_extra_id',
        'tipo_soporte',
        'descripcion',
        'sistema'
    ];

    public function horaExtra()
    {
        return $this->belongsTo(HoraExtra::class, 'hora_extra_id');
    }
    
}
