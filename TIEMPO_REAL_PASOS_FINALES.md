# 🚀 TIEMPO REAL - RESUMEN Y PRÓXIMOS PASOS

## ✅ Completado

1. **Rutas API** → Agregadas en `routes/web.php`
2. **EquipoController** → Método `enviarMensajeApi()` agregado
3. **JavaScript** → Archivo completo en `public/js/equipos-tiempo-real.js`

---

## 🔧 FALTA COMPLETAR

### 1. Métodos API en TareaController

Agregar estos 3 métodos al final de `TareaController.php`:

```php
/**
 * Crear tarea vía API (AJAX)
 */
public function storeApi(Request $request, Equipo $equipo)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:200',
        'descripcion' => 'nullable|string|max:1000',
        'participantes' => 'nullable|array|max:2',
        'participantes.*' => 'exists:participantes,id',
    ]);

    if (!$equipo->proyecto) {
        return response()->json([
            'success' => false,
            'message' => 'El equipo no tiene proyecto registrado'
        ], 400);
    }

    $tarea = \App\Models\TareaProyecto::create([
        'proyecto_id' => $equipo->proyecto->id,
        'nombre' => $validated['nombre'],
        'descripcion' => $validated['descripcion'],
        'estado' => 'pendiente',
    ]);

    if (!empty($validated['participantes'])) {
        $tarea->participantes()->attach($validated['participantes']);
    }

    $tarea->load('participantes.user');

    return response()->json([
        'success' => true,
        'tarea' => [
            'id' => $tarea->id,
            'nombre' => $tarea->nombre,
            'descripcion' => $tarea->descripcion,
            'estado' => $tarea->estado,
            'completada' => $tarea->estaCompletada(),
            'porcentaje' => $tarea->valorPorcentual(),
            'equipo_id' => $equipo->id,
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
public function updateApi(Request $request, Equipo $equipo, \App\Models\TareaProyecto $tarea)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:200',
        'descripcion' => 'nullable|string|max:1000',
        'participantes' => 'nullable|array|max:2',
        'participantes.*' => 'exists:participantes,id',
    ]);

    $tarea->update([
        'nombre' => $validated['nombre'],
        'descripcion' => $validated['descripcion'] ?? null,
    ]);

    if (isset($validated['participantes'])) {
        $tarea->participantes()->sync($validated['participantes']);
    } else {
        $tarea->participantes()->detach();
    }

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
public function toggleApi(Request $request, Equipo $equipo, \App\Models\TareaProyecto $tarea)
{
    $nuevoEstado = $tarea->estaCompletada() ? 'pendiente' : 'completada';
    $tarea->update(['estado' => $nuevoEstado]);

    if ($nuevoEstado === 'completada') {
        \App\Services\NotificationService::tareaCompletada($tarea, auth()->user());
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

**Ubicación:** Agregar al final de `app/Http/Controllers/TareaController.php` (antes del último `}`)

---

### 2. Modificar Vista show.blade.php

#### Cambio 1: Formulario de Mensajes

Buscar:
```html
<form method="POST" action="{{ route('equipos.enviar-mensaje', $equipo) }}"
    class="p-4 border-t">
```

Cambiar por:
```html
<form id="formEnviarMensaje" method="POST" action="{{ route('equipos.enviar-mensaje', $equipo) }}"
    class="p-4 border-t">
```

---

#### Cambio 2: Contenedor de Mensajes

Buscar:
```html
<div class="flex-1 p-4 space-y-4 overflow-y-auto" style="max-height: 400px;">
```

Cambiar por:
```html
<div id="mensajesContainer" class="flex-1 p-4 space-y-4 overflow-y-auto" style="max-height: 400px;">
```

---

#### Cambio 3: Formulario Crear Tarea

Buscar:
```html
<form method="POST" action="{{ route('equipos.tareas.store', $equipo) }}">
```

Cambiar por:
```html
<form id="formCrearTarea" method="POST" action="{{ route('equipos.tareas.store', $equipo) }}">
```

---

#### Cambio 4: Botón Toggle Tarea

Buscar (dentro del loop de tareas):
```html
<button type="submit"
    class="mt-1 w-6 h-6 rounded flex items-center justify-center border-2 transition-all hover:scale-110
```

Cambiar por:
```html
<button type="submit" data-toggle-tarea="{{ $tarea->id }}"
    class="mt-1 w-6 h-6 rounded flex items-center justify-center border-2 transition-all hover:scale-110
```

---

#### Cambio 5: Contenedor de Tarea

Buscar (dentro del loop de tareas):
```html
<div class="p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-indigo-300 transition">
```

Cambiar por:
```html
<div data-tarea-id="{{ $tarea->id }}" class="p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-indigo-300 transition">
```

---

#### Cambio 6: Incluir JavaScript

Al final del archivo, antes del `</x-app-layout>`, agregar:

```html
@push('scripts')
<script src="{{ asset('js/equipos-tiempo-real.js') }}"></script>
@endpush
```

---

## 🎯 ARCHIVO AUXILIAR: agregar-metodos-tareas.bat

He creado un script que contiene los métodos a agregar.

---

## 📝 Orden de Ejecución

1. **Agregar métodos a TareaController** (copiar del código arriba)
2. **Modificar show.blade.php** (6 cambios arriba)
3. **Limpiar cache:**
   ```bash
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   composer dump-autoload
   ```
4. **Probar:**
   - Enviar mensaje → Debe aparecer sin recargar
   - Crear tarea → Debe aparecer en lista sin recargar
   - Marcar tarea → Debe cambiar sin recargar

---

## ✅ Cuando Todo Esté Listo

Prueba:

1. **Chat:**
   - Escribe mensaje
   - Presiona Enter
   - Mensaje aparece instantáneamente
   - Input se limpia
   - Notificación verde ✅

2. **Tareas:**
   - Click "Crear Tarea"
   - Completa formulario
   - Click "Crear Tarea"
   - Modal se cierra
   - Tarea aparece en lista
   - Notificación verde ✅

3. **Toggle:**
   - Click en checkbox de tarea
   - Checkbox cambia a verde con ✓
   - Sin recargar página
   - Animación suave

---

## 🎉 Resultado Final

✅ Chat fluido en tiempo real
✅ Tareas se crean/actualizan al instante  
✅ Sin recargas molestas
✅ Sin perder posición de scroll
✅ Notificaciones visuales bonitas
✅ Animaciones suaves

---

**¡Sistema completamente en tiempo real!** 🚀
