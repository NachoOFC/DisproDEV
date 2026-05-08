<template>
  <div class="min-h-screen bg-gradient-to-br from-orange-50 to-amber-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <NuxtLink to="/" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-4">
          <i class="fas fa-arrow-left mr-2"></i>
          Volver al Inicio
        </NuxtLink>
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Módulo Bodeguero</h1>
        <p class="text-slate-600">Gestiona inventario, recepción y despacho de productos</p>
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
              ? 'bg-orange-500 text-white shadow-lg'
              : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'
          ]"
        >
          <i :class="tab.icon" class="mr-2"></i>
          {{ tab.label }}
        </button>
      </div>

      <!-- Contenido -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Recepción de Productos -->
        <div v-if="activeTab === 'recepcion'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Recepción de Productos</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
              <p class="text-slate-600 text-sm mb-1">Recibos Hoy</p>
              <p class="text-3xl font-bold text-blue-600">{{ recibosHoy }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
              <p class="text-slate-600 text-sm mb-1">Productos Recibidos</p>
              <p class="text-3xl font-bold text-green-600">{{ productosRecibidos }}</p>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-500">
              <p class="text-slate-600 text-sm mb-1">Pendiente Verificar</p>
              <p class="text-3xl font-bold text-yellow-600">{{ pendienteVerificar }}</p>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-slate-100 border-b border-slate-300">
                <tr>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Documento</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Proveedor</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Productos</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Cantidad</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Estado</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="recibo in recibos" :key="recibo.id" class="border-b border-slate-200 hover:bg-slate-50">
                  <td class="px-6 py-3 text-sm font-semibold text-blue-600">{{ recibo.numero }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ recibo.proveedor }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ recibo.cantidadProductos }} items</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ recibo.cantidadTotal }} unidades</td>
                  <td class="px-6 py-3 text-sm text-slate-600">{{ formatDate(recibo.fecha) }}</td>
                  <td class="px-6 py-3 text-sm">
                    <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getStatusClass(recibo.estado)]">
                      {{ recibo.estado }}
                    </span>
                  </td>
                  <td class="px-6 py-3 text-sm">
                    <button @click="procesarRecibo(recibo.id)" class="text-green-500 hover:text-green-700 mr-3">
                      <i class="fas fa-check-circle"></i>
                    </button>
                    <button @click="verDetalles(recibo.id)" class="text-blue-500 hover:text-blue-700">
                      <i class="fas fa-eye"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Despacho de Productos -->
        <div v-if="activeTab === 'despacho'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Despacho de Productos</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
              <p class="text-slate-600 text-sm mb-1">Órdenes Hoy</p>
              <p class="text-3xl font-bold text-blue-600">{{ ordenesHoy }}</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 border-l-4 border-purple-500">
              <p class="text-slate-600 text-sm mb-1">Despachadas</p>
              <p class="text-3xl font-bold text-purple-600">{{ ordenesDespachadas }}</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-500">
              <p class="text-slate-600 text-sm mb-1">Pendiente Despachar</p>
              <p class="text-3xl font-bold text-red-600">{{ ordenesPendiente }}</p>
            </div>
          </div>

          <div class="space-y-4">
            <div
              v-for="orden in ordenesDespacho"
              :key="orden.id"
              class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <div class="flex justify-between items-start mb-3">
                <div>
                  <h3 class="font-semibold text-slate-900">{{ orden.numero }}</h3>
                  <p class="text-sm text-slate-600">Centro: {{ orden.centro }}</p>
                </div>
                <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getDespachoStatusClass(orden.estado)]">
                  {{ orden.estado }}
                </span>
              </div>
              
              <div class="grid grid-cols-3 gap-4 mb-3 text-sm">
                <div>
                  <p class="text-slate-600">Productos</p>
                  <p class="font-semibold">{{ orden.cantidadProductos }}</p>
                </div>
                <div>
                  <p class="text-slate-600">Total Unidades</p>
                  <p class="font-semibold">{{ orden.cantidadTotal }}</p>
                </div>
                <div>
                  <p class="text-slate-600">Fecha Creación</p>
                  <p class="font-semibold">{{ formatDate(orden.fechaCreacion) }}</p>
                </div>
              </div>

              <div class="flex gap-2">
                <button
                  @click="despacharOrden(orden.id)"
                  class="flex-1 bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition-colors text-sm font-semibold"
                  v-if="orden.estado === 'Pendiente'"
                >
                  <i class="fas fa-truck mr-1"></i>
                  Despachar
                </button>
                <button
                  @click="verDetallesDespacho(orden.id)"
                  class="flex-1 bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-colors text-sm"
                >
                  <i class="fas fa-eye mr-1"></i>
                  Ver
                </button>
              </div>
            </div>
          </div>

          <div v-if="ordenesDespacho.length === 0" class="text-center py-8">
            <p class="text-slate-600 text-lg">No hay órdenes pendientes de despacho</p>
          </div>
        </div>

        <!-- Requerimientos (Aprobación/Rechazo) -->
        <div v-if="activeTab === 'requerimientos'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Requerimientos por Validar</h2>

          <div v-if="loadingRequerimientos" class="py-10 text-center text-slate-600">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            Cargando requerimientos...
          </div>

          <div v-else class="space-y-4">
            <div
              v-for="req in requerimientosPendientes"
              :key="req.id"
              class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <div class="flex justify-between items-start gap-4">
                <div>
                  <div class="flex items-center gap-2 mb-1">
                    <h3 class="font-semibold text-slate-900">{{ req.numero || `REQ-${req.id}` }}</h3>
                    <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getRequerimientoStatusClass(req.estado)]">
                      {{ req.estado }}
                    </span>
                  </div>
                  <p class="text-sm text-slate-600 font-medium mb-1">{{ req.nombre || 'Sin nombre' }}</p>
                  <p class="text-sm text-slate-600">Cliente: {{ req.cliente || 'Sin asignar' }}</p>
                  <p class="text-sm text-slate-600">Fecha: {{ formatDate(req.fecha) }}</p>
                  <p class="text-sm text-slate-600">Total: ${{ req.total?.toLocaleString?.() || req.total || '0' }}</p>
                </div>

                <div class="flex gap-2 shrink-0">
                  <button
                    @click="openViewModal(req)"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm"
                    type="button"
                  >
                    <i class="fas fa-eye mr-1" aria-hidden="true"></i>
                    Ver
                  </button>
                  <button
                    @click="aprobarRequerimiento(req.id)"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm"
                    type="button"
                  >
                    <i class="fas fa-check mr-1" aria-hidden="true"></i>
                    Aprobar
                  </button>
                  <button
                    @click="rechazarRequerimiento(req.id)"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm"
                    type="button"
                  >
                    <i class="fas fa-times mr-1" aria-hidden="true"></i>
                    Rechazar
                  </button>
                </div>
              </div>
            </div>

            <div v-if="requerimientosPendientes.length === 0" class="text-center py-8">
              <p class="text-slate-600 text-lg">No hay requerimientos pendientes</p>
            </div>
          </div>

          <div v-if="requerimientosRechazados.length > 0" class="mt-10">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Requerimientos Rechazados</h3>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-slate-100 border-b border-slate-300">
                  <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Número</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Cliente</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Estado</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="req in requerimientosRechazados" :key="`rech-${req.id}`" class="border-b border-slate-200 hover:bg-slate-50">
                    <td class="px-6 py-3 text-sm font-semibold text-slate-900">{{ req.numero || `REQ-${req.id}` }}</td>
                    <td class="px-6 py-3 text-sm text-slate-900">{{ req.cliente || 'Sin asignar' }}</td>
                    <td class="px-6 py-3 text-sm text-slate-900">{{ formatDate(req.fecha) }}</td>
                    <td class="px-6 py-3 text-sm">
                      <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getRequerimientoStatusClass(req.estado)]">
                        {{ req.estado }}
                      </span>
                    </td>
                    <td class="px-6 py-3 text-sm">
                      <button
                        @click="openViewModal(req)"
                        class="px-3 py-1 rounded bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors text-xs font-semibold"
                        type="button"
                      >
                        Ver
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Inventario -->
        <div v-if="activeTab === 'inventario'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Estado de Inventario</h2>
          
          <div class="mb-6">
            <input
              v-model="busquedaProducto"
              type="text"
              placeholder="Buscar producto..."
              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500"
            />
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-slate-100 border-b border-slate-300">
                <tr>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Producto</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Código</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Stock Actual</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Stock Mínimo</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Estado</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Ubicación</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in inventario" :key="item.id" class="border-b border-slate-200 hover:bg-slate-50">
                  <td class="px-6 py-3 text-sm font-semibold text-slate-900">{{ item.nombre }}</td>
                  <td class="px-6 py-3 text-sm text-slate-600">{{ item.codigo }}</td>
                  <td class="px-6 py-3 text-sm font-semibold" :class="item.stock > item.stockMinimo ? 'text-green-600' : 'text-red-600'">
                    {{ item.stock }}
                  </td>
                  <td class="px-6 py-3 text-sm text-slate-600">{{ item.stockMinimo }}</td>
                  <td class="px-6 py-3 text-sm">
                    <span :class="['px-3 py-1 rounded-full text-xs font-semibold', item.stock > item.stockMinimo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
                      {{ item.stock > item.stockMinimo ? 'OK' : 'BAJO' }}
                    </span>
                  </td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ item.ubicacion }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Movimientos -->
        <div v-if="activeTab === 'movimientos'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Historial de Movimientos</h2>
          
          <div class="space-y-3">
            <div
              v-for="movimiento in movimientos"
              :key="movimiento.id"
              class="border-l-4 pl-4 py-2"
              :class="movimiento.tipo === 'Entrada' ? 'border-green-500' : 'border-blue-500'"
            >
              <div class="flex justify-between items-start">
                <div>
                  <p class="font-semibold text-slate-900">{{ movimiento.producto }}</p>
                  <p class="text-sm text-slate-600">{{ movimiento.referencia }}</p>
                </div>
                <div class="text-right">
                  <p class="font-semibold" :class="movimiento.tipo === 'Entrada' ? 'text-green-600' : 'text-blue-600'">
                    {{ movimiento.tipo === 'Entrada' ? '+' : '-' }} {{ movimiento.cantidad }} unidades
                  </p>
                  <p class="text-xs text-slate-500">{{ formatDate(movimiento.fecha) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal: Ver Requerimiento -->
      <div
        v-if="isViewModalOpen"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center"
        @click.self="closeViewModal"
      >
        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-lg w-full mx-4">
          <div class="flex items-start justify-between gap-4 mb-4">
            <h3 class="text-xl font-bold text-slate-900">Detalle de Requerimiento</h3>
            <button
              @click="closeViewModal"
              class="text-slate-400 hover:text-slate-600"
              type="button"
              aria-label="Cerrar"
              title="Cerrar"
            >
              <i class="fas fa-times" aria-hidden="true"></i>
            </button>
          </div>

          <div v-if="viewingReq" class="space-y-3 text-sm">
            <div class="flex justify-between gap-4">
              <span class="text-slate-500">Número</span>
              <span class="text-slate-900 font-semibold">{{ viewingReq.numero || `REQ-${viewingReq.id}` }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-slate-500">Nombre</span>
              <span class="text-slate-900 font-semibold">{{ viewingReq.nombre || 'Sin nombre' }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-slate-500">Cliente</span>
              <span class="text-slate-900 font-semibold">{{ viewingReq.cliente || 'Sin asignar' }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-slate-500">Fecha</span>
              <span class="text-slate-900 font-semibold">{{ formatDate(viewingReq.fecha) }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-slate-500">Total</span>
              <span class="text-slate-900 font-semibold">${{ viewingReq.total?.toLocaleString?.() || viewingReq.total || '0' }}</span>
            </div>
            <div class="flex justify-between gap-4 items-center">
              <span class="text-slate-500">Estado</span>
              <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getRequerimientoStatusClass(viewingReq.estado)]">
                {{ viewingReq.estado }}
              </span>
            </div>
          </div>

          <div class="mt-6 flex justify-end">
            <button
              @click="closeViewModal"
              class="bg-slate-200 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-300 transition-colors text-sm font-semibold"
              type="button"
            >
              Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const activeTab = ref('recepcion')

const tabs = [
  { id: 'recepcion', label: 'Recepción', icon: 'fas fa-inbox' },
  { id: 'despacho', label: 'Despacho', icon: 'fas fa-truck' },
  { id: 'requerimientos', label: 'Requerimientos', icon: 'fas fa-clipboard-check' },
  { id: 'inventario', label: 'Inventario', icon: 'fas fa-warehouse' },
  { id: 'movimientos', label: 'Movimientos', icon: 'fas fa-exchange-alt' }
]

// Requerimientos (pedidos) - aprobaciones del bodeguero
const requerimientos = ref([])
const loadingRequerimientos = ref(false)

const isViewModalOpen = ref(false)
const viewingReq = ref(null)

const normalizeEstado = (estado) => String(estado || '').toLowerCase().trim()
const isPendiente = (estado) => {
  const s = normalizeEstado(estado)
  return s === 'pendiente' || s === 'reenviada'
}

const requerimientosPendientes = computed(() => (requerimientos.value || []).filter((r) => isPendiente(r.estado)))
const requerimientosRechazados = computed(() => (requerimientos.value || []).filter((r) => normalizeEstado(r.estado) === 'rechazada' || normalizeEstado(r.estado) === 'rechazado'))

const getRequerimientoStatusClass = (estado) => {
  const s = normalizeEstado(estado)
  if (s === 'pendiente') return 'bg-yellow-100 text-yellow-800'
  if (s === 'aprobada' || s === 'aprobado') return 'bg-green-100 text-green-800'
  if (s === 'rechazada' || s === 'rechazado') return 'bg-red-100 text-red-800'
  if (s === 'reenviada') return 'bg-purple-100 text-purple-800'
  return 'bg-slate-100 text-slate-800'
}

const openViewModal = (req) => {
  viewingReq.value = req || null
  isViewModalOpen.value = true
}

const closeViewModal = () => {
  isViewModalOpen.value = false
  viewingReq.value = null
}

const loadRequerimientos = async () => {
  try {
    loadingRequerimientos.value = true
    const response = await fetch('/api/requerimientos')
    const data = await response.json()
    requerimientos.value = data.data || []
  } catch (error) {
    console.error('Error cargando requerimientos:', error)
    useToast().error('No se pudieron cargar los requerimientos')
  } finally {
    loadingRequerimientos.value = false
  }
}

const updateEstadoRequerimiento = async (id, estado) => {
  const toast = useToast()
  try {
    if (id === undefined || id === null || String(id).trim() === '' || String(id) === 'undefined') {
      throw new Error('No se encontró el id del requerimiento')
    }

    const response = await fetch(`/api/requerimientos/${id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ estado })
    })

    const data = await response.json()
    if (!response.ok || data?.success === false || data?.statusCode) {
      const msg = data?.errors?.[0] || data?.message || 'No se pudo actualizar el estado'
      throw new Error(msg)
    }

    await loadRequerimientos()
    toast.success(`Requerimiento ${estado}`)
  } catch (error) {
    console.error('Error actualizando requerimiento:', error)
    toast.error(`Error: ${error.message || error}`)
  }
}

const aprobarRequerimiento = (id) => updateEstadoRequerimiento(id, 'aprobada')
const rechazarRequerimiento = (id) => updateEstadoRequerimiento(id, 'rechazada')

onMounted(() => {
  loadRequerimientos()
})

const busquedaProducto = ref('')

// Mock data - Recepción
const recibos = ref([
  { id: 1, numero: 'REC-2024-001', proveedor: 'Proveedor A', cantidadProductos: 3, cantidadTotal: 150, fecha: '2024-10-26', estado: 'Pendiente' },
  { id: 2, numero: 'REC-2024-000', proveedor: 'Proveedor B', cantidadProductos: 5, cantidadTotal: 250, fecha: '2024-10-25', estado: 'Verificado' },
])

const recibosHoy = computed(() => recibos.value.filter(r => r.estado === 'Pendiente').length)
const productosRecibidos = computed(() => recibos.value.reduce((sum, r) => sum + r.cantidadTotal, 0))
const pendienteVerificar = computed(() => recibos.value.filter(r => r.estado === 'Pendiente').length)

// Mock data - Despacho
const ordenesDespacho = ref([
  { id: 1, numero: 'ORD-2024-001', centro: 'Centro Santiago', cantidadProductos: 4, cantidadTotal: 120, fechaCreacion: '2024-10-26', estado: 'Pendiente' },
  { id: 2, numero: 'ORD-2024-000', centro: 'Centro Valparaíso', cantidadProductos: 2, cantidadTotal: 80, fechaCreacion: '2024-10-25', estado: 'Despachado' },
])

const ordenesHoy = computed(() => ordenesDespacho.value.length)
const ordenesDespachadas = computed(() => ordenesDespacho.value.filter(o => o.estado === 'Despachado').length)
const ordenesPendiente = computed(() => ordenesDespacho.value.filter(o => o.estado === 'Pendiente').length)

// Mock data - Inventario
const inventario = ref([
  { id: 1, nombre: 'Producto A', codigo: 'PA-001', stock: 150, stockMinimo: 50, ubicacion: 'Estante A1' },
  { id: 2, nombre: 'Producto B', codigo: 'PB-002', stock: 30, stockMinimo: 100, ubicacion: 'Estante B2' },
  { id: 3, nombre: 'Producto C', codigo: 'PC-003', stock: 200, stockMinimo: 50, ubicacion: 'Estante C1' },
])

// Mock data - Movimientos
const movimientos = ref([
  { id: 1, tipo: 'Entrada', producto: 'Producto A', cantidad: 100, referencia: 'REC-2024-001', fecha: '2024-10-26' },
  { id: 2, tipo: 'Salida', producto: 'Producto B', cantidad: 20, referencia: 'ORD-2024-001', fecha: '2024-10-26' },
  { id: 3, tipo: 'Entrada', producto: 'Producto C', cantidad: 150, referencia: 'REC-2024-000', fecha: '2024-10-25' },
])

const procesarRecibo = (id) => {
  const recibo = recibos.value.find(r => r.id === id)
  if (recibo) {
    recibo.estado = 'Verificado'
    alert('Recibo procesado exitosamente')
  }
}

const despacharOrden = (id) => {
  const orden = ordenesDespacho.value.find(o => o.id === id)
  if (orden) {
    orden.estado = 'Despachado'
    alert('Orden despachada exitosamente')
  }
}

const verDetalles = (id) => {
  alert(`Ver detalles del recibo ${id}`)
}

const verDetallesDespacho = (id) => {
  alert(`Ver detalles de la orden ${id}`)
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })
}

const getStatusClass = (estado) => {
  const classes = {
    'Pendiente': 'bg-yellow-100 text-yellow-800',
    'Verificado': 'bg-green-100 text-green-800',
    'Procesado': 'bg-blue-100 text-blue-800'
  }
  return classes[estado] || 'bg-slate-100 text-slate-800'
}

const getDespachoStatusClass = (estado) => {
  const classes = {
    'Pendiente': 'bg-yellow-100 text-yellow-800',
    'Despachado': 'bg-green-100 text-green-800',
    'EnTransito': 'bg-blue-100 text-blue-800'
  }
  return classes[estado] || 'bg-slate-100 text-slate-800'
}
</script>
