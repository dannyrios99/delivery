<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoraExtra extends Model
{
    protected $table = 'horas_extras';

    protected $fillable = [
        'nombre',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'total_horas',
        'area',
        'observacion'
    ];

    public function actividadesSoporte()
    {
        return $this->hasMany(ActividadSoporte::class, 'hora_extra_id');
    }
}
