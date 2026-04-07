-- ============================================
-- ALOGIS - Datos de Prueba (Seed Data)
-- Inserta datos iniciales para testing
-- ============================================

-- 👥 Usuarios de prueba
INSERT INTO "users" (name, email, password, created_at, updated_at) VALUES
('Admin Sistema', 'admin@dispro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
('Juan Pérez', 'juan@dispro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
('María García', 'maria@dispro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW())
ON CONFLICT (email) DO NOTHING;

-- 📦 Productos de prueba
INSERT INTO "productos" (codigo, nombre, descripcion, precio, stock, created_at, updated_at) VALUES
('PROD-001', 'Agua Filtrada 5L', 'Agua filtrada purificada en bidón de 5 litros', 2500.00, 150, NOW(), NOW()),
('PROD-002', 'Agua Destilada 5L', 'Agua destilada para uso industrial', 3000.00, 89, NOW(), NOW()),
('PROD-003', 'Agua Purificada 10L', 'Agua purificada en bidón de 10 litros', 5000.00, 45, NOW(), NOW()),
('PROD-004', 'Agua Mineral 5L', 'Agua mineral natural', 3500.00, 120, NOW(), NOW()),
('PROD-005', 'Agua Alcalina 5L', 'Agua alcalina ionizada', 4000.00, 75, NOW(), NOW())
ON CONFLICT (codigo) DO NOTHING;

-- 🏢 Clientes de prueba
INSERT INTO "clientes" (nombre, rut, contacto, telefono, email, direccion, created_at, updated_at) VALUES
('Centro Santiago', '76.123.456-7', 'Pedro López', '+56912345678', 'contacto@centrosantiago.cl', 'Av. Providencia 1234, Santiago', NOW(), NOW()),
('Empresa A', '77.234.567-8', 'Ana Martínez', '+56987654321', 'ventas@empresaa.cl', 'Calle Principal 567, Valparaíso', NOW(), NOW()),
('Distribuidora B', '78.345.678-9', 'Carlos Rojas', '+56923456789', 'info@distrib.cl', 'Los Aromos 890, Concepción', NOW(), NOW())
ON CONFLICT (rut) DO NOTHING;

-- 🏭 Empresas
INSERT INTO "empresas" (razon_social, rut, giro, created_at, updated_at) VALUES
('DisproDEV S.A.', '76.987.654-3', 'Distribución de agua purificada', NOW(), NOW()),
('Alogis Chile Ltda.', '77.876.543-2', 'Logística y abastecimiento', NOW(), NOW())
ON CONFLICT DO NOTHING;

-- 📍 Centros de distribución
INSERT INTO "centros" (nombre, direccion, ciudad, comuna, created_at, updated_at) VALUES
('Centro Maipú', 'Av. Pajaritos 5678', 'Santiago', 'Maipú', NOW(), NOW()),
('Centro Ñuñoa', 'Av. Irarrázaval 3456', 'Santiago', 'Ñuñoa', NOW(), NOW()),
('Centro Valparaíso', 'Av. España 1234', 'Valparaíso', 'Valparaíso', NOW(), NOW())
ON CONFLICT DO NOTHING;

-- 🚚 Proveedores
INSERT INTO "proveedors" (nombre, rut, contacto, telefono, email, created_at, updated_at) VALUES
('Proveedor Principal', '79.123.456-0', 'Roberto Silva', '+56934567890', 'ventas@proveedor.cl', NOW(), NOW()),
('Suministros del Sur', '80.234.567-1', 'Laura Fernández', '+56945678901', 'contacto@suministros.cl', NOW(), NOW()),
('Distribuidora Norte', '81.345.678-2', 'Carlos Ramírez', '+56956789012', 'info@distinorte.cl', NOW(), NOW())
ON CONFLICT DO NOTHING;

-- 📊 Estados
INSERT INTO "estados" (nombre, descripcion, created_at, updated_at) VALUES
('Pendiente', 'Estado inicial de documento', NOW(), NOW()),
('En Proceso', 'Documento en procesamiento', NOW(), NOW()),
('Completado', 'Documento finalizado', NOW(), NOW()),
('Cancelado', 'Documento cancelado', NOW(), NOW())
ON CONFLICT DO NOTHING;

-- 📋 Requerimientos (Órdenes de Pedido)
INSERT INTO "requerimientos" (cliente_id, numero, fecha, total, estado, created_at, updated_at) VALUES
(1, 'REQ-2024-001', '2024-10-14', 125000, 'aprobada', '2024-10-14 10:00:00', '2024-10-14 10:00:00'),
(2, 'REQ-2024-002', '2024-09-19', 87500, 'entregada', '2024-09-19 14:30:00', '2024-09-20 16:00:00'),
(3, 'REQ-2024-003', '2024-08-09', 156000, 'pendiente', '2024-08-09 09:15:00', '2024-08-09 09:15:00'),
(1, 'REQ-2025-001', '2025-01-15', 98000, 'pendiente', NOW(), NOW()),
(2, 'REQ-2025-002', '2025-01-10', 210000, 'aprobada', NOW(), NOW()),
-- Órdenes por validar (para la pestaña "Por Validar")
(1, 'REQ-2025-003', '2024-10-25', 45000, 'Por Validar', NOW(), NOW()),
(3, 'REQ-2025-004', '2024-10-26', 67500, 'Por Validar', NOW(), NOW()),
-- Órdenes rechazadas (para la pestaña "Rechazadas")
(2, 'REQ-2024-099', '2024-07-15', 89000, 'Rechazado', '2024-07-15 10:00:00', '2024-07-15 14:00:00')
ON CONFLICT DO NOTHING;

-- 📦 Órdenes de Compra a Proveedores
INSERT INTO "orden_compras" (proveedor_id, numero, fecha, total, estado, created_at, updated_at) VALUES
(1, 'OC-2024-001', '2024-10-20', 1200000, 'Entregada', '2024-10-20 10:00:00', '2024-10-27 14:00:00'),
(2, 'OC-2024-002', '2024-10-22', 850000, 'En Tránsito', '2024-10-22 09:00:00', NOW()),
(1, 'OC-2024-003', '2024-11-01', 950000, 'Pendiente', NOW(), NOW()),
(3, 'OC-2024-004', '2024-11-05', 1500000, 'Pendiente', NOW(), NOW())
ON CONFLICT DO NOTHING;

-- 🧾 Facturas Electrónicas
INSERT INTO "factura_electronicas" (numero, cliente_id, fecha, total, estado, created_at, updated_at) VALUES
('F-2024-001', 1, '2024-10-25', 500000, 'Pagada', '2024-10-25 10:00:00', '2024-11-01 14:00:00'),
('F-2024-002', 2, '2024-10-20', 750000, 'Pagada', '2024-10-20 11:00:00', '2024-10-28 15:00:00'),
('F-2024-003', 3, '2024-11-01', 320000, 'Pendiente', NOW(), NOW()),
('F-2024-004', 1, '2024-11-10', 890000, 'Pendiente', NOW(), NOW())
ON CONFLICT DO NOTHING;

-- 💳 Notas de Crédito
INSERT INTO "nota_creditos" (numero, factura_id, fecha, total, created_at, updated_at) VALUES
('NC-2024-001', 1, '2024-10-26', 50000, NOW(), NOW()),
('NC-2024-002', 2, '2024-10-22', 25000, NOW(), NOW())
ON CONFLICT DO NOTHING;

-- 🚚 Guías de Despacho
INSERT INTO "guia_despachos" (numero, requerimiento_id, fecha, estado, created_at, updated_at) VALUES
('GD-2024-001', 1, '2024-10-25', 'Entregada', '2024-10-25 09:00:00', '2024-10-27 10:45:00'),
('GD-2024-002', 2, '2024-10-26', 'En Tránsito', '2024-10-26 08:00:00', NOW()),
('GD-2024-003', 5, '2024-11-12', 'Pendiente', NOW(), NOW())
ON CONFLICT DO NOTHING;

-- 🔧 Ajustes de Inventario
INSERT INTO "ajustes" (bidon_id, cantidad, fecha_ingreso, suma, created_at, updated_at) VALUES
(1, '10', '2024-10-20', 1, NOW(), NOW()),
(1, '5', '2024-10-21', 0, NOW(), NOW()),
(1, '15', '2024-10-22', 1, NOW(), NOW())
ON CONFLICT DO NOTHING;

-- 📊 Cierres de Período
INSERT INTO "cierres" (centro_id, fecha_inicio, fecha_fin, estado, created_at, updated_at) VALUES
(1, '2024-09-01', '2024-09-30', 'cerrado', '2024-09-30 23:59:59', '2024-09-30 23:59:59'),
(2, '2024-09-01', '2024-09-30', 'cerrado', '2024-09-30 23:59:59', '2024-09-30 23:59:59'),
(1, '2024-10-01', '2024-10-31', 'abierto', NOW(), NOW())
ON CONFLICT DO NOTHING;

-- 📥 Entradas a Bodega
INSERT INTO "entradas" (numero, fecha, cantidad, created_at, updated_at) VALUES
('ENT-2024-001', '2024-10-26', 150, NOW(), NOW()),
('ENT-2024-002', '2024-10-25', 200, NOW(), NOW())
ON CONFLICT DO NOTHING;

-- 📤 Salidas de Bodega
INSERT INTO "salidas" (numero, fecha, cantidad, created_at, updated_at) VALUES
('SAL-2024-001', '2024-10-26', 80, NOW(), NOW()),
('SAL-2024-002', '2024-10-25', 120, NOW(), NOW())
ON CONFLICT DO NOTHING;

-- 💰 Presupuestos
INSERT INTO "presupuestos" (cliente_id, numero, fecha, total, estado, created_at, updated_at) VALUES
(1, 'PRES-2024-001', '2024-10-20', 500000, 'aprobado', NOW(), NOW()),
(2, 'PRES-2024-002', '2024-10-22', 750000, 'pendiente', NOW(), NOW())
ON CONFLICT DO NOTHING;

-- 📋 Cargas Iniciales
INSERT INTO "carga_inicials" (archivo, tipo, registros_procesados, usuario_id, created_at, updated_at) VALUES
('productos_octubre.csv', 'Productos', 150, 1, '2024-10-20 09:30:00', '2024-10-20 09:30:00'),
('proveedores_octubre.csv', 'Proveedores', 43, 1, '2024-10-18 14:15:00', '2024-10-18 14:15:00')
ON CONFLICT DO NOTHING;

-- ============================================
-- FIN - Datos de Prueba
-- ============================================

SELECT '✅ Datos de prueba insertados correctamente' as mensaje;
