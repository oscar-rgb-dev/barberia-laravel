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
        // ✅ FORZAR CARGA DE RUTAS API - VERSIÓN CORRECTA
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
        
        // También carga rutas API manualmente en web para asegurar
        Route::prefix('api')
            ->middleware('web')
            ->group(function() {
                Route::get('/test-web', function() {
                    return response()->json(['from' => 'web middleware']);
                });
            });
    }
}