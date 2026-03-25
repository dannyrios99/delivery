<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComentarioTarea extends Model
{
    use HasFactory;

    // Nombre exacto de la tabla en tu base de datos
    protected $table = 'comentarios_tareas';

    // Campos que se pueden llenar mediante asignación masiva
    protected $fillable = [
        'tarea_id',
        'user_id',
        'contenido',
    ];

    /**
     * El comentario pertenece a una tarea específica.
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'tarea_id');
    }

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    /**
     * El comentario fue escrito por un usuario (autor).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}