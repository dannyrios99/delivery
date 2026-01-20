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
        'grupo_id',
        'titulo',
        'descripcion',
        'prioridad',
        'fecha_limite',
    ];

    protected $casts = [
        'fecha_limite' => 'date:Y-m-d',
    ];



    public function responsables()
    {
        // Esto conecta con la tabla 'tarea_user' que creamos por SQL
        return $this->belongsToMany(User::class, 'tarea_user', 'tarea_id', 'user_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function grupo()
    {
        return $this->belongsTo(GrupoTarea::class, 'grupo_id');
    }
    public function checklist()
    {
        return $this->hasMany(TareaChecklist::class, 'tarea_id');
    }

    public function comentarios()
    {
        // Relación de uno a muchos, ordenados por los más recientes
        return $this->hasMany(ComentarioTarea::class, 'tarea_id')->latest();
    }

}

