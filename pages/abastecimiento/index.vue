<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <NuxtLink to="/" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-4">
          <i class="fas fa-arrow-left mr-2"></i>
          Volver al Inicio
        </NuxtLink>
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Abastecimiento</h1>
        <p class="text-slate-600">Gestiona compras a proveedores y órdenes de compra</p>
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
              ? 'bg-purple-500 text-white shadow-lg'
              : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'
          ]"
        >
          <i :class="tab.icon" class="mr-2"></i>
          {{ tab.label }}
        </button>
      </div>

      <!-- Contenido -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Crear Orden de Compra -->
        <div v-if="activeTab === 'crear'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Nueva Orden de Compra</h2>
          
          <form @submit.prevent="crearOrdenCompra" class="max-w-2xl space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Proveedor</label>
                <select
                  v-model="formularioOC.proveedor"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                  required
                >
                  <option value="">-- Selecciona proveedor --</option>
                  <option value="Proveedor A">Proveedor A</option>
                  <option value="Proveedor B">Proveedor B</option>
                  <option value="Proveedor C">Proveedor C</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Centro de Distribución</label>
                <select
                  v-model="formularioOC.centro"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                  required
                >
                  <option value="">-- Selecciona centro --</option>
                  <option value="Centro Santiago">Centro Santiago</option>
                  <option value="Centro Valparaíso">Centro Valparaíso</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Fecha Orden</label>
                <input
                  v-model="formularioOC.fechaOrden"
                  type="date"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Fecha Entrega Esperada</label>
                <input
                  v-model="formularioOC.fechaEntrega"
                  type="date"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                  required
                />
              </div>
            </div>

            <div class="border-t pt-6">
              <h3 class="font-semibold text-slate-900 mb-4">Productos</h3>
              
              <div class="space-y-3 mb-4">
                <div v-for="(item, idx) in formularioOC.items" :key="idx" class="border border-slate-200 p-4 rounded-lg">
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
                    <select
                      v-model="item.producto"
                      class="px-3 py-2 border border-slate-300 rounded text-sm"
                    >
                      <option value="">-- Producto --</option>
                      <option value="Bidón 20L">Bidón 20L</option>
                      <option value="Bidón 10L">Bidón 10L</option>
                      <option value="Bidón 5L">Bidón 5L</option>
                    </select>
                    <input
                      v-model.number="item.cantidad"
                      type="number"
                      placeholder="Cantidad"
                      class="px-3 py-2 border border-slate-300 rounded text-sm"
                    />
                    <input
                      v-model.number="item.valorUnitario"
                      type="number"
                      placeholder="Valor Unitario"
                      class="px-3 py-2 border border-slate-300 rounded text-sm"
                    />
                    <div class="text-right text-sm font-semibold">
                      Total: ${{ formatCurrency(item.cantidad * item.valorUnitario) }}
                    </div>
                  </div>
                  <button
                    type="button"
                    @click="formularioOC.items.splice(idx, 1)"
                    class="text-red-500 hover:text-red-700 text-sm"
                  >
                    <i class="fas fa-trash mr-1"></i> Eliminar
                  </button>
                </div>
              </div>

              <button
                type="button"
                @click="formularioOC.items.push({ producto: '', cantidad: 0, valorUnitario: 0 })"
                class="text-blue-500 hover:text-blue-700 text-sm font-semibold mb-6"
              >
                <i class="fas fa-plus mr-1"></i> Agregar Producto
              </button>
            </div>

            <div class="bg-slate-100 p-4 rounded-lg">
              <div class="text-right">
                <p class="text-slate-600 text-sm">Total OC</p>
                <p class="text-3xl font-bold text-purple-600">${{ formatCurrency(totalOC) }}</p>
              </div>
            </div>

            <div class="flex gap-3 pt-6 border-t border-slate-200">
              <button
                type="button"
                @click="activeTab = 'ordenes'"
                class="flex-1 bg-slate-200 text-slate-700 py-2 rounded-lg font-semibold hover:bg-slate-300"
              >
                Cancelar
              </button>
              <button
                type="submit"
                class="flex-1 bg-purple-500 text-white py-2 rounded-lg font-semibold hover:bg-purple-600"
              >
                <i class="fas fa-save mr-2"></i>
                Crear Orden
              </button>
            </div>
          </form>
        </div>

        <!-- Órdenes de Compra -->
        <div v-if="activeTab === 'ordenes'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Órdenes de Compra</h2>
          
          <div class="mb-4 flex gap-2">
            <input
              v-model="filtroOC"
              type="text"
              placeholder="Buscar orden..."
              class="flex-1 px-4 py-2 border border-slate-300 rounded-lg"
            />
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-slate-100 border-b border-slate-300">
                <tr>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Orden</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Proveedor</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Centro</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Fecha</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Total</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Estado</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="oc in ordenesFiltradas" :key="oc.id" class="border-b border-slate-200 hover:bg-slate-50">
                  <td class="px-6 py-3 text-sm font-semibold text-purple-600">{{ oc.numero }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ oc.proveedor }}</td>
                  <td class="px-6 py-3 text-sm text-slate-900">{{ oc.centro }}</td>
                  <td class="px-6 py-3 text-sm text-slate-600">{{ formatDate(oc.fecha) }}</td>
                  <td class="px-6 py-3 text-sm font-semibold text-slate-900">${{ formatCurrency(oc.total) }}</td>
                  <td class="px-6 py-3 text-sm">
                    <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getEstadoClass(oc.estado)]">
                      {{ oc.estado }}
                    </span>
                  </td>
                  <td class="px-6 py-3 text-sm space-x-2">
                    <button class="text-blue-500 hover:text-blue-700">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button v-if="oc.estado === 'Pendiente'" class="text-green-500 hover:text-green-700">
                      <i class="fas fa-check"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Gestión de Proveedores -->
        <div v-if="activeTab === 'proveedores'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Gestión de Proveedores</h2>
          
          <button
            @click="mostrarFormProveedor = !mostrarFormProveedor"
            class="mb-6 bg-purple-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-purple-600"
          >
            <i class="fas fa-plus mr-2"></i> Nuevo Proveedor
          </button>

          <!-- Form Nuevo Proveedor -->
          <div v-if="mostrarFormProveedor" class="mb-8 bg-purple-50 p-6 rounded-lg border border-purple-200">
            <h3 class="font-semibold text-slate-900 mb-4">Agregar Proveedor</h3>
            
            <form @submit.prevent="agregarProveedor" class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input
                  v-model="nuevoProveedor.nombre"
                  type="text"
                  placeholder="Nombre del Proveedor"
                  class="px-4 py-2 border border-slate-300 rounded-lg"
                  required
                />
                <input
                  v-model="nuevoProveedor.contacto"
                  type="text"
                  placeholder="Persona de Contacto"
                  class="px-4 py-2 border border-slate-300 rounded-lg"
                />
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input
                  v-model="nuevoProveedor.email"
                  type="email"
                  placeholder="Email"
                  class="px-4 py-2 border border-slate-300 rounded-lg"
                />
                <input
                  v-model="nuevoProveedor.telefono"
                  type="tel"
                  placeholder="Teléfono"
                  class="px-4 py-2 border border-slate-300 rounded-lg"
                />
              </div>

              <textarea
                v-model="nuevoProveedor.direccion"
                placeholder="Dirección"
                rows="2"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg"
              ></textarea>

              <div class="flex gap-3">
                <button
                  type="button"
                  @click="mostrarFormProveedor = false"
                  class="flex-1 bg-slate-200 text-slate-700 py-2 rounded-lg font-semibold hover:bg-slate-300"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  class="flex-1 bg-purple-500 text-white py-2 rounded-lg font-semibold hover:bg-purple-600"
                >
                  Agregar Proveedor
                </button>
              </div>
            </form>
          </div>

          <!-- Lista de Proveedores -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="prov in proveedores"
              :key="prov.id"
              class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <div class="flex justify-between items-start mb-3">
                <h3 class="font-semibold text-slate-900">{{ prov.nombre }}</h3>
                <i class="fas fa-star text-yellow-400"></i>
              </div>
              <p class="text-sm text-slate-600 mb-2">{{ prov.contacto }}</p>
              <p class="text-xs text-slate-500 mb-2">
                <i class="fas fa-envelope mr-1"></i> {{ prov.email }}
              </p>
              <p class="text-xs text-slate-500 mb-3">
                <i class="fas fa-phone mr-1"></i> {{ prov.telefono }}
              </p>
              <p class="text-xs text-slate-600 mb-4">{{ prov.direccion }}</p>
              <div class="flex gap-2 text-sm">
                <button class="text-blue-500 hover:text-blue-700 flex-1">
                  <i class="fas fa-edit mr-1"></i> Editar
                </button>
                <button class="text-red-500 hover:text-red-700 flex-1">
                  <i class="fas fa-trash mr-1"></i> Eliminar
                </button>
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
const filtroOC = ref('')
const mostrarFormProveedor = ref(false)

const tabs = [
  { id: 'crear', label: 'Crear OC', icon: 'fas fa-file-invoice' },
  { id: 'ordenes', label: 'Órdenes', icon: 'fas fa-list' },
  { id: 'proveedores', label: 'Proveedores', icon: 'fas fa-building' }
]

const formularioOC = ref({
  proveedor: '',
  centro: '',
  fechaOrden: new Date().toISOString().split('T')[0],
  fechaEntrega: new Date(Date.now() + 7*24*60*60*1000).toISOString().split('T')[0],
  items: [
    { producto: '', cantidad: 0, valorUnitario: 0 }
  ]
})

const nuevoProveedor = ref({
  nombre: '',
  contacto: '',
  email: '',
  telefono: '',
  direccion: ''
})

const ordenesCompra = ref([
  {
    id: 1,
    numero: 'OC-2024-001',
    proveedor: 'Proveedor A',
    centro: 'Centro Santiago',
    fecha: '2024-10-20',
    total: 1200000,
    estado: 'Entregada'
  },
  {
    id: 2,
    numero: 'OC-2024-002',
    proveedor: 'Proveedor B',
    centro: 'Centro Valparaíso',
    fecha: '2024-10-22',
    total: 850000,
    estado: 'En Tránsito'
  },
])

const proveedores = ref([
  {
    id: 1,
    nombre: 'Proveedor A',
    contacto: 'Juan Pérez',
    email: 'juan@proveedora.com',
    telefono: '+56 9 1234 5678',
    direccion: 'Av. Principal 123, Santiago'
  },
  {
    id: 2,
    nombre: 'Proveedor B',
    contacto: 'María García',
    email: 'maria@proveedorb.com',
    telefono: '+56 9 8765 4321',
    direccion: 'Calle Secundaria 456, Valparaíso'
  },
])

const totalOC = computed(() => {
  return formularioOC.value.items.reduce((sum, item) => sum + (item.cantidad * item.valorUnitario), 0)
})

const ordenesFiltradas = computed(() => {
  return ordenesCompra.value.filter(oc =>
    oc.numero.toLowerCase().includes(filtroOC.value.toLowerCase()) ||
    oc.proveedor.toLowerCase().includes(filtroOC.value.toLowerCase())
  )
})

const crearOrdenCompra = () => {
  if (!formularioOC.value.proveedor || !formularioOC.value.centro) {
    alert('Por favor completa todos los campos requeridos')
    return
  }

  alert('Orden de compra creada exitosamente')

  formularioOC.value = {
    proveedor: '',
    centro: '',
    fechaOrden: new Date().toISOString().split('T')[0],
    fechaEntrega: new Date(Date.now() + 7*24*60*60*1000).toISOString().split('T')[0],
    items: [{ producto: '', cantidad: 0, valorUnitario: 0 }]
  }

  activeTab.value = 'ordenes'
}

const agregarProveedor = () => {
  if (!nuevoProveedor.value.nombre || !nuevoProveedor.value.email) {
    alert('Por favor completa los campos requeridos')
    return
  }

  proveedores.value.push({
    id: proveedores.value.length + 1,
    ...nuevoProveedor.value
  })

  nuevoProveedor.value = {
    nombre: '',
    contacto: '',
    email: '',
    telefono: '',
    direccion: ''
  }

  mostrarFormProveedor.value = false
  alert('Proveedor agregado exitosamente')
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
    'Entregada': 'bg-green-100 text-green-800',
    'En Tránsito': 'bg-blue-100 text-blue-800',
    'Pendiente': 'bg-yellow-100 text-yellow-800',
    'Cancelada': 'bg-red-100 text-red-800'
  }
  return classes[estado] || 'bg-slate-100 text-slate-800'
}
</script>
