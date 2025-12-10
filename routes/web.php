<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HorariosInoutController;
use App\Http\Controllers\RappiController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\PlataformasHorarioController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\VentasInoutController;


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
    Route::get('/horarios/rappi', [RappiController::class, 'index'])->name('rappi.index');
    Route::post('/horarios/rappi/{sucursal_id}', [RappiController::class, 'store'])->name('rappi.store');
    Route::post('/horarios/rappi/update/{id}', [RappiController::class, 'update'])->name('rappi.update');
    Route::delete('/horarios/rappi/{id}', [RappiController::class, 'destroy'])->name('rappi.destroy');

    // Ventas
    Route::get('/ventas', [VentasController::class, 'index'])->name('ventas.index');
    Route::get('/ventas/metricas', [VentasController::class, 'metricas'])->name('ventas.metricas');

    // Ventas Inout
    Route::get('/ventas/inout', [VentasInoutController::class, 'index'])->name('ventas.inout');
    Route::get('/ventas/inout/dashboard', [VentasInoutController::class, 'dashboard'])->name('ventas.inout.dashboard');
    Route::get('/ventas/inout/graficas', [VentasInoutController::class, 'graficas'])->name('ventas.inout.graficas');
    Route::get('/ventas/inout/data', [VentasInoutController::class, 'inoutData'])->name('ventas.inout.data');


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
    Route::get('/test-inout', function () {
        try {
            $result = DB::connection('inout')->select('SELECT NOW() as fecha');
            return 'Conexión exitosa → ' . $result[0]->fecha;
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    });
    Route::get('/test-inout-orders', function () {
        try {
            $estadosFinales = ['Entregado', 'Reparto', 'Cerrado con novedad'];

            $orden = DB::connection('inout')
                ->table('orders_hotamericas')
                ->whereIn('stateCurrent', $estadosFinales)
                ->orderBy('createdAt', 'DESC')
                ->first();

            return $orden ?: 'No hay órdenes en estados finalizados.';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    });

Route::get('/clear-laravel-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return 'Cache cleared!';
});

require __DIR__.'/auth.php';
