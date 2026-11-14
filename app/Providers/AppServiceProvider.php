<?php

namespace App\Providers;

use App\Models\Configuracion;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Envía el nombre y el logo del estacionamiento a las vistas que los usan
        View::composer(['layouts.app', 'login', 'operacion.ticket'], function ($vista) {
            $vista->with('config', Configuracion::first());
        });
    }
}
