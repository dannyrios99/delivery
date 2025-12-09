<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retiro extends Model
{
    use HasFactory;

    protected $table = 'retiros';

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'fecha',
        'user_id',
        'sucursal_id',
        'valor',
        'concepto',
        'pdf_path',
    ];

    /**
     * Relación: cada retiro pertenece a un usuario (socio)
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación: cada retiro pertenece a una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}
