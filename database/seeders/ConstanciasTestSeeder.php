<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evento;
use App\Models\Participante;
use App\Models\Equipo;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ConstanciasTestSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Iniciando creación de datos de prueba...');
        $this->command->info('');

        // 1. Crear un evento completado para poder generar constancias
        $this->command->info('[1/5] Creando evento...');
        $evento = Evento::create([
            'nombre' => 'Hackathon 2025',
            'descripcion' => 'Evento de prueba para generar constancias',
            'tipo' => 'hackathon',
            'fecha_inicio' => now()->subDays(30),
            'fecha_fin' => now()->subDays(25),
            'fecha_limite_registro' => now()->subDays(31),
            'fecha_evaluacion' => now()->subDays(24),
            'fecha_premiacion' => now()->subDays(23),
            'ubicacion' => 'TecNM Campus Oaxaca',
            'es_virtual' => false,
            'duracion_horas' => 48,
            'max_participantes' => 100,
            'min_miembros_equipo' => 2,
            'max_miembros_equipo' => 5,
            'estado' => 'completado', // Evento completado
            'created_by' => 1, // Usuario admin
        ]);
        $this->command->info('   ✓ Evento creado: Hackathon 2025');

        // 2. Crear usuarios participantes
        $this->command->info('[2/5] Creando participantes...');
        $usuarios = [];
        $nombres = [
            'Karla Delgado Molina',
            'Jesús Martínez Martínez',
            'Ángel Zárate Matus',
            'María García López',
            'Carlos Hernández Silva'
        ];

        foreach ($nombres as $index => $nombre) {
            $email = 'participante' . ($index + 1) . '@tecnm.mx';
            
            // Verificar si el usuario ya existe
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                // Crear usuario
                $user = User::create([
                    'name' => $nombre,
                    'email' => $email,
                    'password' => Hash::make('password123'),
                ]);
                $this->command->info("   ✓ Usuario creado: {$nombre}");
            } else {
                $this->command->info("   ⚠ Usuario ya existe: {$nombre}");
            }

            // Verificar si el participante ya existe
            $participante = Participante::where('user_id', $user->id)->first();
            
            if (!$participante) {
                // Crear participante
                $participante = Participante::create([
                    'user_id' => $user->id,
                ]);
            }

            // Verificar si ya está inscrito al evento
            if (!$evento->participantes()->where('participante_id', $participante->id)->exists()) {
                // Inscribir al evento
                $evento->participantes()->attach($participante->id, [
                    'fecha_inscripcion' => now()->subDays(30),
                ]);
            }

            $usuarios[] = [
                'user' => $user,
                'participante' => $participante,
            ];
        }

        // 3. Crear equipos
        $this->command->info('[3/5] Creando equipos...');
        $equipos = [
            [
                'nombre' => 'The Boings',
                'lider_id' => $usuarios[0]['participante']->id,
                'miembros' => [0, 1],
                'proyecto' => 'Sistema de Gestión Infantil',
            ],
            [
                'nombre' => 'Equipo X',
                'lider_id' => $usuarios[2]['participante']->id,
                'miembros' => [2, 3],
                'proyecto' => 'Innovadores Tech',
            ],
        ];

        foreach ($equipos as $equipoData) {
            // Verificar si el equipo ya existe
            $equipoExiste = Equipo::where('evento_id', $evento->id)
                ->where('nombre', $equipoData['nombre'])
                ->first();

            if ($equipoExiste) {
                $this->command->info("   ⚠ Equipo ya existe: {$equipoData['nombre']}");
                continue;
            }

            // Crear equipo
            $equipo = Equipo::create([
                'evento_id' => $evento->id,
                'nombre' => $equipoData['nombre'],
                'descripcion' => 'Equipo de prueba para constancias',
                'lider_id' => $equipoData['lider_id'],
                'estado' => 'activo',
            ]);
            $this->command->info("   ✓ Equipo creado: {$equipoData['nombre']}");

            // Agregar miembros
            foreach ($equipoData['miembros'] as $miembroIndex) {
                if (!$equipo->participantes()->where('participante_id', $usuarios[$miembroIndex]['participante']->id)->exists()) {
                    $equipo->participantes()->attach($usuarios[$miembroIndex]['participante']->id, [
                        'fecha_union' => now()->subDays(28),
                        'estado' => 'aceptado',
                    ]);
                }
            }

            // 4. Crear proyecto
            $this->command->info('[4/5] Creando proyectos...');
            if (!$equipo->proyecto) {
                Proyecto::create([
                    'equipo_id' => $equipo->id,
                    'nombre' => $equipoData['proyecto'],
                    'descripcion' => 'Proyecto innovador desarrollado durante el hackathon',
                    'repositorio' => 'https://github.com/ejemplo/proyecto',
                    'demo' => 'https://demo.ejemplo.com',
                    'presentacion' => 'https://slides.ejemplo.com',
                    'tecnologias' => 'Laravel, React, MySQL',
                ]);
                $this->command->info("   ✓ Proyecto creado: {$equipoData['proyecto']}");
            }
        }

        $this->command->info('[5/5] Resumen final...');
        $this->command->info('');
        $this->command->info('✅ ¡Datos de prueba creados exitosamente!');
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('📋 RESUMEN:');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('   - Evento: Hackathon 2025 (COMPLETADO)');
        $this->command->info('   - Participantes: 5');
        $this->command->info('   - Equipos: 2');
        $this->command->info('   - Proyectos: 2');
        $this->command->info('');
        $this->command->info('🎯 EMAILS DE PRUEBA:');
        foreach ($nombres as $index => $nombre) {
            $email = 'participante' . ($index + 1) . '@tecnm.mx';
            $this->command->info("   - {$nombre}");
            $this->command->info("     Email: {$email}");
        }
        $this->command->info('');
        $this->command->info('🔑 Contraseña para todos: password123');
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('🚀 SIGUIENTE PASO:');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('1. Ve al navegador');
        $this->command->info('2. Dashboard Admin → Constancias');
        $this->command->info('3. Pestaña "Generar Nuevas"');
        $this->command->info('4. Email: participante1@tecnm.mx');
        $this->command->info('5. Evento: Hackathon 2025');
        $this->command->info('6. Tipo: Participación');
        $this->command->info('7. ¡Generar Constancia!');
        $this->command->info('');
    }
}
