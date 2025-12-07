<?php

namespace App\Console\Commands;

use App\Models\Evento;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ActualizarEstadoEventos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eventos:actualizar-estados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza automáticamente los estados de los eventos según sus fechas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Actualizando estados de eventos...');
        
        $ahora = Carbon::now();
        $actualizados = 0;

        // 1. Eventos que deberían estar EN PROGRESO (en_progreso)
        $eventosEnProgreso = Evento::whereIn('estado', ['draft', 'abierto'])
            ->where('fecha_inicio', '<=', $ahora)
            ->where('fecha_fin', '>=', $ahora)
            ->get();

        foreach ($eventosEnProgreso as $evento) {
            $evento->update(['estado' => 'en_progreso']);
            $this->line("✅ '{$evento->nombre}' → EN PROGRESO");
            $actualizados++;
            
            Log::info("Evento cambiado a EN PROGRESO", [
                'evento_id' => $evento->id,
                'nombre' => $evento->nombre
            ]);
        }

        // 2. Eventos que deberían estar COMPLETADOS
        $eventosCompletados = Evento::whereIn('estado', ['draft', 'abierto', 'en_progreso'])
            ->where('fecha_fin', '<', $ahora)
            ->get();

        foreach ($eventosCompletados as $evento) {
            $evento->update(['estado' => 'completado']);
            $this->line("✅ '{$evento->nombre}' → COMPLETADO");
            $actualizados++;
            
            Log::info("Evento cambiado a COMPLETADO", [
                'evento_id' => $evento->id,
                'nombre' => $evento->nombre
            ]);
        }

        if ($actualizados === 0) {
            $this->info('ℹ️  No hay eventos que actualizar');
        } else {
            $this->info("✨ Total de eventos actualizados: {$actualizados}");
        }

        return Command::SUCCESS;
    }
}
