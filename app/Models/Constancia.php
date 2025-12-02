<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Constancia extends Model
{
    use HasFactory;

    protected $fillable = [
        'participante_id',
        'evento_id',
        'tipo',
        'codigo_verificacion',
        'fecha_emision',
        'ruta_pdf',
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
    ];

    // Tipos de constancia disponibles
    const TIPO_PARTICIPACION = 'participacion';
    const TIPO_PRIMER_LUGAR = 'primer_lugar';
    const TIPO_SEGUNDO_LUGAR = 'segundo_lugar';
    const TIPO_TERCER_LUGAR = 'tercer_lugar';
    const TIPO_MENCION = 'mencion_honorifica';

    /**
     * Obtener array de tipos disponibles con sus labels
     */
    public static function tipos(): array
    {
        return [
            self::TIPO_PARTICIPACION => '📜 Participación',
            self::TIPO_PRIMER_LUGAR => '🥇 Primer Lugar',
            self::TIPO_SEGUNDO_LUGAR => '🥈 Segundo Lugar',
            self::TIPO_TERCER_LUGAR => '🥉 Tercer Lugar',
            self::TIPO_MENCION => '⭐ Mención Honorífica',
        ];
    }

    public function participante(): BelongsTo
    {
        return $this->belongsTo(Participante::class);
    }

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    /**
     * Generar código único de verificación
     */
    public static function generarCodigoUnico(): string
    {
        do {
            $codigo = 'HACK' . strtoupper(Str::random(4)) . '-' 
                    . strtoupper(Str::random(3)) . '-' 
                    . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        } while (self::where('codigo_verificacion', $codigo)->exists());

        return $codigo;
    }

    /**
     * Obtener texto legible del tipo de constancia
     */
    public function getTipoTextoAttribute(): string
    {
        return self::tipos()[$this->tipo] ?? ucfirst($this->tipo);
    }

    /**
     * Obtener emoji según el tipo
     */
    public function getTipoEmojiAttribute(): string
    {
        return match($this->tipo) {
            self::TIPO_PARTICIPACION => '📜',
            self::TIPO_PRIMER_LUGAR => '🥇',
            self::TIPO_SEGUNDO_LUGAR => '🥈',
            self::TIPO_TERCER_LUGAR => '🥉',
            self::TIPO_MENCION => '⭐',
            default => '📄'
        };
    }

    /**
     * Obtener color según el tipo
     */
    public function getTipoColorAttribute(): string
    {
        return match($this->tipo) {
            self::TIPO_PARTICIPACION => 'purple',
            self::TIPO_PRIMER_LUGAR => 'yellow',
            self::TIPO_SEGUNDO_LUGAR => 'gray',
            self::TIPO_TERCER_LUGAR => 'orange',
            self::TIPO_MENCION => 'blue',
            default => 'indigo'
        };
    }
}