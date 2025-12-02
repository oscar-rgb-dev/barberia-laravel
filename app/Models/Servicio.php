<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo_servicio', 
        'costo',
        'descripcion',
        'imagen_url',
        'duracion',
        'activo'
    ];

    protected $casts = [
        'costo' => 'decimal:2',
        'activo' => 'boolean'
    ];

    // CAMBIA EL NOMBRE DEL ACCESOR para evitar conflicto
    public function getImagenUrlCompletaAttribute()
    {
        if ($this->imagen_url) {
            return asset('storage/' . $this->imagen_url);
        }
        
        // Imagen por defecto si no hay imagen
        return asset('images/servicio-default.jpg');
    }

    // Scope para servicios activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}