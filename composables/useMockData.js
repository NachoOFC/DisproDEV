import { ref } from 'vue'

export const useMockData = () => {
  // Datos simulados de requerimientos
  const requerimientos = ref([
    { id: 1, numero: 'REQ-001', descripcion: 'Equipos de oficina', estado: 'Pendiente', fecha: '2024-01-15' },
    { id: 2, numero: 'REQ-002', descripcion: 'Materiales de construcción', estado: 'Aprobado', fecha: '2024-01-14' },
    { id: 3, numero: 'REQ-003', descripcion: 'Suministros informáticos', estado: 'En proceso', fecha: '2024-01-13' }
  ])

  // Datos simulados de productos
  const productos = ref([
    { id: 1, nombre: 'Laptop Dell', sku: 'DELL-001', precio: '$1,200', stock: 45 },
    { id: 2, nombre: 'Monitor LG 27"', sku: 'LG-027', precio: '$350', stock: 120 },
    { id: 3, nombre: 'Teclado Mecánico', sku: 'MECH-001', precio: '$150', stock: 89 },
    { id: 4, nombre: 'Mouse Logitech', sku: 'LOG-M01', precio: '$45', stock: 200 }
  ])

  // Datos simulados de reportes
  const reportes = ref([
    { id: 1, titulo: 'Reporte de Ventas Q1', fecha: '2024-01-31', descarga: 'PDF' },
    { id: 2, titulo: 'Análisis de Inventario', fecha: '2024-01-30', descarga: 'XLSX' },
    { id: 3, titulo: 'Proyección de Demanda', fecha: '2024-01-29', descarga: 'PDF' }
  ])

  // Datos simulados de usuarios
  const usuarios = ref([
    { id: 1, nombre: 'Juan Pérez', email: 'juan@dispro.com', rol: 'Bodeguero', estado: 'Activo' },
    { id: 2, nombre: 'María García', email: 'maria@dispro.com', rol: 'Administrador', estado: 'Activo' },
    { id: 3, nombre: 'Carlos López', email: 'carlos@dispro.com', rol: 'Transportista', estado: 'Inactivo' }
  ])

  return {
    requerimientos,
    productos,
    reportes,
    usuarios
  }
}
