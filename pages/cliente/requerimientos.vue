<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Mis Órdenes de Pedido</h1>
        <p class="text-slate-600">Gestiona todas tus órdenes de pedido en un solo lugar</p>
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
        <!-- Crear Nueva Orden -->
        <div v-if="activeTab === 'crear'" class="p-8">
          <div class="max-w-2xl">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Nueva Orden de Pedido</h2>
            
            <form @submit.prevent="handleCreateOrder" class="space-y-6">
              <!-- Nombre del Requerimiento -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  Nombre del Requerimiento
                </label>
                <input
                  v-model="form.nombre"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Ej: Orden Mensual Octubre"
                  required
                />
              </div>

              <!-- Número de Requerimiento -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  Número de Requerimiento
                </label>
                <input
                  v-model="form.numero"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Auto-generado"
                  disabled
                />
              </div>

              <!-- Productos -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  Seleccionar Productos
                </label>
                <div class="max-h-64 border border-slate-300 rounded-lg overflow-y-auto">
                  <div v-for="producto in productos" :key="producto.id" class="p-3 border-b border-slate-200 hover:bg-slate-50">
                    <label class="flex items-center">
                      <input
                        type="checkbox"
                        :value="producto.id"
                        v-model="form.productos"
                        class="w-4 h-4 text-blue-500 rounded focus:ring-2 focus:ring-blue-500"
                      />
                      <span class="ml-3 text-sm text-slate-700">
                        {{ producto.nombre }} - ${{ producto.precio }}
                      </span>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Botones de Acción -->
              <div class="flex gap-3 pt-6 border-t border-slate-200">
                <button
                  type="submit"
                  class="flex-1 bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600 transition-colors"
                >
                  <i class="fas fa-save mr-2"></i>
                  Guardar Orden
                </button>
                <button
                  type="button"
                  @click="downloadFormatTemplate"
                  class="flex-1 bg-slate-200 text-slate-700 py-2 rounded-lg font-semibold hover:bg-slate-300 transition-colors"
                >
                  <i class="fas fa-download mr-2"></i>
                  Descargar Formato
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Historial de Órdenes -->
        <div v-if="activeTab === 'historial'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Historial de Órdenes</h2>
          
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-slate-100 border-b border-slate-300">
                <tr>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">#</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Nombre</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Estado</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(orden, idx) in requerimientos" :key="orden.id" class="border-b border-slate-200 hover:bg-slate-50">
                  <td class="px-6 py-3 text-sm text-slate-900">{{ idx + 1 }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ orden.nombre }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ formatDate(orden.fecha) }}</td>
                  <td class="px-6 py-3 text-sm">
                    <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getStatusClass(orden.estado)]">
                      {{ orden.estado }}
                    </span>
                  </td>
                  <td class="px-6 py-3 text-sm">
                    <button
                      @click="viewOrder(orden.id)"
                      class="text-blue-500 hover:text-blue-700 mr-3"
                      title="Ver detalles"
                    >
                      <i class="fas fa-eye"></i>
                    </button>
                    <button
                      @click="editOrder(orden.id)"
                      class="text-amber-500 hover:text-amber-700 mr-3"
                      title="Editar"
                      v-if="orden.estado === 'Pendiente'"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      @click="deleteOrder(orden.id)"
                      class="text-red-500 hover:text-red-700"
                      title="Eliminar"
                      v-if="orden.estado === 'Pendiente'"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="requerimientos.length === 0" class="text-center py-8">
            <p class="text-slate-600 text-lg">No hay órdenes de pedido aún</p>
          </div>
        </div>

        <!-- Órdenes Pendientes por Validar -->
        <div v-if="activeTab === 'validar'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Órdenes Pendientes por Validar</h2>
          
          <div class="space-y-4">
            <div
              v-for="orden in ordenesParaValidar"
              :key="orden.id"
              class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-semibold text-slate-900">{{ orden.nombre }}</h3>
                  <p class="text-sm text-slate-600">Solicitante: {{ orden.solicitante }}</p>
                  <p class="text-sm text-slate-600">Fecha: {{ formatDate(orden.fecha) }}</p>
                </div>
                <div class="flex gap-2">
                  <button
                    @click="validarOrden(orden.id, true)"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm"
                  >
                    <i class="fas fa-check mr-1"></i>
                    Aprobar
                  </button>
                  <button
                    @click="validarOrden(orden.id, false)"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm"
                  >
                    <i class="fas fa-times mr-1"></i>
                    Rechazar
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div v-if="ordenesParaValidar.length === 0" class="text-center py-8">
            <p class="text-slate-600 text-lg">No hay órdenes pendientes por validar</p>
          </div>
        </div>

        <!-- Rechazo de Órdenes -->
        <div v-if="activeTab === 'rechazos'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Órdenes Rechazadas</h2>
          
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-slate-100 border-b border-slate-300">
                <tr>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Orden</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Motivo Rechazo</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha Rechazo</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="orden in ordenesRechazadas" :key="orden.id" class="border-b border-slate-200 hover:bg-slate-50">
                  <td class="px-6 py-3 text-sm text-slate-900">{{ orden.nombre }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ orden.motivo }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ formatDate(orden.fechaRechazo) }}</td>
                  <td class="px-6 py-3 text-sm">
                    <button
                      @click="resubmitOrder(orden.id)"
                      class="text-blue-500 hover:text-blue-700"
                      title="Reenviar orden"
                    >
                      <i class="fas fa-redo mr-1"></i>
                      Reenviar
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="ordenesRechazadas.length === 0" class="text-center py-8">
            <p class="text-slate-600 text-lg">No hay órdenes rechazadas</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const activeTab = ref('crear')

const tabs = [
  { id: 'crear', label: 'Nueva Orden', icon: 'fas fa-plus-circle' },
  { id: 'historial', label: 'Historial', icon: 'fas fa-history' },
  { id: 'validar', label: 'Por Validar', icon: 'fas fa-check-circle' },
  { id: 'rechazos', label: 'Rechazadas', icon: 'fas fa-times-circle' }
]

const form = ref({
  nombre: '',
  numero: 'REQ-2024-0001',
  productos: []
})

// Mock data - será reemplazado por datos reales de la API
const productos = ref([
  { id: 1, nombre: 'Producto A', precio: 1500 },
  { id: 2, nombre: 'Producto B', precio: 2500 },
  { id: 3, nombre: 'Producto C', precio: 3500 },
  { id: 4, nombre: 'Producto D', precio: 4500 },
])

const requerimientos = ref([
  { id: 1, nombre: 'Orden Octubre 2024', fecha: '2024-10-15', estado: 'Aprobada', productos: 5 },
  { id: 2, nombre: 'Orden Septiembre 2024', fecha: '2024-09-20', estado: 'Entregada', productos: 3 },
  { id: 3, nombre: 'Orden Agosto 2024', fecha: '2024-08-10', estado: 'Pendiente', productos: 7 },
])

const ordenesParaValidar = ref([
  { id: 10, nombre: 'Orden Centro Sur', solicitante: 'Centro 1', fecha: '2024-10-25' },
  { id: 11, nombre: 'Orden Centro Norte', solicitante: 'Centro 2', fecha: '2024-10-26' },
])

const ordenesRechazadas = ref([
  { id: 20, nombre: 'Orden Julio 2024', motivo: 'Productos sin stock', fechaRechazo: '2024-07-15' },
])

const handleCreateOrder = () => {
  if (!form.value.nombre) {
    alert('Por favor ingresa un nombre para la orden')
    return
  }
  if (form.value.productos.length === 0) {
    alert('Por favor selecciona al menos un producto')
    return
  }
  
  const newOrder = {
    id: Date.now(),
    nombre: form.value.nombre,
    fecha: new Date().toISOString().split('T')[0],
    estado: 'Pendiente',
    productos: form.value.productos.length
  }
  
  requerimientos.value.unshift(newOrder)
  
  // Reset form
  form.value.nombre = ''
  form.value.productos = []
  
  alert('Orden creada exitosamente')
  activeTab.value = 'historial'
}

const downloadFormatTemplate = () => {
  alert('Descargando formato de template...')
  // Aquí se descargaría el archivo real
}

const viewOrder = (id) => {
  alert(`Ver detalles de orden: ${id}`)
  // Navegar a la página de detalles
}

const editOrder = (id) => {
  alert(`Editar orden: ${id}`)
  // Navegar a la página de edición
}

const deleteOrder = (id) => {
  if (confirm('¿Estás seguro de que deseas eliminar esta orden?')) {
    requerimientos.value = requerimientos.value.filter(r => r.id !== id)
  }
}

const validarOrden = (id, aprobada) => {
  const orden = ordenesParaValidar.value.find(o => o.id === id)
  if (orden) {
    ordenesParaValidar.value = ordenesParaValidar.value.filter(o => o.id !== id)
    
    if (aprobada) {
      requerimientos.value.unshift({
        ...orden,
        estado: 'Aprobada',
        fecha: new Date().toISOString().split('T')[0]
      })
      alert('Orden aprobada')
    } else {
      ordenesRechazadas.value.unshift({
        ...orden,
        estado: 'Rechazada',
        motivo: 'Validación negada por el revisor',
        fechaRechazo: new Date().toISOString().split('T')[0]
      })
      alert('Orden rechazada')
    }
  }
}

const resubmitOrder = (id) => {
  const orden = ordenesRechazadas.value.find(o => o.id === id)
  if (orden) {
    ordenesRechazadas.value = ordenesRechazadas.value.filter(o => o.id !== id)
    requerimientos.value.unshift({
      ...orden,
      estado: 'Reenviada'
    })
    alert('Orden reenviada para validación')
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })
}

const getStatusClass = (estado) => {
  const classes = {
    'Pendiente': 'bg-yellow-100 text-yellow-800',
    'Aprobada': 'bg-green-100 text-green-800',
    'Entregada': 'bg-blue-100 text-blue-800',
    'Rechazada': 'bg-red-100 text-red-800',
    'Reenviada': 'bg-purple-100 text-purple-800'
  }
  return classes[estado] || 'bg-slate-100 text-slate-800'
}
</script>
