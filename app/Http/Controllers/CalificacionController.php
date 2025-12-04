<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalificacionController extends Controller
{
    /**
     * Mostrar formulario de calificación
     */
    public function mostrarFormulario($id)
    {
        $cita = Cita::findOrFail($id);
        
        // Verificar que el usuario puede calificar esta cita
        if ($cita->id_usuario !== Auth::id()) {
            abort(403, 'No puedes calificar esta cita');
        }

        if (!$cita->puedeCalificar()) {
            return redirect()->route('citas.historial')
                ->with('error', 'Esta cita no puede ser calificada o ya fue calificada');
        }

        return view('citas.calificar', compact('cita'));
    }

    /**
     * Guardar calificación
     */
    public function guardar(Request $request, $id)
    {
        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
        ]);

        $cita = Cita::findOrFail($id);
        
        // Verificar que el usuario puede calificar esta cita
        if ($cita->id_usuario !== Auth::id()) {
            abort(403, 'No puedes calificar esta cita');
        }

        if (!$cita->puedeCalificar()) {
            return redirect()->route('citas.historial')
                ->with('error', 'Esta cita no puede ser calificada');
        }

        $cita->update([
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario,
            'calificado_en' => now(),
        ]);

        return redirect()->route('citas.historial')
            ->with('success', '¡Gracias por tu calificación! Tu feedback es importante para nosotros.');
    }
}