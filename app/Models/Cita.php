<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        // ... otros campos existentes
        'calificacion',
        'comentario',
        'calificado_en'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'calificado_en' => 'datetime',
    ];

    // ... otros métodos existentes

    /**
     * Obtener la calificación en estrellas
     */
    public function getEstrellasAttribute()
    {
        if (!$this->calificacion) {
            return 'No calificado';
        }

        $estrellas = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->calificacion) {
                $estrellas .= '★';
            } else {
                $estrellas .= '☆';
            }
        }
        return $estrellas;
    }

    /**
     * Obtener la descripción de la calificación
     */
    public function getDescripcionCalificacionAttribute()
    {
        if (!$this->calificacion) {
            return 'Sin calificar';
        }

        switch ($this->calificacion) {
            case 1: return 'Muy Malo';
            case 2: return 'Malo';
            case 3: return 'Regular';
            case 4: return 'Bueno';
            case 5: return 'Excelente';
            default: return 'Sin calificar';
        }
    }

    /**
     * Verificar si la cita puede ser calificada
     */
    public function puedeCalificar()
    {
        return $this->estado === 'completada' && !$this->calificacion && $this->fecha_hora->diffInDays(now()) <= 7;
    }
}