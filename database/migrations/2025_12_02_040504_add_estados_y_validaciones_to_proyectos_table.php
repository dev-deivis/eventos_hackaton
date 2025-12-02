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
        Schema::table('proyectos', function (Blueprint $table) {
            // Estado del proyecto
            $table->enum('estado', [
                'borrador',
                'en_progreso',
                'pendiente_revision',
                'entregado',
                'listo_para_evaluar',
                'evaluado',
                'finalizado'
            ])->default('borrador')->after('descripcion');
            
            // Fecha de entrega formal
            $table->timestamp('fecha_entrega')->nullable()->after('estado');
            
            // Porcentaje de completitud (0-100)
            $table->integer('porcentaje_completado')->default(0)->after('fecha_entrega');
            
            // Flag de entrega completa
            $table->boolean('entrega_completa')->default(false)->after('porcentaje_completado');
        });
        
        // Actualizar tabla de eventos con requisitos
        Schema::table('eventos', function (Blueprint $table) {
            // Mínimo de tareas requeridas por proyecto
            $table->integer('min_tareas_proyecto')->default(5)->after('max_miembros_equipo');
            
            // Requisitos de links
            $table->boolean('requiere_demo')->default(true)->after('min_tareas_proyecto');
            $table->boolean('requiere_repositorio')->default(true)->after('requiere_demo');
            $table->boolean('requiere_presentacion')->default(true)->after('requiere_repositorio');
        });
        
        // Actualizar tabla de equipos con flags de entrega
        Schema::table('equipos', function (Blueprint $table) {
            $table->boolean('proyecto_entregado')->default(false)->after('estado');
            $table->timestamp('fecha_entrega_proyecto')->nullable()->after('proyecto_entregado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn([
                'estado',
                'fecha_entrega',
                'porcentaje_completado',
                'entrega_completa'
            ]);
        });
        
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn([
                'min_tareas_proyecto',
                'requiere_demo',
                'requiere_repositorio',
                'requiere_presentacion'
            ]);
        });
        
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropColumn([
                'proyecto_entregado',
                'fecha_entrega_proyecto'
            ]);
        });
    }
};
