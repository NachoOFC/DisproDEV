<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Reportes</h1>
        <p class="text-slate-600">Consulta y descarga reportes de tu actividad</p>
      </div>

      <!-- Tabs -->
      <div class="mb-6 flex gap-4 flex-wrap">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="[
            'px-6 py-3 rounded-lg font-semibold transition-all',
            activeTab === tab.id
              ? 'bg-blue-500 text-white shadow-lg'
              : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'
          ]"
        >
          <i :class="tab.icon" class="mr-2"></i>
          {{ tab.label }}
        </button>
      </div>

      <!-- Contenido -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Reporte de Órdenes -->
        <div v-if="activeTab === 'ordenes'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Reporte de Órdenes de Pedido</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
              <p class="text-slate-600 text-sm mb-1">Total de Órdenes</p>
              <p class="text-3xl font-bold text-blue-600">{{ totalOrdenes }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
              <p class="text-slate-600 text-sm mb-1">Aprobadas</p>
              <p class="text-3xl font-bold text-green-600">{{ ordenesAprobadas }}</p>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-500">
              <p class="text-slate-600 text-sm mb-1">Pendientes</p>
              <p class="text-3xl font-bold text-yellow-600">{{ ordenesPendientes }}</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-500">
              <p class="text-slate-600 text-sm mb-1">Rechazadas</p>
              <p class="text-3xl font-bold text-red-600">{{ ordenesRechazadas }}</p>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-slate-100 border-b border-slate-300">
                <tr>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Orden</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha Creación</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Productos</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Estado</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Última Actualización</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="orden in reporteOrdenes" :key="orden.id" class="border-b border-slate-200 hover:bg-slate-50">
                  <td class="px-6 py-3 text-sm font-semibold text-blue-600">{{ orden.numero }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ formatDate(orden.fechaCreacion) }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ orden.cantidadProductos }} artículos</td>
                  <td class="px-6 py-3 text-sm">
                    <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getStatusClass(orden.estado)]">
                      {{ orden.estado }}
                    </span>
                  </td>
                  <td class="px-6 py-3 text-sm text-slate-600">{{ formatDate(orden.ultimaActualizacion) }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-6 flex gap-3">
            <button @click="exportarExcel('ordenes')" class="flex-1 bg-green-500 text-white py-2 rounded-lg font-semibold hover:bg-green-600 transition-colors">
              <i class="fas fa-file-excel mr-2"></i>
              Exportar a Excel
            </button>
            <button @click="exportarPDF('ordenes')" class="flex-1 bg-red-500 text-white py-2 rounded-lg font-semibold hover:bg-red-600 transition-colors">
              <i class="fas fa-file-pdf mr-2"></i>
              Exportar a PDF
            </button>
          </div>
        </div>

        <!-- Reporte de Facturación -->
        <div v-if="activeTab === 'facturacion'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Reporte de Facturación</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
              <p class="text-slate-600 text-sm mb-1">Total Facturado</p>
              <p class="text-3xl font-bold text-blue-600">${{ formatCurrency(totalFacturado) }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
              <p class="text-slate-600 text-sm mb-1">Total Pagado</p>
              <p class="text-3xl font-bold text-green-600">${{ formatCurrency(totalPagado) }}</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-500">
              <p class="text-slate-600 text-sm mb-1">Deuda Pendiente</p>
              <p class="text-3xl font-bold text-red-600">${{ formatCurrency(deudaPendiente) }}</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 border-l-4 border-purple-500">
              <p class="text-slate-600 text-sm mb-1">% Cobranza</p>
              <p class="text-3xl font-bold text-purple-600">{{ porcentajeCobranza }}%</p>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-slate-100 border-b border-slate-300">
                <tr>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Factura</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Total</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Pagado</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Saldo</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Estado</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="factura in reporteFacturas" :key="factura.id" class="border-b border-slate-200 hover:bg-slate-50">
                  <td class="px-6 py-3 text-sm font-semibold text-blue-600">#{{ factura.numero }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ formatDate(factura.fecha) }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">${{ formatCurrency(factura.total) }}</td>
                  <td class="px-6 py-3 text-sm text-green-600 font-semibold">${{ formatCurrency(factura.pagado) }}</td>
                  <td class="px-6 py-3 text-sm text-red-600 font-semibold">${{ formatCurrency(factura.saldo) }}</td>
                  <td class="px-6 py-3 text-sm">
                    <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getFaturacionStatusClass(factura.estado)]">
                      {{ factura.estado }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-6 flex gap-3">
            <button @click="exportarExcel('facturacion')" class="flex-1 bg-green-500 text-white py-2 rounded-lg font-semibold hover:bg-green-600 transition-colors">
              <i class="fas fa-file-excel mr-2"></i>
              Exportar a Excel
            </button>
            <button @click="exportarPDF('facturacion')" class="flex-1 bg-red-500 text-white py-2 rounded-lg font-semibold hover:bg-red-600 transition-colors">
              <i class="fas fa-file-pdf mr-2"></i>
              Exportar a PDF
            </button>
          </div>
        </div>

        <!-- Reporte de Entregas -->
        <div v-if="activeTab === 'entregas'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Reporte de Entregas</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
              <p class="text-slate-600 text-sm mb-1">Total Entregas</p>
              <p class="text-3xl font-bold text-blue-600">{{ totalEntregas }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
              <p class="text-slate-600 text-sm mb-1">Completadas</p>
              <p class="text-3xl font-bold text-green-600">{{ entregasCompletadas }}</p>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-500">
              <p class="text-slate-600 text-sm mb-1">En Tránsito</p>
              <p class="text-3xl font-bold text-yellow-600">{{ entregasEnTransito }}</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-500">
              <p class="text-slate-600 text-sm mb-1">Retrasadas</p>
              <p class="text-3xl font-bold text-red-600">{{ entregasRetrasadas }}</p>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-slate-100 border-b border-slate-300">
                <tr>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Pedido</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha Despacho</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha Estimada</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha Entrega</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Transporte</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Estado</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="entrega in reporteEntregas" :key="entrega.id" class="border-b border-slate-200 hover:bg-slate-50">
                  <td class="px-6 py-3 text-sm font-semibold text-blue-600">{{ entrega.pedido }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ formatDate(entrega.fechaDespacho) }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ formatDate(entrega.fechaEstimada) }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ entrega.fechaEntrega ? formatDate(entrega.fechaEntrega) : '-' }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ entrega.transporte }}</td>
                  <td class="px-6 py-3 text-sm">
                    <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getEntregaStatusClass(entrega.estado)]">
                      {{ entrega.estado }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-6 flex gap-3">
            <button @click="exportarExcel('entregas')" class="flex-1 bg-green-500 text-white py-2 rounded-lg font-semibold hover:bg-green-600 transition-colors">
              <i class="fas fa-file-excel mr-2"></i>
              Exportar a Excel
            </button>
            <button @click="exportarPDF('entregas')" class="flex-1 bg-red-500 text-white py-2 rounded-lg font-semibold hover:bg-red-600 transition-colors">
              <i class="fas fa-file-pdf mr-2"></i>
              Exportar a PDF
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const activeTab = ref('ordenes')

const tabs = [
  { id: 'ordenes', label: 'Órdenes', icon: 'fas fa-clipboard-list' },
  { id: 'facturacion', label: 'Facturación', icon: 'fas fa-file-invoice-dollar' },
  { id: 'entregas', label: 'Entregas', icon: 'fas fa-truck' }
]

const reporteOrdenes = ref([])
const reporteFacturas = ref([])
const reporteEntregas = ref([])

const normalizeEstado = (estado) => String(estado || '').toLowerCase()

const loadReporteOrdenes = async () => {
  try {
    const response = await fetch('/api/reportes/ordenes')
    const data = await response.json()
    reporteOrdenes.value = data?.data || []
  } catch (error) {
    console.error('Error cargando reporte de órdenes:', error)
    reporteOrdenes.value = []
  }
}

const loadReporteFacturacion = async () => {
  try {
    const response = await fetch('/api/reportes/facturacion')
    const data = await response.json()
    reporteFacturas.value = data?.data || []
  } catch (error) {
    console.error('Error cargando reporte de facturación:', error)
    reporteFacturas.value = []
  }
}

const loadReporteEntregas = async () => {
  try {
    const response = await fetch('/api/reportes/entregas')
    const data = await response.json()
    reporteEntregas.value = data?.data || []
  } catch (error) {
    console.error('Error cargando reporte de entregas:', error)
    reporteEntregas.value = []
  }
}

onMounted(() => {
  loadReporteOrdenes()
  loadReporteFacturacion()
  loadReporteEntregas()
})

const totalOrdenes = computed(() => reporteOrdenes.value.length)
const ordenesAprobadas = computed(() => reporteOrdenes.value.filter(o => normalizeEstado(o.estado) === 'aprobada').length)
const ordenesPendientes = computed(() => reporteOrdenes.value.filter(o => normalizeEstado(o.estado) === 'pendiente').length)
const ordenesRechazadas = computed(() => reporteOrdenes.value.filter(o => normalizeEstado(o.estado) === 'rechazada').length)

const totalFacturado = computed(() => reporteFacturas.value.reduce((sum, f) => sum + f.total, 0))
const totalPagado = computed(() => reporteFacturas.value.reduce((sum, f) => sum + f.pagado, 0))
const deudaPendiente = computed(() => reporteFacturas.value.reduce((sum, f) => sum + f.saldo, 0))
const porcentajeCobranza = computed(() => {
  if (totalFacturado.value === 0) return 0
  return Math.round((totalPagado.value / totalFacturado.value) * 100)
})

const totalEntregas = computed(() => reporteEntregas.value.length)
const entregasCompletadas = computed(() => reporteEntregas.value.filter(e => normalizeEstado(e.estado) === 'entregada').length)
const entregasEnTransito = computed(() => reporteEntregas.value.filter(e => normalizeEstado(e.estado) === 'en tránsito' || normalizeEstado(e.estado) === 'en transito').length)
const entregasRetrasadas = computed(() => reporteEntregas.value.filter(e => normalizeEstado(e.estado) === 'retrasada').length)

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })
}

const formatCurrency = (value) => {
  return new Intl.NumberFormat('es-ES', { minimumFractionDigits: 0 }).format(value)
}

const getStatusClass = (estado) => {
  const s = normalizeEstado(estado)
  const classes = {
    'aprobada': 'bg-green-100 text-green-800',
    'pendiente': 'bg-yellow-100 text-yellow-800',
    'entregada': 'bg-blue-100 text-blue-800',
    'rechazada': 'bg-red-100 text-red-800'
  }
  return classes[s] || 'bg-slate-100 text-slate-800'
}

const getFaturacionStatusClass = (estado) => {
  const s = normalizeEstado(estado)
  const classes = {
    'pagado': 'bg-green-100 text-green-800',
    'parcial': 'bg-yellow-100 text-yellow-800',
    'pendiente': 'bg-yellow-100 text-yellow-800',
    'vencido': 'bg-red-100 text-red-800'
  }
  return classes[s] || 'bg-slate-100 text-slate-800'
}

const getEntregaStatusClass = (estado) => {
  const s = normalizeEstado(estado)
  const classes = {
    'entregada': 'bg-green-100 text-green-800',
    'en tránsito': 'bg-blue-100 text-blue-800',
    'en transito': 'bg-blue-100 text-blue-800',
    'pendiente': 'bg-yellow-100 text-yellow-800',
    'retrasada': 'bg-red-100 text-red-800'
  }
  return classes[s] || 'bg-slate-100 text-slate-800'
}

const exportarExcel = (tipo) => {
  alert(`Exportando reporte de ${tipo} a Excel...`)
}

const exportarPDF = (tipo) => {
  alert(`Exportando reporte de ${tipo} a PDF...`)
}
</script>
