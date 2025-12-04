<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\JornadaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarberoController; // <-- Asegúrate de tener esta
use App\Http\Controllers\BarberoCitaController;
use Illuminate\Http\Request; // <-- AÑADE ESTA LÍNEA
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServicioController as ApiServicioController;
use App\Http\Controllers\Api\ProductoController as ApiProductoController;
use App\Http\Controllers\Api\CitaController as ApiCitaController;


// Ruta principal
Route::get('/', [HomeController::class, 'index'])->name('home');

// ============================
// RUTAS PÚBLICAS
// ============================
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto');
Route::get('/productos', [ProductoController::class, 'catalogo'])->name('productos.catalogo');
Route::get('/citas/info-jornada', [CitaController::class, 'infoJornada'])->name('citas.info-jornada');
Route::get('/citas/horarios-disponibles', [CitaController::class, 'horariosDisponibles'])->name('citas.horarios-disponibles');
// Rutas de autenticación
Auth::routes();

// En routes/web.php
Route::get('/debug-images', function() {
    $rutaTmp = '/tmp/images/servicios/';
    
    return response()->json([
        'tmp_path' => $rutaTmp,
        'tmp_exists' => file_exists($rutaTmp),
        'files_in_tmp' => file_exists($rutaTmp) ? scandir($rutaTmp) : [],
        'servicios_db' => \App\Models\Servicio::select(['id', 'nombre', 'imagen_url'])->get()->toArray(),
        'last_servicio' => \App\Models\Servicio::latest()->first()
    ]);
});

// ============================
// RUTAS PARA CLIENTES
// ============================
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Citas para clientes
    Route::resource('citas', CitaController::class);
    Route::patch('/citas/{id}/cancelar', [CitaController::class, 'cancelar'])->name('citas.cancelar');
    Route::get('/citas/{id}/detalle', [CitaController::class, 'detalle'])->name('citas.detalle');
    // Evaluaciones
    Route::resource('evaluaciones', EvaluacionController::class);
});

// ============================
// RUTAS ADMINISTRATIVAS (accesibles desde cualquier parte)
// ============================
Route::middleware(['auth', 'admin'])->group(function () {
    // Rutas individuales para acceso rápido
    Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
    Route::get('/departamentos', [DepartamentoController::class, 'index'])->name('departamentos.index');
    Route::get('/jornadas', [JornadaController::class, 'index'])->name('jornadas.index');
    
    // Rutas CRUD completas para servicios (sin prefijo admin)
    Route::resource('servicios', ServicioController::class)->except(['index', 'show']);
});

// ============================
// RUTAS PARA BARBEROS
// ============================
Route::middleware(['auth', 'barbero'])->prefix('barbero')->name('barbero.')->group(function () {
    // Dashboard
// Usando el namespace completo
    Route::get('/dashboard', [App\Http\Controllers\Barbero\DashboardController::class, 'index'])
    ->name('dashboard');

    // Citas
    Route::get('/citas', [BarberoCitaController::class, 'index'])->name('citas.index');
    Route::get('/citas/{cita}', [BarberoCitaController::class, 'show'])->name('citas.show');
    Route::post('/citas/{cita}/estado', [BarberoCitaController::class, 'actualizarEstado'])->name('citas.actualizar-estado');
    Route::get('/horarios-disponibles', [BarberoCitaController::class, 'horariosDisponibles'])->name('citas.horarios-disponibles');
    
    // Clientes
    Route::get('/clientes', [BarberoCitaController::class, 'clientes'])->name('clientes');
    
    // Reportes
    Route::get('/reportes', [BarberoCitaController::class, 'reportes'])->name('reportes');
});

// ============================
// PANEL ADMINISTRATIVO COMPLETO
// ============================
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    
    // Dashboard del admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestión completa del sistema (con prefijo admin)
    Route::resource('empleados', EmpleadoController::class);
    Route::resource('departamentos', DepartamentoController::class);
    Route::resource('jornadas', JornadaController::class);
    Route::resource('users', UserController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('ventas', VentaController::class);
    Route::resource('detalles', DetalleVentaController::class);
    
    // Gestión administrativa de citas
    Route::get('/citas', [CitaController::class, 'indexAdmin'])->name('citas.index');
    Route::get('/citas/{id}', [CitaController::class, 'show'])->name('citas.show');
    Route::put('/citas/{id}', [CitaController::class, 'updateAdmin'])->name('citas.update');
    Route::delete('/citas/{id}', [CitaController::class, 'destroy'])->name('citas.destroy');
    Route::patch('/citas/{id}/estado', [CitaController::class, 'cambiarEstado'])->name('citas.estado');
});

// Redireccionar /admin a /admin/dashboard
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

// Ruta PÚBLICA para la tienda de servicios
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');

// Rutas ADMINISTRATIVAS para servicios
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/servicios', [ServicioController::class, 'indexAdmin'])->name('admin.servicios.index');
    Route::resource('admin/servicios', ServicioController::class)->except(['index']);
});

// ====================================================
// API SIN SEGURIDAD - PARA TRABAJO ESCOLAR
// ====================================================

Route::prefix('api')->group(function () {
    
    // Health Check
    Route::get('/', function () {
        return response()->json([
            'app' => 'Barberia API (Modo Escolar)',
            'version' => '1.0',
            'status' => 'online SIN SEGURIDAD',
            'warning' => 'API sin autenticación para desarrollo'
        ]);
    });
    
    // REGISTRO - SIN SEGURIDAD
    Route::post('/register', function (Request $request) {
        try {
            // Validación simple
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'telefono' => 'nullable',
                'password' => 'required|min:6'
            ]);
            
            // Crear usuario
            $user = \App\Models\User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'telefono' => $data['telefono'] ?? null,
                'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
                'role' => 'cliente'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado (modo escolar)',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'telefono' => $user->telefono
                ],
                'token' => 'fake-token-for-school',  // Token fijo
                'token_type' => 'Bearer'
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    });
    
    // LOGIN - SIN SEGURIDAD
    Route::post('/login', function (Request $request) {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $user = \App\Models\User::where('email', $data['email'])->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Login exitoso (modo escolar)',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'telefono' => $user->telefono
            ],
            'token' => 'fake-token-for-school',  // Mismo token fijo
            'token_type' => 'Bearer'
        ]);
    });
    
    // SERVICIOS - PÚBLICO
    Route::get('/servicios', function () {
        $servicios = \App\Models\Servicio::all();
        return response()->json([
            'success' => true,
            'data' => $servicios
        ]);
    });
    
    // CITAS - SIN AUTENTICACIÓN
    Route::get('/citas', function () {
        $citas = \App\Models\Cita::with('servicio')->get();
        return response()->json([
            'success' => true,
            'data' => $citas
        ]);
    });
    
    Route::post('/citas', function (Request $request) {
        $data = $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'user_email' => 'required|email',  // En lugar de user_id con auth
            'notas' => 'nullable'
        ]);
        
        // Buscar usuario por email
        $user = \App\Models\User::where('email', $data['user_email'])->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }
        
        $cita = \App\Models\Cita::create([
            'user_id' => $user->id,
            'servicio_id' => $data['servicio_id'],
            'fecha' => $data['fecha'],
            'hora' => $data['hora'],
            'notas' => $data['notas'] ?? null,
            'estado' => 'pendiente'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Cita creada',
            'data' => $cita->load('servicio')
        ], 201);
    });
});

Route::get('/api-test/super-simple', function() {
    return response()->json(['message' => 'Funciona al 100%']);
});

Route::post('/api-test/register-simple', function(Illuminate\Http\Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Registro simulado',
        'data' => $request->all()
    ]);
});