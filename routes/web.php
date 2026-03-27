<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HorariosInoutController;
use App\Http\Controllers\HorariosRappiController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\PlataformasHorarioController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\VentasInoutController;
use App\Http\Controllers\DidiOrderController;
use App\Http\Controllers\VentasRappiController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\GrupoTareaController;
use App\Models\Tarea;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\MapaEmbebidoController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // Si no está autenticado
        if (! $user) {
            return redirect()->route('login');
        }

        // Restringir rol lector
        if (! in_array($user->role, ['admin'])) {
            return redirect()->route('activos.index');
        }

        // Roles permitidos sí ven el dashboard
        return app(DashboardController::class)->index();
    })->middleware(['auth', 'verified'])->name('dashboard');

    //Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'show'])->name('usuarios.index');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // Sucursal
    Route::get('/sucursales', [SucursalController::class, 'index'])->name('sucursales.index');
    Route::post('/sucursales', [SucursalController::class, 'store'])->name('sucursales.store');
    Route::put('/sucursales/{id}', [SucursalController::class, 'update'])->name('sucursales.update');
    Route::delete('/sucursales/{id}', [SucursalController::class, 'destroy'])->name('sucursales.destroy');

    // Plataformas
    Route::get('/plataformas', [PlataformasHorarioController::class, 'index'])->name('horarios.index');

    // Horarios Inout
    Route::get('horarios/inout', [HorariosInOutController::class, 'index'])->name('inout.index');
    Route::post('/horarios/inout/store', [HorariosInOutController::class, 'store'])->name('horarios.inout.store');
    Route::get('horarios/inout/{id}/edit', [HorariosInOutController::class, 'edit'])->name('horarios.inout.edit');
    Route::post('horarios/inout/{id}', [HorariosInOutController::class, 'update'])->name('horarios.inout.update');

    // Rappi
    Route::get('/horarios/rappi', [HorariosRappiController::class, 'index'])->name('rappi.index');
    Route::post('/horarios/rappi/{sucursal_id}', [HorariosRappiController::class, 'store'])->name('rappi.store');
    Route::post('/horarios/rappi/update/{id}', [HorariosRappiController::class, 'update'])->name('rappi.update');
    Route::delete('/horarios/rappi/{id}', [HorariosRappiController::class, 'destroy'])->name('rappi.destroy');

    // Ventas
    Route::get('/ventas', [VentasController::class, 'index'])->name('ventas.index');
    Route::get('/ventas/metricas', [VentasController::class, 'metricas'])->name('ventas.metricas');

    // Ventas Inout
    Route::get('/ventas/inout', [VentasInoutController::class, 'index'])->name('ventas.inout');
    Route::get('/ventas/inout/dashboard', [VentasInoutController::class, 'dashboard'])->name('ventas.inout.dashboard');
    Route::get('/ventas/inout/graficas', [VentasInoutController::class, 'graficas'])->name('ventas.inout.graficas');
    Route::get('/ventas/inout/data', [VentasInoutController::class, 'inoutData'])->name('ventas.inout.data');

    // Ventas Didi
    Route::get('/didi', [DidiOrderController::class, 'index'])->name('ventas.didi');
    Route::post('/didi/import', [DidiOrderController::class, 'import'])->name('didi.import');
    Route::get('/didi/template', [DidiOrderController::class, 'downloadTemplate'])->name('didi.template');
    Route::get('/didi/dashboard', [DidiOrderController::class, 'dashboard'])->name('didi.dashboard');


    // Ventas rappi
    Route::get('/rappi', [VentasRappiController::class, 'index'])->name('ventas.rappi');
    Route::post('/rappi-importar', [VentasRappiController::class, 'importar'])->name('rappi.upload');
    Route::get('/rappi-plantilla', [VentasRappiController::class, 'descargarPlantilla'])->name('rappi.plantilla');

    //Proyectos
    Route::resource('proyectos', ProyectoController::class)->only(['store', 'show']);
    Route::get('proyectos/{proyecto}/historial', [ProyectoController::class, 'historial'])->name('proyectos.historial');
    Route::resource('tareas', TareaController::class)->only(['store', 'update', 'destroy']);
        Route::get('tareas/{tarea}/checklist', function (Tarea $tarea) {
        return $tarea->checklist()
            ->orderBy('orden')
            ->get();
    });

    // Tareas
    Route::delete('/tareas/{tarea}', [TareaController::class, 'destroy'])->name('tareas.destroy');
    Route::post('/comentarios-tareas', [TareaController::class, 'storeComentario'])->name('comentarios.store');
    Route::delete('/comentarios-tareas/{comentario}', [TareaController::class, 'destroyComentario'])->name('comentarios.destroy');
    Route::patch('tareas/{tarea}/estado',[TareaController::class, 'cambiarEstado'])->name('tareas.estado');
    Route::resource('grupos-tareas', GrupoTareaController::class)->only(['store', 'update', 'destroy']);
    Route::resource('tareas', TareaController::class);
    Route::patch('/tareas/{tarea}/mover', [TareaController::class, 'mover'])->name('tareas.mover');
    Route::patch('/tareas/{tarea}/archivar', [TareaController::class, 'archivar'])->name('tareas.archivar');
    Route::patch('/tareas/{tarea}/restaurar', [TareaController::class, 'restaurar'])->name('tareas.restaurar');


    Route::get('/benchmark-inout', function () {
        try {
            $t1 = microtime(true);

            $data = DB::connection('inout')
                ->table('orders_hotamericas')
                ->select('id')
                ->limit(1)
                ->get();

            $t2 = microtime(true);
            $elapsed = round($t2 - $t1, 3);

            return "Tiempo de respuesta: {$elapsed} segundos";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    });
Route::get('/mapas', [MapaEmbebidoController::class, 'index'])
    ->name('mapas.index');

Route::get('/mapas/sucursal/{sucursal}', [MapaEmbebidoController::class, 'show'])
    ->name('mapas.show');
Route::post('/mapas/asignar', [MapaEmbebidoController::class, 'store'])
    ->name('mapas.store');

    Route::get('/api/dashboard/kpi-stats', [DashboardController::class, 'getKpiStats'])->name('dashboard.kpi-stats');
    Route::get('/api/dashboard/movimientos-inout', [DashboardController::class, 'getMovimientosInout'])->name('dashboard.movimientos-inout');
    Route::get('/api/dashboard/weekly-insights', [DashboardController::class, 'getWeeklyInsights'])->name('dashboard.weekly-insights');

    // Rutas Asíncronas Inout Dashboard
    Route::get('/api/ventas/inout/kpis', [VentasInoutController::class, 'apiKpis'])->name('ventas.inout.api.kpis');
    Route::get('/api/ventas/inout/charts', [VentasInoutController::class, 'apiCharts'])->name('ventas.inout.api.charts');
    Route::get('/api/ventas/inout/historicos', [VentasInoutController::class, 'apiHistoricos'])->name('ventas.inout.api.historicos');
    Route::get('/api/ventas/inout/frecuencias', [VentasInoutController::class, 'apiFrecuencias'])->name('ventas.inout.api.frecuencias');
    Route::get('/api/ventas/inout/horarios', [VentasInoutController::class, 'apiHorarios'])->name('ventas.inout.api.horarios');
    Route::get('/api/ventas/inout/top-productos', [VentasInoutController::class, 'apiTopProductos'])->name('ventas.inout.api.top-productos');
    Route::get('/api/ventas/inout/canceladas', [VentasInoutController::class, 'apiCanceladas'])->name('ventas.inout.api.canceladas');

});
    

Route::get('/clear-laravel-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return 'Cache cleared!';
});

require __DIR__.'/auth.php';

Route::get('/limpiar-tareas', function () {
    $gruposTerminados = \App\Models\GrupoTarea::whereIn('nombre', ['Terminado', 'Hecho', 'Completado', 'Done'])->get();
    $archivadas = 0;
    
    foreach($gruposTerminados as $grupo) {
        $activeTasks = \App\Models\Tarea::where('grupo_id', $grupo->id)
                                        ->where('archivada', false)
                                        ->orderBy('updated_at', 'asc') // antíguas primero
                                        ->get();
        if ($activeTasks->count() > 8) {
            $cantidadAArchivar = $activeTasks->count() - 8;
            $tareasViejas = $activeTasks->take($cantidadAArchivar);
            foreach ($tareasViejas as $oldTask) {
                $oldTask->update(['archivada' => true]);
                $archivadas++;
            }
        }
    }
    return "Listo. Se archivaron {$archivadas} tareas antiguas para respetar el límite de 8.";
});
