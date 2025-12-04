<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ✅ FORZAR CARGA DE RUTAS API AQUÍ
        Route::prefix('api')
            ->middleware('api')  // Middleware API, NO 'web'
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}