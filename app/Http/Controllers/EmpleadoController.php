<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\User; // ¡IMPORTANTE: Agregar esta línea!
use App\Models\Departamento;
use App\Models\Jornada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash; // ¡IMPORTANTE: Agregar esta línea!

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with(['departamento', 'jornada'])->get();
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $departamentos = Departamento::all();
        $jornadas = Jornada::all();
        return view('empleados.create', compact('departamentos', 'jornadas'));
    }

    public function store(Request $request)
    {
        // Paso 1: Verificar que los datos llegan
        //dd('Datos recibidos:', $request->all()); // DESCOMENTA ESTO PRIMERO
        
        // Paso 2: Validar los datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:empleados,email',
            'telefono' => 'required|string',
            'contraseña' => 'required|min:6|confirmed',
            'id_depto' => 'required|exists:departamentos,id',
            'id_jornada' => 'required|exists:jornadas,id',
        ]);
        
        //dd('Validación exitosa:', $validated); // DESCOMENTA ESTO SEGUNDO

        // Usar transacción
        DB::beginTransaction();

        try {
            // 1. Crear el usuario para login
            $userData = [
                'name' => $validated['nombre'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['contraseña']),
                'role' => 'barbero',
            ];
            
            //dd('Datos del usuario a crear:', $userData); // DESCOMENTA ESTO TERCERO
            
            $user = User::create($userData);
            
            //dd('Usuario creado:', $user); // DESCOMENTA ESTO CUARTO
            
            // 2. Crear el empleado
            $empleadoData = [
                'id_depto' => $validated['id_depto'],
                'id_jornada' => $validated['id_jornada'],
                'nombre' => $validated['nombre'],
                'telefono' => $validated['telefono'],
                'email' => $validated['email'],
                'contraseña' => Hash::make($validated['contraseña']), // CAMBIADO: 'contrasena'
            ];
            
            //dd('Datos del empleado a crear:', $empleadoData); // DESCOMENTA ESTO QUINTO
            
            $empleado = Empleado::create($empleadoData);
            
            //dd('Empleado creado:', $empleado); // DESCOMENTA ESTO SEXTO

            DB::commit();

            return redirect()->route('admin.empleados.index')
                ->with('success', 'Empleado creado exitosamente. El barbero puede iniciar sesión con su email y contraseña.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Esto mostrará el error exacto
            //dd('Error en store:', $e->getMessage(), $e->getFile(), $e->getLine());
            
            return back()->with('error', 'Error al crear el empleado: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function edit(Empleado $empleado)
    {
        $departamentos = Departamento::all();
        $jornadas = Jornada::all();
        
        // Buscar el usuario asociado al email del empleado
        $user = User::where('email', $empleado->email)->first();
        
        return view('empleados.edit', compact('empleado', 'departamentos', 'jornadas', 'user'));
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);
        
        // CORRECCIÓN: Usar $validated y validar correctamente
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|email|unique:empleados,email,' . $id,
            'contraseña' => 'nullable|min:6|confirmed',
            'id_depto' => 'required|exists:departamentos,id',
            'id_jornada' => 'required|exists:jornadas,id',
        ]);
        
        DB::beginTransaction();

        try {
            // Guardar email original para buscar usuario
            $emailOriginal = $empleado->email;
            
            // 1. Actualizar el empleado
            $empleadoData = [
                'id_depto' => $validated['id_depto'],
                'id_jornada' => $validated['id_jornada'],
                'nombre' => $validated['nombre'],
                'telefono' => $validated['telefono'],
                'email' => $validated['email'],
            ];
            
            // Si se proporcionó una nueva contraseña, actualizarla
            if (!empty($validated['contraseña'])) {
                $empleadoData['contrasena'] = Hash::make($validated['contraseña']);
            }
            
            $empleado->update($empleadoData);

            // 2. Buscar y actualizar el usuario correspondiente
            $user = User::where('email', $emailOriginal)->first();
            
            if ($user) {
                $userData = [
                    'name' => $validated['nombre'],
                    'email' => $validated['email'],
                ];
                
                // Actualizar contraseña si se proporcionó
                if (!empty($validated['contraseña'])) {
                    $userData['password'] = Hash::make($validated['contraseña']);
                }
                
                $user->update($userData);
            }

            DB::commit();

            return redirect()->route('admin.empleados.index')
                ->with('success', 'Empleado actualizado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el empleado: ' . $e->getMessage());
        }
    }

    public function destroy(Empleado $empleado)
    {
        DB::beginTransaction();

        try {
            // Buscar y eliminar el usuario correspondiente
            $user = User::where('email', $empleado->email)->first();
            if ($user) {
                $user->delete();
            }

            // Eliminar el empleado
            $empleado->delete();

            DB::commit();

            return redirect()->route('admin.empleados.index')
                ->with('success', 'Empleado eliminado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar el empleado: ' . $e->getMessage());
        }
    }
}