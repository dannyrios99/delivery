<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TareaChecklist extends Model
{
    protected $table = 'tarea_checklists';

    protected $fillable = [
        'tarea_id',
        'texto',
        'completado',
        'orden',
    ];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'tarea_id');
    }
}
