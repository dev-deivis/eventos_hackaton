<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participante extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'carrera_id',
        'no_control',
        'semestre',
        'telefono',
        'biografia',
    ];

    /**
     * Usuario asociado
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Carrera del participante
     */
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    /**
     * Equipos en los que participa
     */
    public function equipos(): BelongsToMany
    {
        return $this->belongsToMany(Equipo::class, 'equipo_participante', 'participante_id', 'equipo_id')
                    ->withPivot('perfil_id', 'estado')
                    ->withTimestamps();
    }

    /**
     * Constancias del participante
     */
    public function constancias(): HasMany
    {
        return $this->hasMany(Constancia::class);
    }

    /**
     * Equipos donde es líder
     */
    public function equiposLiderados(): HasMany
    {
        return $this->hasMany(Equipo::class, 'lider_id');
    }

    /**
     * Verificar si está en un equipo específico
     */
    public function estaEnEquipo(int $equipoId): bool
    {
        return $this->equipos()->where('equipos.id', $equipoId)->exists();
    }

    /**
     * Obtener equipos activos
     */
    public function equiposActivos()
    {
        return $this->equipos()->wherePivot('estado', 'activo');
    }

    /**
     * Nombre completo del participante
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->user->name;
    }

    /**
     * Habilidades del participante
     */
    public function habilidades()
    {
        return $this->hasMany(Habilidad::class)->orderBy('orden');
    }

    /**
     * Tareas asignadas al participante
     */
    public function tareas()
    {
        return $this->belongsToMany(TareaProyecto::class, 'participante_tarea', 'participante_id', 'tarea_id')
            ->withTimestamps();
    }

    /**
     * Tareas pendientes del participante
     */
    public function tareasPendientes()
    {
        return $this->tareas()->where('estado', '!=', 'completada');
    }
}