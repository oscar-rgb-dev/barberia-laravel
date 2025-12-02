<?php

namespace App\Http\Controllers;

use App\Models\Jornada;
use Illuminate\Http\Request;

class JornadaController extends Controller
{
    public function index()
    {
        $jornadas = Jornada::all();
        return view('jornadas.index', compact('jornadas'));
    }

    public function create()
    {
        return view('jornadas.create');
    }

    public function store(Request $request)
    {
        Jornada::create($request->all());
        return redirect()->route('jornadas.index')->with('success', 'Jornada creada correctamente');
    }

    public function edit($id)
    {
        $jornada = Jornada::findOrFail($id);
        return view('jornadas.edit', compact('jornada'));
    }

    public function update(Request $request, $id)
    {
        $jornada = Jornada::findOrFail($id);
        $jornada->update($request->all());
        return redirect()->route('jornadas.index')->with('success', 'Jornada actualizada');
    }

    public function destroy($id)
    {
        Jornada::findOrFail($id)->delete();
        return redirect()->route('jornadas.index')->with('success', 'Jornada eliminada');
    }
}
