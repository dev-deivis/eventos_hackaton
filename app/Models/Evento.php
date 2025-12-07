<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Evento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'fecha_limite_registro',
        'fecha_evaluacion',
        'fecha_premiacion',
        'ubicacion',
        'es_virtual',
        'duracion_horas',
        'max_participantes',
        'min_miembros_equipo',
        'max_miembros_equipo',
        'roles_requeridos',
        'estado',
        'imagen_portada',
        'created_by',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'fecha_limite_registro' => 'datetime',
        'fecha_evaluacion' => 'datetime',
        'fecha_premiacion' => 'datetime',
        'es_virtual' => 'boolean',
        'roles_requeridos' => 'array',
    ];

    /**
     * Usuario que creó el evento
     */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Premios del evento
     */
    public function premios(): HasMany
    {
        return $this->hasMany(EventPremio::class)->orderBy('orden');
    }

    /**
     * Criterios de evaluación
     */
    public function criterios(): HasMany
    {
        return $this->hasMany(CriterioEvaluacion::class)->orderBy('orden');
    }

    /**
     * Equipos participantes
     */
    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class);
    }

    /**
     * Proyectos del evento
     */
    public function proyectos(): HasMany
    {
        return $this->hasMany(Proyecto::class);
    }

    /**
     * Constancias del evento
     */
    public function constancias(): HasMany
    {
        return $this->hasMany(Constancia::class);
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeAbiertos($query)
    {
        return $query->where('estado', 'abierto');
    }

    public function scopeActivos($query)
    {
        return $query->whereIn('estado', ['abierto', 'en_progreso']);
    }

    public function scopeProximos($query)
    {
        return $query->where('fecha_inicio', '>', Carbon::now())
                    ->where('estado', 'abierto')
                    ->orderBy('fecha_inicio', 'asc');
    }

    // ========================================
    // HELPERS
    // ========================================

    /**
     * Verificar si está abierto para inscripciones
     */
    public function estaAbierto(): bool
    {
        return $this->estado === 'abierto' &&
               Carbon::now()->lte($this->fecha_limite_registro);
    }

    /**
     * Puede aceptar más participantes
     */
    public function puedeRegistrarse(): bool
    {
        if (!$this->estaAbierto()) {
            return false;
        }

        if ($this->max_participantes) {
            $totalParticipantes = $this->totalParticipantes();
            return $totalParticipantes < $this->max_participantes;
        }

        return true;
    }

    /**
     * Total de participantes (contando miembros de equipos)
     */
    public function totalParticipantes(): int
    {
        return $this->equipos()
                    ->join('equipo_participante', 'equipos.id', '=', 'equipo_participante.equipo_id')
                    ->where('equipo_participante.estado', 'activo')
                    ->distinct('equipo_participante.participante_id')
                    ->count('equipo_participante.participante_id');
    }

    /**
     * Total de equipos
     */
    public function totalEquipos(): int
    {
        return $this->equipos()->count();
    }

    /**
     * Obtener el estado en español
     */
    public function getEstadoTextoAttribute(): string
    {
        $estados = [
            'draft' => 'Borrador',
            'abierto' => 'Abierto',
            'en_progreso' => 'En Progreso',
            'cerrado' => 'Cerrado',
            'completado' => 'Completado',
        ];

        return $estados[$this->estado] ?? $this->estado;
    }

    /**
     * Obtener el tipo en español
     */
    public function getTipoTextoAttribute(): string
    {
        $tipos = [
            'hackathon' => 'Hackathon',
            'datathon' => 'Datathon',
            'concurso' => 'Concurso',
            'workshop' => 'Workshop',
        ];

        return $tipos[$this->tipo] ?? $this->tipo;
    }

    /**
     * Obtener color del badge según el estado
     */
    public function getEstadoColorAttribute(): string
    {
        $colores = [
            'draft' => 'gray',
            'abierto' => 'green',
            'en_progreso' => 'blue',
            'cerrado' => 'red',
            'completado' => 'purple',
        ];

        return $colores[$this->estado] ?? 'gray';
    }

    /**
     * Actualizar automáticamente los estados de los eventos según las fechas
     */
    public static function actualizarEstadosAutomaticamente(): int
    {
        $ahora = Carbon::now();
        $actualizados = 0;

        // 1. Eventos que deberían estar EN PROGRESO
        $eventosEnProgreso = self::whereIn('estado', ['draft', 'abierto'])
            ->where('fecha_inicio', '<=', $ahora)
            ->where('fecha_fin', '>=', $ahora)
            ->get();

        foreach ($eventosEnProgreso as $evento) {
            $evento->update(['estado' => 'en_progreso']);
            $actualizados++;
        }

        // 2. Eventos que deberían estar COMPLETADOS
        $eventosCompletados = self::whereIn('estado', ['draft', 'abierto', 'en_progreso'])
            ->where('fecha_fin', '<', $ahora)
            ->get();

        foreach ($eventosCompletados as $evento) {
            $evento->update(['estado' => 'completado']);
            $actualizados++;
        }

        return $actualizados;
    }
}