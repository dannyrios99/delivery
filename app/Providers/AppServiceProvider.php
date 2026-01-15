<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;
use App\Models\Proyecto;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        view()->composer('*', function ($view) {

            $sidebarProyectos = Proyecto::all();

            // 👇 Route model binding: proyectos/{proyecto}
            $proyectoActualId = null;

            if (Request::route('proyecto') instanceof Proyecto) {
                $proyectoActualId = Request::route('proyecto')->id;
            }

            $view->with([
                'sidebarProyectos' => $sidebarProyectos,
                'proyectoActualId' => $proyectoActualId,
            ]);
        });
    }
}
