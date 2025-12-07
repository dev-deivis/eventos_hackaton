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

        // 1. Eventos que deberían estar EN CURSO
        $eventosEnCurso = Evento::where('estado', 'proximo')
            ->where('fecha_inicio', '<=', $ahora)
            ->where('fecha_fin', '>=', $ahora)
            ->get();

        foreach ($eventosEnCurso as $evento) {
            $evento->update(['estado' => 'en_curso']);
            $this->line("✅ '{$evento->nombre}' → EN CURSO");
            $actualizados++;
            
            Log::info("Evento cambiado a EN CURSO", [
                'evento_id' => $evento->id,
                'nombre' => $evento->nombre
            ]);
        }

        // 2. Eventos que deberían estar FINALIZADOS
        $eventosFinalizados = Evento::whereIn('estado', ['proximo', 'en_curso'])
            ->where('fecha_fin', '<', $ahora)
            ->get();

        foreach ($eventosFinalizados as $evento) {
            $evento->update(['estado' => 'finalizado']);
            $this->line("✅ '{$evento->nombre}' → FINALIZADO");
            $actualizados++;
            
            Log::info("Evento cambiado a FINALIZADO", [
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
