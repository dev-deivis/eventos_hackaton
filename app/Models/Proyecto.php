<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyecto extends Model
{
    use HasFactory;

    // Estados del proyecto
    const ESTADO_BORRADOR = 'borrador';
    const ESTADO_EN_PROGRESO = 'en_progreso';
    const ESTADO_PENDIENTE_REVISION = 'pendiente_revision';
    const ESTADO_ENTREGADO = 'entregado';
    const ESTADO_LISTO_EVALUAR = 'listo_para_evaluar';
    const ESTADO_EVALUADO = 'evaluado';
    const ESTADO_FINALIZADO = 'finalizado';

    protected $fillable = [
        'equipo_id',
        'evento_id',
        'nombre',
        'descripcion',
        'estado',
        'link_repositorio',
        'link_demo',
        'link_presentacion',
        'tecnologias',
        'porcentaje_completado',
        'entrega_completa',
        'fecha_entrega',
        'calificacion_final',
        'posicion_final',
    ];

    protected $casts = [
        'calificacion_final' => 'decimal:2',
        'entrega_completa' => 'boolean',
        'fecha_entrega' => 'datetime',
    ];

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class);
    }

    public function tareas(): HasMany
    {
        return $this->hasMany(TareaProyecto::class);
    }

    /**
     * Verificar si el proyecto cumple con requisitos mínimos
     */
    public function cumpleRequisitosMinimos(): bool
    {
        $evento = $this->evento;
        $checks = [];
        
        // 1. Verificar nombre y descripción
        $checks[] = !empty($this->nombre) && strlen($this->nombre) >= 5;
        $checks[] = !empty($this->descripcion) && strlen($this->descripcion) >= 50;
        
        // 2. Verificar links requeridos
        if ($evento->requiere_repositorio) {
            $checks[] = !empty($this->link_repositorio) && filter_var($this->link_repositorio, FILTER_VALIDATE_URL);
        }
        
        if ($evento->requiere_demo) {
            $checks[] = !empty($this->link_demo) && filter_var($this->link_demo, FILTER_VALIDATE_URL);
        }
        
        if ($evento->requiere_presentacion) {
            $checks[] = !empty($this->link_presentacion) && filter_var($this->link_presentacion, FILTER_VALIDATE_URL);
        }
        
        // 3. Verificar tareas
        $totalTareas = $this->tareas()->count();
        $tareasCompletadas = $this->tareas()->where('estado', 'completada')->count();
        
        $checks[] = $totalTareas >= $evento->min_tareas_proyecto;
        $checks[] = $totalTareas > 0 && $totalTareas === $tareasCompletadas; // Todas completas
        
        // Todas las validaciones deben pasar
        return !in_array(false, $checks, true);
    }

    /**
     * Calcular porcentaje de completitud
     */
    public function calcularPorcentajeCompletado(): int
    {
        $checks = 0;
        $total = 0;
        
        // Nombre (1 punto)
        $total++;
        if (!empty($this->nombre) && strlen($this->nombre) >= 5) $checks++;
        
        // Descripción (1 punto)
        $total++;
        if (!empty($this->descripcion) && strlen($this->descripcion) >= 50) $checks++;
        
        // Links (3 puntos)
        $evento = $this->evento;
        
        if ($evento->requiere_repositorio) {
            $total++;
            if (!empty($this->link_repositorio)) $checks++;
        }
        
        if ($evento->requiere_demo) {
            $total++;
            if (!empty($this->link_demo)) $checks++;
        }
        
        if ($evento->requiere_presentacion) {
            $total++;
            if (!empty($this->link_presentacion)) $checks++;
        }
        
        // Tareas (peso mayor: 50% del total)
        $totalTareas = $this->tareas()->count();
        $tareasCompletadas = $this->tareas()->where('estado', 'completada')->count();
        
        if ($totalTareas > 0) {
            $porcentajeTareas = ($tareasCompletadas / $totalTareas) * 50;
        } else {
            $porcentajeTareas = 0;
        }
        
        // Calcular porcentaje base (50%)
        $porcentajeBase = $total > 0 ? (($checks / $total) * 50) : 0;
        
        return round($porcentajeBase + $porcentajeTareas);
    }

    /**
     * Actualizar porcentaje automáticamente
     */
    public function actualizarPorcentaje(): void
    {
        $porcentaje = $this->calcularPorcentajeCompletado();
        $this->update(['porcentaje_completado' => $porcentaje]);
        
        // Actualizar estado automáticamente
        if ($porcentaje === 100 && $this->estado === self::ESTADO_EN_PROGRESO) {
            $this->update(['estado' => self::ESTADO_PENDIENTE_REVISION]);
        }
    }

    /**
     * Realizar entrega final (acción del equipo)
     */
    public function entregarProyecto(): bool
    {
        if (!$this->cumpleRequisitosMinimos()) {
            return false;
        }
        
        $this->update([
            'estado' => self::ESTADO_ENTREGADO,
            'fecha_entrega' => now(),
            'entrega_completa' => true,
            'porcentaje_completado' => 100,
        ]);
        
        // Actualizar equipo
        $this->equipo->update([
            'proyecto_entregado' => true,
            'fecha_entrega_proyecto' => now(),
        ]);
        
        return true;
    }

    /**
     * Aprobar proyecto para evaluación (acción del admin)
     */
    public function aprobarParaEvaluacion(): void
    {
        $this->update([
            'estado' => self::ESTADO_LISTO_EVALUAR,
        ]);
    }

    /**
     * Rechazar proyecto (acción del admin)
     */
    public function rechazarProyecto(string $motivo = null): void
    {
        $this->update([
            'estado' => self::ESTADO_EN_PROGRESO,
            'entrega_completa' => false,
        ]);
        
        $this->equipo->update([
            'proyecto_entregado' => false,
            'fecha_entrega_proyecto' => null,
        ]);
    }

    /**
     * Verificar si está listo para evaluar
     */
    public function estaListoParaEvaluar(): bool
    {
        return $this->estado === self::ESTADO_LISTO_EVALUAR;
    }

    /**
     * Marcar como evaluado
     */
    public function marcarComoEvaluado(): void
    {
        $this->update([
            'estado' => self::ESTADO_EVALUADO,
        ]);
    }

    /**
     * Obtener requisitos faltantes
     */
    public function requisitosFaltantes(): array
    {
        $faltantes = [];
        $evento = $this->evento;
        
        if (empty($this->nombre) || strlen($this->nombre) < 5) {
            $faltantes[] = 'Nombre del proyecto (mínimo 5 caracteres)';
        }
        
        if (empty($this->descripcion) || strlen($this->descripcion) < 50) {
            $faltantes[] = 'Descripción del proyecto (mínimo 50 caracteres)';
        }
        
        if ($evento->requiere_repositorio && empty($this->link_repositorio)) {
            $faltantes[] = 'Link del repositorio';
        }
        
        if ($evento->requiere_demo && empty($this->link_demo)) {
            $faltantes[] = 'Link de la demo';
        }
        
        if ($evento->requiere_presentacion && empty($this->link_presentacion)) {
            $faltantes[] = 'Link de la presentación';
        }
        
        $totalTareas = $this->tareas()->count();
        $tareasCompletadas = $this->tareas()->where('estado', 'completada')->count();
        
        if ($totalTareas < $evento->min_tareas_proyecto) {
            $faltantes[] = 'Mínimo ' . $evento->min_tareas_proyecto . ' tareas (tienes ' . $totalTareas . ')';
        }
        
        if ($totalTareas > 0 && $tareasCompletadas < $totalTareas) {
            $faltantes[] = ($totalTareas - $tareasCompletadas) . ' tareas sin completar';
        }
        
        return $faltantes;
    }

    /**
     * Obtener texto del estado
     */
    public function getEstadoTextoAttribute(): string
    {
        $estados = [
            self::ESTADO_BORRADOR => 'Borrador',
            self::ESTADO_EN_PROGRESO => 'En Progreso',
            self::ESTADO_PENDIENTE_REVISION => 'Pendiente de Revisión',
            self::ESTADO_ENTREGADO => 'Entregado',
            self::ESTADO_LISTO_EVALUAR => 'Listo para Evaluar',
            self::ESTADO_EVALUADO => 'Evaluado',
            self::ESTADO_FINALIZADO => 'Finalizado',
        ];
        
        return $estados[$this->estado] ?? $this->estado;
    }

    /**
     * Obtener color del badge según estado
     */
    public function getEstadoColorAttribute(): string
    {
        $colores = [
            self::ESTADO_BORRADOR => 'gray',
            self::ESTADO_EN_PROGRESO => 'blue',
            self::ESTADO_PENDIENTE_REVISION => 'yellow',
            self::ESTADO_ENTREGADO => 'purple',
            self::ESTADO_LISTO_EVALUAR => 'green',
            self::ESTADO_EVALUADO => 'indigo',
            self::ESTADO_FINALIZADO => 'pink',
        ];
        
        return $colores[$this->estado] ?? 'gray';
    }

    public function calcularCalificacionFinal(): float
    {
        $criterios = $this->evento->criterios;
        $calificacionTotal = 0;

        foreach ($criterios as $criterio) {
            $promedioCalificaciones = $this->calificaciones()
                ->where('criterio_id', $criterio->id)
                ->avg('puntuacion');
            
            if ($promedioCalificaciones) {
                $calificacionTotal += ($promedioCalificaciones * $criterio->ponderacion) / 100;
            }
        }

        return round($calificacionTotal, 2);
    }
}
