<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CitaController;

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

// ============================================
// GESTIÓN DE PERFIL DE USUARIO
// ============================================

// Obtener perfil de usuario
Route::get('/user/{id}', function ($id) {
    try {
        $user = \App\Models\User::find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'telefono' => $user->telefono,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error del servidor',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Actualizar perfil de usuario
Route::put('/user/{id}', function (Request $request, $id) {
    try {
        $user = \App\Models\User::find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }
        
        // Validar
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string'
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->telefono = $request->telefono ?? $user->telefono;
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado exitosamente',
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
// RUTAS DE CITAS
// ============================================

// Obtener todas las citas (con filtro por user_id)
Route::get('/citas', [CitaController::class, 'index']);

// Crear nueva cita
Route::post('/citas', [CitaController::class, 'store']);

// Obtener una cita específica
Route::get('/citas/{id}', [CitaController::class, 'show']);

// Actualizar cita
Route::put('/citas/{id}', [CitaController::class, 'update']);

// Cancelar cita (marcar como cancelada)
Route::delete('/citas/{id}', function ($id) {
    try {
        $cita = \App\Models\Cita::find($id);
        
        if (!$cita) {
            return response()->json([
                'success' => false,
                'message' => 'Cita no encontrada'
            ], 404);
        }
        
        // Cambiar estado a cancelada en lugar de eliminar
        $cita->estado = 'cancelada';
        $cita->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Cita cancelada exitosamente',
            'data' => $cita
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error del servidor',
            'error' => $e->getMessage()
        ], 500);
    }
});

// ============================================
// RUTAS PÚBLICAS PARA INFORMACIÓN
// ============================================

// Obtener servicios disponibles
Route::get('/servicios', function () {
    try {
        $servicios = \App\Models\Servicio::all();
        return response()->json([
            'success' => true,
            'data' => $servicios
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error del servidor',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Obtener barberos disponibles
Route::get('/barberos', function () {
    try {
        $barberos = \App\Models\Empleado::with('jornada')->get();
        return response()->json([
            'success' => true,
            'data' => $barberos
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error del servidor',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Obtener horarios disponibles para un barbero y fecha
Route::get('/horarios-disponibles/{barbero_id}/{fecha}', function ($barbero_id, $fecha) {
    try {
        $controller = new \App\Http\Controllers\Api\CitaController();
        $request = new \Illuminate\Http\Request([
            'barbero_id' => $barbero_id,
            'fecha' => $fecha
        ]);
        
        return $controller->horariosDisponibles($request);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error del servidor',
            'error' => $e->getMessage()
        ], 500);
    }
});

// ============================================
// RUTAS DE PRUEBA Y DIAGNÓSTICO
// ============================================

// Ruta de prueba para verificar conexión
Route::get('/test', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API funcionando para Android',
        'timestamp' => now(),
        'endpoints_activos' => [
            '/register',
            '/login', 
            '/user/{id}',
            '/citas',
            '/servicios',
            '/barberos',
            '/horarios-disponibles/{barbero_id}/{fecha}'
        ]
    ]);
});

// Ruta para verificar estado de la base de datos
Route::get('/status', function () {
    try {
        $usersCount = \App\Models\User::count();
        $citasCount = \App\Models\Cita::count();
        $serviciosCount = \App\Models\Servicio::count();
        $barberosCount = \App\Models\Empleado::count();
        
        return response()->json([
            'success' => true,
            'database_status' => 'connected',
            'counts' => [
                'usuarios' => $usersCount,
                'citas' => $citasCount,
                'servicios' => $serviciosCount,
                'barberos' => $barberosCount
            ],
            'timestamp' => now()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'database_status' => 'error',
            'error' => $e->getMessage()
        ], 500);
    }
});