<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <NuxtLink to="/" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-4">
          <i class="fas fa-arrow-left mr-2"></i>
          Volver al Inicio
        </NuxtLink>
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Guías de Despacho</h1>
        <p class="text-slate-600">Gestiona el envío de órdenes a clientes</p>
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
        <!-- Crear Guía -->
        <div v-if="activeTab === 'crear'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Nueva Guía de Despacho</h2>
          
          <form @submit.prevent="crearGuia" class="max-w-2xl space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Orden de Compra</label>
                <select
                  v-model="formularioGuia.ordenCompra"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                >
                  <option value="">-- Selecciona orden --</option>
                  <option value="OC-2024-001">OC-2024-001</option>
                  <option value="OC-2024-002">OC-2024-002</option>
                  <option value="OC-2024-003">OC-2024-003</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Cliente</label>
                <input
                  v-model="formularioGuia.cliente"
                  type="text"
                  placeholder="Centro de Distribución"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Transportista</label>
                <select
                  v-model="formularioGuia.transportista"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                >
                  <option value="">-- Selecciona transportista --</option>
                  <option value="TransLogis">TransLogis</option>
                  <option value="Transportes Sur">Transportes Sur</option>
                  <option value="Despachos Rápidos">Despachos Rápidos</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Patente Vehículo</label>
                <input
                  v-model="formularioGuia.patente"
                  type="text"
                  placeholder="XXXX-AB"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Fecha Despacho</label>
                <input
                  v-model="formularioGuia.fechaDespacho"
                  type="date"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Fecha Entrega Estimada</label>
                <input
                  v-model="formularioGuia.fechaEntregaEstimada"
                  type="date"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Cantidad Bidones</label>
                <input
                  v-model.number="formularioGuia.cantidadBidones"
                  type="number"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Observaciones</label>
              <textarea
                v-model="formularioGuia.observaciones"
                rows="3"
                placeholder="Instrucciones especiales de entrega..."
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>

            <div class="flex gap-3 pt-6 border-t border-slate-200">
              <button
                type="button"
                @click="activeTab = 'historial'"
                class="flex-1 bg-slate-200 text-slate-700 py-2 rounded-lg font-semibold hover:bg-slate-300"
              >
                Cancelar
              </button>
              <button
                type="submit"
                class="flex-1 bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600"
              >
                <i class="fas fa-paper-plane mr-2"></i>
                Crear Guía
              </button>
            </div>
          </form>
        </div>

        <!-- Historial de Guías -->
        <div v-if="activeTab === 'historial'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Historial de Guías</h2>
          
          <div class="mb-4 flex gap-2">
            <input
              v-model="filtroGuia"
              type="text"
              placeholder="Buscar por cliente o guía..."
              class="flex-1 px-4 py-2 border border-slate-300 rounded-lg"
            />
          </div>

          <div class="space-y-4">
            <div
              v-for="guia in guiasFiltradas"
              :key="guia.id"
              class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <div class="flex justify-between items-start mb-3">
                <div>
                  <h3 class="font-semibold text-slate-900 text-lg">Guía #{{ guia.numero }}</h3>
                  <p class="text-sm text-slate-600">Orden: {{ guia.ordenCompra }}</p>
                </div>
                <span :class="['px-3 py-1 rounded-full text-sm font-semibold', getEstadoClass(guia.estado)]">
                  {{ guia.estado }}
                </span>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                <div>
                  <p class="text-xs text-slate-600">Cliente</p>
                  <p class="font-semibold text-slate-900">{{ guia.cliente }}</p>
                </div>
                <div>
                  <p class="text-xs text-slate-600">Transportista</p>
                  <p class="font-semibold text-slate-900">{{ guia.transportista }}</p>
                </div>
                <div>
                  <p class="text-xs text-slate-600">Patente</p>
                  <p class="font-semibold text-slate-900">{{ guia.patente }}</p>
                </div>
                <div>
                  <p class="text-xs text-slate-600">Bidones</p>
                  <p class="font-semibold text-slate-900">{{ guia.cantidadBidones }}</p>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mb-3">
                <div>
                  <p class="text-xs text-slate-600">Despacho</p>
                  <p class="text-slate-900">{{ formatDate(guia.fechaDespacho) }}</p>
                </div>
                <div>
                  <p class="text-xs text-slate-600">Entrega Estimada</p>
                  <p class="text-slate-900">{{ formatDate(guia.fechaEntregaEstimada) }}</p>
                </div>
                <div v-if="guia.fechaEntrega">
                  <p class="text-xs text-slate-600">Entrega Real</p>
                  <p class="text-slate-900">{{ formatDate(guia.fechaEntrega) }}</p>
                </div>
              </div>

              <div class="flex gap-2">
                <button 
                  @click="descargarGuiaPDF(guia)"
                  class="text-blue-500 hover:text-blue-700 text-sm hover:scale-110 transition-transform"
                  title="Descargar Guía en PDF"
                >
                  <i class="fas fa-download mr-1"></i> Descargar
                </button>
                <button
                  v-if="guia.estado === 'En Tránsito'"
                  @click="marcarEntregada(guia.id)"
                  class="text-green-500 hover:text-green-700 text-sm"
                >
                  <i class="fas fa-check-circle mr-1"></i> Marcar Entregada
                </button>
              </div>
            </div>
          </div>

          <div v-if="guiasFiltradas.length === 0" class="text-center py-8">
            <p class="text-slate-600">No se encontraron guías</p>
          </div>
        </div>

        <!-- Rastreo -->
        <div v-if="activeTab === 'rastreo'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Rastreo de Entregas</h2>
          
          <div class="mb-6">
            <input
              v-model="numeroGuiaRastreo"
              type="text"
              placeholder="Número de guía..."
              class="w-full px-4 py-2 border border-slate-300 rounded-lg"
            />
          </div>

          <div v-if="guiaRastreada" class="space-y-6">
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
              <p class="text-sm text-slate-600 mb-1">Guía de Despacho</p>
              <p class="text-2xl font-bold text-slate-900">{{ guiaRastreada.numero }}</p>
            </div>

            <div class="relative">
              <div class="space-y-4">
                <div
                  v-for="(evento, idx) in guiaRastreada.eventos"
                  :key="idx"
                  class="flex gap-4"
                >
                  <div class="text-center">
                    <div :class="['w-8 h-8 rounded-full mx-auto mb-2', getEventoColor(evento.tipo)]">
                      <i :class="getEventoIcon(evento.tipo)" class="text-white leading-8"></i>
                    </div>
                    <div v-if="idx < guiaRastreada.eventos.length - 1" class="w-1 h-12 bg-slate-300 mx-auto"></div>
                  </div>
                  <div class="flex-1 pb-8">
                    <p class="font-semibold text-slate-900">{{ evento.tipo }}</p>
                    <p class="text-sm text-slate-600">{{ evento.ubicacion }}</p>
                    <p class="text-xs text-slate-500">{{ formatDate(evento.fecha) }} {{ evento.hora }}</p>
                    <p class="text-sm text-slate-700 mt-1">{{ evento.descripcion }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-8">
            <p class="text-slate-600">Ingresa un número de guía para ver el rastreo</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { generarPdfGuia } from '~/composables/usePdfGenerator'

const activeTab = ref('crear')
const filtroGuia = ref('')
const numeroGuiaRastreo = ref('')

const tabs = [
  { id: 'crear', label: 'Crear Guía', icon: 'fas fa-file-alt' },
  { id: 'historial', label: 'Historial', icon: 'fas fa-list' },
  { id: 'rastreo', label: 'Rastreo', icon: 'fas fa-map-marker-alt' }
]

const formularioGuia = ref({
  ordenCompra: '',
  cliente: '',
  transportista: '',
  patente: '',
  fechaDespacho: new Date().toISOString().split('T')[0],
  fechaEntregaEstimada: new Date(Date.now() + 3*24*60*60*1000).toISOString().split('T')[0],
  cantidadBidones: 0,
  observaciones: ''
})

// Cargar desde PostgreSQL
const guias = ref([])
const loading = ref(false)

const loadGuias = async () => {
  try {
    loading.value = true
    const response = await fetch('/api/guias-despacho')
    const data = await response.json()
    guias.value = data.data || []
  } catch (error) {
    console.error('Error cargando guías de despacho:', error)
  } finally {
    loading.value = false
  }
}

// Cargar al montar
onMounted(() => {
  loadGuias()
})

const guiasFiltradas = computed(() => {
  return guias.value.filter(g =>
    g.numero.toLowerCase().includes(filtroGuia.value.toLowerCase()) ||
    g.cliente.toLowerCase().includes(filtroGuia.value.toLowerCase()) ||
    g.ordenCompra.toLowerCase().includes(filtroGuia.value.toLowerCase())
  )
})

const guiaRastreada = computed(() => {
  return guias.value.find(g => g.numero === numeroGuiaRastreo.value)
})

const crearGuia = () => {
  if (!formularioGuia.value.ordenCompra || !formularioGuia.value.transportista) {
    alert('Por favor completa todos los campos requeridos')
    return
  }

  alert('Guía de despacho creada exitosamente')

  formularioGuia.value = {
    ordenCompra: '',
    cliente: '',
    transportista: '',
    patente: '',
    fechaDespacho: new Date().toISOString().split('T')[0],
    fechaEntregaEstimada: new Date(Date.now() + 3*24*60*60*1000).toISOString().split('T')[0],
    cantidadBidones: 0,
    observaciones: ''
  }

  activeTab.value = 'historial'
}

const marcarEntregada = (id) => {
  const guia = guias.value.find(g => g.id === id)
  if (guia) {
    guia.estado = 'Entregada'
    guia.fechaEntrega = new Date().toISOString().split('T')[0]
    alert('Entrega marcada como completada')
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })
}

const getEstadoClass = (estado) => {
  const classes = {
    'Despachada': 'bg-yellow-100 text-yellow-800',
    'En Tránsito': 'bg-blue-100 text-blue-800',
    'Entregada': 'bg-green-100 text-green-800',
    'Rechazada': 'bg-red-100 text-red-800'
  }
  return classes[estado] || 'bg-slate-100 text-slate-800'
}

const getEventoColor = (tipo) => {
  const colors = {
    'Despachada': 'bg-yellow-500',
    'En Tránsito': 'bg-blue-500',
    'Entregada': 'bg-green-500',
    'Rechazada': 'bg-red-500'
  }
  return colors[tipo] || 'bg-slate-500'
}

const getEventoIcon = (tipo) => {
  const icons = {
    'Despachada': 'fas fa-box',
    'En Tránsito': 'fas fa-truck',
    'Entregada': 'fas fa-check-circle',
    'Rechazada': 'fas fa-times-circle'
  }
  return icons[tipo] || 'fas fa-circle'
}

const descargarGuiaPDF = (guia) => {
  generarPdfGuia(guia, formatDate)
}
</script>
