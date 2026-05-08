-- ============================================
-- ALOGIS - Seed DEMO (PostgreSQL)
-- Objetivo: poblar TODAS las tablas con pocos datos,
-- respetando claves foráneas y constraints (únicos).
-- Fecha: 2026-04-09
-- ============================================

BEGIN;

-- Limpieza completa (ordena/borra dependencias automáticamente)
TRUNCATE TABLE
  "cliente_contacto",
  "programacion_precios",
  "guia_despacho_producto",
  "guia_despachos",
  "producto_requerimiento",
  "requerimiento_user",
  "centro_nota_credito_tributaria",
  "centro_orden_compra",
  "centro_user",
  "factura_electronica_orden_compra",
  "nota_credito_tributarias",
  "nota_creditos",
  "factura_electronicas",
  "orden_compras",
  "presupuestos",
  "requerimientos",
  "rechazos",
  "ajustes",
  "bidons",
  "cierres",
  "horarios",
  "historial_estados",
  "historials",
  "notificacion_estados",
  "notifications",
  "carga_inicials",
  "entradas",
  "salidas",
  "abastecimientos",
  "bodegueros",
  "transportes",
  "estados",
  "clientes",
  "productos",
  "centros",
  "empresas",
  "holdings",
  "proveedors",
  "folios",
  "tipo_observacions",
  "compass_roles",
  "password_resets",
  "failed_jobs",
  "jobs",
  "migrations",
  "users"
RESTART IDENTITY CASCADE;

-- Timestamps fijos para que el seed sea determinista
-- (cambia si prefieres NOW())

-- ============
-- Tablas base
-- ============

INSERT INTO "users" (name, email, password, created_at, updated_at)
VALUES
  ('Admin Demo', 'admin.demo@alogi.cl', '$2y$10$demo_hash_no_login', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('Operador Demo', 'operador.demo@alogi.cl', '$2y$10$demo_hash_no_login', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('Bodeguero Demo', 'bodeguero.demo@alogi.cl', '$2y$10$demo_hash_no_login', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "holdings" (nombre, descripcion, created_at, updated_at)
VALUES
  ('Holding Demo', 'Grupo empresarial de ejemplo', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "empresas" (razon_social, rut, giro, created_at, updated_at)
VALUES
  ('Alogis SpA', '76.123.456-7', 'Logística y abastecimiento', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('Proveedor Demo Ltda', '77.987.654-3', 'Servicios', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "centros" (nombre, direccion, ciudad, comuna, created_at, updated_at)
VALUES
  ('Centro Puerto Montt', 'Av. Costanera 123', 'Puerto Montt', 'Puerto Montt', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('Centro Osorno', 'Ruta 5 Sur Km 920', 'Osorno', 'Osorno', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "proveedors" (nombre, rut, contacto, telefono, email, created_at, updated_at)
VALUES
  ('Manantial Agua', '76.111.222-3', 'Soporte', '+56 9 1111 1111', 'contacto@manantial.cl', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('AquaPro', '77.222.333-4', 'Ventas', '+56 9 2222 2222', 'ventas@aquapro.cl', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "clientes" (nombre, rut, contacto, telefono, email, direccion, created_at, updated_at)
VALUES
  ('Cliente Demo 1', '11.111.111-1', 'Ana Demo', '+56 9 3333 3333', 'ana@cliente.cl', 'Calle Demo 100', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('Cliente Demo 2', '22.222.222-2', 'Benja Demo', '+56 9 4444 4444', 'benja@cliente.cl', 'Pasaje Demo 200', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('Cliente Demo 3', '33.333.333-3', 'Carla Demo', '+56 9 5555 5555', 'carla@cliente.cl', 'Av. Demo 300', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "categorias_productos" (nombre, created_at, updated_at)
VALUES
  ('Aguas', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('Envases', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('Accesorios', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('Servicios', '2026-04-09 12:00:00', '2026-04-09 12:00:00')
ON CONFLICT (nombre) DO NOTHING;

INSERT INTO "productos" (codigo, nombre, categoria_id, descripcion, precio, stock, created_at, updated_at)
VALUES
  ('P-0001', 'Agua Purificada 20L', (SELECT id FROM "categorias_productos" WHERE nombre = 'Aguas'), 'Bidón retornable 20 litros', 3500.00, 120, '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('P-0002', 'Bidón Vacío 20L', (SELECT id FROM "categorias_productos" WHERE nombre = 'Envases'), 'Bidón retornable vacío', 1500.00, 80, '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('P-0003', 'Agua Purificada 10L', (SELECT id FROM "categorias_productos" WHERE nombre = 'Aguas'), 'Formato 10 litros', 2200.00, 60, '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('P-0004', 'Dispensador', (SELECT id FROM "categorias_productos" WHERE nombre = 'Accesorios'), 'Dispensador simple', 12990.00, 10, '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('P-0005', 'Servicio Mantención', (SELECT id FROM "categorias_productos" WHERE nombre = 'Servicios'), 'Mantención preventiva', 19990.00, 5, '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "estados" (nombre, descripcion, created_at, updated_at)
VALUES
  ('pendiente', 'Pendiente de gestión', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('aprobado', 'Aprobado', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('rechazado', 'Rechazado', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('cerrado', 'Cerrado', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "transportes" (nombre, rut, contacto, telefono, email, created_at, updated_at)
VALUES
  ('Transporte Demo', '76.333.444-5', 'Juan Chofer', '+56 9 6666 6666', 'chofer@transporte.cl', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "bodegueros" (nombre, rut, created_at, updated_at)
VALUES
  ('Bodeguero 1', '19.000.000-1', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('Bodeguero 2', '20.000.000-2', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "abastecimientos" (nombre, comuna, ciudad, created_at, updated_at)
VALUES
  ('Muelle Demo', 'Puerto Montt', 'Puerto Montt', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('Bodega Demo', 'Osorno', 'Osorno', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "compass_roles" (nombre, descripcion, created_at, updated_at)
VALUES
  ('admin', 'Administrador', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('operador', 'Operador', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "tipo_observacions" (nombre, descripcion, created_at, updated_at)
VALUES
  ('general', 'Observación general', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('calidad', 'Calidad de producto', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "folios" (tipo, numero_actual, created_at, updated_at)
VALUES
  ('requerimiento', 1000, '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('factura', 5000, '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "migrations" (migration, batch)
VALUES
  ('2026_01_01_000001_create_base_tables', 1),
  ('2026_01_01_000002_create_documents_tables', 1);

INSERT INTO "password_resets" (email, token, created_at)
VALUES
  ('admin.demo@alogi.cl', 'demo_token_1', '2026-04-09 12:00:00');

INSERT INTO "failed_jobs" (connection, queue, payload, exception, failed_at)
VALUES
  ('database', 'default', '{"demo":true}', 'Demo exception', '2026-04-09 12:00:00');

INSERT INTO "jobs" (queue, payload, attempts, reserved_at, available_at, created_at)
VALUES
  ('default', '{"type":"demo"}', 0, NULL, 1712664000, 1712664000);

-- ======================
-- Tablas con dependencias
-- ======================

-- Bidones dependen de proveedores
INSERT INTO "bidons" (proveedor_id, codigo, nombre, created_at, updated_at)
VALUES
  (1, 'B-0001', 'Bidón 20L Manantial', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  (2, 'B-0002', 'Bidón 20L AquaPro', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

-- Entradas / Salidas independientes
INSERT INTO "entradas" (numero, fecha, cantidad, created_at, updated_at)
VALUES
  ('E-0001', '2026-04-01', 10, '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('E-0002', '2026-04-02', 6, '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "salidas" (numero, fecha, cantidad, created_at, updated_at)
VALUES
  ('S-0001', '2026-04-03', 5, '2026-04-09 12:00:00', '2026-04-09 12:00:00');

-- Rechazos dependen de entradas
INSERT INTO "rechazos" (entrada_id, motivo, cantidad_rechazada, created_at, updated_at)
VALUES
  (1, 'Envase dañado', 1, '2026-04-09 12:00:00', '2026-04-09 12:00:00');

-- Cierres dependen de centros
INSERT INTO "cierres" (centro_id, fecha_inicio, fecha_fin, estado, created_at, updated_at)
VALUES
  (1, '2026-03-01', '2026-03-31', 'cerrado', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  (2, '2026-04-01', '2026-04-30', 'abierto', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

-- Horarios dependen de users y opcionalmente centros
INSERT INTO "horarios" (usuario_id, centro_id, dia_semana, hora_inicio, hora_fin, created_at, updated_at)
VALUES
  (1, 1, 'lunes', '08:00', '17:00', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  (2, 1, 'martes', '08:00', '17:00', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  (3, 2, 'miércoles', '09:00', '18:00', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

-- Notifications / historiales dependen de users
INSERT INTO "notifications" (usuario_id, titulo, mensaje, leida, created_at, updated_at)
VALUES
  (1, 'Bienvenido', 'Notificación de prueba', FALSE, '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  (2, 'Recordatorio', 'Revisar requerimientos pendientes', FALSE, '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "historials" (usuario_id, tabla, accion, registro_id, detalles, created_at, updated_at)
VALUES
  (1, 'productos', 'create', 1, 'Creación de producto demo', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "historial_estados" (tabla, registro_id, estado_anterior, estado_nuevo, usuario_id, created_at, updated_at)
VALUES
  ('requerimientos', 1, NULL, 'pendiente', 1, '2026-04-09 12:00:00', '2026-04-09 12:00:00');

-- Notificaciones por estado dependen de estados
INSERT INTO "notificacion_estados" (estado_id, tipo_notificacion, created_at, updated_at)
VALUES
  (1, 'email', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  (2, 'web', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

-- Carga inicial depende de users
INSERT INTO "carga_inicials" (archivo, tipo, registros_procesados, usuario_id, created_at, updated_at)
VALUES
  ('carga_demo.csv', 'productos', 5, 1, '2026-04-09 12:00:00', '2026-04-09 12:00:00');

-- Requerimientos / presupuestos dependen de clientes
INSERT INTO "requerimientos" (cliente_id, numero, fecha, total, estado, created_at, updated_at)
VALUES
  (1, 'R-0001', '2026-04-01', 10500.00, 'pendiente', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  (2, 'R-0002', '2026-04-02', 3500.00, 'aprobado', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "presupuestos" (cliente_id, numero, fecha, total, estado, created_at, updated_at)
VALUES
  (1, 'PRES-0001', '2026-04-01', 12990.00, 'pendiente', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

-- Ordenes / facturas dependen de proveedores y clientes
INSERT INTO "orden_compras" (proveedor_id, numero, fecha, total, estado, created_at, updated_at)
VALUES
  (1, 'OC-0001', '2026-04-01', 8000.00, 'pendiente', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  (2, 'OC-0002', '2026-04-02', 3500.00, 'aprobado', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "factura_electronicas" (numero, cliente_id, fecha, total, estado, created_at, updated_at)
VALUES
  ('F-0001', 1, '2026-04-03', 10500.00, 'pendiente', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  ('F-0002', 2, '2026-04-04', 3500.00, 'pagado', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

-- Tabla puente factura-orden de compra
INSERT INTO "factura_electronica_orden_compra" (factura_electronica_id, orden_compra_id)
VALUES
  (1, 1),
  (2, 2);

-- Notas de crédito dependen de facturas
INSERT INTO "nota_creditos" (numero, factura_id, fecha, total, created_at, updated_at)
VALUES
  ('NC-0001', 1, '2026-04-05', 1500.00, '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "nota_credito_tributarias" (nota_credito_id, motivo, created_at, updated_at)
VALUES
  (1, 'Descuento por devolución', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

-- Centro - nota crédito tributaria
INSERT INTO "centro_nota_credito_tributaria" (centro_id, nota_credito_tributaria_id)
VALUES
  (1, 1);

-- Centro - orden compra
INSERT INTO "centro_orden_compra" (centro_id, orden_compra_id)
VALUES
  (1, 1),
  (2, 2);

-- Centro - user
INSERT INTO "centro_user" (centro_id, user_id)
VALUES
  (1, 1),
  (1, 2),
  (2, 3);

-- Requerimiento - user
INSERT INTO "requerimiento_user" (requerimiento_id, user_id)
VALUES
  (1, 1),
  (2, 2);

-- Items de requerimiento
INSERT INTO "producto_requerimiento" (requerimiento_id, producto_id, cantidad, precio_unitario, subtotal)
VALUES
  (1, 1, 2, 3500.00, 7000.00),
  (1, 2, 1, 1500.00, 1500.00),
  (1, 4, 1, 12990.00, 12990.00),
  (2, 1, 1, 3500.00, 3500.00);

-- Guías de despacho (ligadas a requerimientos)
INSERT INTO "guia_despachos" (numero, requerimiento_id, fecha, estado, created_at, updated_at)
VALUES
  ('GD-0001', 1, '2026-04-06', 'pendiente', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

INSERT INTO "guia_despacho_producto" (guia_despacho_id, producto_id, cantidad)
VALUES
  (1, 1, 2),
  (1, 2, 1);

-- Programación de precios depende de productos
INSERT INTO "programacion_precios" (producto_id, precio, fecha_vigencia, created_at, updated_at)
VALUES
  (1, 3600.00, '2026-05-01', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  (2, 1600.00, '2026-05-01', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

-- Contactos de clientes dependen de clientes
INSERT INTO "cliente_contacto" (cliente_id, nombre, cargo, telefono, email, created_at, updated_at)
VALUES
  (1, 'Ana Demo', 'Compras', '+56 9 7777 7777', 'ana@cliente.cl', '2026-04-09 12:00:00', '2026-04-09 12:00:00'),
  (2, 'Benja Demo', 'Finanzas', '+56 9 8888 8888', 'benja@cliente.cl', '2026-04-09 12:00:00', '2026-04-09 12:00:00');

-- Ajustes dependen de bidons
INSERT INTO "ajustes" (bidon_id, cantidad, fecha_ingreso, suma, created_at, updated_at)
VALUES
  (1, '2', '2026-04-02', 1, '2026-04-09 12:00:00', '2026-04-09 12:00:00');

COMMIT;
