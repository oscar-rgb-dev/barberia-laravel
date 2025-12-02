<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $fillable = ['nombre_depto'];

    // Si quieres usar un nombre de columna diferente al estándar
    public function getNombreAttribute()
    {
        return $this->nombre_depto;
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'id_depto');
    }
}