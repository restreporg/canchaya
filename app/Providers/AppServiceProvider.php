<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Asegúrate de importar esta fachada

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
        // Fuerza el uso de HTTPS si el entorno no es local
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}