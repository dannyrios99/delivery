<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VentasRappiImport;
use App\Models\VentasRappi;
use App\Exports\RappiPlantillaExport;
use Exception;

class VentasRappiController extends Controller
{
    /**
     * Muestra el formulario para subir el archivo.
     */
public function index()
    {
        // Traemos los últimos 2000 registros para no saturar la vista inicial
        // Seleccionamos solo las columnas que mostramos en la tabla para optimizar memoria
        $ventas = VentasRappi::select(
            'fecha_creacion_orden',
            'id_orden',
            'nombre_tienda',
            'estado_orden',
            'venta_bruta',
            'valor_a_transferir'
        )
        ->orderBy('fecha_creacion_orden', 'desc')
        ->take(2000) 
        ->get();

        // Retornamos la vista donde tienes tu Tabla y tu Modal
        return view('ventas.rappi', compact('ventas'));
    }
    /**
     * Procesa la importación del Excel.
     */
    public function importar(Request $request)
    {
        // 1. Validación del archivo
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,csv,xls|max:50000', // Máx 50MB (ajusta según necesites)
        ]);

        // 2. Aumentar límites de PHP temporalmente para este proceso
        // Esto evita el error "Maximum execution time exceeded"
        set_time_limit(0); 
        // Esto evita errores de memoria si el proceso de lectura es intenso
        ini_set('memory_limit', '512M'); 

        try {
            // 3. Ejecutar la importación usando la clase que creamos antes
            // Laravel Excel detectará automáticamente si es CSV o Excel
            Excel::import(new VentasRappiImport, $request->file('archivo'));

            // 4. Retornar éxito
            return back()->with('success', '¡Importación completada exitosamente! Los datos se han cargado en la base de datos.');

        } catch (Exception $e) {
            // 5. Manejo de errores (por si el Excel tiene formato incorrecto o falla la BD)
            return back()->with('error', 'Ocurrió un error durante la importación: ' . $e->getMessage());
        }
    }
    
    public function descargarPlantilla()
    {
        return Excel::download(new RappiPlantillaExport, 'plantilla_rappi_pagos.xlsx');
    }

}