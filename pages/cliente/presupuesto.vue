<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Presupuesto - Cuenta Corriente</h1>
        <p class="text-slate-600">Revisa tu saldo disponible y estado de cuenta</p>
      </div>

      <!-- Balance Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-500">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-slate-600 text-sm font-medium mb-1">Saldo Disponible</p>
              <p class="text-3xl font-bold text-blue-600">${{ formatCurrency(balanceData.disponible) }}</p>
            </div>
            <i class="fas fa-wallet text-4xl text-blue-200"></i>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-amber-500">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-slate-600 text-sm font-medium mb-1">Monto Facturado</p>
              <p class="text-3xl font-bold text-amber-600">${{ formatCurrency(balanceData.facturado) }}</p>
            </div>
            <i class="fas fa-file-invoice text-4xl text-amber-200"></i>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-red-500">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-slate-600 text-sm font-medium mb-1">Total Deuda</p>
              <p class="text-3xl font-bold text-red-600">${{ formatCurrency(balanceData.deuda) }}</p>
            </div>
            <i class="fas fa-exclamation-circle text-4xl text-red-200"></i>
          </div>
        </div>
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

      <!-- Content Area -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Movimientos -->
        <div v-if="activeTab === 'movimientos'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Movimientos de Cuenta</h2>
          
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-slate-100 border-b border-slate-300">
                <tr>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Tipo</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Descripción</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Monto</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Saldo</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="movimiento in movimientos" :key="movimiento.id" class="border-b border-slate-200 hover:bg-slate-50">
                  <td class="px-6 py-3 text-sm text-slate-900">{{ formatDate(movimiento.fecha) }}</td>
                  <td class="px-6 py-3 text-sm">
                    <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getTipoClass(movimiento.tipo)]">
                      {{ movimiento.tipo }}
                    </span>
                  </td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ movimiento.descripcion }}</td>
                  <td class="px-6 py-3 text-sm font-semibold" :class="movimiento.tipo === 'Cargo' ? 'text-red-600' : 'text-green-600'">
                    {{ movimiento.tipo === 'Cargo' ? '-' : '+' }} ${{ formatCurrency(movimiento.monto) }}
                  </td>
                  <td class="px-6 py-3 text-sm font-semibold text-slate-900">${{ formatCurrency(movimiento.saldo) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Facturas -->
        <div v-if="activeTab === 'facturas'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Facturas Emitidas</h2>
          
          <div class="space-y-4">
            <div
              v-for="factura in facturas"
              :key="factura.id"
              class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <div class="flex justify-between items-start mb-3">
                <div>
                  <h3 class="font-semibold text-slate-900">Factura #{{ factura.numero }}</h3>
                  <p class="text-sm text-slate-600">Fecha: {{ formatDate(factura.fecha) }}</p>
                </div>
                <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getEstadoClass(factura.estado)]">
                  {{ factura.estado }}
                </span>
              </div>
              
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div>
                  <p class="text-xs text-slate-600">Total</p>
                  <p class="font-bold text-slate-900">${{ formatCurrency(factura.total) }}</p>
                </div>
                <div>
                  <p class="text-xs text-slate-600">Pagado</p>
                  <p class="font-bold text-green-600">${{ formatCurrency(factura.pagado) }}</p>
                </div>
                <div>
                  <p class="text-xs text-slate-600">Saldo</p>
                  <p class="font-bold text-red-600">${{ formatCurrency(factura.saldo) }}</p>
                </div>
                <div>
                  <p class="text-xs text-slate-600">Vencimiento</p>
                  <p class="font-bold text-slate-900">{{ formatDate(factura.vencimiento) }}</p>
                </div>
              </div>

              <div class="flex gap-2">
                <button class="text-blue-500 hover:text-blue-700 text-sm" title="Descargar PDF">
                  <i class="fas fa-download mr-1"></i> Descargar
                </button>
                <button class="text-slate-500 hover:text-slate-700 text-sm" title="Ver detalles">
                  <i class="fas fa-eye mr-1"></i> Ver
                </button>
              </div>
            </div>
          </div>

          <div v-if="facturas.length === 0" class="text-center py-8">
            <p class="text-slate-600 text-lg">No hay facturas</p>
          </div>
        </div>

        <!-- Pagos -->
        <div v-if="activeTab === 'pagos'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Realizar Pago</h2>
          
          <div class="max-w-2xl">
            <form @submit.prevent="handlePayment" class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  Seleccionar Factura
                </label>
                <select
                  v-model="pago.facturaId"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                >
                  <option value="">-- Selecciona una factura --</option>
                  <option v-for="factura in facturasConSaldo" :key="factura.id" :value="factura.id">
                    Factura #{{ factura.numero }} - Saldo: ${{ formatCurrency(factura.saldo) }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  Monto a Pagar
                </label>
                <input
                  v-model.number="pago.monto"
                  type="number"
                  step="0.01"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="0.00"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  Método de Pago
                </label>
                <select
                  v-model="pago.metodo"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                >
                  <option value="">-- Selecciona un método --</option>
                  <option value="transferencia">Transferencia Bancaria</option>
                  <option value="tarjeta">Tarjeta de Crédito</option>
                  <option value="cheque">Cheque</option>
                  <option value="efectivo">Efectivo</option>
                </select>
              </div>

              <button
                type="submit"
                class="w-full bg-green-500 text-white py-3 rounded-lg font-semibold hover:bg-green-600 transition-colors"
              >
                <i class="fas fa-check-circle mr-2"></i>
                Procesar Pago
              </button>
            </form>
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
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-semibold text-slate-900">Nota de Crédito #{{ nota.numero }}</h3>
                  <p class="text-sm text-slate-600">Factura Asociada: #{{ nota.facturaNumero }}</p>
                  <p class="text-sm text-slate-600">Motivo: {{ nota.motivo }}</p>
                </div>
                <p class="text-lg font-bold text-green-600">${{ formatCurrency(nota.monto) }}</p>
              </div>
              <p class="text-xs text-slate-500 mt-2">Fecha: {{ formatDate(nota.fecha) }}</p>
            </div>
          </div>

          <div v-if="notasCredito.length === 0" class="text-center py-8">
            <p class="text-slate-600 text-lg">No hay notas de crédito</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const activeTab = ref('movimientos')

const tabs = [
  { id: 'movimientos', label: 'Movimientos', icon: 'fas fa-exchange-alt' },
  { id: 'facturas', label: 'Facturas', icon: 'fas fa-file-invoice-dollar' },
  { id: 'pagos', label: 'Realizar Pago', icon: 'fas fa-credit-card' },
  { id: 'notas', label: 'Notas de Crédito', icon: 'fas fa-receipt' }
]

const balanceData = ref({
  disponible: 150000,
  facturado: 450000,
  deuda: 120000
})

const movimientos = ref([
  { id: 1, fecha: '2024-10-25', tipo: 'Abono', descripcion: 'Pago Factura #001', monto: 50000, saldo: 150000 },
  { id: 2, fecha: '2024-10-20', tipo: 'Cargo', descripcion: 'Factura #001', monto: 45000, saldo: 100000 },
  { id: 3, fecha: '2024-10-15', tipo: 'Abono', descripcion: 'Pago Factura #000', monto: 75000, saldo: 145000 },
  { id: 4, fecha: '2024-10-10', tipo: 'Cargo', descripcion: 'Factura #000', monto: 70000, saldo: 70000 },
])

const facturas = ref([
  { id: 1, numero: '001', fecha: '2024-10-20', vencimiento: '2024-11-20', total: 450000, pagado: 330000, saldo: 120000, estado: 'Parcial' },
  { id: 2, numero: '000', fecha: '2024-10-10', vencimiento: '2024-11-10', total: 700000, pagado: 700000, saldo: 0, estado: 'Pagado' },
])

const facturasConSaldo = computed(() => {
  return facturas.value.filter(f => f.saldo > 0)
})

const notasCredito = ref([
  { id: 1, numero: 'NC-001', facturaNumero: '001', fecha: '2024-10-22', monto: 15000, motivo: 'Devolución de producto' },
])

const pago = ref({
  facturaId: '',
  monto: 0,
  metodo: ''
})

const handlePayment = () => {
  if (!pago.value.facturaId || !pago.value.monto || !pago.value.metodo) {
    alert('Por favor completa todos los campos')
    return
  }
  
  alert(`Pago de $${pago.value.monto} procesado mediante ${pago.value.metodo}`)
  
  // Reset form
  pago.value = {
    facturaId: '',
    monto: 0,
    metodo: ''
  }
}

const formatCurrency = (value) => {
  return new Intl.NumberFormat('es-ES', { minimumFractionDigits: 0 }).format(value)
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })
}

const getTipoClass = (tipo) => {
  return tipo === 'Cargo' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'
}

const getEstadoClass = (estado) => {
  const classes = {
    'Pagado': 'bg-green-100 text-green-800',
    'Parcial': 'bg-yellow-100 text-yellow-800',
    'Vencido': 'bg-red-100 text-red-800'
  }
  return classes[estado] || 'bg-slate-100 text-slate-800'
}
</script>
