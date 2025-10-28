<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Seguimiento de Pedidos</h1>
        <p class="text-slate-600">Monitorea el estado de tus pedidos en tiempo real</p>
      </div>

      <!-- Filtros -->
      <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Buscar por Orden</label>
            <input
              v-model="filtros.busqueda"
              type="text"
              placeholder="Ej: REQ-2024-001"
              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Estado</label>
            <select
              v-model="filtros.estado"
              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">-- Todos --</option>
              <option value="Pendiente">Pendiente</option>
              <option value="Procesando">Procesando</option>
              <option value="Despachado">Despachado</option>
              <option value="En Tránsito">En Tránsito</option>
              <option value="Entregado">Entregado</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Período</label>
            <select
              v-model="filtros.periodo"
              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">-- Todos --</option>
              <option value="hoy">Hoy</option>
              <option value="semana">Última Semana</option>
              <option value="mes">Último Mes</option>
              <option value="todo">Todo el Año</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Lista de Pedidos -->
      <div class="space-y-6">
        <div
          v-for="pedido in pedidosFiltrados"
          :key="pedido.id"
          class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow"
        >
          <!-- Header del Pedido -->
          <div class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white p-6">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="text-2xl font-bold">{{ pedido.numero }}</h3>
                <p class="text-blue-100">Pedido realizado el {{ formatDate(pedido.fechaCreacion) }}</p>
              </div>
              <span :class="['px-4 py-2 rounded-full font-semibold', getEstadoColorClass(pedido.estado)]">
                {{ pedido.estado }}
              </span>
            </div>
          </div>

          <!-- Contenido del Pedido -->
          <div class="p-6">
            <!-- Información General -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div>
                <p class="text-sm text-slate-600 mb-1">Centro Solicitante</p>
                <p class="font-semibold text-slate-900">{{ pedido.centro }}</p>
              </div>
              <div>
                <p class="text-sm text-slate-600 mb-1">Total de Productos</p>
                <p class="font-semibold text-slate-900">{{ pedido.productos.length }} artículos</p>
              </div>
              <div>
                <p class="text-sm text-slate-600 mb-1">Fecha Estimada de Entrega</p>
                <p class="font-semibold text-slate-900">{{ formatDate(pedido.fechaEstimada) }}</p>
              </div>
              <div>
                <p class="text-sm text-slate-600 mb-1">Total del Pedido</p>
                <p class="font-bold text-lg text-blue-600">${{ formatCurrency(pedido.total) }}</p>
              </div>
            </div>

            <!-- Línea de Tiempo del Estado -->
            <div class="mb-6 pb-6 border-b border-slate-200">
              <h4 class="font-semibold text-slate-900 mb-4">Estado del Pedido</h4>
              <div class="relative">
                <div class="absolute left-2 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 to-cyan-500"></div>
                
                <div
                  v-for="(estado, idx) in pedido.timeline"
                  :key="idx"
                  class="ml-10 mb-6 relative"
                >
                  <div :class="['absolute -left-8 top-2 w-5 h-5 rounded-full border-4 border-white', estado.completado ? 'bg-green-500' : 'bg-slate-300']"></div>
                  
                  <div :class="[estado.completado ? 'text-slate-900' : 'text-slate-500']">
                    <p class="font-semibold">{{ estado.nombre }}</p>
                    <p class="text-sm">{{ estado.fecha ? formatDate(estado.fecha) : 'Pendiente' }}</p>
                    <p v-if="estado.descripcion" class="text-sm text-slate-600 mt-1">{{ estado.descripcion }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Productos -->
            <div class="mb-6">
              <h4 class="font-semibold text-slate-900 mb-4">Productos Solicitados</h4>
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-slate-100 border-b border-slate-300">
                    <tr>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-slate-700">Producto</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-slate-700">Cantidad</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-slate-700">Precio Unit.</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-slate-700">Subtotal</th>
                      <th class="px-4 py-2 text-left text-sm font-semibold text-slate-700">Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="prod in pedido.productos" :key="prod.id" class="border-b border-slate-200 hover:bg-slate-50">
                      <td class="px-4 py-2 text-sm text-slate-900">{{ prod.nombre }}</td>
                      <td class="px-4 py-2 text-sm text-slate-900">{{ prod.cantidad }}</td>
                      <td class="px-4 py-2 text-sm text-slate-900">${{ formatCurrency(prod.precioUnitario) }}</td>
                      <td class="px-4 py-2 text-sm font-semibold text-slate-900">${{ formatCurrency(prod.subtotal) }}</td>
                      <td class="px-4 py-2 text-sm">
                        <span :class="['px-2 py-1 rounded text-xs font-semibold', getProdEstadoClass(prod.estado)]">
                          {{ prod.estado }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Detalles de Entrega -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
              <h4 class="font-semibold text-slate-900 mb-3">Detalles de Entrega</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-slate-600">Empresa Responsable</p>
                  <p class="font-semibold text-slate-900">{{ pedido.transporte }}</p>
                </div>
                <div>
                  <p class="text-sm text-slate-600">Número de Seguimiento</p>
                  <p class="font-semibold text-slate-900">{{ pedido.numeroSeguimiento }}</p>
                </div>
                <div v-if="pedido.conductor" class="md:col-span-2">
                  <p class="text-sm text-slate-600">Conductor / Contacto</p>
                  <p class="font-semibold text-slate-900">{{ pedido.conductor.nombre }} - {{ pedido.conductor.telefono }}</p>
                </div>
              </div>
            </div>

            <!-- Acciones -->
            <div class="flex gap-3 pt-6 border-t border-slate-200">
              <button
                @click="descargarDocumento(pedido.id)"
                class="flex-1 bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600 transition-colors"
              >
                <i class="fas fa-download mr-2"></i>
                Descargar Pedido
              </button>
              <button
                @click="verMapa(pedido.id)"
                class="flex-1 bg-green-500 text-white py-2 rounded-lg font-semibold hover:bg-green-600 transition-colors"
                v-if="pedido.estado === 'En Tránsito'"
              >
                <i class="fas fa-map-marker-alt mr-2"></i>
                Ver Ubicación
              </button>
              <button
                @click="contactarSoporte(pedido.id)"
                class="flex-1 bg-slate-500 text-white py-2 rounded-lg font-semibold hover:bg-slate-600 transition-colors"
              >
                <i class="fas fa-headset mr-2"></i>
                Soporte
              </button>
            </div>
          </div>
        </div>

        <div v-if="pedidosFiltrados.length === 0" class="bg-white rounded-xl shadow-lg p-12 text-center">
          <i class="fas fa-inbox text-6xl text-slate-300 mb-4"></i>
          <p class="text-xl text-slate-600">No hay pedidos que coincidan con los filtros</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const filtros = ref({
  busqueda: '',
  estado: '',
  periodo: ''
})

const pedidos = ref([
  {
    id: 1,
    numero: 'REQ-2024-001',
    centro: 'Centro Santiago Sur',
    estado: 'En Tránsito',
    fechaCreacion: '2024-10-15',
    fechaEstimada: '2024-10-28',
    total: 450000,
    transporte: 'Logística Express',
    numeroSeguimiento: 'LE-2024-1234567',
    conductor: { nombre: 'Juan García', telefono: '+56 9 8765 4321' },
    productos: [
      { id: 1, nombre: 'Producto A', cantidad: 50, precioUnitario: 5000, subtotal: 250000, estado: 'Despachado' },
      { id: 2, nombre: 'Producto B', cantidad: 40, precioUnitario: 5000, subtotal: 200000, estado: 'Despachado' },
    ],
    timeline: [
      { nombre: 'Orden Creada', fecha: '2024-10-15', completado: true, descripcion: 'Orden ingresada al sistema' },
      { nombre: 'Validada', fecha: '2024-10-16', completado: true, descripcion: 'Aprobada por gerencia' },
      { nombre: 'Procesada', fecha: '2024-10-17', completado: true, descripcion: 'Productos separados' },
      { nombre: 'Despachada', fecha: '2024-10-20', completado: true, descripcion: 'Transportista en ruta' },
      { nombre: 'En Tránsito', fecha: '2024-10-25', completado: true, descripcion: 'Camión en camino' },
      { nombre: 'Entregada', fecha: null, completado: false },
    ]
  },
  {
    id: 2,
    numero: 'REQ-2024-000',
    centro: 'Centro Valparaíso',
    estado: 'Entregado',
    fechaCreacion: '2024-10-10',
    fechaEstimada: '2024-10-22',
    total: 700000,
    transporte: 'Logística Express',
    numeroSeguimiento: 'LE-2024-0987654',
    conductor: { nombre: 'Carlos López', telefono: '+56 9 7654 3210' },
    productos: [
      { id: 3, nombre: 'Producto C', cantidad: 60, precioUnitario: 7000, subtotal: 420000, estado: 'Entregado' },
      { id: 4, nombre: 'Producto D', cantidad: 40, precioUnitario: 7000, subtotal: 280000, estado: 'Entregado' },
    ],
    timeline: [
      { nombre: 'Orden Creada', fecha: '2024-10-10', completado: true },
      { nombre: 'Validada', fecha: '2024-10-11', completado: true },
      { nombre: 'Procesada', fecha: '2024-10-12', completado: true },
      { nombre: 'Despachada', fecha: '2024-10-15', completado: true },
      { nombre: 'En Tránsito', fecha: '2024-10-18', completado: true },
      { nombre: 'Entregada', fecha: '2024-10-22', completado: true },
    ]
  },
])

const pedidosFiltrados = computed(() => {
  return pedidos.value.filter(pedido => {
    if (filtros.value.busqueda && !pedido.numero.includes(filtros.value.busqueda.toUpperCase())) {
      return false
    }
    if (filtros.value.estado && pedido.estado !== filtros.value.estado) {
      return false
    }
    return true
  })
})

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })
}

const formatCurrency = (value) => {
  return new Intl.NumberFormat('es-ES', { minimumFractionDigits: 0 }).format(value)
}

const getEstadoColorClass = (estado) => {
  const classes = {
    'Pendiente': 'bg-yellow-100 text-yellow-800',
    'Procesando': 'bg-blue-100 text-blue-800',
    'Despachado': 'bg-purple-100 text-purple-800',
    'En Tránsito': 'bg-orange-100 text-orange-800',
    'Entregado': 'bg-green-100 text-green-800'
  }
  return classes[estado] || 'bg-slate-100 text-slate-800'
}

const getProdEstadoClass = (estado) => {
  const classes = {
    'Pendiente': 'bg-yellow-100 text-yellow-800',
    'Despachado': 'bg-blue-100 text-blue-800',
    'En Tránsito': 'bg-orange-100 text-orange-800',
    'Entregado': 'bg-green-100 text-green-800'
  }
  return classes[estado] || 'bg-slate-100 text-slate-800'
}

const descargarDocumento = (id) => {
  alert(`Descargando documento del pedido ${id}...`)
}

const verMapa = (id) => {
  alert(`Abriendo mapa de ubicación para pedido ${id}...`)
}

const contactarSoporte = (id) => {
  alert(`Abriendo chat de soporte para pedido ${id}...`)
}
</script>
