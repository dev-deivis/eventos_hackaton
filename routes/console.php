<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Programar actualización automática de estados de eventos
Schedule::command('eventos:actualizar-estados')
    ->hourly() // Ejecutar cada hora
    ->withoutOverlapping() // No ejecutar si ya está corriendo
    ->onSuccess(function () {
        \Illuminate\Support\Facades\Log::info('Estados de eventos actualizados correctamente');
    })
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error('Error al actualizar estados de eventos');
    });
