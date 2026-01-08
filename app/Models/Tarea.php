<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';

    protected $fillable = [
        'proyecto_id',
        'asignado_a',
        'titulo',
        'descripcion',
        'estado',
        'prioridad',
        'fecha_limite'
    ];

    // La tarea pertenece a un proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    // La tarea está asignada a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }
}
