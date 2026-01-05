<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RappiPago extends Model
{
    use HasFactory;

    /**
     * El nombre exacto de la tabla en MySQL
     */
    protected $table = 'rappi_pagos';

    /**
     * Desactivamos timestamps porque la tabla no tiene columnas created_at y updated_at
     */
    public $timestamps = false;

    /**
     * Permitimos asignación masiva para todas las columnas
     * (Seguro siempre y cuando valides el archivo antes de importar)
     */
    protected $guarded = [];

    /**
     * Opcional: Asegura que las fechas sean tratadas como objetos Carbon
     */
    protected $casts = [
        'fecha_creacion_orden' => 'datetime',
    ];
}