// ==========================================
// EQUIPOS EN TIEMPO REAL - JAVASCRIPT
// ==========================================

// ==========================================
// CHAT EN TIEMPO REAL
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    const formEnviarMensaje = document.getElementById('formEnviarMensaje');
    
    if (formEnviarMensaje) {
        formEnviarMensaje.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = this;
            const input = form.querySelector('input[name="mensaje"]');
            const mensajesContainer = document.getElementById('mensajesContainer');
            const mensaje = input.value.trim();
            
            if (!mensaje) return;
            
            // Deshabilitar input mientras se envía
            input.disabled = true;
            const btnSubmit = form.querySelector('button[type="submit"]');
            btnSubmit.disabled = true;
            
            try {
                const url = form.action.replace('/mensaje', '/mensajes/api');
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ mensaje })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Limpiar input
                    input.value = '';
                    
                    // Agregar mensaje al chat
                    agregarMensajeAlChat(data.mensaje, mensajesContainer);
                    
                    // Scroll al último mensaje
                    scrollToBottom(mensajesContainer);
                } else {
                    mostrarNotificacion(data.message || 'Error al enviar mensaje', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarNotificacion('Error al enviar mensaje', 'error');
            } finally {
                input.disabled = false;
                btnSubmit.disabled = false;
                input.focus();
            }
        });
    }
});

function agregarMensajeAlChat(mensaje, container) {
    if (!container) return;
    
    // Quitar mensaje de "no hay mensajes"
    const emptyState = container.querySelector('.text-center.py-8');
    if (emptyState) {
        emptyState.remove();
    }
    
    // Crear elemento del mensaje
    const div = document.createElement('div');
    div.className = `flex gap-3 ${mensaje.is_own ? 'justify-end' : ''}`;
    div.innerHTML = `
        ${!mensaje.is_own ? `
        <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
            ${escapeHtml(mensaje.user_initial)}
        </div>
        ` : ''}
        <div class="${mensaje.is_own ? 'bg-indigo-600 text-white' : 'bg-gray-100'} rounded-lg px-4 py-2 max-w-md">
            ${!mensaje.is_own ? `<div class="text-xs font-semibold mb-1 ${mensaje.is_own ? 'text-indigo-200' : 'text-gray-600'}">${escapeHtml(mensaje.user_name)}</div>` : ''}
            <p class="text-sm">${escapeHtml(mensaje.mensaje)}</p>
            <span class="text-xs ${mensaje.is_own ? 'text-indigo-200' : 'text-gray-500'} mt-1 block">
                ${mensaje.created_at}
            </span>
        </div>
        ${mensaje.is_own ? `
        <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
            ${escapeHtml(mensaje.user_initial)}
        </div>
        ` : ''}
    `;
    
    container.appendChild(div);
}

// ==========================================
// TAREAS EN TIEMPO REAL
// ==========================================

// Crear Tarea
document.addEventListener('DOMContentLoaded', function() {
    const formCrearTarea = document.getElementById('formCrearTarea');
    
    if (formCrearTarea) {
        formCrearTarea.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creando...';
            
            try {
                const url = form.action.replace('/tareas', '/tareas/api');
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
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
                    mostrarNotificacion(data.message || 'Error al crear tarea', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarNotificacion('Error al crear tarea', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }
});

function agregarTareaALista(tarea) {
    const tareasContainer = document.getElementById('listaTareas');
    if (!tareasContainer) {
        console.error('No se encontró el contenedor de tareas #listaTareas');
        return;
    }
    
    // Quitar mensaje de "no hay tareas" si existe
    const emptyState = document.getElementById('estadoSinTareas');
    if (emptyState) {
        emptyState.remove();
    }
    
    // Crear elemento de tarea
    const div = document.createElement('div');
    div.className = 'border rounded-lg p-4 bg-white';
    div.setAttribute('data-tarea-id', tarea.id);
    
    div.innerHTML = `
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-3 flex-1">
                <form method="POST" action="/equipos/${tarea.equipo_id}/tareas/${tarea.id}/toggle">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                    <button type="submit" data-toggle-tarea="${tarea.id}" 
                        class="mt-1 w-6 h-6 rounded flex items-center justify-center border-2 transition-all hover:scale-110 bg-white border-gray-300 hover:border-indigo-500">
                    </button>
                </form>
                
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">${escapeHtml(tarea.nombre)}</h4>
                            ${tarea.descripcion ? `<p class="text-sm text-gray-600 mt-1">${escapeHtml(tarea.descripcion)}</p>` : ''}
                            
                            <!-- Asignados -->
                            <div class="flex items-center gap-2 mt-2">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                ${tarea.participantes.length > 0 
                                    ? `<div class="flex gap-1">${tarea.participantes.map(p => `<span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded text-xs">${escapeHtml(p.nombre)}</span>`).join('')}</div>`
                                    : '<span class="text-xs text-gray-400">Sin asignar</span>'
                                }
                            </div>
                        </div>
                        
                        <!-- Valor de la tarea -->
                        <div class="text-right ml-4">
                            <span class="text-sm font-semibold text-indigo-600">${tarea.porcentaje}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Insertar al principio de la lista
    tareasContainer.insertBefore(div, tareasContainer.firstChild);
    
    // Animar entrada
    div.style.opacity = '0';
    div.style.transform = 'translateY(-10px)';
    setTimeout(() => {
        div.style.transition = 'opacity 0.3s, transform 0.3s';
        div.style.opacity = '1';
        div.style.transform = 'translateY(0)';
    }, 10);
}

// Toggle Tarea
document.addEventListener('click', async function(e) {
    const toggleBtn = e.target.closest('[data-toggle-tarea]');
    if (!toggleBtn) return;
    
    e.preventDefault();
    
    const tareaId = toggleBtn.getAttribute('data-toggle-tarea');
    const form = toggleBtn.closest('form');
    
    // Deshabilitar botón
    toggleBtn.disabled = true;
    
    try {
        const url = form.action.replace('/toggle', '/toggle-api');
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            actualizarEstadoTarea(tareaId, data.tarea.completada);
        } else {
            mostrarNotificacion(data.message || 'Error al actualizar tarea', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion('Error al actualizar tarea', 'error');
    } finally {
        toggleBtn.disabled = false;
    }
});

function actualizarEstadoTarea(tareaId, completada) {
    const tareaElement = document.querySelector(`[data-tarea-id="${tareaId}"]`);
    if (!tareaElement) return;
    
    const checkbox = tareaElement.querySelector('[data-toggle-tarea]');
    const titulo = tareaElement.querySelector('h4');
    
    // Agregar animación
    checkbox.style.transition = 'all 0.3s ease';
    
    if (completada) {
        checkbox.classList.add('bg-green-500', 'border-green-500', 'text-white');
        checkbox.classList.remove('bg-white', 'border-gray-300');
        checkbox.innerHTML = `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>`;
        titulo.classList.add('line-through');
        
        // Animación de escala
        checkbox.style.transform = 'scale(1.2)';
        setTimeout(() => {
            checkbox.style.transform = 'scale(1)';
        }, 200);
    } else {
        checkbox.classList.remove('bg-green-500', 'border-green-500', 'text-white');
        checkbox.classList.add('bg-white', 'border-gray-300');
        checkbox.innerHTML = '';
        titulo.classList.remove('line-through');
    }
}

// ==========================================
// UTILIDADES
// ==========================================

function scrollToBottom(element) {
    if (element) {
        element.scrollTo({
            top: element.scrollHeight,
            behavior: 'smooth'
        });
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function mostrarNotificacion(mensaje, tipo = 'success') {
    const color = tipo === 'success' ? 'green' : 'red';
    const icon = tipo === 'success' 
        ? '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
        : '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
    
    const notif = document.createElement('div');
    notif.className = `fixed top-4 right-4 bg-${color}-50 border border-${color}-200 text-${color}-800 px-4 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2 animate-slide-in`;
    notif.innerHTML = `
        <div class="text-${color}-600">${icon}</div>
        <span>${escapeHtml(mensaje)}</span>
    `;
    
    document.body.appendChild(notif);
    
    setTimeout(() => {
        notif.style.animation = 'slide-out 0.3s ease-out forwards';
        setTimeout(() => notif.remove(), 300);
    }, 3000);
}

// Agregar estilos CSS para las animaciones
const style = document.createElement('style');
style.textContent = `
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slide-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .animate-slide-in {
        animation: slide-in 0.3s ease-out forwards;
    }
`;
document.head.appendChild(style);

// ==========================================
// SOLICITUDES EN TIEMPO REAL
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    const formSolicitarUnirse = document.getElementById('formSolicitarUnirse');
    
    if (formSolicitarUnirse) {
        formSolicitarUnirse.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = this;
            const selectPerfil = form.querySelector('select[name="perfil_id"]');
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            if (!selectPerfil.value) {
                mostrarNotificacion('Debes seleccionar un rol', 'error');
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enviando...';
            
            try {
                const url = form.action.replace('/solicitar', '/solicitar/api');
                const formData = new FormData(form);
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Cerrar modal
                    if (typeof toggleModalUnirse === 'function') {
                        toggleModalUnirse();
                    }
                    
                    // Resetear formulario
                    form.reset();
                    
                    // Mostrar mensaje de éxito más detallado
                    mostrarNotificacion('✅ ' + (data.message || 'Solicitud enviada exitosamente. El líder del equipo la revisará pronto.'), 'success');
                    
                    // Opcional: Ocultar el botón "Solicitar Unirse" ya que ya envió solicitud
                    const botonSolicitar = document.querySelector('[onclick="toggleModalUnirse()"]');
                    if (botonSolicitar && botonSolicitar.closest('.bg-white.rounded-xl')) {
                        const card = botonSolicitar.closest('.bg-white.rounded-xl');
                        card.innerHTML = `
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Solicitud Enviada</h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    Tu solicitud para unirte a este equipo está pendiente de aprobación por el líder.
                                </p>
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-50 text-yellow-700 rounded-lg">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-medium">Esperando respuesta del líder</span>
                                </div>
                            </div>
                        `;
                    }
                } else {
                    mostrarNotificacion(data.message || 'Error al enviar solicitud', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarNotificacion('Error al enviar solicitud', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }
});

function agregarSolicitudALista(solicitud) {
    const listaSolicitudes = document.getElementById('listaSolicitudes');
    if (!listaSolicitudes) return; // Solo visible para el líder
    
    // Crear elemento de solicitud
    const div = document.createElement('div');
    div.className = 'p-3 bg-yellow-50 rounded-lg border border-yellow-100';
    div.setAttribute('data-solicitud-id', solicitud.id);
    
    div.innerHTML = `
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 bg-yellow-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                ${escapeHtml(solicitud.user_initial)}
            </div>
            <div class="flex-1">
                <div class="font-semibold text-sm">${escapeHtml(solicitud.user_name)}</div>
                <div class="text-xs text-gray-600">${escapeHtml(solicitud.perfil_nombre)}</div>
            </div>
            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">Pendiente</span>
        </div>
        <div class="flex gap-2 mt-2">
            <form method="POST" action="/equipos/${solicitud.equipo_id}/aceptar-miembro/${solicitud.id}" class="flex-1">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                <button type="submit" class="w-full px-3 py-1.5 bg-green-600 text-white rounded text-xs font-medium hover:bg-green-700">
                    Aceptar
                </button>
            </form>
            <form method="POST" action="/equipos/${solicitud.equipo_id}/rechazar-miembro/${solicitud.id}" class="flex-1">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                <button type="submit" class="w-full px-3 py-1.5 bg-red-600 text-white rounded text-xs font-medium hover:bg-red-700">
                    Rechazar
                </button>
            </form>
        </div>
    `;
    
    // Insertar al principio de la lista
    listaSolicitudes.insertBefore(div, listaSolicitudes.firstChild);
    
    // Animar entrada
    div.style.opacity = '0';
    div.style.transform = 'translateY(-10px)';
    setTimeout(() => {
        div.style.transition = 'all 0.3s ease-out';
        div.style.opacity = '1';
        div.style.transform = 'translateY(0)';
    }, 10);
}

// ==========================================
// POLLING PARA SOLICITUDES PENDIENTES (LÍDER)
// ==========================================

// Variable global para almacenar IDs de solicitudes ya mostradas
let solicitudesMostradas = new Set();

// Iniciar polling solo si el contenedor de solicitudes existe (es decir, si es líder)
document.addEventListener('DOMContentLoaded', function() {
    const listaSolicitudes = document.getElementById('listaSolicitudes');
    
    if (listaSolicitudes) {
        // Guardar IDs de solicitudes que ya están en la página
        const solicitudesExistentes = document.querySelectorAll('[data-solicitud-id]');
        solicitudesExistentes.forEach(sol => {
            solicitudesMostradas.add(sol.getAttribute('data-solicitud-id'));
        });
        
        // Obtener ID del equipo desde la URL
        const equipoId = obtenerEquipoIdDesdeUrl();
        
        if (equipoId) {
            // Iniciar polling cada 10 segundos
            setInterval(() => {
                verificarNuevasSolicitudes(equipoId);
            }, 10000); // 10 segundos
            
            console.log('✅ Polling de solicitudes activado (cada 10 segundos)');
        }
    }
});

function obtenerEquipoIdDesdeUrl() {
    // URL esperada: /equipos/{id} o /equipos/{id}/...
    const path = window.location.pathname;
    const match = path.match(/\/equipos\/(\d+)/);
    return match ? match[1] : null;
}

async function verificarNuevasSolicitudes(equipoId) {
    try {
        const response = await fetch(`/equipos/${equipoId}/solicitudes/pendientes/api`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) return;
        
        const data = await response.json();
        
        if (data.success && data.solicitudes) {
            // Verificar si hay solicitudes nuevas
            data.solicitudes.forEach(solicitud => {
                const solicitudId = solicitud.id.toString();
                
                // Si no la hemos mostrado aún, agregarla
                if (!solicitudesMostradas.has(solicitudId)) {
                    agregarSolicitudALista(solicitud);
                    solicitudesMostradas.add(solicitudId);
                    
                    // Mostrar notificación al líder
                    mostrarNotificacion(`🔔 Nueva solicitud de ${solicitud.user_name} para unirse al equipo`, 'success');
                    
                    // Reproducir sonido de notificación (opcional)
                    reproducirSonidoNotificacion();
                }
            });
        }
    } catch (error) {
        console.error('Error al verificar solicitudes:', error);
    }
}

function reproducirSonidoNotificacion() {
    // Crear un beep corto usando Web Audio API
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    } catch (e) {
        // Silenciar errores de audio
    }
}
