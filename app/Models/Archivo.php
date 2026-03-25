<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    protected $table = 'archivos';

    protected $fillable = [
        'nombre_original',
        'ruta',
        'mime',
        'size',
        'archivable_id',
        'archivable_type',
    ];

    /**
     * Relación polimórfica
     * Puede pertenecer a Comentario, TareaChecklist, etc.
     */
    public function archivable()
    {
        return $this->morphTo();
    }
}
