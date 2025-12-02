<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_depto',
        'id_jornada',
        'nombre',
        'telefono',
        'email',
        'contraseña', 
    ];

    // Relación con Departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_depto');
    }

    // Relación con Jornada
    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'id_jornada');
    }
}