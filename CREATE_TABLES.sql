-- ============================================
-- ALOGIS - PostgreSQL Database Schema
-- Sistema de GestiÃ³n de DistribuciÃ³n y Abastecimiento
-- Convertido desde MariaDB a PostgreSQL
-- ============================================

-- Tabla: users (Usuarios del sistema)
CREATE TABLE IF NOT EXISTS "users" (
  "id" BIGSERIAL PRIMARY KEY,
  "name" VARCHAR(255) NOT NULL,
  "email" VARCHAR(255) UNIQUE NOT NULL,
  "email_verified_at" TIMESTAMP NULL,
  "password" VARCHAR(255) NOT NULL,
  "remember_token" VARCHAR(100) NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: empresas (Empresas del sistema)
CREATE TABLE IF NOT EXISTS "empresas" (
  "id" BIGSERIAL PRIMARY KEY,
  "razon_social" VARCHAR(255) NOT NULL,
  "rut" VARCHAR(20) NOT NULL,
  "giro" VARCHAR(255) NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: centros (Centros de distribuciÃ³n)
CREATE TABLE IF NOT EXISTS "centros" (
  "id" BIGSERIAL PRIMARY KEY,
  "nombre" VARCHAR(255) NOT NULL,
  "direccion" VARCHAR(500) NULL,
  "ciudad" VARCHAR(255) NULL,
  "comuna" VARCHAR(255) NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: productos (CatÃ¡logo de productos)
CREATE TABLE IF NOT EXISTS "productos" (
  "id" BIGSERIAL PRIMARY KEY,
  "codigo" VARCHAR(255) UNIQUE NOT NULL,
  "nombre" VARCHAR(500) NOT NULL,
  "descripcion" TEXT NULL,
  "precio" DECIMAL(10,2) NULL,
  "stock" INT DEFAULT 0,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: proveedores
CREATE TABLE IF NOT EXISTS "proveedors" (
  "id" BIGSERIAL PRIMARY KEY,
  "nombre" VARCHAR(255) NOT NULL,
  "rut" VARCHAR(20) NOT NULL,
  "contacto" VARCHAR(255) NULL,
  "telefono" VARCHAR(20) NULL,
  "email" VARCHAR(255) NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: clientes (Clientes del sistema)
CREATE TABLE IF NOT EXISTS "clientes" (
  "id" BIGSERIAL PRIMARY KEY,
  "nombre" VARCHAR(255) NOT NULL,
  "rut" VARCHAR(20) UNIQUE NOT NULL,
  "contacto" VARCHAR(255) NULL,
  "telefono" VARCHAR(20) NULL,
  "email" VARCHAR(255) NULL,
  "direccion" VARCHAR(500) NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: requerimientos (Ã“rdenes de compra de clientes)
CREATE TABLE IF NOT EXISTS "requerimientos" (
  "id" BIGSERIAL PRIMARY KEY,
  "cliente_id" BIGINT NOT NULL,
  "numero" VARCHAR(50) UNIQUE NOT NULL,
  "fecha" DATE NOT NULL,
  "total" DECIMAL(12,2) NULL,
  "estado" VARCHAR(50) DEFAULT 'pendiente',
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("cliente_id") REFERENCES "clientes" ("id")
);

-- Tabla: requerimiento_user (RelaciÃ³n entre requerimientos y usuarios)
CREATE TABLE IF NOT EXISTS "requerimiento_user" (
  "id" BIGSERIAL PRIMARY KEY,
  "requerimiento_id" BIGINT NOT NULL,
  "user_id" BIGINT NOT NULL,
  FOREIGN KEY ("requerimiento_id") REFERENCES "requerimientos" ("id"),
  FOREIGN KEY ("user_id") REFERENCES "users" ("id")
);

-- Tabla: producto_requerimiento (Detalle de requerimientos)
CREATE TABLE IF NOT EXISTS "producto_requerimiento" (
  "id" BIGSERIAL PRIMARY KEY,
  "requerimiento_id" BIGINT NOT NULL,
  "producto_id" BIGINT NOT NULL,
  "cantidad" INT NOT NULL,
  "precio_unitario" DECIMAL(10,2) NULL,
  "subtotal" DECIMAL(12,2) NULL,
  FOREIGN KEY ("requerimiento_id") REFERENCES "requerimientos" ("id"),
  FOREIGN KEY ("producto_id") REFERENCES "productos" ("id")
);

-- Tabla: presupuestos (Presupuestos para clientes)
CREATE TABLE IF NOT EXISTS "presupuestos" (
  "id" BIGSERIAL PRIMARY KEY,
  "cliente_id" BIGINT NOT NULL,
  "numero" VARCHAR(50) UNIQUE NOT NULL,
  "fecha" DATE NOT NULL,
  "total" DECIMAL(12,2) NULL,
  "estado" VARCHAR(50) DEFAULT 'pendiente',
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("cliente_id") REFERENCES "clientes" ("id")
);

-- Tabla: orden_compras (Ã“rdenes de compra a proveedores)
CREATE TABLE IF NOT EXISTS "orden_compras" (
  "id" BIGSERIAL PRIMARY KEY,
  "proveedor_id" BIGINT NOT NULL,
  "numero" VARCHAR(50) UNIQUE NOT NULL,
  "fecha" DATE NOT NULL,
  "total" DECIMAL(12,2) NULL,
  "estado" VARCHAR(50) DEFAULT 'pendiente',
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("proveedor_id") REFERENCES "proveedors" ("id")
);

-- Tabla: factura_electronicas (Facturas electrÃ³nicas)
CREATE TABLE IF NOT EXISTS "factura_electronicas" (
  "id" BIGSERIAL PRIMARY KEY,
  "numero" VARCHAR(50) UNIQUE NOT NULL,
  "cliente_id" BIGINT NOT NULL,
  "fecha" DATE NOT NULL,
  "total" DECIMAL(12,2) NULL,
  "estado" VARCHAR(50) DEFAULT 'pendiente',
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("cliente_id") REFERENCES "clientes" ("id")
);

-- Tabla: guia_despachos (GuÃ­as de despacho)
CREATE TABLE IF NOT EXISTS "guia_despachos" (
  "id" BIGSERIAL PRIMARY KEY,
  "numero" VARCHAR(50) UNIQUE NOT NULL,
  "requerimiento_id" BIGINT NULL,
  "fecha" DATE NOT NULL,
  "estado" VARCHAR(50) DEFAULT 'pendiente',
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("requerimiento_id") REFERENCES "requerimientos" ("id")
);

-- Tabla: guia_despacho_producto (Detalle de guÃ­as de despacho)
CREATE TABLE IF NOT EXISTS "guia_despacho_producto" (
  "id" BIGSERIAL PRIMARY KEY,
  "guia_despacho_id" BIGINT NOT NULL,
  "producto_id" BIGINT NOT NULL,
  "cantidad" INT NOT NULL,
  FOREIGN KEY ("guia_despacho_id") REFERENCES "guia_despachos" ("id"),
  FOREIGN KEY ("producto_id") REFERENCES "productos" ("id")
);

-- Tabla: transportes (Transportistas)
CREATE TABLE IF NOT EXISTS "transportes" (
  "id" BIGSERIAL PRIMARY KEY,
  "nombre" VARCHAR(255) NOT NULL,
  "rut" VARCHAR(20) NOT NULL,
  "contacto" VARCHAR(255) NULL,
  "telefono" VARCHAR(20) NULL,
  "email" VARCHAR(255) NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: estados (Estados disponibles para documentos)
CREATE TABLE IF NOT EXISTS "estados" (
  "id" BIGSERIAL PRIMARY KEY,
  "nombre" VARCHAR(100) NOT NULL,
  "descripcion" TEXT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: abastecimientos (Puntos de abastecimiento)
CREATE TABLE IF NOT EXISTS "abastecimientos" (
  "id" BIGSERIAL PRIMARY KEY,
  "nombre" VARCHAR(255) NOT NULL,
  "comuna" VARCHAR(255) NULL,
  "ciudad" VARCHAR(255) NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: bodegueros (Bodegueros del sistema)
CREATE TABLE IF NOT EXISTS "bodegueros" (
  "id" BIGSERIAL PRIMARY KEY,
  "nombre" VARCHAR(255) NOT NULL,
  "rut" VARCHAR(20) NOT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: bidons (Productos por bidon)
CREATE TABLE IF NOT EXISTS "bidons" (
  "id" BIGSERIAL PRIMARY KEY,
  "proveedor_id" BIGINT NOT NULL,
  "codigo" VARCHAR(255) NOT NULL,
  "nombre" VARCHAR(255) NOT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("proveedor_id") REFERENCES "proveedors" ("id")
);

-- Tabla: ajustes (Ajustes de inventario)
CREATE TABLE IF NOT EXISTS "ajustes" (
  "id" BIGSERIAL PRIMARY KEY,
  "bidon_id" BIGINT NOT NULL,
  "cantidad" VARCHAR(255) NOT NULL,
  "fecha_ingreso" DATE NOT NULL,
  "suma" SMALLINT DEFAULT 0,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("bidon_id") REFERENCES "bidons" ("id")
);

-- Tabla: entradas (Entradas a bodega)
CREATE TABLE IF NOT EXISTS "entradas" (
  "id" BIGSERIAL PRIMARY KEY,
  "numero" VARCHAR(50) UNIQUE NOT NULL,
  "fecha" DATE NOT NULL,
  "cantidad" INT NOT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: salidas (Salidas de bodega)
CREATE TABLE IF NOT EXISTS "salidas" (
  "id" BIGSERIAL PRIMARY KEY,
  "numero" VARCHAR(50) UNIQUE NOT NULL,
  "fecha" DATE NOT NULL,
  "cantidad" INT NOT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: cierres (Cierres de perÃ­odo)
CREATE TABLE IF NOT EXISTS "cierres" (
  "id" BIGSERIAL PRIMARY KEY,
  "centro_id" BIGINT NULL,
  "fecha_inicio" DATE NOT NULL,
  "fecha_fin" DATE NOT NULL,
  "estado" VARCHAR(50) DEFAULT 'abierto',
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("centro_id") REFERENCES "centros" ("id")
);

-- Tabla: horarios (Horarios de trabajo)
CREATE TABLE IF NOT EXISTS "horarios" (
  "id" BIGSERIAL PRIMARY KEY,
  "usuario_id" BIGINT NOT NULL,
  "centro_id" BIGINT NULL,
  "dia_semana" VARCHAR(20) NOT NULL,
  "hora_inicio" TIME NOT NULL,
  "hora_fin" TIME NOT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("usuario_id") REFERENCES "users" ("id"),
  FOREIGN KEY ("centro_id") REFERENCES "centros" ("id")
);

-- Tabla: nota_creditos (Notas de crÃ©dito)
CREATE TABLE IF NOT EXISTS "nota_creditos" (
  "id" BIGSERIAL PRIMARY KEY,
  "numero" VARCHAR(50) UNIQUE NOT NULL,
  "factura_id" BIGINT NULL,
  "fecha" DATE NOT NULL,
  "total" DECIMAL(12,2) NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("factura_id") REFERENCES "factura_electronicas" ("id")
);

-- Tabla: nota_credito_tributarias (Detalles de notas de crÃ©dito tributarias)
CREATE TABLE IF NOT EXISTS "nota_credito_tributarias" (
  "id" BIGSERIAL PRIMARY KEY,
  "nota_credito_id" BIGINT NOT NULL,
  "motivo" TEXT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("nota_credito_id") REFERENCES "nota_creditos" ("id")
);

-- Tabla: factura_electronica_orden_compra (RelaciÃ³n factura-orden compra)
CREATE TABLE IF NOT EXISTS "factura_electronica_orden_compra" (
  "id" BIGSERIAL PRIMARY KEY,
  "factura_electronica_id" BIGINT NOT NULL,
  "orden_compra_id" BIGINT NOT NULL,
  FOREIGN KEY ("factura_electronica_id") REFERENCES "factura_electronicas" ("id"),
  FOREIGN KEY ("orden_compra_id") REFERENCES "orden_compras" ("id")
);

-- Tabla: centro_orden_compra (RelaciÃ³n centro-orden compra)
CREATE TABLE IF NOT EXISTS "centro_orden_compra" (
  "id" BIGSERIAL PRIMARY KEY,
  "centro_id" BIGINT NOT NULL,
  "orden_compra_id" BIGINT NOT NULL,
  FOREIGN KEY ("centro_id") REFERENCES "centros" ("id"),
  FOREIGN KEY ("orden_compra_id") REFERENCES "orden_compras" ("id")
);

-- Tabla: centro_user (RelaciÃ³n centro-usuario)
CREATE TABLE IF NOT EXISTS "centro_user" (
  "id" BIGSERIAL PRIMARY KEY,
  "centro_id" BIGINT NOT NULL,
  "user_id" BIGINT NOT NULL,
  FOREIGN KEY ("centro_id") REFERENCES "centros" ("id"),
  FOREIGN KEY ("user_id") REFERENCES "users" ("id")
);

-- Tabla: centro_nota_credito_tributaria (RelaciÃ³n centro-nota crÃ©dito)
CREATE TABLE IF NOT EXISTS "centro_nota_credito_tributaria" (
  "id" BIGSERIAL PRIMARY KEY,
  "centro_id" BIGINT NOT NULL,
  "nota_credito_tributaria_id" BIGINT NOT NULL,
  FOREIGN KEY ("centro_id") REFERENCES "centros" ("id"),
  FOREIGN KEY ("nota_credito_tributaria_id") REFERENCES "nota_credito_tributarias" ("id")
);

-- Tabla: compass_roles (Roles en mÃ³dulo Compass)
CREATE TABLE IF NOT EXISTS "compass_roles" (
  "id" BIGSERIAL PRIMARY KEY,
  "nombre" VARCHAR(100) NOT NULL,
  "descripcion" TEXT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: notifications (Notificaciones del sistema)
CREATE TABLE IF NOT EXISTS "notifications" (
  "id" BIGSERIAL PRIMARY KEY,
  "usuario_id" BIGINT NOT NULL,
  "titulo" VARCHAR(255) NOT NULL,
  "mensaje" TEXT NULL,
  "leida" BOOLEAN DEFAULT FALSE,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("usuario_id") REFERENCES "users" ("id")
);

-- Tabla: historials (Historial de cambios)
CREATE TABLE IF NOT EXISTS "historials" (
  "id" BIGSERIAL PRIMARY KEY,
  "usuario_id" BIGINT NOT NULL,
  "tabla" VARCHAR(100) NOT NULL,
  "accion" VARCHAR(50) NOT NULL,
  "registro_id" BIGINT NULL,
  "detalles" TEXT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("usuario_id") REFERENCES "users" ("id")
);

-- Tabla: historial_estados (Historial de cambios de estado)
CREATE TABLE IF NOT EXISTS "historial_estados" (
  "id" BIGSERIAL PRIMARY KEY,
  "tabla" VARCHAR(100) NOT NULL,
  "registro_id" BIGINT NOT NULL,
  "estado_anterior" VARCHAR(50) NULL,
  "estado_nuevo" VARCHAR(50) NOT NULL,
  "usuario_id" BIGINT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("usuario_id") REFERENCES "users" ("id")
);

-- Tabla: notificacion_estados (Notificaciones por estado)
CREATE TABLE IF NOT EXISTS "notificacion_estados" (
  "id" BIGSERIAL PRIMARY KEY,
  "estado_id" BIGINT NOT NULL,
  "tipo_notificacion" VARCHAR(100) NOT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("estado_id") REFERENCES "estados" ("id")
);

-- Tabla: rechazos (Rechazos de recepciÃ³n)
CREATE TABLE IF NOT EXISTS "rechazos" (
  "id" BIGSERIAL PRIMARY KEY,
  "entrada_id" BIGINT NOT NULL,
  "motivo" TEXT NOT NULL,
  "cantidad_rechazada" INT NOT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("entrada_id") REFERENCES "entradas" ("id")
);

-- Tabla: tipo_observacions (Tipos de observaciones)
CREATE TABLE IF NOT EXISTS "tipo_observacions" (
  "id" BIGSERIAL PRIMARY KEY,
  "nombre" VARCHAR(100) NOT NULL,
  "descripcion" TEXT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: carga_inicials (Cargas iniciales de datos)
CREATE TABLE IF NOT EXISTS "carga_inicials" (
  "id" BIGSERIAL PRIMARY KEY,
  "archivo" VARCHAR(255) NOT NULL,
  "tipo" VARCHAR(50) NOT NULL,
  "registros_procesados" INT DEFAULT 0,
  "usuario_id" BIGINT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("usuario_id") REFERENCES "users" ("id")
);

-- Tabla: holdings (Holdings/grupos empresariales)
CREATE TABLE IF NOT EXISTS "holdings" (
  "id" BIGSERIAL PRIMARY KEY,
  "nombre" VARCHAR(255) NOT NULL,
  "descripcion" TEXT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: programacion_precios (ProgramaciÃ³n de precios)
CREATE TABLE IF NOT EXISTS "programacion_precios" (
  "id" BIGSERIAL PRIMARY KEY,
  "producto_id" BIGINT NOT NULL,
  "precio" DECIMAL(10,2) NOT NULL,
  "fecha_vigencia" DATE NOT NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("producto_id") REFERENCES "productos" ("id")
);

-- Tabla: password_resets (Reinicios de contraseÃ±a)
CREATE TABLE IF NOT EXISTS "password_resets" (
  "email" VARCHAR(255) NOT NULL PRIMARY KEY,
  "token" VARCHAR(255) NOT NULL,
  "created_at" TIMESTAMP NULL
);

-- Tabla: failed_jobs (Trabajos fallidos)
CREATE TABLE IF NOT EXISTS "failed_jobs" (
  "id" BIGSERIAL PRIMARY KEY,
  "connection" TEXT NOT NULL,
  "queue" TEXT NOT NULL,
  "payload" TEXT NOT NULL,
  "exception" TEXT NOT NULL,
  "failed_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla: jobs (Trabajos en cola)
CREATE TABLE IF NOT EXISTS "jobs" (
  "id" BIGSERIAL PRIMARY KEY,
  "queue" VARCHAR(255) NOT NULL,
  "payload" TEXT NOT NULL,
  "attempts" INT DEFAULT 0,
  "reserved_at" INT NULL,
  "available_at" INT NOT NULL,
  "created_at" INT NOT NULL
);

-- Tabla: migrations (Control de migraciones)
CREATE TABLE IF NOT EXISTS "migrations" (
  "id" SERIAL PRIMARY KEY,
  "migration" VARCHAR(255) NOT NULL,
  "batch" INT NOT NULL
);

-- Tabla: folios (Control de folios)
CREATE TABLE IF NOT EXISTS "folios" (
  "id" BIGSERIAL PRIMARY KEY,
  "tipo" VARCHAR(50) NOT NULL,
  "numero_actual" INT DEFAULT 0,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL
);

-- Tabla: cliente_contacto (Contactos de clientes)
CREATE TABLE IF NOT EXISTS "cliente_contacto" (
  "id" BIGSERIAL PRIMARY KEY,
  "cliente_id" BIGINT NOT NULL,
  "nombre" VARCHAR(255) NOT NULL,
  "cargo" VARCHAR(255) NULL,
  "telefono" VARCHAR(20) NULL,
  "email" VARCHAR(255) NULL,
  "deleted_at" TIMESTAMP NULL,
  "created_at" TIMESTAMP NULL,
  "updated_at" TIMESTAMP NULL,
  FOREIGN KEY ("cliente_id") REFERENCES "clientes" ("id")
);

-- ============================================
-- FIN - PostgreSQL Database Schema
-- ============================================
