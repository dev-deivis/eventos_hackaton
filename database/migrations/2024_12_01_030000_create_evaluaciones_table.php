<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('juez_id')->constrained('users')->onDelete('cascade');
            
            // Criterios de evaluación (0-100)
            $table->decimal('implementacion', 5, 2);    // 30% - Calidad técnica
            $table->decimal('innovacion', 5, 2);        // 25% - Originalidad
            $table->decimal('presentacion', 5, 2);      // 20% - Claridad
            $table->decimal('trabajo_equipo', 5, 2);    // 15% - Colaboración
            $table->decimal('viabilidad', 5, 2);        // 10% - Potencial comercial
            
            // Calificación total (promedio ponderado)
            $table->decimal('calificacion_total', 5, 2);
            
            // Comentarios del juez
            $table->text('comentarios')->nullable();
            
            // Fecha de evaluación
            $table->timestamp('fecha_evaluacion');
            
            $table->timestamps();
            
            // Índices
            $table->index('equipo_id');
            $table->index('juez_id');
            $table->index('fecha_evaluacion');
            
            // Un juez solo puede evaluar un equipo una vez
            $table->unique(['equipo_id', 'juez_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
