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

    // Ventas rappi
    Route::get('/rappi', [VentasRappiController::class, 'index'])->name('ventas.rappi');
    Route::post('/rappi-importar', [VentasRappiController::class, 'importar'])->name('rappi.upload');
    Route::get('/rappi-plantilla', [VentasRappiController::class, 'descargarPlantilla'])->name('rappi.plantilla');

    Route::resource('proyectos', ProyectoController::class)
        ->only(['store', 'show']);
        Route::resource('tareas', TareaController::class)
        ->only(['store', 'update', 'destroy']);
        Route::get('tareas/{tarea}/checklist', function (Tarea $tarea) {
        return $tarea->checklist()
            ->orderBy('orden')
            ->get();
    });

    // Comentarios
    Route::post('/comentarios-tareas', [TareaController::class, 'storeComentario'])->name('comentarios.store');
    Route::delete('/comentarios-tareas/{comentario}', [TareaController::class, 'destroyComentario'])->name('comentarios.destroy');

    Route::patch(
        'tareas/{tarea}/estado',
        [TareaController::class, 'cambiarEstado']
    )->name('tareas.estado');

    Route::resource('grupos-tareas', GrupoTareaController::class)
    ->only(['store', 'update', 'destroy']);


    Route::resource('proyectos', ProyectoController::class)
    ->only(['store', 'show']);
    Route::resource('tareas', TareaController::class);

    Route::patch('/tareas/{tarea}/mover', [TareaController::class, 'mover'])
    ->name('tareas.mover');

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


});
    

Route::get('/clear-laravel-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return 'Cache cleared!';
});

require __DIR__.'/auth.php';
