# 🎯 GUÍA COMPLETA: CÓMO GENERAR CONSTANCIAS

## 📋 PASO 1: INSTALACIÓN (Una sola vez)

### Ejecuta este comando:
```
Haz doble clic en: instalar_todo.bat
```

Esto hará automáticamente:
- ✅ Instalar DomPDF
- ✅ Ejecutar migraciones
- ✅ Crear 5 participantes de prueba
- ✅ Crear 1 evento finalizado
- ✅ Crear 2 equipos con proyectos

---

## 🎓 DATOS DE PRUEBA CREADOS:

### Evento:
- **Nombre**: Hackathon 2025
- **Estado**: Finalizado
- **Participantes**: 5 personas

### Participantes creados:
```
1. Karla Delgado Molina - participante1@tecnm.mx
2. Jesús Martínez Martínez - participante2@tecnm.mx
3. Ángel Zárate Matus - participante3@tecnm.mx
4. María García López - participante4@tecnm.mx
5. Carlos Hernández Silva - participante5@tecnm.mx

Contraseña para todos: password123
```

### Equipos creados:
```
1. The Boings
   - Líder: Karla Delgado Molina
   - Miembros: Karla, Jesús
   - Proyecto: Sistema de Gestión Infantil

2. Equipo X
   - Líder: Ángel Zárate Matus
   - Miembros: Ángel, María
   - Proyecto: Innovadores Tech
```

---

## 🚀 PASO 2: GENERAR CONSTANCIAS

### OPCIÓN A: Constancia Individual

1. **Ve al Dashboard Admin**
   - Ya estás como admin ✅

2. **Haz clic en "Constancias"** (botón rosa)

3. **Haz clic en la pestaña "Generar Nuevas"**

4. **Llena el formulario:**
   ```
   Destinatario: Karla Delgado Molina
   Email: participante1@tecnm.mx
   Evento: Hackathon 2025
   Tipo: Participación
   Notas: (opcional)
   ```

5. **Haz clic en "Generar Constancia"** (botón rosa)

6. **¡Listo!** Serás redirigido a "Constancias Emitidas"

7. **Verás una card con:**
   - Nombre: Karla Delgado Molina
   - Evento: Hackathon 2025
   - Equipo: The Boings
   - Proyecto: Sistema de Gestión Infantil
   - Código: HACKXXXX-XXX-000
   - Botón: **Descargar** ⬇️

8. **Haz clic en "Descargar"** para obtener el PDF

---

### OPCIÓN B: Constancias en Lote (Recomendada)

1. **Ve al Dashboard Admin**

2. **Haz clic en "Constancias"** (botón rosa)

3. **Haz clic en la pestaña "Generar Nuevas"**

4. **Scroll hacia abajo** hasta "Generar en Lote"

5. **Selecciona:**
   ```
   Evento: Hackathon 2025 - 5 participantes
   Tipo: Participación (todos los participantes)
   ```

6. **Haz clic en "Generar Constancias en Lote"** (botón morado)

7. **Verás mensaje:** "Se generaron 5 constancias exitosamente."

8. **Ve a "Constancias Emitidas"**

9. **Verás 5 cards** - una por cada participante

10. **Haz clic en "Descargar"** en cualquier card para ver el PDF

---

## 📄 CÓMO SE VE EL PDF:

### Certificado de Participación:
```
┌─────────────────────────────────────────────┐
│ 🏫 TecNM          📚 SEP EDUCACIÓN         │
│                                             │
│   Instituto Tecnológico Nacional de México  │
│   Campus Oaxaca otorga el presente         │
│                                             │
│   Certificado de participación a            │
│                                             │
│        KARLA DELGADO MOLINA                 │
│                                             │
│   por haber participado en el evento        │
│   Hackathon 2025                            │
│                                             │
│   con el proyecto "Sistema de Gestión       │
│   Infantil" con The Boings                  │
│                                             │
│   ────────────────    ────────────────     │
│   Firma 1              Firma 2              │
│                                             │
│   Código: HACKABCD-XYZ-123                  │
└─────────────────────────────────────────────┘
```

**Diseño:**
- Fondo: Gradiente morado
- Formato: Horizontal (landscape)
- Logos: TecNM y SEP
- 4 firmas de autoridades
- Código de verificación único

---

## ❓ PREGUNTAS FRECUENTES:

### ¿Necesito inscribirme al evento?
**NO.** Los datos de prueba ya están inscritos automáticamente.

### ¿Un juez me tiene que dar ganador?
**NO.** Para constancias de **Participación**, no necesitas ser ganador.
Para constancias de **Ganador**, sí (pero puedes generarlas igual como admin).

### ¿Puedo generar constancias de Ganador?
**SÍ.** Selecciona "Ganador" en lugar de "Participación". 
El PDF será rosa en lugar de morado y dirá "🏆 1ER LUGAR".

### ¿Puedo generar constancias para eventos activos?
**SÍ.** Aunque el ideal es para eventos finalizados.

### ¿Se pueden duplicar las constancias?
**NO.** El sistema previene duplicados. Un participante solo puede tener UNA constancia de cada tipo por evento.

### ¿Dónde se guardan los PDFs?
**No se guardan.** Se generan al momento de descargar (on-the-fly).

---

## 🎨 TIPOS DE CONSTANCIAS:

### 1. Participación (Morado)
- Para todos los participantes
- Solo por haber participado
- No requiere ser ganador

### 2. Ganador (Rosa)
- Para equipos ganadores
- Incluye badge "🏆 1ER LUGAR"
- Mismo formato pero rosa

---

## ✅ CHECKLIST DE PRUEBA:

```
[ ] Ejecutar: instalar_todo.bat
[ ] Ver mensaje: "Se generaron 5 constancias"
[ ] Ir a Dashboard Admin
[ ] Clic en "Constancias"
[ ] Ver 3 pestañas
[ ] Generar constancia individual
[ ] Ver constancia en "Constancias Emitidas"
[ ] Descargar PDF
[ ] Verificar que el PDF se abre correctamente
[ ] Probar "Generar en Lote"
[ ] Ver 5 constancias generadas
[ ] Descargar varias constancias
```

---

## 🐛 SOLUCIÓN DE PROBLEMAS:

### Error: "No se encontró un participante con ese email"
**Causa**: El email no existe en la BD
**Solución**: Usa los emails de prueba listados arriba

### Error: "Ya tiene una constancia de este tipo"
**Causa**: Ya generaste una constancia para ese participante
**Solución**: Normal, el sistema previene duplicados. Genera para otro participante.

### No aparece el evento en el dropdown
**Causa**: No ejecutaste el seeder
**Solución**: Ejecuta `php artisan db:seed --class=ConstanciasTestSeeder`

### El PDF no se descarga
**Causa**: DomPDF no está instalado
**Solución**: Ejecuta `composer require barryvdh/laravel-dompdf`

---

## 🚀 RESUMEN RÁPIDO:

```bash
# 1. Instalar todo
instalar_todo.bat

# 2. En el navegador:
Dashboard Admin → Constancias → Generar Nuevas

# 3. Llenar formulario:
Email: participante1@tecnm.mx
Evento: Hackathon 2025
Tipo: Participación

# 4. ¡Generar y descargar!
```

---

**¿Listo para probarlo? Ejecuta `instalar_todo.bat` y luego dime si funciona!** 🚀
