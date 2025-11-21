<?php

use App\Http\Controllers\EventoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    });
});

/*
|--------------------------------------------------------------------------
| Rutas de Equipos (Requieren Autenticación)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('equipos')->name('equipos.')->group(function () {
    // Ver equipos de un evento
    Route::get('/evento/{evento}', [EquipoController::class, 'index'])->name('index');
    
    // Ver detalle de un equipo
    Route::get('/{equipo}', [EquipoController::class, 'show'])->name('show');
    
    // Crear equipo
    Route::get('/evento/{evento}/crear', [EquipoController::class, 'create'])->name('create');
    Route::post('/evento/{evento}', [EquipoController::class, 'store'])->name('store');
    
    // Solicitar unirse a equipo
    Route::post('/{equipo}/solicitar', [EquipoController::class, 'solicitarUnirse'])->name('solicitar');
    
    // Gestión de miembros (solo líder)
    Route::post('/{equipo}/aceptar/{userId}', [EquipoController::class, 'aceptarMiembro'])->name('aceptar-miembro');
    Route::post('/{equipo}/rechazar/{userId}', [EquipoController::class, 'rechazarMiembro'])->name('rechazar-miembro');
    
    // Abandonar equipo
    Route::delete('/{equipo}/abandonar', [EquipoController::class, 'abandonar'])->name('abandonar');
    
    // Actualizar/eliminar equipo (solo líder)
    Route::put('/{equipo}', [EquipoController::class, 'update'])->name('update');
    Route::delete('/{equipo}', [EquipoController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Dashboard y Perfil (Requieren Autenticación)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Dashboard principal - Redirige según rol
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Si es admin, mostrar dashboard de administrador
        if ($user->isAdmin()) {
            return view('admin.dashboard');
        }
        
        // Si es participante, mostrar dashboard de usuario
        return view('dashboard');
    })->name('dashboard');

    // Perfil de usuario
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Completar perfil
    Route::get('/perfil/completar', [ProfileController::class, 'complete'])->name('profile.complete');
    Route::post('/perfil/completar', [ProfileController::class, 'storeComplete'])->name('profile.store-complete');
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
    Route::get('/dashboard', function() {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Gestión de usuarios
    Route::get('/usuarios', function() {
        $usuarios = \App\Models\User::with(['roles', 'perfil'])->paginate(20);
        return view('admin.usuarios.index', compact('usuarios'));
    })->name('usuarios.index');
    
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
