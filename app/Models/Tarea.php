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

}

