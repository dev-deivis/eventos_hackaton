# 🔧 FIX: Error IF() en PostgreSQL (Railway)

## ❌ PROBLEMA

**Error en Railway:**
```
SQLSTATE[42703]: Undefined column: 7 ERROR: column "Sí" does not exist
LINE 1: ...IF(proyectos.id IS NOT NULL, "Sí", "No")...
```

**Funcionaba en localhost pero NO en producción**

## 💡 CAUSA

**Diferencia de bases de datos:**

- **Localhost:** MySQL → Usa función `IF(condición, valor_si, valor_no)`
- **Railway:** PostgreSQL → NO tiene función `IF()`, usa `CASE WHEN`

## 🐛 CÓDIGO PROBLEMÁTICO

```php
// ❌ MySQL only - NO funciona en PostgreSQL
DB::raw('IF(proyectos.id IS NOT NULL, "Sí", "No") as proyecto_entregado')
```

## ✅ SOLUCIÓN

Usar **`CASE WHEN`** que funciona en **ambas** bases de datos:

```php
// ✅ Compatible con MySQL y PostgreSQL
DB::raw("CASE WHEN proyectos.id IS NOT NULL THEN 'Sí' ELSE 'No' END as proyecto_entregado")
```

## 📝 ARCHIVO CORREGIDO

**`app/Exports/ReportesExport.php`** - Línea 253

```php
public function collection()
{
    $query = DB::table('equipos')
        ->leftJoin('equipo_participante', 'equipos.id', '=', 'equipo_participante.equipo_id')
        ->leftJoin('proyectos', 'equipos.id', '=', 'proyectos.equipo_id')
        ->select(
            'equipos.nombre',
            DB::raw('COUNT(DISTINCT equipo_participante.participante_id) as miembros'),
            DB::raw("CASE WHEN proyectos.id IS NOT NULL THEN 'Sí' ELSE 'No' END as proyecto_entregado"),
            'equipos.estado'
        )
        ->groupBy('equipos.id', 'equipos.nombre', 'equipos.estado', 'proyectos.id');

    if ($this->eventoId) {
        $query->where('equipos.evento_id', $this->eventoId);
    }

    return $query->get();
}
```

## 🔍 DIFERENCIAS CLAVE

### MySQL:
```sql
IF(condición, 'valor_si', 'valor_no')
```

### PostgreSQL:
```sql
CASE WHEN condición THEN 'valor_si' ELSE 'valor_no' END
```

### Ambos (CASE WHEN):
```sql
CASE WHEN condición THEN 'valor_si' ELSE 'valor_no' END
```

## 🚀 DEPLOY

```bash
git add app/Exports/ReportesExport.php
git commit -m "fix: Cambiar IF() por CASE WHEN para compatibilidad PostgreSQL"
git push origin main
```

## ✅ RESULTADO ESPERADO

Después del deploy:

- ✅ Exportación Excel funciona en localhost (MySQL)
- ✅ Exportación Excel funciona en Railway (PostgreSQL)
- ✅ Columna "Proyecto Entregado" muestra "Sí" o "No"
- ✅ Sin errores SQLSTATE[42703]

## 📚 OTRAS FUNCIONES MYSQL QUE PUEDEN CAUSAR PROBLEMAS

Si encuentras estos en el futuro, aquí están las conversiones:

| MySQL | PostgreSQL | Universal |
|-------|-----------|-----------|
| `IF(cond, a, b)` | `CASE WHEN cond THEN a ELSE b END` | **CASE WHEN** |
| `IFNULL(col, val)` | `COALESCE(col, val)` | **COALESCE** |
| `CONCAT(a, b)` | `a || b` o `CONCAT(a, b)` | **CONCAT** |
| `DATE_FORMAT()` | `TO_CHAR()` | *Diferentes* |
| `LIMIT 10` | `LIMIT 10` | **LIMIT** ✅ |

## 💡 BUENAS PRÁCTICAS

Para evitar este tipo de problemas:

1. **Usar funciones estándar SQL** cuando sea posible
2. **Probar con ambas bases de datos** (MySQL y PostgreSQL)
3. **Revisar queries con DB::raw()** - son los más problemáticos
4. **Usar Eloquent** en lugar de raw queries cuando sea posible

## 🎯 CHECKLIST

- [x] Identificar función incompatible (IF)
- [x] Cambiar a CASE WHEN
- [x] Probar localmente
- [x] Commit y push
- [ ] Verificar en Railway
- [ ] Probar exportación Excel

---

**Fix aplicado:** 7 de Diciembre, 2025
**Archivo:** app/Exports/ReportesExport.php
**Línea:** 253
**Compatibilidad:** ✅ MySQL y PostgreSQL
