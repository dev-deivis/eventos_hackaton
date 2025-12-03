<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Roles del usuario
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'user_rol', 'user_id', 'rol_id')
                    ->withTimestamps();
    }

    /**
     * Perfil de participante
     */
    public function participante(): HasOne
    {
        return $this->hasOne(Participante::class);
    }

    /**
     * Notificaciones del usuario
     */
    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class);
    }

    /**
     * Calificaciones dadas como juez
     */
    public function calificacionesDadas(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'juez_user_id');
    }

    /**
     * Equipos asignados al juez para evaluar
     */
    public function equiposAsignados(): BelongsToMany
    {
        return $this->belongsToMany(Equipo::class, 'juez_equipo', 'juez_id', 'equipo_id')
                    ->withTimestamps();
    }

    /**
     * Eventos creados por este usuario
     */
    public function eventosCreados(): HasMany
    {
        return $this->hasMany(Evento::class, 'created_by');
    }

    // ========================================
    // HELPERS PARA ROLES
    // ========================================

    /**
     * Verificar si tiene un rol
     */
    public function tieneRol(string $nombreRol): bool
    {
        return $this->roles()->where('nombre', $nombreRol)->exists();
    }

    /**
     * Verificar si es administrador
     */
    public function isAdmin(): bool
    {
        return $this->tieneRol('admin');
    }

    /**
     * Verificar si es juez
     */
    public function isJuez(): bool
    {
        return $this->tieneRol('juez');
    }

    /**
     * Verificar si es participante
     */
    public function isParticipante(): bool
    {
        return $this->tieneRol('participante');
    }

    /**
     * Asignar rol
     */
    public function asignarRol(string $nombreRol): void
    {
        $rol = Rol::where('nombre', $nombreRol)->first();
        if ($rol && !$this->tieneRol($nombreRol)) {
            $this->roles()->attach($rol->id);
        }
    }

    /**
     * Verificar si es participante con perfil completo
     */
    public function esParticipanteCompleto(): bool
    {
        return $this->isParticipante() && $this->participante()->exists();
    }

    /**
     * Notificaciones no leídas
     */
    public function notificacionesNoLeidas()
    {
        return $this->notificaciones()->where('leida', false)->latest();
    }

    /**
     * Cantidad de notificaciones no leídas
     */
    public function cantidadNotificacionesNoLeidas(): int
    {
        return $this->notificaciones()->where('leida', false)->count();
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarNotificacionesComoLeidas(): void
    {
        $this->notificaciones()
            ->where('leida', false)
            ->update([
                'leida' => true,
                'leida_en' => now(),
            ]);
    }

    /**
     * Equipos activos del usuario (a través de su participante)
     */
    public function getEquiposActivosAttribute()
    {
        if (!$this->participante) {
            return collect([]); // Retorna colección vacía si no es participante
        }
        
        return $this->participante->equiposActivos()->get();
    }

    /**
     * Todos los equipos del usuario (a través de su participante)
     */
    public function getEquiposAttribute()
    {
        if (!$this->participante) {
            return collect([]); // Retorna colección vacía si no es participante
        }
        
        return $this->participante->equipos;
    }

    /**
     * Eventos del usuario (para estadísticas)
     */
    public function getEventosAttribute()
    {
        if (!$this->participante) {
            return collect([]);
        }
        
        // Eventos donde el usuario tiene equipos
        return $this->participante->equipos()->with('evento')->get()->pluck('evento')->unique('id');
    }

    /**
     * Proyectos completados del usuario
     * Nota: La tabla proyectos no tiene columna 'estado', 
     * por ahora retornamos el total de proyectos
     */
    public function getProyectosCompletadosAttribute()
    {
        if (!$this->participante) {
            return 0;
        }
        
        return $this->participante->equipos()
            ->whereHas('proyecto')
            ->count();
    }

    /**
     * Constancias del usuario
     */
    public function getConstanciasAttribute()
    {
        if (!$this->participante) {
            return collect([]);
        }
        
        return $this->participante->constancias;
    }
}