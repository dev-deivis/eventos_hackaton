<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'evento_id',
        'nombre',
        'descripcion',
        'lider_id',
        'max_miembros',
        'estado',
    ];

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    public function lider(): BelongsTo
    {
        return $this->belongsTo(Participante::class, 'lider_id');
    }

    public function participantes(): BelongsToMany
    {
        return $this->belongsToMany(Participante::class, 'equipo_participante', 'equipo_id', 'participante_id')
                    ->withPivot('perfil_id', 'estado')
                    ->using(EquipoParticipante::class)
                    ->withTimestamps();
    }

    public function proyecto(): HasOne
    {
        return $this->hasOne(Proyecto::class);
    }

    public function mensajes(): HasMany
    {
        return $this->hasMany(MensajeEquipo::class);
    }

    public function evaluaciones(): HasMany
    {
        return $this->hasMany(Evaluacion::class);
    }

    public function miembrosActivos()
    {
        return $this->participantes()
                    ->wherePivot('estado', 'activo')
                    ->with('carrera');
    }

    public function totalMiembros(): int
    {
        return $this->miembrosActivos()->count();
    }

    public function estaCompleto(): bool
    {
        return $this->totalMiembros() >= $this->evento->min_miembros_equipo;
    }

    public function puedeAceptarMiembros(): bool
    {
        return $this->totalMiembros() < $this->max_miembros;
    }

    /**
     * Verificar si un usuario es el líder del equipo
     */
    public function esLider($user): bool
    {
        // Si el usuario no tiene participante, no puede ser líder
        if (!$user->participante) {
            return false;
        }
        
        return $this->lider_id === $user->participante->id;
    }
}
