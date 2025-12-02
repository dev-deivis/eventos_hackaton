<?php

use App\Http\Controllers\EventoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConstanciaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página principal
Route::get('/', [EventoController::class, 'index'])->name('home');

// Autenticación (Laravel Breeze)
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Rutas de Eventos (Públicas y Protegidas)
|--------------------------------------------------------------------------
*/
Route::prefix('eventos')->name('eventos.')->group(function () {
    // Rutas públicas
    Route::get('/', [EventoController::class, 'index'])->name('index');
    Route::get('/{evento}', [EventoController::class, 'show'])->name('show');

    // Rutas protegidas (requieren autenticación)
    Route::middleware('auth')->group(function () {
        // Inscripción a eventos
        Route::post('/{evento}/registrar', [EventoController::class, 'register'])->name('register');
        Route::delete('/{evento}/cancelar-registro', [EventoController::class, 'cancelarRegistro'])->name('cancelar-registro');
    });

    // Rutas de administración (solo admins)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/crear/nuevo', [EventoController::class, 'create'])->name('create');
        Route::post('/crear', [EventoController::class, 'store'])->name('store');
        Route::get('/{evento}/editar', [EventoController::class, 'edit'])->name('edit');
        Route::put('/{evento}', [EventoController::class, 'update'])->name('update');
        Route::delete('/{evento}', [EventoController::class, 'destroy'])->name('destroy');
        Route::get('/{evento}/dashboard', [EventoController::class, 'dashboard'])->name('dashboard');
        // Cambiar estado del evento
        Route::patch('/{evento}/cambiar-estado', [EventoController::class, 'cambiarEstado'])->name('cambiar-estado');
    });
});

/*
|--------------------------------------------------------------------------
| Rutas de Equipos (Requieren Autenticación y Perfil Completo)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'profile.complete'])->prefix('equipos')->name('equipos.')->group(function () {
    // Mis equipos
    Route::get('/mis-equipos', [EquipoController::class, 'misEquipos'])->name('mis-equipos');
    
    // Seleccionar evento para crear equipo (desde dashboard)
    Route::get('/seleccionar-evento', [EquipoController::class, 'seleccionarEvento'])->name('seleccionar-evento');
    
    // Ver equipos de un evento
    Route::get('/evento/{evento}', [EquipoController::class, 'index'])->name('index');
    
    // Ver detalle de un equipo
    Route::get('/{equipo}', [EquipoController::class, 'show'])->name('show');
    
    // Crear equipo
    Route::get('/evento/{evento}/crear', [EquipoController::class, 'create'])->name('create');
    Route::post('/evento/{evento}', [EquipoController::class, 'store'])->name('store');
    
    // Editar equipo (solo líder)
    Route::put('/{equipo}', [EquipoController::class, 'update'])->name('update');
    
    // Solicitar unirse a equipo
    Route::post('/{equipo}/solicitar', [EquipoController::class, 'solicitarUnirse'])->name('solicitar');
    
    // Gestión de miembros (solo líder)
    Route::post('/{equipo}/aceptar/{participanteId}', [EquipoController::class, 'aceptarMiembro'])->name('aceptar-miembro');
    Route::post('/{equipo}/rechazar/{participanteId}', [EquipoController::class, 'rechazarMiembro'])->name('rechazar-miembro');
    
    // Abandonar equipo
    Route::delete('/{equipo}/abandonar', [EquipoController::class, 'abandonar'])->name('abandonar');
    
    // Chat del equipo
    Route::post('/{equipo}/mensaje', [EquipoController::class, 'enviarMensaje'])->name('enviar-mensaje');
    // Tareas del proyecto
    Route::post('/{equipo}/tareas', [TareaController::class, 'store'])->name('tareas.store');
    Route::put('/{equipo}/tareas/{tarea}', [TareaController::class, 'update'])->name('tareas.update');
    Route::delete('/{equipo}/tareas/{tarea}', [TareaController::class, 'destroy'])->name('tareas.destroy');
    Route::post('/{equipo}/tareas/{tarea}/toggle', [TareaController::class, 'toggleEstado'])->name('tareas.toggle');
    
    // Actualizar/eliminar equipo (solo líder)
    Route::put('/{equipo}', [EquipoController::class, 'update'])->name('update');
    Route::delete('/{equipo}', [EquipoController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Rutas de Proyectos (Requieren Autenticación y Perfil Completo)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'profile.complete'])->prefix('proyectos')->name('proyectos.')->group(function () {
    // Crear proyecto para un equipo
    Route::get('/equipo/{equipo}/crear', [ProyectoController::class, 'create'])->name('create');
    Route::post('/equipo/{equipo}', [ProyectoController::class, 'store'])->name('store');
    
    // Editar/actualizar proyecto
    Route::get('/equipo/{equipo}/editar', [ProyectoController::class, 'edit'])->name('edit');
    Route::put('/equipo/{equipo}', [ProyectoController::class, 'update'])->name('update');
    
    // Entregar proyecto (NUEVO)
    Route::post('/{proyecto}/entregar', [ProyectoController::class, 'entregar'])->name('entregar');
    
    // Eliminar proyecto (solo líder)
    Route::delete('/equipo/{equipo}', [ProyectoController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Dashboard y Perfil (Requieren Autenticación)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Completar perfil
    Route::get('/perfil/completar', [ProfileController::class, 'complete'])->name('profile.complete');
    Route::post('/perfil/completar', [ProfileController::class, 'storeComplete'])->name('profile.store-complete');
});

Route::middleware(['auth', 'profile.complete'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return view('admin.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // Perfil
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/perfil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Habilidades del perfil
    Route::post('/perfil/habilidades', [ProfileController::class, 'storeHabilidad'])->name('profile.habilidad.store');
    Route::put('/perfil/habilidades/{habilidad}', [ProfileController::class, 'updateHabilidad'])->name('profile.habilidad.update');
    Route::delete('/perfil/habilidades/{habilidad}', [ProfileController::class, 'destroyHabilidad'])->name('profile.habilidad.destroy');
});

/*
|--------------------------------------------------------------------------
| Rutas de Notificaciones (Requieren Autenticación)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('notificaciones')->name('notificaciones.')->group(function () {
    Route::get('/', function() {
        $notificaciones = auth()->user()->notificaciones()
                                ->recientes()
                                ->paginate(20);
        return view('notificaciones.index', compact('notificaciones'));
    })->name('index');
    
    Route::post('/{notificacion}/marcar-leida', function($id) {
        $notificacion = auth()->user()->notificaciones()->findOrFail($id);
        $notificacion->marcarComoLeida();
        return redirect($notificacion->url_accion ?? route('dashboard'));
    })->name('marcar-leida');
    
    Route::post('/marcar-todas-leidas', function() {
        auth()->user()->marcarNotificacionesComoLeidas();
        return redirect()->back()->with('success', 'Todas las notificaciones marcadas como leídas');
    })->name('marcar-todas-leidas');
});

/*
|--------------------------------------------------------------------------
| Panel de Administración (Solo Admins)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Rankings
    Route::get('/rankings', [\App\Http\Controllers\AdminController::class, 'rankings'])->name('rankings');
    
    // Gestión de usuarios
    Route::resource('usuarios', \App\Http\Controllers\AdminUserController::class)->except(['show']);
    Route::put('/usuarios/{usuario}/password', [\App\Http\Controllers\AdminUserController::class, 'updatePassword'])->name('usuarios.update-password');
    
    // Constancias
    Route::prefix('constancias')->name('constancias.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ConstanciaController::class, 'index'])->name('index');
        Route::get('/plantillas', [\App\Http\Controllers\ConstanciaController::class, 'plantillas'])->name('plantillas');
        Route::get('/generar-nuevas', [\App\Http\Controllers\ConstanciaController::class, 'generarNuevas'])->name('generar-nuevas');
        Route::post('/generar-lote', [\App\Http\Controllers\ConstanciaController::class, 'generarEnLote'])->name('generar-lote');
        Route::post('/generar-individual', [\App\Http\Controllers\ConstanciaController::class, 'generarIndividual'])->name('generar-individual');
        Route::get('/{constancia}/preview', [\App\Http\Controllers\ConstanciaController::class, 'vistaPrevia'])->name('preview');
        Route::get('/{constancia}/descargar', [\App\Http\Controllers\ConstanciaController::class, 'descargar'])->name('descargar');
        Route::delete('/{constancia}', [\App\Http\Controllers\ConstanciaController::class, 'destroy'])->name('destroy');
        
        // API endpoints
        Route::get('/participantes/{evento}', [\App\Http\Controllers\ConstanciaController::class, 'obtenerParticipantes']);
        Route::get('/estadisticas/{evento}', [\App\Http\Controllers\ConstanciaController::class, 'obtenerEstadisticas']);
    });
    
    // Gestión de Proyectos
    Route::prefix('proyectos')->name('proyectos.')->group(function () {
        Route::get('/pendientes', [\App\Http\Controllers\AdminController::class, 'proyectosPendientes'])->name('pendientes');
        Route::get('/{proyecto}/revisar', [\App\Http\Controllers\AdminController::class, 'revisarProyecto'])->name('revisar');
        Route::post('/{proyecto}/aprobar', [\App\Http\Controllers\AdminController::class, 'aprobarProyecto'])->name('aprobar');
        Route::post('/{proyecto}/rechazar', [\App\Http\Controllers\AdminController::class, 'rechazarProyecto'])->name('rechazar');
    });
    
    // Estadísticas
    Route::get('/estadisticas', function() {
        $stats = [
            'total_usuarios' => \App\Models\User::count(),
            'total_eventos' => \App\Models\Evento::count(),
            'total_equipos' => \App\Models\Equipo::count(),
            'total_proyectos' => \App\Models\Proyecto::count(),
        ];
        return view('admin.estadisticas', compact('stats'));
    })->name('estadisticas');
});

/*
|--------------------------------------------------------------------------
| Panel de Juez (Solo Jueces)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'juez'])->prefix('juez')->name('juez.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\JuezController::class, 'dashboard'])->name('dashboard');
    
    // Evaluaciones
    Route::get('/evaluar/{equipo}', [\App\Http\Controllers\JuezController::class, 'evaluar'])->name('evaluar');
    Route::post('/evaluar/{equipo}', [\App\Http\Controllers\JuezController::class, 'guardarEvaluacion'])->name('guardar-evaluacion');
    
    // Mis evaluaciones
    Route::get('/mis-evaluaciones', [\App\Http\Controllers\JuezController::class, 'misEvaluaciones'])->name('mis-evaluaciones');
    
    // Rankings
    Route::get('/rankings', [\App\Http\Controllers\JuezController::class, 'rankings'])->name('rankings');
});
