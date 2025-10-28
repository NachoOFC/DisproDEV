<template>
  <div class="min-h-screen bg-gradient-to-br from-red-50 to-pink-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <NuxtLink to="/" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-4">
          <i class="fas fa-arrow-left mr-2"></i>
          Volver al Inicio
        </NuxtLink>
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Facturación Electrónica</h1>
        <p class="text-slate-600">Emite facturas y notas de crédito electrónicas</p>
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
              ? 'bg-red-500 text-white shadow-lg'
              : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'
          ]"
        >
          <i :class="tab.icon" class="mr-2"></i>
          {{ tab.label }}
        </button>
      </div>

      <!-- Contenido -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Crear Factura -->
        <div v-if="activeTab === 'crear'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Nueva Factura</h2>
          
          <form @submit.prevent="crearFactura" class="max-w-2xl space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Cliente</label>
                <select
                  v-model="formularioFactura.cliente"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500"
                  required
                >
                  <option value="">-- Selecciona cliente --</option>
                  <option value="Centro Santiago">Centro Santiago</option>
                  <option value="Centro Valparaíso">Centro Valparaíso</option>
                  <option value="Empresa A">Empresa A</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Tipo Documento</label>
                <select
                  v-model="formularioFactura.tipo"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500"
                  required
                >
                  <option value="Factura">Factura</option>
                  <option value="Boleta">Boleta</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Folio</label>
                <input
                  v-model="formularioFactura.folio"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-50"
                  disabled
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Fecha Emisión</label>
                <input
                  v-model="formularioFactura.fechaEmision"
                  type="date"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Fecha Vencimiento</label>
                <input
                  v-model="formularioFactura.fechaVencimiento"
                  type="date"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500"
                  required
                />
              </div>
            </div>

            <div class="border-t pt-6">
              <h3 class="font-semibold text-slate-900 mb-4">Detalles de la Factura</h3>
              
              <div class="space-y-3 mb-4">
                <div v-for="(detalle, idx) in formularioFactura.detalles" :key="idx" class="border border-slate-200 p-4 rounded-lg">
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
                    <input
                      v-model="detalle.descripcion"
                      type="text"
                      placeholder="Descripción"
                      class="px-3 py-2 border border-slate-300 rounded text-sm"
                    />
                    <input
                      v-model.number="detalle.cantidad"
                      type="number"
                      placeholder="Cantidad"
                      class="px-3 py-2 border border-slate-300 rounded text-sm"
                    />
                    <input
                      v-model.number="detalle.valorUnitario"
                      type="number"
                      placeholder="Valor Unitario"
                      class="px-3 py-2 border border-slate-300 rounded text-sm"
                    />
                    <div class="text-right text-sm font-semibold">
                      Total: ${{ formatCurrency(detalle.cantidad * detalle.valorUnitario) }}
                    </div>
                  </div>
                  <button
                    type="button"
                    @click="formularioFactura.detalles.splice(idx, 1)"
                    class="text-red-500 hover:text-red-700 text-sm"
                  >
                    <i class="fas fa-trash mr-1"></i> Eliminar
                  </button>
                </div>
              </div>

              <button
                type="button"
                @click="formularioFactura.detalles.push({ descripcion: '', cantidad: 0, valorUnitario: 0 })"
                class="text-blue-500 hover:text-blue-700 text-sm font-semibold mb-6"
              >
                <i class="fas fa-plus mr-1"></i> Agregar Línea
              </button>
            </div>

            <div class="bg-slate-100 p-4 rounded-lg">
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                  <p class="text-slate-600 text-sm">Subtotal</p>
                  <p class="text-xl font-bold text-slate-900">${{ formatCurrency(subtotal) }}</p>
                </div>
                <div>
                  <p class="text-slate-600 text-sm">IVA (19%)</p>
                  <p class="text-xl font-bold text-slate-900">${{ formatCurrency(iva) }}</p>
                </div>
                <div>
                  <p class="text-slate-600 text-sm">Total</p>
                  <p class="text-2xl font-bold text-red-600">${{ formatCurrency(total) }}</p>
                </div>
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
                class="flex-1 bg-red-500 text-white py-2 rounded-lg font-semibold hover:bg-red-600"
              >
                <i class="fas fa-file-invoice mr-2"></i>
                Emitir Factura
              </button>
            </div>
          </form>
        </div>

        <!-- Historial de Facturas -->
        <div v-if="activeTab === 'historial'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Historial de Facturas</h2>
          
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-slate-100 border-b border-slate-300">
                <tr>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Folio</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Cliente</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Total</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Estado</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="factura in facturas" :key="factura.id" class="border-b border-slate-200 hover:bg-slate-50">
                  <td class="px-6 py-3 text-sm font-semibold text-red-600">{{ factura.folio }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ factura.cliente }}</td>
                  <td class="px-6 py-3 text-sm text-slate-600">{{ formatDate(factura.fecha) }}</td>
                  <td class="px-6 py-3 text-sm font-semibold text-slate-900">${{ formatCurrency(factura.total) }}</td>
                  <td class="px-6 py-3 text-sm">
                    <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getEstadoClass(factura.estado)]">
                      {{ factura.estado }}
                    </span>
                  </td>
                  <td class="px-6 py-3 text-sm">
                    <button class="text-blue-500 hover:text-blue-700 mr-3">
                      <i class="fas fa-download"></i>
                    </button>
                    <button class="text-red-500 hover:text-red-700">
                      <i class="fas fa-times-circle"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Notas de Crédito -->
        <div v-if="activeTab === 'notas'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Notas de Crédito</h2>
          
          <div class="space-y-4">
            <div
              v-for="nota in notasCredito"
              :key="nota.id"
              class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <div class="flex justify-between items-start mb-2">
                <div>
                  <h3 class="font-semibold text-slate-900">Nota de Crédito #{{ nota.numero }}</h3>
                  <p class="text-sm text-slate-600">Factura Asociada: #{{ nota.facturaAsociada }}</p>
                </div>
                <p class="text-lg font-bold text-green-600">-${{ formatCurrency(nota.monto) }}</p>
              </div>
              <p class="text-sm text-slate-600 mb-3">Motivo: {{ nota.motivo }}</p>
              <p class="text-xs text-slate-500">Fecha: {{ formatDate(nota.fecha) }}</p>
            </div>
          </div>

          <div v-if="notasCredito.length === 0" class="text-center py-8">
            <p class="text-slate-600">No hay notas de crédito registradas</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const activeTab = ref('crear')

const tabs = [
  { id: 'crear', label: 'Crear Factura', icon: 'fas fa-file-invoice-dollar' },
  { id: 'historial', label: 'Historial', icon: 'fas fa-history' },
  { id: 'notas', label: 'Notas de Crédito', icon: 'fas fa-undo' }
]

const formularioFactura = ref({
  cliente: '',
  tipo: 'Factura',
  folio: 'F-2024-001',
  fechaEmision: new Date().toISOString().split('T')[0],
  fechaVencimiento: new Date(Date.now() + 30*24*60*60*1000).toISOString().split('T')[0],
  detalles: [
    { descripcion: '', cantidad: 0, valorUnitario: 0 }
  ]
})

const facturas = ref([
  { id: 1, folio: 'F-2024-001', cliente: 'Centro Santiago', fecha: '2024-10-25', total: 500000, estado: 'Pagada' },
  { id: 2, folio: 'F-2024-000', cliente: 'Empresa A', fecha: '2024-10-20', total: 750000, estado: 'Pagada' },
])

const notasCredito = ref([
  { id: 1, numero: 'NC-2024-001', facturaAsociada: 'F-2024-001', monto: 50000, motivo: 'Devolución de producto', fecha: '2024-10-26' },
])

const subtotal = computed(() => {
  return formularioFactura.value.detalles.reduce((sum, d) => sum + (d.cantidad * d.valorUnitario), 0)
})

const iva = computed(() => subtotal.value * 0.19)
const total = computed(() => subtotal.value + iva.value)

const crearFactura = () => {
  if (!formularioFactura.value.cliente) {
    alert('Por favor selecciona un cliente')
    return
  }
  
  alert('Factura emitida exitosamente')
  
  // Reset
  formularioFactura.value = {
    cliente: '',
    tipo: 'Factura',
    folio: 'F-2024-002',
    fechaEmision: new Date().toISOString().split('T')[0],
    fechaVencimiento: new Date(Date.now() + 30*24*60*60*1000).toISOString().split('T')[0],
    detalles: [{ descripcion: '', cantidad: 0, valorUnitario: 0 }]
  }
  
  activeTab.value = 'historial'
}

const formatCurrency = (value) => {
  return new Intl.NumberFormat('es-ES', { minimumFractionDigits: 0 }).format(value)
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })
}

const getEstadoClass = (estado) => {
  const classes = {
    'Pagada': 'bg-green-100 text-green-800',
    'Pendiente': 'bg-yellow-100 text-yellow-800',
    'Anulada': 'bg-red-100 text-red-800'
  }
  return classes[estado] || 'bg-slate-100 text-slate-800'
}
</script>
