<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoTarea extends Model
{
    protected $table = 'grupos_tareas';

    protected $fillable = [
        'proyecto_id',
        'nombre',
        'orden'
    ];

    // Un grupo pertenece a un proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    // Un grupo tiene muchas tareas
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'grupo_id');
    }
}
