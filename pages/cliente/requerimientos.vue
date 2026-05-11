<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Modal de Selección de Cliente (Admin) -->
      <div v-if="!clienteId" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4">
          <h3 class="text-2xl font-bold text-slate-900 mb-6">Seleccionar Cliente</h3>
          

          <select
            v-model="selectedClienteId"
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-6"
          >
            <option value="" disabled>Selecciona un cliente</option>
            <option v-for="c in clientes" :key="c.id" :value="c.id">
              {{ c.nombre }}
            </option>
          </select>
          <button
            @click="confirmCliente"
            :disabled="!selectedClienteId"
            class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 disabled:bg-slate-300 disabled:cursor-not-allowed transition-colors font-semibold"
          >
            Continuar
          </button>
        </div>
      </div>

      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Mis Órdenes de Pedido</h1>
        <p class="text-slate-600">Gestiona todas tus órdenes de pedido en un solo lugar</p>
        <p v-if="clienteId" class="text-sm text-blue-600 mt-2 flex items-center justify-between">
          <span>
            <i class="fas fa-user mr-1"></i>
            Cliente seleccionado: <strong>{{ clienteNombre }}</strong>
          </span>
          <button
            @click="clienteId = null; selectedClienteId = ''; sessionStorage.removeItem('clienteId')"
            class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200 transition-colors"
            type="button"
          >
            <i class="fas fa-exchange-alt mr-1"></i>
            Cambiar cliente
          </button>
        </p>
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
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Número</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Nombre</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Cliente</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Total</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Estado</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="loading" class="border-b border-slate-200">
                  <td colspan="8" class="px-6 py-8 text-center text-slate-600">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    Cargando órdenes desde PostgreSQL...
                  </td>
                </tr>
                <tr v-for="(orden, idx) in requerimientos" :key="orden.id" class="border-b border-slate-200 hover:bg-slate-50">
                  <td class="px-6 py-3 text-sm text-slate-900">{{ idx + 1 }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900 font-semibold">{{ orden.numero }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ orden.nombre || 'Sin nombre' }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ orden.cliente || 'Sin asignar' }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ formatDate(orden.fecha) }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">${{ orden.total?.toLocaleString() || '0' }}</td>
                  <td class="px-6 py-3 text-sm">
                    <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getStatusClass(orden.estado)]">
                      {{ orden.estado }}
                    </span>
                  </td>
                  <td class="px-6 py-3 text-sm">
                    <div class="flex items-center gap-3">
                      <button
                        @click="openViewModal(orden)"
                        class="text-blue-600 hover:text-blue-700 transition-colors text-base"
                        type="button"
                        title="Ver"
                        aria-label="Ver"
                      >
                        <i class="fas fa-eye" aria-hidden="true"></i>
                      </button>
                      <button
                        v-if="isPendiente(orden.estado)"
                        @click="editOrder(orden.id)"
                        class="text-amber-600 hover:text-amber-700 transition-colors text-base"
                        type="button"
                        title="Editar"
                        aria-label="Editar"
                      >
                        <i class="fas fa-edit" aria-hidden="true"></i>
                      </button>
                      <button
                        v-if="isRechazada(orden.estado)"
                        @click="reenviarOrder(orden.id)"
                        class="text-purple-600 hover:text-purple-700 transition-colors text-base"
                        type="button"
                        title="Reenviar"
                        aria-label="Reenviar"
                      >
                        <i class="fas fa-paper-plane" aria-hidden="true"></i>
                      </button>
                      <button
                        @click="openDeleteModal(orden)"
                        class="text-red-600 hover:text-red-700 transition-colors text-base"
                        type="button"
                        title="Eliminar"
                        aria-label="Eliminar"
                      >
                        <i class="fas fa-trash" aria-hidden="true"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="requerimientos.length === 0 && !loading" class="text-center py-8">
            <p class="text-slate-600 text-lg mb-2">
              <i class="fas fa-database mr-2"></i>
              No hay requerimientos para este cliente
            </p>
            <p class="text-sm text-slate-500">
              Los datos se cargan desde PostgreSQL. Ejecuta SEED_DATA.sql en Neon para agregar datos de prueba.
            </p>
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
            <h3 class="text-xl font-bold text-slate-900">Detalle de Orden</h3>
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

          <div v-if="viewingOrden" class="space-y-3 text-sm">
            <div class="flex justify-between gap-4">
              <span class="text-slate-500">Número</span>
              <span class="text-slate-900 font-semibold">{{ viewingOrden.numero }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-slate-500">Nombre</span>
              <span class="text-slate-900 font-semibold">{{ viewingOrden.nombre || 'Sin nombre' }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-slate-500">Cliente</span>
              <span class="text-slate-900 font-semibold">{{ viewingOrden.cliente || 'Sin asignar' }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-slate-500">Fecha</span>
              <span class="text-slate-900 font-semibold">{{ formatDate(viewingOrden.fecha) }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-slate-500">Total</span>
              <span class="text-slate-900 font-semibold">${{ viewingOrden.total?.toLocaleString?.() || viewingOrden.total || '0' }}</span>
            </div>
            <div class="flex justify-between gap-4 items-center">
              <span class="text-slate-500">Estado</span>
              <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getStatusClass(viewingOrden.estado)]">
                {{ viewingOrden.estado }}
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

      <!-- Modal: Confirmar Eliminación -->
      <div
        v-if="isDeleteModalOpen"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center"
        @click.self="closeDeleteModal"
      >
        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
          <div class="flex items-start justify-between gap-4 mb-3">
            <h3 class="text-xl font-bold text-slate-900">Eliminar Orden</h3>
            <button
              @click="closeDeleteModal"
              class="text-slate-400 hover:text-slate-600"
              type="button"
              aria-label="Cerrar"
              title="Cerrar"
            >
              <i class="fas fa-times" aria-hidden="true"></i>
            </button>
          </div>

          <p class="text-slate-700 text-sm mb-4">
            ¿Seguro que deseas eliminar esta orden? Esta acción la marcará como eliminada.
          </p>

          <div v-if="ordenToDelete" class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-sm mb-5">
            <div class="flex justify-between gap-4">
              <span class="text-slate-500">Número</span>
              <span class="text-slate-900 font-semibold">{{ ordenToDelete.numero }}</span>
            </div>
            <div class="flex justify-between gap-4 mt-1">
              <span class="text-slate-500">Estado</span>
              <span class="text-slate-900 font-semibold">{{ ordenToDelete.estado }}</span>
            </div>
          </div>

          <div class="flex gap-3 justify-end">
            <button
              @click="closeDeleteModal"
              class="bg-slate-200 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-300 transition-colors text-sm font-semibold"
              type="button"
            >
              Cancelar
            </button>
            <button
              @click="confirmDelete"
              class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors text-sm font-semibold"
              type="button"
              :disabled="deleting"
            >
              <span v-if="!deleting">Eliminar</span>
              <span v-else>
                <i class="fas fa-spinner fa-spin mr-2" aria-hidden="true"></i>
                Eliminando...
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'

const activeTab = ref('crear')
const selectedClienteId = ref('')
const clienteId = ref(null)
const clientes = ref([])

const tabs = [
  { id: 'crear', label: 'Nueva Orden', icon: 'fas fa-plus-circle' },
  { id: 'historial', label: 'Historial', icon: 'fas fa-history' }
]

const form = ref({
  nombre: '',
  numero: '',
  productos: []
})

// Datos desde BD PostgreSQL
const productos = ref([])
const requerimientos = ref([])
const loading = ref(false)

const isViewModalOpen = ref(false)
const viewingOrden = ref(null)

const isDeleteModalOpen = ref(false)
const ordenToDelete = ref(null)
const deleting = ref(false)

const clienteNombre = computed(() => {
  const selectedId = Number(clienteId.value)
  const cliente = clientes.value.find(c => Number(c.id) === selectedId)
  return cliente?.nombre || ''
})

// Generar número único para requerimiento
const generateNumero = () => {
  const timestamp = Date.now()
  return `REQ-${timestamp}`
}

// Cargar clientes
const loadClientes = async () => {
  try {
    const response = await fetch('/api/clientes')
    const data = await response.json()
    clientes.value = (data.data || []).map(c => ({
      ...c,
      id: Number(c.id)
    }))
  } catch (error) {
    console.error('Error cargando clientes:', error)
    useToast().error('No se pudieron cargar los clientes')
  }
}

// Cargar productos desde la BD
const loadProductos = async () => {
  try {
    const response = await fetch('/api/productos')
    const data = await response.json()
    productos.value = data.data || []
  } catch (error) {
    console.error('Error cargando productos:', error)
    useToast().error('No se pudieron cargar los productos')
  }
}

// Cargar requerimientos desde la BD (filtrados por clienteId si es necesario)
const loadRequerimientos = async () => {
  try {
    loading.value = true
    const response = await fetch('/api/requerimientos')
    const data = await response.json()

    const normalizarEstado = (estado) => String(estado || '').toLowerCase().trim()
    const mostrarEnHistorialCliente = (estado) => {
      const e = normalizarEstado(estado)
      return e === 'pendiente' || e === 'aprobada' || e === 'rechazada' || e === 'reenviada'
    }
    
    // Si hay cliente seleccionado, filtrar solo sus requerimientos
    if (clienteId.value) {
      requerimientos.value = (data.data || [])
        .filter(r => Number(r.cliente_id) === Number(clienteId.value))
        .filter(r => mostrarEnHistorialCliente(r.estado))
    } else {
      requerimientos.value = (data.data || []).filter(r => mostrarEnHistorialCliente(r.estado))
    }
  } catch (error) {
    console.error('Error cargando requerimientos:', error)
    useToast().error('No se pudieron cargar los requerimientos')
  } finally {
    loading.value = false
  }
}

const confirmCliente = () => {
  if (!selectedClienteId.value) return
  clienteId.value = Number(selectedClienteId.value)
  activeTab.value = 'historial'
}

// Cargar datos al montar el componente
onMounted(async () => {
  form.value.numero = generateNumero()
  await loadClientes()
  await loadProductos()
  
  // Restaurar cliente guardado SOLO si existe en la lista
  const savedClienteId = sessionStorage.getItem('clienteId')
  
  if (savedClienteId) {
    const savedId = Number(savedClienteId)
    const clienteExists = clientes.value.find(c => Number(c.id) === savedId)
    
    if (clienteExists) {
      clienteId.value = savedId
      selectedClienteId.value = savedId
      activeTab.value = 'historial'
      await loadRequerimientos()
    } else {
      // Si el cliente no existe, limpiar y resetear
      sessionStorage.clear()
      clienteId.value = null
      selectedClienteId.value = ''
    }
  }
})

// Cuando cambia el cliente, recargar requerimientos y guardar en sessionStorage
watch(clienteId, (newClienteId) => {
  if (newClienteId) {
    sessionStorage.setItem('clienteId', newClienteId)
    loadRequerimientos()
  } else {
    sessionStorage.removeItem('clienteId')
  }
})

const handleCreateOrder = async () => {
  const toast = useToast()
  
  if (!clienteId.value) {
    toast.warning('Selecciona un cliente primero')
    return
  }
  if (!form.value.nombre) {
    toast.warning('Por favor ingresa un nombre para la orden')
    return
  }
  if (form.value.productos.length === 0) {
    toast.warning('Por favor selecciona al menos un producto')
    return
  }
  
  try {
    const total = form.value.productos.reduce((sum, prodId) => {
      const prod = productos.value.find(p => p.id === prodId)
      return sum + (prod?.precio || 0)
    }, 0)

    const response = await fetch('/api/requerimientos', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        cliente_id: clienteId.value,
        numero: form.value.numero,
        nombre: form.value.nombre,
        fecha: new Date().toISOString().split('T')[0],
        total,
        estado: 'pendiente'
      })
    })

    const data = await response.json()
    if (!response.ok || data?.statusCode) {
      const errorMsg = data?.error || data?.errors?.[0] || data?.message || 'Error desconocido'
      throw new Error(errorMsg)
    }

    await loadRequerimientos()
    
    // Reset form
    form.value.nombre = ''
    form.value.numero = generateNumero()
    form.value.productos = []
    
    toast.success('Orden creada exitosamente')
    activeTab.value = 'historial'
  } catch (error) {
    console.error('Error creando orden:', error)
    toast.error(`Error: ${error.message}`)
  }
}

const downloadFormatTemplate = () => {
  useToast().info('Descarga de template: pendiente de implementar')
}

const openViewModal = (orden) => {
  viewingOrden.value = orden || null
  isViewModalOpen.value = true
}

const closeViewModal = () => {
  isViewModalOpen.value = false
  viewingOrden.value = null
}

const editOrder = (id) => {
  useToast().info(`Editar orden: ${id}`)
}

const openDeleteModal = (orden) => {
  ordenToDelete.value = orden || null
  isDeleteModalOpen.value = true
}

const closeDeleteModal = () => {
  if (deleting.value) return
  isDeleteModalOpen.value = false
  ordenToDelete.value = null
}

const confirmDelete = async () => {
  const toast = useToast()
  const id = ordenToDelete.value?.id
  if (!id) return

  try {
    deleting.value = true
    const response = await fetch(`/api/requerimientos/${id}`, { method: 'DELETE' })
    if (!response.ok) {
      throw new Error('No se pudo eliminar la orden')
    }

    await loadRequerimientos()
    closeDeleteModal()
    toast.success('Orden eliminada')
  } catch (error) {
    console.error('Error eliminando orden:', error)
    toast.error(`Error: ${error.message}`)
  } finally {
    deleting.value = false
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })
}

const isPendiente = (estado) => String(estado || '').toLowerCase().trim() === 'pendiente'

const isRechazada = (estado) => {
  const e = String(estado || '').toLowerCase().trim()
  return e === 'rechazada' || e === 'rechazado'
}

const reenviarOrder = async (id) => {
  const toast = useToast()
  try {
    const response = await fetch(`/api/requerimientos/${id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ estado: 'reenviada' })
    })

    const data = await response.json()
    if (!response.ok || data?.success === false || data?.statusCode) {
      const msg = data?.errors?.[0] || data?.message || 'No se pudo reenviar el requerimiento'
      throw new Error(msg)
    }

    await loadRequerimientos()
    toast.success('Requerimiento reenviado')
  } catch (error) {
    console.error('Error reenviando requerimiento:', error)
    toast.error(`Error: ${error.message || error}`)
  }
}

const getStatusClass = (estado) => {
  const classes = {
    'Pendiente': 'bg-yellow-100 text-yellow-800',
    'pendiente': 'bg-yellow-100 text-yellow-800',
    'Aprobada': 'bg-green-100 text-green-800',
    'aprobada': 'bg-green-100 text-green-800',
    'Entregada': 'bg-blue-100 text-blue-800',
    'entregada': 'bg-blue-100 text-blue-800',
    'Rechazada': 'bg-red-100 text-red-800',
    'rechazada': 'bg-red-100 text-red-800',
    'Reenviada': 'bg-purple-100 text-purple-800',
    'reenviada': 'bg-purple-100 text-purple-800'
  }
  return classes[estado] || 'bg-slate-100 text-slate-800'
}
</script>
