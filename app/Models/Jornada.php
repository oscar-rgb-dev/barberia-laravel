<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jornada extends Model
{
    use HasFactory;

    protected $fillable = ['descripcion', 'horario'];

    // Para acceder a la descripción como "nombre"
    public function getNombreAttribute()
    {
        return $this->descripcion;
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'id_jornada');
    }
}