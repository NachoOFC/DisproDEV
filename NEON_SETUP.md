# 🗄️ Base de Datos PostgreSQL - Neon

## 📋 Información General

- **Archivo Original**: `mline.sql` (MariaDB/MySQL - 73,194 líneas)
- **Archivo Convertido**: `mline_postgres.sql` (PostgreSQL - 73,149 líneas)
- **Tablas**: 45+ tablas con relaciones y datos
- **Plataforma**: Compatible con **Neon PostgreSQL**

## 🚀 Guía de Setup con Neon

### 1. Crear Proyecto en Neon

```
1. Ve a https://neon.tech
2. Regístrate o inicia sesión
3. Crea un nuevo proyecto
4. Copia la cadena de conexión (URL PostgreSQL)
```

### 2. Subir Base de Datos

#### Opción A: Desde Neon Console

```
1. En Neon Dashboard, ve a "SQL Editor"
2. Abre el archivo `mline_postgres.sql`
3. Copia TODO el contenido
4. Pégalo en el editor SQL de Neon
5. Ejecuta el script
```

#### Opción B: Usando pgAdmin (recomendado para archivos grandes)

```
1. Descarga pgAdmin: https://www.pgadmin.org/download/
2. Conecta a tu base de datos Neon:
   - Host: [tu-proyecto].neon.tech
   - Port: 5432
   - Database: neondb (o la que configuraste)
   - Username: [tu-usuario]
   - Password: [tu-contraseña]
3. Clic derecho en la BD > "Restore"
4. Selecciona el archivo `mline_postgres.sql`
```

#### Opción C: Usando psql (línea de comandos)

```bash
# Instala PostgreSQL Client si no lo tienes
# https://www.postgresql.org/download/

# Conéctate y ejecuta el script
psql -h [proyecto].neon.tech -U [usuario] -d neondb -f mline_postgres.sql

# Ejemplo real:
# psql -h ep-rapid-river-12345.neon.tech -U neondb_owner -d neondb -f mline_postgres.sql
```

### 3. Verificar Conexión en Nuxt

Edita `nuxt.config.ts`:

```typescript
export default defineNuxtConfig({
  nitro: {
    env: {
      DATABASE_URL: process.env.DATABASE_URL || 'postgresql://user:password@host:5432/database'
    }
  }
})
```

### 4. Instalar Dependencias (si necesitas conectar desde backend)

```bash
npm install pg
npm install prisma @prisma/client
```

### 5. Configurar Variables de Entorno

Crea `.env.local`:

```
DATABASE_URL="postgresql://[usuario]:[contraseña]@[proyecto].neon.tech:5432/neondb"
```

## 📊 Tablas Principales Convertidas

| Tabla | Descripción |
|-------|-------------|
| `usuarios` | Información de usuarios |
| `empresas` | Datos de empresas |
| `centros` | Centros de distribución |
| `productos` | Catálogo de productos |
| `requerimientos` | Órdenes de cliente |
| `orden_compras` | Compras a proveedores |
| `factura_electronicas` | Facturas emitidas |
| `guia_despachos` | Guías de transporte |
| `entradas` | Recepción de productos |
| `salidas` | Despacho de productos |
| `ajustes` | Correcciones de inventario |
| `cierres` | Cierre de períodos |

## ⚙️ Cambios Realizados en la Conversión

### MySQL → PostgreSQL

- ✅ `AUTO_INCREMENT` → `SERIAL` (automático en CREATE TABLE)
- ✅ `BIGINT(20) unsigned` → `BIGINT`
- ✅ `INT(11)` → `INTEGER`
- ✅ `TINYINT(3)` → `SMALLINT`
- ✅ `` ` `` (backticks) → `"` (comillas)
- ✅ `ENGINE=InnoDB` → Omitido (PostgreSQL default)
- ✅ `CHARSET utf8mb4` → Omitido (PostgreSQL default UTF-8)
- ✅ `DELETE FROM` → `TRUNCATE` (más eficiente)
- ✅ Líneas `/*!...*/` de MySQL → Omitidas

## 🔍 Verificar que Todo Funciona

```sql
-- Conecta a Neon y ejecuta:
SELECT table_name FROM information_schema.tables 
WHERE table_schema = 'public';

-- Debería listar todas las tablas ~45 tablas

-- Ver cantidad de registros
SELECT tablename, n_live_tup FROM pg_stat_user_tables;
```

## 🛡️ Backup y Seguridad

### Hacer backup desde Neon

```bash
pg_dump -h [proyecto].neon.tech -U [usuario] -d neondb > backup.sql
```

### Restaurar desde backup

```bash
psql -h [proyecto].neon.tech -U [usuario] -d neondb < backup.sql
```

## 🐛 Troubleshooting

### Error: "relation does not exist"
→ Asegúrate de haber ejecutado TODO el archivo SQL antes

### Error: "permission denied"
→ Verifica credenciales de Neon

### Error: "duplicate key value"
→ La BD ya tiene datos. Usa `DROP SCHEMA public CASCADE` primero

### Error: "connection timeout"
→ Verifica que tu IP esté en la whitelist de Neon (Settings > IP Whitelist)

## 📝 Notas Importantes

1. **Tamaño del archivo**: 73MB+ cuando se descomprime completamente
2. **Tiempo de importación**: ~2-5 minutos según velocidad de conexión
3. **Datos sensibles**: Cambia las contraseñas de usuarios después de importar
4. **Indices**: Todos están creados automáticamente con las claves foráneas

## 🎯 Próximos Pasos

1. ✅ Importar BD a Neon
2. ⏳ Conectar Nuxt a la BD (crear API endpoints)
3. ⏳ Reemplazar mock data con datos reales
4. ⏳ Deploy a producción

---

**Script de conversión**: `convert_to_postgres.py`  
**Última actualización**: 28 de octubre de 2025
