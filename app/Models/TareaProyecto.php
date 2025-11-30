<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TareaProyecto extends Model
{
    protected $table = 'tareas_proyecto';
    
    protected $fillable = [
        'proyecto_id',
        'asignado_a',
        'nombre',
        'descripcion',
        'estado',
        'orden',
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    /**
     * Relación con el proyecto
     */
    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }

    /**
     * Relación con participante asignado (legacy - un solo participante)
     */
    public function asignado(): BelongsTo
    {
        return $this->belongsTo(Participante::class, 'asignado_a');
    }

    /**
     * Relación con participantes asignados (múltiples)
     */
    public function participantes(): BelongsToMany
    {
        return $this->belongsToMany(Participante::class, 'participante_tarea', 'tarea_id', 'participante_id')
            ->withTimestamps();
    }

    /**
     * Obtener nombres de participantes asignados
     */
    public function nombresAsignados(): string
    {
        $participantes = $this->participantes;
        
        if ($participantes->isEmpty()) {
            return 'Sin asignar';
        }
        
        if ($participantes->count() === 1) {
            return $participantes->first()->user->name;
        }
        
        return $participantes->count() . ' participantes';
    }

    /**
     * Verificar si un participante está asignado a esta tarea
     */
    public function estaAsignado(Participante $participante): bool
    {
        return $this->participantes->contains('id', $participante->id);
    }

    /**
     * Calcular el valor porcentual de esta tarea
     */
    public function valorPorcentual(): float
    {
        $totalTareas = $this->proyecto->tareas()->count();
        
        if ($totalTareas === 0) {
            return 0;
        }
        
        return round(100 / $totalTareas, 2);
    }

    /**
     * Verificar si está completada
     */
    public function estaCompletada(): bool
    {
        return $this->estado === 'completada';
    }
}
