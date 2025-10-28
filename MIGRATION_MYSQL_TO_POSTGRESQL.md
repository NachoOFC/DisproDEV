-- ============================================
-- GUÍA: MIGRAR SQL DE MYSQL A POSTGRESQL EN NEON
-- ============================================

-- OPCIÓN 1: CONVERTIR EL SQL Y EJECUTARLO EN NEON (RECOMENDADO)

-- ============================================
-- PASO 1: CONVERTIR TIPOS DE DATOS
-- ============================================

-- MySQL -> PostgreSQL:
-- BIGINT(20) unsigned -> BIGINT
-- VARCHAR(255) -> VARCHAR(255)
-- TINYINT(3) -> SMALLINT
-- TIMESTAMP -> TIMESTAMP WITHOUT TIME ZONE
-- INT AUTO_INCREMENT -> SERIAL or BIGSERIAL
-- ENGINE=InnoDB -> (No aplica en PostgreSQL)
-- CHARSET utf8mb4 -> ENCODING UTF8 (por defecto)

-- ============================================
-- PASO 2: INSTALAR pgAdmin O USAR NEON UI
-- ============================================

-- Opción A: Desde Neon Console
-- 1. Ve a https://console.neon.tech
-- 2. Selecciona tu proyecto
-- 3. Ve a "SQL Editor"
-- 4. Pega el SQL convertido

-- Opción B: Usar pgAdmin
-- 1. Descarga pgAdmin: https://www.pgadmin.org/
-- 2. Conecta a Neon con connection string
-- 3. Pega el SQL

-- ============================================
-- PASO 3: SQL CONVERTIDO DE MLINECL (EJEMPLO)
-- ============================================

-- Crear extensión UUID si la necesitas
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Tabla: abastecimientos
CREATE TABLE IF NOT EXISTS abastecimientos (
  id BIGSERIAL PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  comuna VARCHAR(255) NOT NULL,
  ciudad VARCHAR(255) NOT NULL,
  deleted_at TIMESTAMP NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL
);

-- Tabla: ajustes
CREATE TABLE IF NOT EXISTS ajustes (
  id BIGSERIAL PRIMARY KEY,
  bidon_id BIGINT UNSIGNED NOT NULL,
  cantidad VARCHAR(255) NOT NULL,
  fecha_ingreso DATE NOT NULL,
  suma SMALLINT NOT NULL,
  deleted_at TIMESTAMP NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (bidon_id) REFERENCES bidons(id)
);

-- Tabla: bidons
CREATE TABLE IF NOT EXISTS bidons (
  id BIGSERIAL PRIMARY KEY,
  proveedor_id BIGINT UNSIGNED NOT NULL,
  codigo VARCHAR(255) NOT NULL,
  nombre VARCHAR(255) NOT NULL,
  deleted_at TIMESTAMP NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (proveedor_id) REFERENCES proveedors(id)
);

-- Tabla: bodegueros
CREATE TABLE IF NOT EXISTS bodegueros (
  id BIGSERIAL PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  rut VARCHAR(255) NOT NULL,
  deleted_at TIMESTAMP NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL
);

-- ... (resto de tablas)

-- ============================================
-- PASO 4: OPCIÓN ALTERNATIVA - USAR HERRAMIENTA
-- ============================================

-- Si quieres convertir automáticamente:
-- 1. Usa: https://www.sqlgenerator.com/
-- 2. O descarga: https://github.com/lovasoa/mysql_to_sqlite
-- 3. O usa: https://www.convert-mysql-to-postgresql.com/

-- ============================================
-- PASO 5: IMPORTAR DATOS
-- ============================================

-- Opción A: Desde archivo SQL en pgAdmin
-- 1. Abre pgAdmin
-- 2. Query Tool
-- 3. Copia y pega el SQL completo
-- 4. Ejecuta

-- Opción B: Desde Neon Console
-- 1. Ve a SQL Editor
-- 2. Pega el SQL
-- 3. Run

-- Opción C: Desde Terminal (psql)
-- psql -h your-host.neon.tech -U your_user -d your_db -f /path/to/converted.sql

-- ============================================
-- PASO 6: VERIFICAR DATOS
-- ============================================

SELECT table_name 
FROM information_schema.tables 
WHERE table_schema = 'public';

-- Ver datos de una tabla
SELECT * FROM abastecimientos;

-- Contar registros
SELECT COUNT(*) as total FROM abastecimientos;

-- ============================================
-- ALTERNATIVA RÁPIDA: USAR RAILWAY
-- ============================================

-- Si no quieres convertir manualmente, Railway puede:
-- 1. Importar directo desde MySQL
-- 2. Ejecutar migraciones de Laravel
-- 3. Sincronizar todo automáticamente

-- Comando en Laravel (funciona con cualquier BD):
-- php artisan migrate
-- php artisan db:seed
