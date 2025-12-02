<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $fillable = [
        'id_empleado',
        'tipo_permiso',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];
}