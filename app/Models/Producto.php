<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion', 
        'costo',
        'stock',
        'imagen'
        // Si tienes más campos en tu BD, agrégalos aquí
    ];

    protected $casts = [
        'costo' => 'decimal:2'
    ];

  
}