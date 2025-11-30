<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Crear tabla pivot para asignaciones de tareas
        Schema::create('participante_tarea', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tareas_proyecto')->onDelete('cascade');
            $table->foreignId('participante_id')->constrained('participantes')->onDelete('cascade');
            $table->timestamps();
            
            // Un participante no puede estar asignado dos veces a la misma tarea
            $table->unique(['tarea_id', 'participante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participante_tarea');
    }
};
