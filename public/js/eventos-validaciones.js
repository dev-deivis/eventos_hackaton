// ==========================================
// VALIDACIONES PARA CREAR/EDITAR EVENTOS
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. VALIDACIÓN DE NOMBRE DEL EVENTO
    // ==========================================
    const nombreEvento = document.getElementById('nombre');
    const nombreCount = document.getElementById('nombreCount');
    
    if (nombreEvento) {
        nombreEvento.addEventListener('input', function() {
            let value = this.value;
            
            // Solo permitir letras, números, espacios y guion (-)
            value = value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-]/g, '');
            this.value = value;
            
            // Limitar a 35 caracteres
            if (value.length > 35) {
                value = value.substring(0, 35);
                this.value = value;
            }
            
            // Actualizar contador
            if (nombreCount) {
                nombreCount.textContent = value.length;
                
                // Cambiar color del contador
                if (value.length >= 33) {
                    nombreCount.parentElement.classList.add('text-red-500');
                    nombreCount.parentElement.classList.remove('text-gray-500', 'text-yellow-500');
                } else if (value.length >= 30) {
                    nombreCount.parentElement.classList.add('text-yellow-500');
                    nombreCount.parentElement.classList.remove('text-gray-500', 'text-red-500');
                } else {
                    nombreCount.parentElement.classList.add('text-gray-500');
                    nombreCount.parentElement.classList.remove('text-yellow-500', 'text-red-500');
                }
            }
        });
    }
    
    // ==========================================
    // 2. VALIDACIÓN DE DESCRIPCIÓN
    // ==========================================
    const descripcionEvento = document.getElementById('descripcion');
    const descripcionCount = document.getElementById('descripcionCount');
    
    if (descripcionEvento) {
        descripcionEvento.addEventListener('input', function() {
            let value = this.value;
            
            // Limitar a 150 caracteres
            if (value.length > 150) {
                value = value.substring(0, 150);
                this.value = value;
            }
            
            // Actualizar contador
            if (descripcionCount) {
                descripcionCount.textContent = value.length;
                
                // Cambiar color del contador
                if (value.length >= 148) {
                    descripcionCount.parentElement.classList.add('text-red-500');
                    descripcionCount.parentElement.classList.remove('text-gray-500', 'text-yellow-500');
                } else if (value.length >= 140) {
                    descripcionCount.parentElement.classList.add('text-yellow-500');
                    descripcionCount.parentElement.classList.remove('text-gray-500', 'text-red-500');
                } else {
                    descripcionCount.parentElement.classList.add('text-gray-500');
                    descripcionCount.parentElement.classList.remove('text-yellow-500', 'text-red-500');
                }
            }
        });
    }
    
    // ==========================================
    // 3. VALIDACIÓN DE UBICACIÓN
    // ==========================================
    const ubicacion = document.getElementById('ubicacion');
    const ubicacionCount = document.getElementById('ubicacionCount');
    
    if (ubicacion) {
        ubicacion.addEventListener('input', function() {
            let value = this.value;
            
            // Permitir letras, números, comas, puntos y espacios
            value = value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s,\.]/g, '');
            this.value = value;
            
            // Limitar a 50 caracteres
            if (value.length > 50) {
                value = value.substring(0, 50);
                this.value = value;
            }
            
            // Actualizar contador
            if (ubicacionCount) {
                ubicacionCount.textContent = value.length;
                
                // Cambiar color del contador
                if (value.length >= 48) {
                    ubicacionCount.parentElement.classList.add('text-red-500');
                    ubicacionCount.parentElement.classList.remove('text-gray-500', 'text-yellow-500');
                } else if (value.length >= 45) {
                    ubicacionCount.parentElement.classList.add('text-yellow-500');
                    ubicacionCount.parentElement.classList.remove('text-gray-500', 'text-red-500');
                } else {
                    ubicacionCount.parentElement.classList.add('text-gray-500');
                    ubicacionCount.parentElement.classList.remove('text-yellow-500', 'text-red-500');
                }
            }
        });
    }
    
    // ==========================================
    // 4. VALIDACIÓN DE MÁXIMO PARTICIPANTES
    // ==========================================
    const maxParticipantes = document.getElementById('max_participantes');
    
    if (maxParticipantes) {
        maxParticipantes.addEventListener('input', function() {
            let value = parseInt(this.value) || 0;
            
            // Limitar a 1000
            if (value > 1000) {
                this.value = 1000;
            }
            
            // Mínimo 10
            if (value < 10 && value > 0) {
                this.value = 10;
            }
        });
    }
    
    // ==========================================
    // 5. VALIDACIÓN DE TAMAÑO DE EQUIPO
    // ==========================================
    const minMiembros = document.getElementById('min_miembros_equipo');
    const maxMiembros = document.getElementById('max_miembros_equipo');
    
    if (minMiembros) {
        minMiembros.addEventListener('change', function() {
            let value = parseInt(this.value) || 5;
            
            // Forzar mínimo a 5
            if (value !== 5) {
                this.value = 5;
                alert('El tamaño mínimo de equipo debe ser 5');
            }
            
            // Verificar que max sea 6
            if (maxMiembros && parseInt(maxMiembros.value) < 6) {
                maxMiembros.value = 6;
            }
        });
    }
    
    if (maxMiembros) {
        maxMiembros.addEventListener('change', function() {
            let value = parseInt(this.value) || 6;
            
            // Forzar máximo a 6
            if (value !== 6) {
                this.value = 6;
                alert('El tamaño máximo de equipo debe ser 6');
            }
            
            // Verificar que min sea 5
            if (minMiembros && parseInt(minMiembros.value) > 5) {
                minMiembros.value = 5;
            }
        });
    }
    
    // ==========================================
    // 6. VALIDACIÓN DE FECHAS
    // ==========================================
    const fechaRegistro = document.getElementById('fecha_limite_registro');
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    const fechaEvaluacion = document.getElementById('fecha_evaluacion');
    const fechaPremiacion = document.getElementById('fecha_premiacion');
    const duracionHoras = document.getElementById('duracion_horas');
    
    function validarFechas() {
        if (!fechaRegistro || !fechaInicio || !fechaFin) return;
        
        const registro = new Date(fechaRegistro.value);
        const inicio = new Date(fechaInicio.value);
        const fin = new Date(fechaFin.value);
        const evaluacion = fechaEvaluacion ? new Date(fechaEvaluacion.value) : null;
        const premiacion = fechaPremiacion ? new Date(fechaPremiacion.value) : null;
        
        // Validar que fechas no sean iguales
        if (fechaRegistro.value && fechaFin.value && registro.getTime() === fin.getTime()) {
            alert('La fecha de registro no puede ser igual a la fecha de finalización');
            fechaRegistro.value = '';
            return false;
        }
        
        if (fechaRegistro.value && fechaInicio.value && registro.getTime() === inicio.getTime()) {
            alert('La fecha de registro no puede ser igual a la fecha de inicio');
            fechaRegistro.value = '';
            return false;
        }
        
        if (fechaInicio.value && fechaFin.value && inicio.getTime() === fin.getTime()) {
            alert('La fecha de inicio no puede ser igual a la fecha de finalización');
            fechaFin.value = '';
            return false;
        }
        
        // Validar orden de fechas
        if (fechaRegistro.value && fechaInicio.value && registro >= inicio) {
            alert('La fecha de registro debe ser anterior a la fecha de inicio');
            return false;
        }
        
        if (fechaInicio.value && fechaFin.value && inicio >= fin) {
            alert('La fecha de inicio debe ser anterior a la fecha de finalización');
            return false;
        }
        
        // Validar duración en horas
        if (fechaInicio.value && fechaFin.value && duracionHoras && duracionHoras.value) {
            const diffMs = fin - inicio;
            const diffHoras = Math.floor(diffMs / (1000 * 60 * 60));
            const duracionEsperada = parseInt(duracionHoras.value);
            
            if (diffHoras !== duracionEsperada) {
                alert(`La duración entre la fecha de inicio y fin es de ${diffHoras} horas, pero especificaste ${duracionEsperada} horas. Por favor, ajusta las fechas o la duración.`);
                return false;
            }
        }
        
        // Validar fecha de evaluación (debe ser después o igual a fecha fin)
        if (evaluacion && fechaFin.value && evaluacion < fin) {
            alert('La fecha de evaluación debe ser posterior o igual a la fecha de finalización');
            fechaEvaluacion.value = '';
            return false;
        }
        
        // Validar fecha de premiación (puede ser después)
        if (premiacion && fechaFin.value && premiacion < fin) {
            alert('La fecha de premiación debe ser posterior a la fecha de finalización');
            fechaPremiacion.value = '';
            return false;
        }
        
        return true;
    }
    
    if (fechaRegistro) fechaRegistro.addEventListener('change', validarFechas);
    if (fechaInicio) fechaInicio.addEventListener('change', validarFechas);
    if (fechaFin) fechaFin.addEventListener('change', validarFechas);
    if (fechaEvaluacion) fechaEvaluacion.addEventListener('change', validarFechas);
    if (fechaPremiacion) fechaPremiacion.addEventListener('change', validarFechas);
    if (duracionHoras) duracionHoras.addEventListener('change', validarFechas);
    
    // ==========================================
    // 7. VALIDACIÓN DE PREMIOS
    // ==========================================
    function validarPremio(input) {
        let value = input.value;
        
        // Permitir: $, letras, números, +, puntos y espacios
        value = value.replace(/[^$a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\+\.]/g, '');
        input.value = value;
        
        // Limitar a 40 caracteres
        if (value.length > 40) {
            value = value.substring(0, 40);
            input.value = value;
        }
    }
    
    // Observar nuevos premios agregados dinámicamente
    const premiosContainer = document.getElementById('premios-container');
    if (premiosContainer) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) {
                        const inputDescripcion = node.querySelector('input[name*="[descripcion]"]');
                        if (inputDescripcion) {
                            inputDescripcion.addEventListener('input', function() {
                                validarPremio(this);
                            });
                        }
                    }
                });
            });
        });
        
        observer.observe(premiosContainer, { childList: true });
    }
    
    // ==========================================
    // 8. VALIDACIÓN AL ENVIAR FORMULARIO
    // ==========================================
    const formEvento = document.querySelector('form[action*="eventos"]');
    
    if (formEvento) {
        formEvento.addEventListener('submit', function(e) {
            // Validar nombre
            const nombre = nombreEvento.value.trim();
            if (nombre.length === 0) {
                e.preventDefault();
                alert('El nombre del evento es obligatorio');
                nombreEvento.focus();
                return false;
            }
            
            if (nombre.length > 35) {
                e.preventDefault();
                alert('El nombre del evento no puede tener más de 35 caracteres');
                nombreEvento.focus();
                return false;
            }
            
            const nombreRegex = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-]+$/;
            if (!nombreRegex.test(nombre)) {
                e.preventDefault();
                alert('El nombre del evento solo puede contener letras, números y guiones');
                nombreEvento.focus();
                return false;
            }
            
            // Validar descripción
            if (descripcionEvento) {
                const descripcion = descripcionEvento.value.trim();
                if (descripcion.length === 0) {
                    e.preventDefault();
                    alert('La descripción del evento es obligatoria');
                    descripcionEvento.focus();
                    return false;
                }
                
                if (descripcion.length > 150) {
                    e.preventDefault();
                    alert('La descripción no puede tener más de 150 caracteres');
                    descripcionEvento.focus();
                    return false;
                }
            }
            
            // Validar fechas
            if (!validarFechas()) {
                e.preventDefault();
                return false;
            }
            
            // Validar tamaño de equipo
            if (minMiembros && parseInt(minMiembros.value) !== 5) {
                e.preventDefault();
                alert('El tamaño mínimo de equipo debe ser 5');
                minMiembros.focus();
                return false;
            }
            
            if (maxMiembros && parseInt(maxMiembros.value) !== 6) {
                e.preventDefault();
                alert('El tamaño máximo de equipo debe ser 6');
                maxMiembros.focus();
                return false;
            }
            
            // Validar que el rol de Asesor esté seleccionado
            const checkboxAsesor = document.querySelector('input[type="checkbox"][value="Asesor"]');
            if (!checkboxAsesor || !checkboxAsesor.checked) {
                e.preventDefault();
                alert('El rol de Asesor es obligatorio');
                return false;
            }
            
            return true;
        });
    }
});

// ==========================================
// FUNCIONES PARA PREMIOS
// ==========================================
let premioIndex = 0;
let contadorLugar = 1;

function agregarPremio() {
    const container = document.getElementById('premios-container');
    const div = document.createElement('div');
    div.className = 'flex items-center gap-4';
    
    // Determinar el texto del lugar según el número
    let textoLugar = '';
    if (contadorLugar === 1) textoLugar = '1er lugar';
    else if (contadorLugar === 2) textoLugar = '2do lugar';
    else if (contadorLugar === 3) textoLugar = '3er lugar';
    else textoLugar = `${contadorLugar}to lugar`;
    
    div.innerHTML = `
        <input type="text" 
               name="premios[${premioIndex}][lugar]" 
               value="${textoLugar}"
               class="w-32 px-4 py-2 border border-gray-300 rounded-lg">
        <input type="text" 
               name="premios[${premioIndex}][descripcion]" 
               placeholder="Ej: $10,000 + Trofeo"
               maxlength="40"
               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
        <button type="button" onclick="eliminarPremio(this)" class="text-red-500 hover:text-red-700">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </button>
    `;
    container.appendChild(div);
    premioIndex++;
    contadorLugar++;
}

function eliminarPremio(button) {
    button.parentElement.remove();
    contadorLugar--;
    recalcularLugares();
}

function recalcularLugares() {
    const container = document.getElementById('premios-container');
    const premios = container.querySelectorAll('div.flex.items-center');
    contadorLugar = 1;
    
    premios.forEach((premio) => {
        const inputLugar = premio.querySelector('input[name*="[lugar]"]');
        if (inputLugar) {
            let textoLugar = '';
            if (contadorLugar === 1) textoLugar = '1er lugar';
            else if (contadorLugar === 2) textoLugar = '2do lugar';
            else if (contadorLugar === 3) textoLugar = '3er lugar';
            else textoLugar = `${contadorLugar}to lugar`;
            
            inputLugar.value = textoLugar;
            contadorLugar++;
        }
    });
}

function agregarRolPersonalizado() {
    const nombreRol = prompt('Ingrese el nombre del rol:');
    if (!nombreRol || nombreRol.trim() === '') return;
    
    const container = document.getElementById('roles-container');
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2 p-4 border-2 border-gray-200 rounded-lg';
    div.innerHTML = `
        <input type="checkbox" 
               name="roles[]" 
               value="${nombreRol.trim()}" 
               checked
               class="w-5 h-5 text-indigo-600 rounded">
        <input type="text" 
               value="${nombreRol.trim()}" 
               readonly
               class="flex-1 font-medium bg-transparent border-0 p-0 focus:ring-0">
        <button type="button" 
                onclick="this.parentElement.remove()" 
                class="text-red-500 hover:text-red-700">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    `;
    container.appendChild(div);
}
