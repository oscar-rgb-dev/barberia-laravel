<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['api' => 'working']);
});

Route::post('/register', function (Request $request) {
    return response()->json([
        'success' => true,
        'data' => $request->all(),
        'message' => 'Desde api.php sin CSRF'
    ]);
});

Route::post('/register-real', function (Request $request) {
    // Tu lógica real de registro aquí
    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'telefono' => $request->telefono
    ]);
    
    return response()->json([
        'success' => true,
        'user' => $user,
        'token' => 'token-' . time()
    ]);
});