<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
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

            $proyectoActualId = null;
            if (Request::route('proyecto') instanceof Proyecto) {
                $proyectoActualId = Request::route('proyecto')->id;
            }

            $user = Auth::user();

            $showGoogleCalendarModal = $user
                && empty($user->google_refresh_token)
                && !session()->has('success');

            $view->with([
                'sidebarProyectos' => $sidebarProyectos,
                'proyectoActualId' => $proyectoActualId,
                'showGoogleCalendarModal' => $showGoogleCalendarModal, // 👈 NUEVO
            ]);
        });
    }
}
