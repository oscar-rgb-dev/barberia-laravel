<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Ruta de prueba GET
Route::get('/', function () {
    return response()->json([
        'app' => 'Barberia API',
        'version' => '1.0',
        'status' => 'online',
        'routes' => [
            'POST /api/register' => 'Registrar usuario',
            'POST /api/register-real' => 'Registrar con DB'
        ]
    ]);
});

// Ruta POST simple (sin DB)
Route::post('/register', function (Request $request) {
    return response()->json([
        'success' => true,
        'data' => $request->all(),
        'message' => 'Registro exitoso desde API',
        'timestamp' => now()
    ]);
});

// Ruta POST con base de datos
Route::post('/register-real', function (Request $request) {
    try {
        // Validación simple
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'telefono' => 'required|string'
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'telefono' => $request->telefono
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Usuario creado',
            'user_id' => $user->id,
            'user' => $user->only(['name', 'email', 'telefono'])
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 422);
    }
});

// Ruta de prueba adicional
Route::get('/test', function () {
    return response()->json(['test' => 'OK', 'time' => date('Y-m-d H:i:s')]);
});