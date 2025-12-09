<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';

    /**
     * Campos asignables
     */
    protected $fillable = [
        'nombre',
        'ciudad',
    ];

    /**
     * Relación: una sucursal puede tener muchos retiros
     */
    public function retiros()
    {
        return $this->hasMany(Retiro::class, 'sucursal_id');
    }

    public function horariosInout()
    {
        return $this->hasMany(HorarioInOut::class);
    }

    public function horariosRappi()
    {
        return $this->hasMany(HorarioRappi::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }


    /**
     * Relación: una sucursal puede estar asociada a usuarios (opcional)
     */
    public function usuarios()
    {
        return $this->hasMany(User::class, 'sucursal_id');
    }
}
