import { ref } from 'vue'

export const useMockData = () => {
  // API Base URL - Conectar a Express Backend en Node
  const API_BASE = process.env.NUXT_PUBLIC_API_URL || 'http://localhost:3001/api'

  // Estado reactivo para datos
  const requerimientos = ref([])
  const productos = ref([])
  const reportes = ref([])
  const usuarios = ref([])
  const loading = ref(false)
  const error = ref(null)

  /**
   * Función genérica para hacer fetch a la API
   */
  const fetchData = async (endpoint) => {
    try {
      loading.value = true
      const response = await fetch(`${API_BASE}/${endpoint}`)
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      
      const json = await response.json()
      return json.data || []
    } catch (err) {
      console.error(`Error fetching ${endpoint}:`, err)
      error.value = err.message
      return getDefaultData(endpoint)
    } finally {
      loading.value = false
    }
  }

  /**
   * Datos por defecto si la API no responde
   */
  const getDefaultData = (endpoint) => {
    const defaults = {
      'data/requerimientos': [
        { id: 1, numero: 'REQ-001', descripcion: 'Equipos de oficina', estado: 'Pendiente', fecha: '2024-01-15' },
        { id: 2, numero: 'REQ-002', descripcion: 'Materiales de construcción', estado: 'Aprobado', fecha: '2024-01-14' },
        { id: 3, numero: 'REQ-003', descripcion: 'Suministros informáticos', estado: 'En proceso', fecha: '2024-01-13' }
      ],
      'data/productos': [
        { id: 1, nombre: 'Laptop Dell', sku: 'DELL-001', precio: '$1,200', stock: 45 },
        { id: 2, nombre: 'Monitor LG 27"', sku: 'LG-027', precio: '$350', stock: 120 },
        { id: 3, nombre: 'Teclado Mecánico', sku: 'MECH-001', precio: '$150', stock: 89 },
        { id: 4, nombre: 'Mouse Logitech', sku: 'LOG-M01', precio: '$45', stock: 200 }
      ],
      'data/reportes': [
        { id: 1, titulo: 'Reporte de Ventas Q1', fecha: '2024-01-31', descarga: 'PDF' },
        { id: 2, titulo: 'Análisis de Inventario', fecha: '2024-01-30', descarga: 'XLSX' },
        { id: 3, titulo: 'Proyección de Demanda', fecha: '2024-01-29', descarga: 'PDF' }
      ],
      'data/usuarios': [
        { id: 1, nombre: 'Juan Pérez', email: 'juan@dispro.com', rol: 'Bodeguero', estado: 'Activo' },
        { id: 2, nombre: 'María García', email: 'maria@dispro.com', rol: 'Administrador', estado: 'Activo' },
        { id: 3, nombre: 'Carlos López', email: 'carlos@dispro.com', rol: 'Transportista', estado: 'Inactivo' }
      ]
    }
    return defaults[endpoint] || []
  }

  /**
   * Cargar datos de la API
   */
  const loadData = async () => {
    requerimientos.value = await fetchData('data/requerimientos')
    productos.value = await fetchData('data/productos')
    reportes.value = await fetchData('data/reportes')
    usuarios.value = await fetchData('data/usuarios')
  }

  // Cargar datos automáticamente
  loadData()

  return {
    requerimientos,
    productos,
    reportes,
    usuarios,
    loading,
    error,
    loadData
  }
}

