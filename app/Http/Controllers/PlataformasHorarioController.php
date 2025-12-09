<?php

namespace App\Http\Controllers;

class PlataformasHorarioController extends Controller
{
    /**
     * Muestra la vista de plataformas disponibles para gestión de horarios.
     */
    public function index()
    {
        // Si algún día quieres agregar plataformas dinámicas, se puede pasar un arreglo aquí.
        $plataformas = [
            [
                'nombre' => 'InOut',
                'ruta'   => route('inout.index'),
                'icono'  => 'fa-solid fa-clock',
                'descripcion' => 'Gestión de horarios detallados por mapa y sucursal.',
            ],
            [
                'nombre' => 'Rappi',
                'ruta'   => route('rappi.index'),
                'icono'  => 'fa-solid fa-motorcycle',
                'descripcion' => 'Horarios operativos de la plataforma Rappi por sucursal.',
            ],
            [
                'nombre' => 'Didi',
                'ruta'   => '#', // cuando tengas su módulo lo cambias
                'icono'  => 'fa-solid fa-box',
                'descripcion' => 'Horarios configurados para Didi Food.',
            ],
        ];

        return view('horarios.plataformas', compact('plataformas'));
    }
}
