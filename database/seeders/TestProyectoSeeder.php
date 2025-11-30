<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipo;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Log;

class TestProyectoSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n=== TEST CREAR PROYECTO ===\n\n";
        
        // Obtener un equipo sin proyecto
        $equipo = Equipo::whereDoesntHave('proyecto')->first();
        
        if (!$equipo) {
            echo "❌ No hay equipos sin proyecto para probar\n";
            return;
        }
        
        echo "Equipo seleccionado: {$equipo->nombre} (ID: {$equipo->id})\n";
        echo "Evento: {$equipo->evento->nombre}\n\n";
        
        try {
            $proyecto = Proyecto::create([
                'equipo_id' => $equipo->id,
                'evento_id' => $equipo->evento_id,
                'nombre' => 'Proyecto de Prueba',
                'descripcion' => 'Esta es una descripción de prueba para verificar que funciona la creación de proyectos.',
                'link_repositorio' => 'https://github.com/test/repo',
                'link_demo' => 'https://test-demo.com',
                'link_presentacion' => 'https://docs.google.com/presentation/test',
                'tecnologias' => 'Laravel, Vue.js, MySQL',
            ]);
            
            echo "✅ PROYECTO CREADO EXITOSAMENTE\n\n";
            echo "ID: {$proyecto->id}\n";
            echo "Nombre: {$proyecto->nombre}\n";
            echo "Equipo: {$proyecto->equipo->nombre}\n";
            echo "Repositorio: {$proyecto->link_repositorio}\n";
            echo "Demo: {$proyecto->link_demo}\n";
            echo "Presentación: {$proyecto->link_presentacion}\n";
            
        } catch (\Exception $e) {
            echo "❌ ERROR AL CREAR PROYECTO\n\n";
            echo "Error: {$e->getMessage()}\n";
            echo "Archivo: {$e->getFile()}\n";
            echo "Línea: {$e->getLine()}\n";
        }
    }
}
