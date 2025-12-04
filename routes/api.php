<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Ruta de prueba GET
Route::get('/', function () {
    return response()->json([
        'app' => 'Barberia API para Android',
        'version' => '1.0',
        'status' => 'online'
    ]);
});

// ============================================
// REGISTRO - Compatible con Android
// ============================================
Route::post('/register', function (Request $request) {
    try {
        // Validación
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'telefono' => 'required|string'
        ]);

        // Crear usuario
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'telefono' => $request->telefono
        ]);
        
        // Respuesta COMPATIBLE con Android
        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado exitosamente',
            'token' => 'android-token-' . $user->id,  // Token fijo para Android
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'telefono' => $user->telefono
            ]
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error del servidor',
            'error' => $e->getMessage()
        ], 500);
    }
});

// ============================================
// LOGIN - Compatible con Android
// ============================================
Route::post('/login', function (Request $request) {
    try {
        // Validar
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Buscar usuario
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 401);
        }

        // Verificar contraseña
        if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Contraseña incorrecta'
            ], 401);
        }

        // Login exitoso - Respuesta COMPATIBLE con Android
        return response()->json([
            'success' => true,
            'message' => 'Login exitoso',
            'token' => 'android-token-' . $user->id,  // Mismo formato que registro
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'telefono' => $user->telefono
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error en login',
            'error' => $e->getMessage()
        ], 500);
    }
});

// ============================================
// RUTAS ADICIONALES PARA ANDROID
// ============================================

// Verificar token (para mantener sesión)
Route::post('/validate-token', function (Request $request) {
    $token = $request->header('Authorization') ?: $request->input('token');
    
    if (strpos($token, 'android-token-') === 0) {
        $userId = str_replace('android-token-', '', $token);
        $user = \App\Models\User::find($userId);
        
        if ($user) {
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        }
    }
    
    return response()->json([
        'success' => false,
        'message' => 'Token inválido'
    ], 401);
});

// Obtener perfil de usuario
Route::get('/user/{id}', function ($id) {
    $user = \App\Models\User::find($id);
    
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ], 404);
    }
    
    return response()->json([
        'success' => true,
        'user' => $user
    ]);
});

// Ruta de prueba para verificar conexión
Route::get('/test', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API funcionando para Android',
        'timestamp' => now()
    ]);
});