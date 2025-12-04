<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Ruta de prueba
Route::get('/', function () {
    return response()->json([
        'message' => 'Barberia API',
        'status' => 'online',
        'endpoints' => [
            'POST /api/register' => 'Register new user',
            'POST /api/login' => 'Login user',
            'GET /api/clientes' => 'List clients (auth required)'
        ]
    ]);
});

// ======================
// RUTAS PÚBLICAS
// ======================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ======================
// RUTAS PROTEGIDAS
// ======================
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // CRUD APIs
    Route::apiResource('clientes', ClienteController::class);
    Route::apiResource('citas', CitaController::class);
    Route::apiResource('productos', ProductoController::class);
    Route::apiResource('servicios', ServiceController::class);
    Route::apiResource('users', UserController::class)->except(['store']); // No crear users desde API
});