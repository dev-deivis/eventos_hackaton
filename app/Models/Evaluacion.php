<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones';

    protected $fillable = [
        'equipo_id',
        'juez_id',
        'implementacion',
        'innovacion',
        'presentacion',
        'trabajo_equipo',
        'viabilidad',
        'calificacion_total',
        'comentarios',
        'fecha_evaluacion',
    ];

    protected $casts = [
        'fecha_evaluacion' => 'datetime',
        'implementacion' => 'decimal:2',
        'innovacion' => 'decimal:2',
        'presentacion' => 'decimal:2',
        'trabajo_equipo' => 'decimal:2',
        'viabilidad' => 'decimal:2',
        'calificacion_total' => 'decimal:2',
    ];

    /**
     * Relación con el equipo evaluado
     */
    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    /**
     * Relación con el juez que evaluó
     */
    public function juez(): BelongsTo
    {
        return $this->belongsTo(User::class, 'juez_id');
    }
}
