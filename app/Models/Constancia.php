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
        'codigo_qr',
        'fecha_emision',
        'ruta_pdf',
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
    ];

    public function participante(): BelongsTo
    {
        return $this->belongsTo(Participante::class);
    }

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    public static function generarCodigoQR(): string
    {
        return strtoupper(Str::random(20));
    }

    public function getTipoTextoAttribute(): string
    {
        $tipos = [
            'participacion' => 'Participación',
            'primer_lugar' => 'Primer Lugar',
            'segundo_lugar' => 'Segundo Lugar',
            'tercer_lugar' => 'Tercer Lugar',
            'mencion_honorifica' => 'Mención Honorífica',
        ];

        return $tipos[$this->tipo_constancia] ?? $this->tipo_constancia;
    }
}