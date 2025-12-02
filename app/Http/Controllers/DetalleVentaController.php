<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;

class DetalleVentaController extends Controller
{
    public function index()
    {
        $detalles = DetalleVenta::with(['venta', 'producto'])->get();
        return view('detalles.index', compact('detalles'));
    }

    public function show($id)
    {
        $detalle = DetalleVenta::with(['venta', 'producto'])->findOrFail($id);
        return view('detalles.show', compact('detalle'));
    }

    public function destroy($id)
    {
        DetalleVenta::findOrFail($id)->delete();
        return redirect()->route('detalles.index')->with('success', 'Detalle eliminado correctamente.');
    }
}
