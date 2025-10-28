# 🚀 MIGRAR BD DE MYSQL A POSTGRESQL EN NEON

## OPCIÓN MÁS RÁPIDA (SIN CONVERSIÓN MANUAL)

### ✅ MÉTODO 1: USAR MIGRATIONS DE LARAVEL (RECOMENDADO)

```bash
# 1. Cambiar BD en .env.production
DB_CONNECTION=pgsql
DB_HOST=your-neon-host.neon.tech
DB_DATABASE=neondb
DB_USERNAME=your_username
DB_PASSWORD=your_password

# 2. Ejecutar migraciones
php artisan migrate --force

# 3. Ejecutar seeds (si tienes datos)
php artisan db:seed --force
```

**Ventaja:** Las migraciones de Laravel hacen TODO automáticamente y funciona en cualquier BD.

---

## OPCIÓN 2: CONVERTIR SQL AUTOMÁTICAMENTE

### A) Usar el script Python que creamos:

```bash
# 1. Asegúrate de tener Python 3 instalado
python3 --version

# 2. Ejecuta la conversión
python3 convert_mysql_to_postgresql.py mline.sql mline_postgresql.sql

# Resultado: mline_postgresql.sql (listo para Neon)
```

### B) Usar online (sin instalar nada):

1. Ve a: https://www.convert-mysql-to-postgresql.com/
2. Pega tu SQL de `mline.sql`
3. Descarga el resultado convertido
4. Copia en Neon

---

## OPCIÓN 3: IMPORTAR DIRECTAMENTE EN NEON

### Paso a Paso:

1. **Ve a Neon Console:**
   - https://console.neon.tech
   - Selecciona tu proyecto

2. **Abre SQL Editor:**
   - Panel izquierdo → SQL Editor
   - O haz click en "Open in Neon console"

3. **Copia el SQL MySQL convertido:**
   ```
   Opción A: Usa el script Python (recomendado)
   Opción B: Usa convertidor online
   Opción C: Copia manual y reemplaza tipos de datos
   ```

4. **Pega en Neon y ejecuta:**
   - Copia todo el SQL convertido
   - Pégalo en el editor
   - Click en "Run"

5. **Verifica que las tablas se crearon:**
   ```sql
   SELECT table_name 
   FROM information_schema.tables 
   WHERE table_schema = 'public'
   ORDER BY table_name;
   ```

---

## CAMBIOS PRINCIPALES MYSQL → POSTGRESQL

Si quieres hacerlo manual, reemplaza esto:

| MySQL | PostgreSQL |
|-------|-----------|
| `backticks` | "comillas" |
| BIGINT(20) UNSIGNED AUTO_INCREMENT | BIGSERIAL |
| INT(11) UNSIGNED AUTO_INCREMENT | SERIAL |
| TINYINT | SMALLINT |
| TIMESTAMP | TIMESTAMP WITHOUT TIME ZONE |
| ENGINE=InnoDB | (No aplica) |
| CHARSET utf8mb4 | (Está por defecto) |
| PRIMARY KEY (id) KEY ... FOREIGN KEY | PRIMARY KEY (...), FOREIGN KEY (...) |

---

## ⚡ LA MANERA MÁS RÁPIDA:

### SIN TOCAR SQL:

1. **En tu local:**
   ```bash
   # Cambiar BD a PostgreSQL
   php artisan tinker
   DB::statement('CREATE SCHEMA public');
   ```

2. **Ejecutar migraciones:**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

3. **¡LISTO!** Todas las tablas se crean automáticamente.

---

## 🎯 RECOMENDACIÓN FINAL:

**USA LAS MIGRACIONES DE LARAVEL** porque:
- ✅ Funciona con cualquier BD
- ✅ No tienes que convertir SQL manual
- ✅ Es exactamente igual a tu BD actual
- ✅ Vercel lo hace automáticamente en el deploy

```bash
# En tu PC local:
php artisan migrate:fresh --seed

# En Vercel (después de deploy):
vercel env pull
php artisan migrate --force
```

---

## ❌ SI QUIERES MANTENER DATOS EXACTOS:

Si tu BD actual tiene datos que quieres preservar:

1. Exporta datos desde MySQL:
   ```bash
   mysqldump -u username -p mlinecl_alogis_dev > backup.sql
   ```

2. Convierte a PostgreSQL:
   ```bash
   python3 convert_mysql_to_postgresql.py backup.sql backup_pg.sql
   ```

3. Importa en Neon:
   - Abre Neon console
   - SQL Editor
   - Pega backup_pg.sql
   - Run

---

## 🔗 ÚTILES:

- **Neon Console:** https://console.neon.tech
- **MySQL to PostgreSQL Converter:** https://www.convert-mysql-to-postgresql.com/
- **pgAdmin Download:** https://www.pgadmin.org/download/

---

**¿Cuál opción prefieres?** Yo recomiendo **Migraciones de Laravel** 100%
