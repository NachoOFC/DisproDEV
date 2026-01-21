<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
          🔵 Verificación de Conexión a Base de Datos
        </h1>
        <p class="text-lg text-gray-600">
          ALOGIS - DisproDEV | PostgreSQL + Neon
        </p>
      </div>

      <!-- Estado de Conexión -->
      <div v-if="dbStatus" class="mb-8">
        <div :class="[
          'p-6 rounded-lg shadow-lg',
          dbStatus.success ? 'bg-green-50 border-2 border-green-500' : 'bg-red-50 border-2 border-red-500'
        ]">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-bold mb-2" :class="dbStatus.success ? 'text-green-800' : 'text-red-800'">
                {{ dbStatus.message }}
              </h2>
              <p class="text-sm text-gray-600">{{ dbStatus.timestamp }}</p>
            </div>
            <div class="text-6xl">
              {{ dbStatus.success ? '✅' : '❌' }}
            </div>
          </div>

          <div v-if="dbStatus.success && dbStatus.database" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-lg shadow">
              <p class="text-sm text-gray-600">Total de Tablas</p>
              <p class="text-3xl font-bold text-blue-600">{{ dbStatus.database.totalTablas }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
              <p class="text-sm text-gray-600">Base de Datos</p>
              <p class="text-xl font-bold text-indigo-600">Neon PostgreSQL</p>
            </div>
          </div>

          <div v-if="dbStatus.error" class="mt-4 p-4 bg-red-100 rounded">
            <p class="text-sm text-red-800">{{ dbStatus.error }}</p>
          </div>
        </div>
      </div>

      <!-- Estadísticas de Datos -->
      <div v-if="dbStatus?.success && dbStatus?.database?.estadisticas" class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">📊 Estadísticas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-blue-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">Productos</p>
                <p class="text-3xl font-bold text-blue-600">{{ dbStatus.database.estadisticas.productos }}</p>
              </div>
              <div class="text-4xl">📦</div>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-green-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">Clientes</p>
                <p class="text-3xl font-bold text-green-600">{{ dbStatus.database.estadisticas.clientes }}</p>
              </div>
              <div class="text-4xl">👔</div>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-purple-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">Usuarios</p>
                <p class="text-3xl font-bold text-purple-600">{{ dbStatus.database.estadisticas.usuarios }}</p>
              </div>
              <div class="text-4xl">👥</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pruebas de Endpoints -->
      <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">🧪 Pruebas de Endpoints</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <EndpointTest 
            v-for="endpoint in endpoints" 
            :key="endpoint.url"
            :endpoint="endpoint"
            @test="testEndpoint"
          />
        </div>
      </div>

      <!-- Datos de Ejemplo -->
      <div v-if="productos.length > 0" class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">📦 Productos en Base de Datos</h3>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="producto in productos" :key="producto.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ producto.codigo }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ producto.nombre }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ producto.precio }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ producto.stock }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Botón Refrescar -->
      <div class="text-center">
        <button 
          @click="loadData" 
          class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-200"
          :disabled="loading"
        >
          {{ loading ? '🔄 Cargando...' : '🔄 Refrescar Datos' }}
        </button>
      </div>

      <!-- Botón Volver -->
      <div class="text-center mt-6">
        <NuxtLink to="/" class="text-blue-600 hover:text-blue-800 font-medium">
          ← Volver al inicio
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const dbStatus = ref(null)
const productos = ref([])
const loading = ref(false)

const endpoints = ref([
  { name: 'Test DB', url: '/api/test-db', icon: '🔧', tested: false, success: false },
  { name: 'Productos', url: '/api/productos', icon: '📦', tested: false, success: false },
  { name: 'Usuarios', url: '/api/usuarios', icon: '👥', tested: false, success: false },
  { name: 'Facturas', url: '/api/facturas', icon: '💰', tested: false, success: false },
  { name: 'Clientes', url: '/api/clientes', icon: '👔', tested: false, success: false },
  { name: 'Centros', url: '/api/centros', icon: '📍', tested: false, success: false },
  { name: 'Empresas', url: '/api/empresas', icon: '🏢', tested: false, success: false }
])

const loadData = async () => {
  loading.value = true
  
  try {
    // Test DB
    const dbResponse = await fetch('/api/test-db')
    dbStatus.value = await dbResponse.json()

    // Productos
    const productosResponse = await fetch('/api/productos')
    const productosData = await productosResponse.json()
    productos.value = productosData.data || []

    // Test todos los endpoints
    for (const endpoint of endpoints.value) {
      await testEndpoint(endpoint)
    }
  } catch (error) {
    console.error('Error cargando datos:', error)
    dbStatus.value = {
      success: false,
      message: '❌ Error de conexión',
      error: error.message,
      timestamp: new Date().toISOString()
    }
  } finally {
    loading.value = false
  }
}

const testEndpoint = async (endpoint) => {
  try {
    const response = await fetch(endpoint.url)
    endpoint.tested = true
    endpoint.success = response.ok
    endpoint.statusCode = response.status
  } catch (error) {
    endpoint.tested = true
    endpoint.success = false
    endpoint.error = error.message
  }
}

onMounted(() => {
  loadData()
})
</script>

<script>
// Componente para probar endpoints
const EndpointTest = {
  name: 'EndpointTest',
  props: ['endpoint'],
  emits: ['test'],
  template: `
    <div :class="[
      'p-4 rounded-lg shadow-lg border-2',
      endpoint.tested ? (endpoint.success ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500') : 'bg-gray-50 border-gray-300'
    ]">
      <div class="flex items-center justify-between mb-2">
        <span class="text-2xl">{{ endpoint.icon }}</span>
        <span v-if="endpoint.tested" class="text-2xl">
          {{ endpoint.success ? '✅' : '❌' }}
        </span>
      </div>
      <p class="font-bold text-gray-900">{{ endpoint.name }}</p>
      <p class="text-xs text-gray-600 mb-2">{{ endpoint.url }}</p>
      <p v-if="endpoint.tested && endpoint.statusCode" class="text-xs font-medium" :class="endpoint.success ? 'text-green-700' : 'text-red-700'">
        Status: {{ endpoint.statusCode }}
      </p>
    </div>
  `
}

export default {
  components: {
    EndpointTest
  }
}
</script>
