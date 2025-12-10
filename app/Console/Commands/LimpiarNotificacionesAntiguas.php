<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notificacion;
use Carbon\Carbon;

class LimpiarNotificacionesAntiguas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificaciones:limpiar {--dias=30 : Días de antigüedad para eliminar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia notificaciones antiguas leídas para mejorar el rendimiento';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dias = $this->option('dias');
        $fecha = Carbon::now()->subDays($dias);

        $this->info("🧹 Limpiando notificaciones leídas anteriores a: {$fecha->format('Y-m-d')}");

        // Eliminar notificaciones leídas antiguas
        $eliminadas = Notificacion::where('leida', true)
            ->where('leida_en', '<', $fecha)
            ->delete();

        $this->info("✅ Se eliminaron {$eliminadas} notificaciones antiguas");

        // También podemos auto-marcar como leídas las notificaciones muy antiguas no leídas
        $marcadas = Notificacion::where('leida', false)
            ->where('created_at', '<', Carbon::now()->subDays($dias * 2)) // El doble de tiempo
            ->update([
                'leida' => true,
                'leida_en' => now()
            ]);

        if ($marcadas > 0) {
            $this->info("📌 Se marcaron como leídas {$marcadas} notificaciones muy antiguas");
        }

        $this->newLine();
        $this->info('🎉 Limpieza completada exitosamente');

        return Command::SUCCESS;
    }
}
