<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        return Cliente::all();
    }

    public function store(Request $request)
    {
        $data = $request->only(['nombre','telefono','email','password']);
        $data['password'] = bcrypt($data['password'] ?? '1234');
        $cliente = Cliente::create($data);
        return response()->json($cliente, 201);
    }

    public function show($id)
    {
        return Cliente::find($id);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) return response()->json(['message'=>'No encontrado'],404);
        $cliente->update($request->all());
        return $cliente;
    }

    public function destroy($id)
    {
        Cliente::destroy($id);
        return response()->json(null, 204);
    }
}
