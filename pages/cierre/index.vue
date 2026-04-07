<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <NuxtLink to="/" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-4">
          <i class="fas fa-arrow-left mr-2"></i>
          Volver al Inicio
        </NuxtLink>
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Cierre de Período</h1>
        <p class="text-slate-600">Cierra períodos contables y genera reportes finales</p>
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
              ? 'bg-indigo-500 text-white shadow-lg'
              : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'
          ]"
        >
          <i :class="tab.icon" class="mr-2"></i>
          {{ tab.label }}
        </button>
      </div>

      <!-- Contenido -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Nuevo Cierre -->
        <div v-if="activeTab === 'nuevo'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Nuevo Cierre de Período</h2>
          
          <form @submit.prevent="crearCierre" class="max-w-2xl space-y-6">
            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded mb-6">
              <p class="text-sm text-indigo-800">
                <i class="fas fa-info-circle mr-2"></i>
                El cierre de período es una operación crítica. Asegúrate de que todos los movimientos estén registrados.
              </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Centro de Distribución</label>
                <select
                  v-model="formularioCierre.centro"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                  required
                >
                  <option value="">-- Selecciona centro --</option>
                  <option value="Centro Santiago">Centro Santiago</option>
                  <option value="Centro Valparaíso">Centro Valparaíso</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Período</label>
                <select
                  v-model="formularioCierre.periodo"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                  required
                >
                  <option value="">-- Selecciona período --</option>
                  <option value="Octubre 2024">Octubre 2024</option>
                  <option value="Noviembre 2024">Noviembre 2024</option>
                  <option value="Diciembre 2024">Diciembre 2024</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Fecha Inicio</label>
                <input
                  v-model="formularioCierre.fechaInicio"
                  type="date"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Fecha Fin</label>
                <input
                  v-model="formularioCierre.fechaFin"
                  type="date"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                  required
                />
              </div>
            </div>

            <div class="border-t pt-6">
              <h3 class="font-semibold text-slate-900 mb-4">Resumen de Validación</h3>
              
              <div class="space-y-3 mb-6">
                <div class="flex items-center p-3 bg-slate-50 rounded border border-slate-200">
                  <input type="checkbox" v-model="validaciones.inventarioContado" class="mr-3" />
                  <label class="text-sm text-slate-700">Inventario físico contado y validado</label>
                </div>
                <div class="flex items-center p-3 bg-slate-50 rounded border border-slate-200">
                  <input type="checkbox" v-model="validaciones.facturasRegistradas" class="mr-3" />
                  <label class="text-sm text-slate-700">Todas las facturas registradas</label>
                </div>
                <div class="flex items-center p-3 bg-slate-50 rounded border border-slate-200">
                  <input type="checkbox" v-model="validaciones.pagosRegistrados" class="mr-3" />
                  <label class="text-sm text-slate-700">Todos los pagos registrados</label>
                </div>
                <div class="flex items-center p-3 bg-slate-50 rounded border border-slate-200">
                  <input type="checkbox" v-model="validaciones.ajustesRealizados" class="mr-3" />
                  <label class="text-sm text-slate-700">Ajustes necesarios realizados</label>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Observaciones Finales</label>
                <textarea
                  v-model="formularioCierre.observaciones"
                  rows="3"
                  placeholder="Comentarios sobre el cierre..."
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                ></textarea>
              </div>
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
                :disabled="!todasValidacionesOK"
                :class="[
                  'flex-1 py-2 rounded-lg font-semibold transition-all',
                  todasValidacionesOK
                    ? 'bg-indigo-500 text-white hover:bg-indigo-600'
                    : 'bg-slate-300 text-slate-500 cursor-not-allowed'
                ]"
              >
                <i class="fas fa-lock mr-2"></i>
                Confirmar Cierre
              </button>
            </div>
          </form>
        </div>

        <!-- Historial de Cierres -->
        <div v-if="activeTab === 'historial'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Historial de Cierres</h2>
          
          <div class="space-y-4">
            <div
              v-for="cierre in cierres"
              :key="cierre.id"
              class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <div class="flex justify-between items-start mb-3">
                <div>
                  <h3 class="font-semibold text-slate-900">{{ cierre.periodo }}</h3>
                  <p class="text-sm text-slate-600">{{ cierre.centro }}</p>
                </div>
                <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getEstadoClass(cierre.estado)]">
                  {{ cierre.estado }}
                </span>
              </div>

              <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm mb-3">
                <div>
                  <p class="text-slate-600">Fecha Cierre</p>
                  <p class="font-semibold text-slate-900">{{ formatDate(cierre.fechaCierre) }}</p>
                </div>
                <div>
                  <p class="text-slate-600">Ingresos</p>
                  <p class="font-semibold text-green-600">${{ formatCurrency(cierre.ingresos) }}</p>
                </div>
                <div>
                  <p class="text-slate-600">Egresos</p>
                  <p class="font-semibold text-red-600">${{ formatCurrency(cierre.egresos) }}</p>
                </div>
                <div>
                  <p class="text-slate-600">Balance</p>
                  <p class="font-semibold text-indigo-600">${{ formatCurrency(cierre.ingresos - cierre.egresos) }}</p>
                </div>
              </div>

              <div class="flex gap-2 text-sm">
                <button class="text-blue-500 hover:text-blue-700">
                  <i class="fas fa-download mr-1"></i> Reporte
                </button>
                <button class="text-slate-500 hover:text-slate-700">
                  <i class="fas fa-eye mr-1"></i> Ver Detalles
                </button>
              </div>
            </div>
          </div>

          <div v-if="cierres.length === 0" class="text-center py-8">
            <p class="text-slate-600">No hay cierres registrados</p>
          </div>
        </div>

        <!-- Reportes -->
        <div v-if="activeTab === 'reportes'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Reportes de Cierre</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border border-slate-200 rounded-lg p-6 hover:shadow-md transition-shadow">
              <h3 class="font-semibold text-slate-900 mb-2">Reporte de Ingresos</h3>
              <p class="text-sm text-slate-600 mb-4">Detalle de todas las transacciones de ingreso</p>
              <button class="text-indigo-500 hover:text-indigo-700 font-semibold">
                <i class="fas fa-file-pdf mr-2"></i> Descargar PDF
              </button>
            </div>

            <div class="border border-slate-200 rounded-lg p-6 hover:shadow-md transition-shadow">
              <h3 class="font-semibold text-slate-900 mb-2">Reporte de Egresos</h3>
              <p class="text-sm text-slate-600 mb-4">Detalle de todas las transacciones de egreso</p>
              <button class="text-indigo-500 hover:text-indigo-700 font-semibold">
                <i class="fas fa-file-pdf mr-2"></i> Descargar PDF
              </button>
            </div>

            <div class="border border-slate-200 rounded-lg p-6 hover:shadow-md transition-shadow">
              <h3 class="font-semibold text-slate-900 mb-2">Balance General</h3>
              <p class="text-sm text-slate-600 mb-4">Resumen financiero del período</p>
              <button class="text-indigo-500 hover:text-indigo-700 font-semibold">
                <i class="fas fa-file-excel mr-2"></i> Descargar Excel
              </button>
            </div>

            <div class="border border-slate-200 rounded-lg p-6 hover:shadow-md transition-shadow">
              <h3 class="font-semibold text-slate-900 mb-2">Inventario Final</h3>
              <p class="text-sm text-slate-600 mb-4">Estado del inventario al cierre</p>
              <button class="text-indigo-500 hover:text-indigo-700 font-semibold">
                <i class="fas fa-file-csv mr-2"></i> Descargar CSV
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const activeTab = ref('nuevo')

const tabs = [
  { id: 'nuevo', label: 'Nuevo Cierre', icon: 'fas fa-plus-circle' },
  { id: 'historial', label: 'Historial', icon: 'fas fa-history' },
  { id: 'reportes', label: 'Reportes', icon: 'fas fa-chart-line' }
]

const formularioCierre = ref({
  centro: '',
  periodo: '',
  fechaInicio: '',
  fechaFin: '',
  observaciones: ''
})

const validaciones = ref({
  inventarioContado: false,
  facturasRegistradas: false,
  pagosRegistrados: false,
  ajustesRealizados: false
})

// Cargar desde PostgreSQL
const cierres = ref([])
const loading = ref(false)

const loadCierres = async () => {
  try {
    loading.value = true
    const response = await fetch('/api/cierres')
    const data = await response.json()
    cierres.value = data.data || []
  } catch (error) {
    console.error('Error cargando cierres:', error)
  } finally {
    loading.value = false
  }
}

// Cargar al montar
onMounted(() => {
  loadCierres()
})

const todasValidacionesOK = computed(() => {
  return validaciones.value.inventarioContado &&
         validaciones.value.facturasRegistradas &&
         validaciones.value.pagosRegistrados &&
         validaciones.value.ajustesRealizados
})

const crearCierre = () => {
  if (!formularioCierre.value.centro || !formularioCierre.value.periodo) {
    alert('Por favor completa todos los campos requeridos')
    return
  }

  alert('Período cerrado exitosamente')

  formularioCierre.value = {
    centro: '',
    periodo: '',
    fechaInicio: '',
    fechaFin: '',
    observaciones: ''
  }

  validaciones.value = {
    inventarioContado: false,
    facturasRegistradas: false,
    pagosRegistrados: false,
    ajustesRealizados: false
  }

  activeTab.value = 'historial'
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })
}

const formatCurrency = (value) => {
  return new Intl.NumberFormat('es-ES', { minimumFractionDigits: 0 }).format(value)
}

const getEstadoClass = (estado) => {
  const classes = {
    'Cerrado': 'bg-blue-100 text-blue-800',
    'Abierto': 'bg-yellow-100 text-yellow-800',
    'Anulado': 'bg-red-100 text-red-800'
  }
  return classes[estado] || 'bg-slate-100 text-slate-800'
}
</script>
