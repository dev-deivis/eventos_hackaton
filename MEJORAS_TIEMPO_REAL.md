# 🚀 MEJORAS EN TIEMPO REAL PARA EQUIPOS

## 📋 Problemas Identificados

1. **Enviar mensaje** → Recarga la página completa
2. **Crear tarea** → Recarga y lleva arriba
3. **Marcar tarea como completada** → Recarga y lleva arriba
4. **Editar tarea** → Recarga y pierde posición

## ✅ Solución: AJAX + Alpine.js

Vamos a hacer que TODO funcione en tiempo real sin recargar la página.

---

## 🎯 Características Nuevas

### 1. Chat en Tiempo Real
- ✅ Enviar mensaje sin recargar
- ✅ Mensajes aparecen instantáneamente
- ✅ Scroll automático al último mensaje
- ✅ Input se limpia automáticamente

### 2. Tareas Fluidas
- ✅ Crear tarea sin recargar
- ✅ Tarea aparece inmediatamente en la lista
- ✅ Modal se cierra automáticamente
- ✅ No pierde posición de scroll

### 3. Marcar Tareas
- ✅ Check/uncheck instantáneo
- ✅ Animación de cambio
- ✅ Mantiene posición en la página
- ✅ Sin recargas

### 4. Editar Tareas
- ✅ Actualización instantánea
- ✅ Modal se cierra solo
- ✅ Cambios visibles de inmediato

---

## 🔧 Implementación

### Paso 1: Modificar Rutas (API)

Necesitamos crear rutas API para responder con JSON.

**Archivo:** `routes/web.php`

```php
// Dentro del grupo de equipos, agregar rutas API
Route::middleware(['auth', 'profile.complete'])->group(function () {
    
    // ... rutas existentes ...
    
    // 🆕 RUTAS API PARA AJAX
    Route::post('/equipos/{equipo}/mensajes/api', [EquipoController::class, 'enviarMensajeApi'])
        ->name('equipos.enviar-mensaje-api');
    
    Route::post('/equipos/{equipo}/tareas/api', [TareaProyectoController::class, 'storeApi'])
        ->name('equipos.tareas.store-api');
    
    Route::put('/equipos/{equipo}/tareas/{tarea}/api', [TareaProyectoController::class, 'updateApi'])
        ->name('equipos.tareas.update-api');
    
    Route::post('/equipos/{equipo}/tareas/{tarea}/toggle-api', [TareaProyectoController::class, 'toggleApi'])
        ->name('equipos.tareas.toggle-api');
});
```

---

### Paso 2: Modificar Controladores

#### A) EquipoController - Método para enviar mensajes vía AJAX

```php
/**
 * Enviar mensaje vía API (AJAX)
 */
public function enviarMensajeApi(Request $request, Equipo $equipo)
{
    $validated = $request->validate([
        'mensaje' => 'required|string|max:500',
    ]);

    // Verificar que el usuario es miembro activo
    $participante = $equipo->participantes()
        ->where('user_id', auth()->id())
        ->wherePivot('estado', 'activo')
        ->first();

    if (!$participante) {
        return response()->json([
            'success' => false,
            'message' => 'No eres miembro de este equipo'
        ], 403);
    }

    // Crear mensaje
    $mensaje = MensajeEquipo::create([
        'equipo_id' => $equipo->id,
        'user_id' => auth()->id(),
        'mensaje' => $validated['mensaje'],
    ]);

    // Notificar a otros miembros
    NotificationService::mensajeEquipo($equipo, auth()->user());

    // Devolver el mensaje con el usuario
    $mensaje->load('user');

    return response()->json([
        'success' => true,
        'mensaje' => [
            'id' => $mensaje->id,
            'mensaje' => $mensaje->mensaje,
            'user_name' => $mensaje->user->name,
            'user_initial' => substr($mensaje->user->name, 0, 1),
            'created_at' => $mensaje->created_at->diffForHumans(),
            'is_own' => $mensaje->user_id === auth()->id(),
        ]
    ]);
}
```

#### B) TareaProyectoController - Métodos AJAX

```php
/**
 * Crear tarea vía API (AJAX)
 */
public function storeApi(Request $request, Equipo $equipo)
{
    // Validación
    $validated = $request->validate([
        'nombre' => 'required|string|max:200',
        'descripcion' => 'nullable|string|max:1000',
        'participantes' => 'nullable|array|max:2',
        'participantes.*' => 'exists:participantes,id',
    ]);

    // Verificar que tiene proyecto
    if (!$equipo->proyecto) {
        return response()->json([
            'success' => false,
            'message' => 'El equipo no tiene proyecto registrado'
        ], 400);
    }

    // Crear tarea
    $tarea = TareaProyecto::create([
        'proyecto_id' => $equipo->proyecto->id,
        'nombre' => $validated['nombre'],
        'descripcion' => $validated['descripcion'],
        'estado' => 'pendiente',
    ]);

    // Asignar participantes
    if (!empty($validated['participantes'])) {
        $tarea->participantes()->attach($validated['participantes']);
    }

    // Cargar relaciones
    $tarea->load('participantes.user');

    // Devolver tarea formateada
    return response()->json([
        'success' => true,
        'tarea' => [
            'id' => $tarea->id,
            'nombre' => $tarea->nombre,
            'descripcion' => $tarea->descripcion,
            'estado' => $tarea->estado,
            'completada' => $tarea->estaCompletada(),
            'porcentaje' => $tarea->valorPorcentual(),
            'participantes' => $tarea->participantes->map(function($p) {
                return [
                    'id' => $p->id,
                    'nombre' => explode(' ', $p->user->name)[0],
                ];
            }),
        ]
    ]);
}

/**
 * Actualizar tarea vía API (AJAX)
 */
public function updateApi(Request $request, Equipo $equipo, TareaProyecto $tarea)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:200',
        'descripcion' => 'nullable|string|max:1000',
        'participantes' => 'nullable|array|max:2',
        'participantes.*' => 'exists:participantes,id',
    ]);

    // Actualizar tarea
    $tarea->update([
        'nombre' => $validated['nombre'],
        'descripcion' => $validated['descripcion'] ?? null,
    ]);

    // Actualizar participantes
    if (isset($validated['participantes'])) {
        $tarea->participantes()->sync($validated['participantes']);
    } else {
        $tarea->participantes()->detach();
    }

    // Cargar relaciones
    $tarea->load('participantes.user');

    return response()->json([
        'success' => true,
        'tarea' => [
            'id' => $tarea->id,
            'nombre' => $tarea->nombre,
            'descripcion' => $tarea->descripcion,
            'participantes' => $tarea->participantes->map(function($p) {
                return [
                    'id' => $p->id,
                    'nombre' => explode(' ', $p->user->name)[0],
                ];
            }),
        ]
    ]);
}

/**
 * Toggle estado de tarea vía API (AJAX)
 */
public function toggleApi(Request $request, Equipo $equipo, TareaProyecto $tarea)
{
    // Toggle estado
    $nuevoEstado = $tarea->estaCompletada() ? 'pendiente' : 'completada';
    $tarea->update(['estado' => $nuevoEstado]);

    // Notificar si se completó
    if ($nuevoEstado === 'completada') {
        NotificationService::tareaCompletada($tarea, auth()->user());
    }

    return response()->json([
        'success' => true,
        'tarea' => [
            'id' => $tarea->id,
            'completada' => $tarea->estaCompletada(),
            'estado' => $nuevoEstado,
        ]
    ]);
}
```

---

### Paso 3: JavaScript en la Vista

Agregar al final de `show.blade.php`, dentro de la sección `@push('scripts')`:

```javascript
<script>
// ==========================================
// CHAT EN TIEMPO REAL
// ==========================================
document.getElementById('formEnviarMensaje')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const input = form.querySelector('input[name="mensaje"]');
    const mensajesContainer = document.getElementById('mensajesContainer');
    const mensaje = input.value.trim();
    
    if (!mensaje) return;
    
    // Deshabilitar input mientras se envía
    input.disabled = true;
    
    try {
        const response = await fetch(form.action.replace('/enviar-mensaje', '/mensajes/api'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ mensaje })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Limpiar input
            input.value = '';
            
            // Agregar mensaje al chat
            agregarMensajeAlChat(data.mensaje);
            
            // Scroll al último mensaje
            scrollToBottom(mensajesContainer);
        } else {
            alert(data.message || 'Error al enviar mensaje');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al enviar mensaje');
    } finally {
        input.disabled = false;
        input.focus();
    }
});

function agregarMensajeAlChat(mensaje) {
    const mensajesContainer = document.getElementById('mensajesContainer');
    const emptyState = mensajesContainer.querySelector('.text-center.py-8');
    
    // Quitar mensaje de "no hay mensajes"
    if (emptyState) {
        emptyState.remove();
    }
    
    // Crear elemento del mensaje
    const div = document.createElement('div');
    div.className = `flex gap-3 ${mensaje.is_own ? 'justify-end' : ''}`;
    div.innerHTML = `
        ${!mensaje.is_own ? `
        <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
            ${mensaje.user_initial}
        </div>
        ` : ''}
        <div class="${mensaje.is_own ? 'bg-indigo-600 text-white' : 'bg-gray-100'} rounded-lg px-4 py-2 max-w-md">
            ${!mensaje.is_own ? `<div class="text-xs font-semibold mb-1 ${mensaje.is_own ? 'text-indigo-200' : 'text-gray-600'}">${mensaje.user_name}</div>` : ''}
            <p class="text-sm">${escapeHtml(mensaje.mensaje)}</p>
            <span class="text-xs ${mensaje.is_own ? 'text-indigo-200' : 'text-gray-500'} mt-1 block">
                ${mensaje.created_at}
            </span>
        </div>
        ${mensaje.is_own ? `
        <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
            ${mensaje.user_initial}
        </div>
        ` : ''}
    `;
    
    mensajesContainer.appendChild(div);
}

// ==========================================
// TAREAS EN TIEMPO REAL
// ==========================================

// Crear Tarea
document.getElementById('formCrearTarea')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Creando...';
    
    try {
        const response = await fetch(form.action.replace('/tareas', '/tareas/api'), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Cerrar modal
            toggleModalCrearTarea();
            
            // Resetear formulario
            form.reset();
            
            // Agregar tarea a la lista
            agregarTareaALista(data.tarea);
            
            // Mostrar mensaje de éxito
            mostrarNotificacion('Tarea creada exitosamente', 'success');
        } else {
            alert(data.message || 'Error al crear tarea');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al crear tarea');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Crear Tarea';
    }
});

function agregarTareaALista(tarea) {
    const tareasContainer = document.querySelector('.space-y-3');
    const emptyState = document.querySelector('.text-center.py-8');
    
    // Quitar mensaje de "no hay tareas"
    if (emptyState) {
        emptyState.remove();
    }
    
    // Crear elemento de tarea
    const div = document.createElement('div');
    div.className = 'p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-indigo-300 transition';
    div.setAttribute('data-tarea-id', tarea.id);
    div.innerHTML = generarHTMLTarea(tarea);
    
    tareasContainer.insertBefore(div, tareasContainer.firstChild);
}

// Toggle Tarea
document.addEventListener('click', async function(e) {
    const toggleBtn = e.target.closest('[data-toggle-tarea]');
    if (!toggleBtn) return;
    
    e.preventDefault();
    
    const tareaId = toggleBtn.getAttribute('data-toggle-tarea');
    const form = toggleBtn.closest('form');
    
    try {
        const response = await fetch(form.action.replace('/toggle', '/toggle-api'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            actualizarEstadoTarea(tareaId, data.tarea.completada);
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

function actualizarEstadoTarea(tareaId, completada) {
    const tareaElement = document.querySelector(`[data-tarea-id="${tareaId}"]`);
    if (!tareaElement) return;
    
    const checkbox = tareaElement.querySelector('[data-toggle-tarea]');
    const titulo = tareaElement.querySelector('h4');
    
    if (completada) {
        checkbox.classList.add('bg-green-500', 'border-green-500', 'text-white');
        checkbox.classList.remove('bg-white', 'border-gray-300');
        checkbox.innerHTML = `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>`;
        titulo.classList.add('line-through');
    } else {
        checkbox.classList.remove('bg-green-500', 'border-green-500', 'text-white');
        checkbox.classList.add('bg-white', 'border-gray-300');
        checkbox.innerHTML = '';
        titulo.classList.remove('line-through');
    }
}

// Utilidades
function scrollToBottom(element) {
    if (element) {
        element.scrollTop = element.scrollHeight;
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function mostrarNotificacion(mensaje, tipo = 'success') {
    const color = tipo === 'success' ? 'green' : 'red';
    const notif = document.createElement('div');
    notif.className = `fixed top-4 right-4 bg-${color}-50 border border-${color}-200 text-${color}-800 px-4 py-3 rounded-lg shadow-lg z-50`;
    notif.textContent = mensaje;
    document.body.appendChild(notif);
    
    setTimeout(() => notif.remove(), 3000);
}
</script>
```

---

## 📝 Modificaciones en HTML

### 1. Formulario de Mensajes

Cambiar:
```html
<form method="POST" action="{{ route('equipos.enviar-mensaje', $equipo) }}" class="p-4 border-t">
```

Por:
```html
<form id="formEnviarMensaje" method="POST" action="{{ route('equipos.enviar-mensaje', $equipo) }}" class="p-4 border-t">
```

### 2. Contenedor de Mensajes

Agregar ID:
```html
<div id="mensajesContainer" class="flex-1 p-4 space-y-4 overflow-y-auto" style="max-height: 400px;">
```

### 3. Formulario de Crear Tarea

Cambiar:
```html
<form method="POST" action="{{ route('equipos.tareas.store', $equipo) }}">
```

Por:
```html
<form id="formCrearTarea" method="POST" action="{{ route('equipos.tareas.store', $equipo) }}">
```

### 4. Checkbox de Tarea

Cambiar:
```html
<button type="submit" class="mt-1 w-6 h-6 rounded flex items-center justify-center...">
```

Por:
```html
<button type="submit" data-toggle-tarea="{{ $tarea->id }}" class="mt-1 w-6 h-6 rounded flex items-center justify-center...">
```

### 5. Contenedor de Tarea

Agregar:
```html
<div data-tarea-id="{{ $tarea->id }}" class="p-4 bg-white border-2...">
```

---

## 🎯 Resultado Final

Con estos cambios:

✅ **Chat:**
- Enviar mensaje → Aparece instantáneamente
- Sin recargas
- Scroll automático
- Input se limpia solo

✅ **Tareas:**
- Crear tarea → Se agrega a la lista sin recargar
- Marcar completada → Cambio instantáneo con animación
- Sin perder posición de scroll
- Modal se cierra automáticamente

✅ **UX Mejorada:**
- Notificaciones de éxito
- Estados de carga (botones "Creando...")
- Animaciones suaves
- Retroalimentación visual inmediata

---

## 🚀 Próximos Pasos

1. Ejecuta: `activar-mejoras-tiempo-real.bat`
2. Prueba enviar mensajes
3. Prueba crear tareas
4. Prueba marcar tareas como completadas

¡Todo debería funcionar en tiempo real sin recargas molestas!
