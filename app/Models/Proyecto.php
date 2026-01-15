<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'nombre',
    ];

    // Un proyecto tiene muchas tareas
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'proyecto_id');
    }

    public function grupos()
    {
        return $this->hasMany(GrupoTarea::class, 'proyecto_id')
                    ->orderBy('orden');
    }

    public function usuarios()
    {
        // Si usas una tabla pivote miembro_proyecto o similar
        return $this->belongsToMany(User::class, 'proyecto_user'); 

    }
}
