<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_servicio',
        'id_barbero', 
        'fecha_hora',
        'estado',
        'notas',
        'total'
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    public function barbero()
    {
        return $this->belongsTo(Empleado::class, 'id_barbero');
    }

    // Relación muchos a muchos con productos
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'cita_producto')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }

    // Método para calcular el total
    public function calcularTotal()
    {
        $total = $this->servicio->costo;
        
        foreach ($this->productos as $producto) {
            $total += $producto->costo * $producto->pivot->cantidad;
        }
        
        return $total;
    }
}