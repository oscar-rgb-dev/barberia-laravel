<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Empleado;
use Illuminate\Http\Request;

class EvaluacionesController extends Controller
{
    public function index()
    {
        $evaluaciones = Evaluacion::with(['cita', 'cita.cliente', 'cita.empleado'])->get();
        return view('evaluaciones.index', compact('evaluaciones'));
    }

    public function create()
    {
        $citas = Cita::with(['cliente', 'empleado'])->get();
        return view('evaluaciones.create', compact('citas'));
    }

    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'id_cita' => 'required|exists:citas,id',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentarios' => 'required|string|max:1000'
        ]);

        Evaluacion::create($request->all());

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluación registrada correctamente.');
    }

    public function edit($id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        $citas = Cita::with(['cliente', 'empleado'])->get();
        return view('evaluaciones.edit', compact('evaluacion', 'citas'));
    }

    public function update(Request $request, $id)
    {
        // Validación
        $request->validate([
            'id_cita' => 'required|exists:citas,id',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentarios' => 'required|string|max:1000'
        ]);

        $evaluacion = Evaluacion::findOrFail($id);
        $evaluacion->update($request->all());

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        $evaluacion->delete();
        
        return redirect()->route('evaluaciones.index')->with('success', 'Evaluación eliminada correctamente.');
    }
}