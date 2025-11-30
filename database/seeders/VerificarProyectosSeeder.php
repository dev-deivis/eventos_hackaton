<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proyecto;

class VerificarProyectosSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n=== PROYECTOS REGISTRADOS ===\n\n";
        
        $proyectos = Proyecto::with('equipo')->get();
        
        if ($proyectos->isEmpty()) {
            echo "❌ NO HAY PROYECTOS REGISTRADOS\n\n";
            echo "Necesitas registrar un proyecto para que se muestren los enlaces.\n";
            return;
        }
        
        foreach ($proyectos as $proyecto) {
            echo "ID: {$proyecto->id}\n";
            echo "Equipo: {$proyecto->equipo->nombre}\n";
            echo "Nombre: {$proyecto->nombre}\n";
            echo "Repositorio: " . ($proyecto->repositorio_url ?: '❌ NO REGISTRADO') . "\n";
            echo "Demo: " . ($proyecto->demo_url ?: '❌ NO REGISTRADO') . "\n";
            echo "Presentación: " . ($proyecto->presentacion_url ?: '❌ NO REGISTRADO') . "\n";
            echo "---\n\n";
        }
    }
}
