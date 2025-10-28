<template>
  <div class="min-h-screen bg-gradient-to-br from-orange-50 to-amber-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <NuxtLink to="/" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-4">
          <i class="fas fa-arrow-left mr-2"></i>
          Volver al Inicio
        </NuxtLink>
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Ajustes de Inventario</h1>
        <p class="text-slate-600">Registra ajustes y correcciones de stock</p>
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
        <!-- Crear Ajuste -->
        <div v-if="activeTab === 'crear'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Nuevo Ajuste de Inventario</h2>
          
          <form @submit.prevent="crearAjuste" class="max-w-2xl space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Centro de Distribución</label>
                <select
                  v-model="formularioAjuste.centro"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                  required
                >
                  <option value="">-- Selecciona centro --</option>
                  <option value="Centro Santiago">Centro Santiago</option>
                  <option value="Centro Valparaíso">Centro Valparaíso</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Tipo de Ajuste</label>
                <select
                  v-model="formularioAjuste.tipo"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                  required
                >
                  <option value="">-- Selecciona tipo --</option>
                  <option value="Aumento">Aumento</option>
                  <option value="Disminución">Disminución</option>
                  <option value="Corrección">Corrección</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Producto</label>
                <select
                  v-model="formularioAjuste.producto"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                  required
                >
                  <option value="">-- Selecciona producto --</option>
                  <option value="Bidón 20L">Bidón 20L</option>
                  <option value="Bidón 10L">Bidón 10L</option>
                  <option value="Bidón 5L">Bidón 5L</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Cantidad Actual</label>
                <input
                  v-model.number="formularioAjuste.cantidadActual"
                  type="number"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-50"
                  disabled
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Cantidad Ajuste</label>
                <input
                  v-model.number="formularioAjuste.cantidadAjuste"
                  type="number"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                  required
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Motivo del Ajuste</label>
              <textarea
                v-model="formularioAjuste.motivo"
                rows="3"
                placeholder="Describe el motivo del ajuste..."
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                required
              ></textarea>
            </div>

            <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
              <p class="text-sm text-slate-600 mb-2">Resumen del Ajuste:</p>
              <p class="text-lg font-bold">
                <span :class="formularioAjuste.tipo === 'Aumento' ? 'text-green-600' : 'text-red-600'">
                  {{ formularioAjuste.tipo === 'Aumento' ? '+' : '-' }}{{ formularioAjuste.cantidadAjuste }}
                </span>
                unidades de {{ formularioAjuste.producto }}
              </p>
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
                class="flex-1 bg-orange-500 text-white py-2 rounded-lg font-semibold hover:bg-orange-600"
              >
                <i class="fas fa-check mr-2"></i>
                Confirmar Ajuste
              </button>
            </div>
          </form>
        </div>

        <!-- Historial de Ajustes -->
        <div v-if="activeTab === 'historial'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Historial de Ajustes</h2>
          
          <div class="mb-4 flex gap-2">
            <input
              v-model="filtroAjuste"
              type="text"
              placeholder="Buscar ajuste..."
              class="flex-1 px-4 py-2 border border-slate-300 rounded-lg"
            />
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-slate-100 border-b border-slate-300">
                <tr>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Centro</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Producto</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Tipo</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Cantidad</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Motivo</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="ajuste in ajustesFiltrados" :key="ajuste.id" class="border-b border-slate-200 hover:bg-slate-50">
                  <td class="px-6 py-3 text-sm text-slate-600">{{ formatDate(ajuste.fecha) }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ ajuste.centro }}</td>
                  <td class="px-6 py-3 text-sm font-semibold text-slate-900">{{ ajuste.producto }}</td>
                  <td class="px-6 py-3 text-sm">
                    <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getTipoClass(ajuste.tipo)]">
                      {{ ajuste.tipo }}
                    </span>
                  </td>
                  <td class="px-6 py-3 text-sm font-semibold" :class="ajuste.tipo === 'Aumento' ? 'text-green-600' : 'text-red-600'">
                    {{ ajuste.tipo === 'Aumento' ? '+' : '-' }}{{ ajuste.cantidad }}
                  </td>
                  <td class="px-6 py-3 text-sm text-slate-600">{{ ajuste.motivo }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="ajustesFiltrados.length === 0" class="text-center py-8">
            <p class="text-slate-600">No se encontraron ajustes</p>
          </div>
        </div>

        <!-- Resumen por Centro -->
        <div v-if="activeTab === 'resumen'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Resumen de Ajustes</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
              v-for="centro in centros"
              :key="centro"
              class="border border-slate-200 rounded-lg p-6"
            >
              <h3 class="font-semibold text-slate-900 mb-4">{{ centro }}</h3>
              <div class="space-y-3">
                <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                  <span class="text-slate-600">Total Aumentos</span>
                  <span class="text-lg font-bold text-green-600">
                    +{{ totalAumentos(centro) }}
                  </span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                  <span class="text-slate-600">Total Disminuciones</span>
                  <span class="text-lg font-bold text-red-600">
                    -{{ totalDisminuciones(centro) }}
                  </span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-slate-600 font-semibold">Balance Neto</span>
                  <span class="text-xl font-bold text-orange-600">
                    {{ balanceNeto(centro) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const activeTab = ref('crear')
const filtroAjuste = ref('')

const tabs = [
  { id: 'crear', label: 'Crear Ajuste', icon: 'fas fa-plus-circle' },
  { id: 'historial', label: 'Historial', icon: 'fas fa-history' },
  { id: 'resumen', label: 'Resumen', icon: 'fas fa-chart-bar' }
]

const formularioAjuste = ref({
  centro: '',
  tipo: '',
  producto: '',
  cantidadActual: 50,
  cantidadAjuste: 0,
  motivo: ''
})

const ajustes = ref([
  {
    id: 1,
    fecha: '2024-10-20',
    centro: 'Centro Santiago',
    producto: 'Bidón 20L',
    tipo: 'Aumento',
    cantidad: 10,
    motivo: 'Recepción de compra OC-2024-001'
  },
  {
    id: 2,
    fecha: '2024-10-21',
    centro: 'Centro Santiago',
    producto: 'Bidón 10L',
    tipo: 'Disminución',
    cantidad: 5,
    motivo: 'Ajuste por daño durante transporte'
  },
  {
    id: 3,
    fecha: '2024-10-22',
    centro: 'Centro Valparaíso',
    producto: 'Bidón 5L',
    tipo: 'Corrección',
    cantidad: 3,
    motivo: 'Corrección de conteo físico'
  },
])

const centros = ['Centro Santiago', 'Centro Valparaíso']

const ajustesFiltrados = computed(() => {
  return ajustes.value.filter(a =>
    a.centro.toLowerCase().includes(filtroAjuste.value.toLowerCase()) ||
    a.producto.toLowerCase().includes(filtroAjuste.value.toLowerCase()) ||
    a.motivo.toLowerCase().includes(filtroAjuste.value.toLowerCase())
  )
})

const crearAjuste = () => {
  if (!formularioAjuste.value.centro || !formularioAjuste.value.tipo || !formularioAjuste.value.producto) {
    alert('Por favor completa todos los campos requeridos')
    return
  }

  alert('Ajuste registrado exitosamente')

  formularioAjuste.value = {
    centro: '',
    tipo: '',
    producto: '',
    cantidadActual: 50,
    cantidadAjuste: 0,
    motivo: ''
  }

  activeTab.value = 'historial'
}

const totalAumentos = (centro) => {
  return ajustes.value
    .filter(a => a.centro === centro && a.tipo === 'Aumento')
    .reduce((sum, a) => sum + a.cantidad, 0)
}

const totalDisminuciones = (centro) => {
  return ajustes.value
    .filter(a => a.centro === centro && a.tipo === 'Disminución')
    .reduce((sum, a) => sum + a.cantidad, 0)
}

const balanceNeto = (centro) => {
  const aumentos = totalAumentos(centro)
  const disminuciones = totalDisminuciones(centro)
  return aumentos - disminuciones
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })
}

const getTipoClass = (tipo) => {
  const classes = {
    'Aumento': 'bg-green-100 text-green-800',
    'Disminución': 'bg-red-100 text-red-800',
    'Corrección': 'bg-blue-100 text-blue-800'
  }
  return classes[tipo] || 'bg-slate-100 text-slate-800'
}
</script>
