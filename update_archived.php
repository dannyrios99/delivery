<?php
App\Models\Tarea::whereHas('grupo', function($q) {
    $q->whereIn('nombre', ['Terminado', 'Hecho', 'Completado', 'Done']);
})->update(['archivada' => true]);
echo "Tareas archivadas con exito.\n";
