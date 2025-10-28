<template>
  <div class="min-h-screen bg-gradient-to-br from-lime-50 to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <NuxtLink to="/" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-4">
          <i class="fas fa-arrow-left mr-2"></i>
          Volver al Inicio
        </NuxtLink>
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Carga Inicial de Datos</h1>
        <p class="text-slate-600">Importa datos maestros desde archivos</p>
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
              ? 'bg-lime-500 text-white shadow-lg'
              : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'
          ]"
        >
          <i :class="tab.icon" class="mr-2"></i>
          {{ tab.label }}
        </button>
      </div>

      <!-- Contenido -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Cargar Datos -->
        <div v-if="activeTab === 'cargar'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Cargar Datos Maestros</h2>
          
          <div class="bg-lime-50 border-l-4 border-lime-500 p-4 rounded mb-6">
            <p class="text-sm text-lime-800">
              <i class="fas fa-info-circle mr-2"></i>
              Sube archivos CSV o Excel con los datos maestros. Los campos deben coincidir con el formato especificado.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div
              v-for="tipo in tiposCarga"
              :key="tipo.id"
              class="border-2 border-dashed border-slate-300 rounded-lg p-6 hover:border-lime-500 hover:bg-lime-50 transition"
            >
              <i :class="tipo.icon" class="text-3xl text-lime-500 mb-3 block"></i>
              <h3 class="font-semibold text-slate-900 mb-2">{{ tipo.nombre }}</h3>
              <p class="text-sm text-slate-600 mb-4">{{ tipo.descripcion }}</p>
              
              <div class="mb-4">
                <label class="block text-xs font-medium text-slate-700 mb-2">Campos Requeridos:</label>
                <ul class="text-xs text-slate-600 space-y-1 list-disc list-inside">
                  <li v-for="campo in tipo.campos" :key="campo">
                    {{ campo }}
                  </li>
                </ul>
              </div>

              <button
                @click="seleccionarTipoCarga(tipo.id)"
                class="w-full bg-lime-500 text-white py-2 rounded-lg font-semibold hover:bg-lime-600 transition"
              >
                <i class="fas fa-upload mr-2"></i>
                Seleccionar Archivo
              </button>
              <input
                type="file"
                ref="fileInputs"
                @change="manejarCarga"
                accept=".csv,.xlsx"
                style="display: none"
              />
            </div>
          </div>

          <!-- Vista Previa de Carga -->
          <div v-if="previewCarga" class="border border-slate-200 rounded-lg p-6 mb-6">
            <h3 class="font-semibold text-slate-900 mb-4">Vista Previa</h3>
            
            <div class="overflow-x-auto mb-4 max-h-64">
              <table class="w-full text-sm">
                <thead class="bg-slate-100 sticky top-0">
                  <tr>
                    <th v-for="col in previewColumnas" :key="col" class="px-3 py-2 text-left">
                      {{ col }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(row, idx) in previewFilas.slice(0, 5)" :key="idx" class="border-b">
                    <td v-for="col in previewColumnas" :key="col" class="px-3 py-2">
                      {{ row[col] }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <p class="text-xs text-slate-600 mb-4">
              Total de registros a importar: {{ previewFilas.length }}
            </p>

            <div class="flex gap-3">
              <button
                @click="cancelarCarga"
                class="flex-1 bg-slate-200 text-slate-700 py-2 rounded-lg font-semibold hover:bg-slate-300"
              >
                Cancelar
              </button>
              <button
                @click="confirmarCarga"
                class="flex-1 bg-lime-500 text-white py-2 rounded-lg font-semibold hover:bg-lime-600"
              >
                <i class="fas fa-check mr-2"></i>
                Confirmar Importación
              </button>
            </div>
          </div>
        </div>

        <!-- Historial de Cargas -->
        <div v-if="activeTab === 'historial'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Historial de Cargas</h2>
          
          <div class="space-y-4">
            <div
              v-for="carga in cargas"
              :key="carga.id"
              class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <div class="flex justify-between items-start mb-2">
                <div>
                  <h3 class="font-semibold text-slate-900">{{ carga.tipo }}</h3>
                  <p class="text-sm text-slate-600">{{ formatDate(carga.fecha) }} - {{ carga.hora }}</p>
                </div>
                <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getEstadoClass(carga.estado)]">
                  {{ carga.estado }}
                </span>
              </div>

              <div class="grid grid-cols-3 gap-4 text-sm mb-3">
                <div>
                  <p class="text-slate-600">Registros</p>
                  <p class="font-semibold text-slate-900">{{ carga.registros }}</p>
                </div>
                <div>
                  <p class="text-slate-600">Exitosos</p>
                  <p class="font-semibold text-green-600">{{ carga.exitosos }}</p>
                </div>
                <div>
                  <p class="text-slate-600">Errores</p>
                  <p class="font-semibold text-red-600">{{ carga.errores }}</p>
                </div>
              </div>

              <button v-if="carga.errores > 0" class="text-red-500 hover:text-red-700 text-sm font-semibold">
                <i class="fas fa-download mr-1"></i> Descargar Errores
              </button>
            </div>
          </div>

          <div v-if="cargas.length === 0" class="text-center py-8">
            <p class="text-slate-600">No hay cargas registradas</p>
          </div>
        </div>

        <!-- Plantillas -->
        <div v-if="activeTab === 'plantillas'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Descargar Plantillas</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
              v-for="plantilla in plantillas"
              :key="plantilla.id"
              class="border border-slate-200 rounded-lg p-6 hover:shadow-md transition-shadow"
            >
              <i :class="plantilla.icon" class="text-3xl text-lime-500 mb-3 block"></i>
              <h3 class="font-semibold text-slate-900 mb-2">{{ plantilla.nombre }}</h3>
              <p class="text-sm text-slate-600 mb-4">{{ plantilla.descripcion }}</p>
              
              <button class="w-full text-lime-500 hover:text-lime-700 font-semibold py-2 border border-lime-500 rounded-lg hover:bg-lime-50 transition">
                <i class="fas fa-download mr-2"></i>
                Descargar {{ plantilla.formato }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const activeTab = ref('cargar')
const previewCarga = ref(false)
const previewColumnas = ref([])
const previewFilas = ref([])
const tipoCargarSeleccionado = ref(null)

const tabs = [
  { id: 'cargar', label: 'Cargar Datos', icon: 'fas fa-upload' },
  { id: 'historial', label: 'Historial', icon: 'fas fa-history' },
  { id: 'plantillas', label: 'Plantillas', icon: 'fas fa-file-template' }
]

const tiposCarga = [
  {
    id: 'productos',
    nombre: 'Productos',
    descripcion: 'Importar catálogo de productos',
    icon: 'fas fa-box',
    campos: ['Código', 'Nombre', 'Categoría', 'Precio Unitario']
  },
  {
    id: 'proveedores',
    nombre: 'Proveedores',
    descripcion: 'Importar lista de proveedores',
    icon: 'fas fa-building',
    campos: ['RUT', 'Nombre', 'Contacto', 'Email', 'Teléfono']
  },
  {
    id: 'clientes',
    nombre: 'Clientes',
    descripcion: 'Importar base de clientes',
    icon: 'fas fa-users',
    campos: ['RUT', 'Nombre', 'Dirección', 'Email', 'Teléfono']
  },
  {
    id: 'inventario',
    nombre: 'Inventario Inicial',
    descripcion: 'Importar stock inicial',
    icon: 'fas fa-warehouse',
    campos: ['Código Producto', 'Centro', 'Stock', 'Valor Unitario']
  }
]

const cargas = ref([
  {
    id: 1,
    tipo: 'Productos',
    fecha: '2024-10-20',
    hora: '09:30',
    estado: 'Completada',
    registros: 150,
    exitosos: 150,
    errores: 0
  },
  {
    id: 2,
    tipo: 'Proveedores',
    fecha: '2024-10-18',
    hora: '14:15',
    estado: 'Completada con Errores',
    registros: 45,
    exitosos: 43,
    errores: 2
  },
])

const plantillas = [
  {
    id: 1,
    nombre: 'Productos',
    descripcion: 'Plantilla para importación de productos',
    formato: 'CSV',
    icon: 'fas fa-file-csv'
  },
  {
    id: 2,
    nombre: 'Proveedores',
    descripcion: 'Plantilla para importación de proveedores',
    formato: 'Excel',
    icon: 'fas fa-file-excel'
  },
  {
    id: 3,
    nombre: 'Clientes',
    descripcion: 'Plantilla para importación de clientes',
    formato: 'CSV',
    icon: 'fas fa-file-csv'
  },
  {
    id: 4,
    nombre: 'Inventario',
    descripcion: 'Plantilla para inventario inicial',
    formato: 'Excel',
    icon: 'fas fa-file-excel'
  }
]

const seleccionarTipoCarga = (tipoId) => {
  tipoCargarSeleccionado.value = tipoId
  // Aquí iría el diálogo de selección de archivo
}

const manejarCarga = () => {
  // Simular carga de archivo
  previewColumnas.value = ['Código', 'Nombre', 'Categoría', 'Precio']
  previewFilas.value = [
    { 'Código': 'P001', 'Nombre': 'Bidón 20L', 'Categoría': 'Envases', 'Precio': '5000' },
    { 'Código': 'P002', 'Nombre': 'Bidón 10L', 'Categoría': 'Envases', 'Precio': '3000' },
    { 'Código': 'P003', 'Nombre': 'Bidón 5L', 'Categoría': 'Envases', 'Precio': '2000' },
  ]
  previewCarga.value = true
}

const confirmarCarga = () => {
  alert('Datos importados exitosamente')
  cancelarCarga()
  activeTab.value = 'historial'
}

const cancelarCarga = () => {
  previewCarga.value = false
  previewColumnas.value = []
  previewFilas.value = []
  tipoCargarSeleccionado.value = null
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })
}

const getEstadoClass = (estado) => {
  const classes = {
    'Completada': 'bg-green-100 text-green-800',
    'Completada con Errores': 'bg-yellow-100 text-yellow-800',
    'Fallida': 'bg-red-100 text-red-800',
    'En Progreso': 'bg-blue-100 text-blue-800'
  }
  return classes[estado] || 'bg-slate-100 text-slate-800'
}
</script>
