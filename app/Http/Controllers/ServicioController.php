<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServicioController extends Controller
{
    /**
     * Asegurar que las carpetas existan
     */
    public function __construct()
    {
        // Crear carpeta si no existe
        $carpetaServicios = public_path('images/servicios');
        if (!File::exists($carpetaServicios)) {
            File::makeDirectory($carpetaServicios, 0755, true);
        }
    }

    /**
     * Vista PÚBLICA - Tienda para clientes
     */
    public function index()
    {
        $servicios = Servicio::all();
        return view('servicios.tienda', compact('servicios'));
    }

    /**
     * Vista ADMINISTRATIVA - Para administradores (CRUD)
     */
    public function indexAdmin()
    {
        $servicios = Servicio::all();
        return view('servicios.index', compact('servicios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servicios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   

        $carpeta = public_path('images/servicios');
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0755, true);
            chmod($carpeta, 0755);
        }

        // Verificar permisos
        $permisos = substr(sprintf('%o', fileperms($carpeta)), -4);
        \Log::info('Permisos carpeta', ['carpeta' => $carpeta, 'permisos' => $permisos]);
        \Log::info('Inicio store', ['request' => $request->all()]);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_servicio' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'descripcion' => 'required|string',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        \Log::info('Validación pasada');
        
        $data = $request->all();
        \Log::info('Datos recibidos', $data);

        try {
            // GUARDAR EN PUBLIC/IMAGES/SERVICIOS (NO EN STORAGE)
            if ($request->hasFile('imagen')) {
                \Log::info('Tiene archivo de imagen');
                
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                \Log::info('Nombre imagen generado', ['nombre' => $nombreImagen]);
                
                // Verificar si la carpeta existe
                $carpetaDestino = public_path('images/servicios');
                \Log::info('Carpeta destino', ['path' => $carpetaDestino, 'exists' => file_exists($carpetaDestino)]);
                
                // Crear carpeta si no existe
                if (!file_exists($carpetaDestino)) {
                    mkdir($carpetaDestino, 0755, true);
                    \Log::info('Carpeta creada');
                }
                
                // Mover a public/images/servicios
                $imagen->move($carpetaDestino, $nombreImagen);
                \Log::info('Imagen movida');
                
                // Guardar ruta relativa
                $data['imagen_url'] = 'images/servicios/' . $nombreImagen;
                \Log::info('URL guardada', ['url' => $data['imagen_url']]);
            }

            \Log::info('Creando servicio en BD', $data);
            Servicio::create($data);
            \Log::info('Servicio creado exitosamente');

            return redirect()->route('admin.servicios.index')->with('success', 'Servicio creado correctamente');
            
        } catch (\Exception $e) {
            \Log::error('Error al crear servicio', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()->withErrors(['error' => 'Error al crear servicio: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $servicio = Servicio::findOrFail($id);
        return view('servicios.edit', compact('servicio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_servicio' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        // Manejar la subida de la nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($servicio->imagen_url && file_exists(public_path($servicio->imagen_url))) {
                unlink(public_path($servicio->imagen_url));
            }
            
            // Guardar nueva imagen
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('images/servicios'), $nombreImagen);
            $data['imagen_url'] = 'images/servicios/' . $nombreImagen;
        }

        $servicio->update($data);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio actualizado correctamente');
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        
        // Eliminar imagen si existe
        if ($servicio->imagen_url && file_exists(public_path($servicio->imagen_url))) {
            unlink(public_path($servicio->imagen_url));
        }
        
        $servicio->delete();

        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente');
    }
}