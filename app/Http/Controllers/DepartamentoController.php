<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::all();
        return view('departamentos.index', compact('departamentos'));
    }

    public function create()
    {
        return view('departamentos.create');
    }

    public function store(Request $request)
    {
        try {
            // Validación para DEPARTAMENTO
            $validated = $request->validate([
                'nombre_depto' => 'required|string|max:255|unique:departamentos,nombre_depto',
            ]);

            // Crear el DEPARTAMENTO
            $departamento = Departamento::create([
                'nombre_depto' => $request->nombre_depto,
            ]);

            return redirect()->route('admin.departamentos.index')->with('success', 'Departamento creado correctamente');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear el departamento: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $departamento = Departamento::findOrFail($id);
        return view('departamentos.edit', compact('departamento'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_depto' => 'required|string|max:255|unique:departamentos,nombre_depto,' . $id,
        ]);

        $departamento = Departamento::findOrFail($id);
        $departamento->update([
            'nombre_depto' => $request->nombre_depto,
        ]);

        return redirect()->route('admin.departamentos.index')->with('success', 'Departamento actualizado correctamente');
    }

    public function destroy($id)
    {
        Departamento::findOrFail($id)->delete();
        return redirect()->route('admin.departamentos.index')->with('success', 'Departamento eliminado correctamente');
    }
}