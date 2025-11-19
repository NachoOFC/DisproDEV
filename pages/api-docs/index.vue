<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-12">
    <div class="max-w-7xl mx-auto px-4">
      <!-- Header -->
      <div class="mb-12 text-center">
        <NuxtLink to="/" class="inline-flex items-center text-cyan-400 hover:text-cyan-300 mb-6">
          <i class="fas fa-arrow-left mr-2"></i>
          Volver
        </NuxtLink>
        <h1 class="text-5xl font-bold text-white mb-2">🔌 API REST ALOGIS</h1>
        <p class="text-gray-400 text-lg">Prueba los endpoints en tiempo real</p>
      </div>

      <!-- Token Input -->
      <div class="mb-8 bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-700">
        <div class="flex gap-3 flex-wrap">
          <input
            v-model="globalToken"
            placeholder="Bearer token (opcional)"
            class="flex-1 min-w-64 px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-cyan-500"
          />
          <button @click="globalToken = ''" class="px-4 py-2 bg-gray-700 text-gray-200 rounded hover:bg-gray-600 font-semibold">Limpiar</button>
          <button @click="generateMockToken" class="px-4 py-2 bg-cyan-600 text-white rounded hover:bg-cyan-700 font-semibold">Generar Token</button>
        </div>
        <p v-if="globalToken" class="text-xs text-gray-400 mt-2">Token: <code class="text-cyan-300">{{ globalToken.substring(0, 20) }}...</code></p>
      </div>

      <!-- Endpoints Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        <!-- Productos GET -->
        <div class="bg-gray-800 rounded-xl shadow-lg border-l-4 border-blue-500 overflow-hidden hover:shadow-xl transition">
          <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-4">
            <div class="flex items-center gap-2 mb-2">
              <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-bold">GET</span>
              <code class="text-cyan-300 font-mono text-sm">/api/productos</code>
            </div>
            <p class="text-gray-300 text-sm">Listar Productos</p>
          </div>
          <div class="p-4 space-y-2">
            <button @click="callEndpoint('/api/productos', 'GET')" :disabled="loading" class="w-full bg-blue-500 hover:bg-blue-600 disabled:opacity-50 text-white py-2 px-4 rounded font-semibold text-sm transition">
              {{ loading && activeEndpoint === '/api/productos-GET' ? '⏳ Cargando...' : '▶ Probar' }}
            </button>
            <button @click="openInBrowser('/api/productos')" class="w-full bg-gray-700 hover:bg-gray-600 text-gray-200 py-2 px-4 rounded font-semibold text-sm transition">
              🔗 Abrir
            </button>
          </div>
        </div>

        <!-- Productos POST -->
        <div class="bg-gray-800 rounded-xl shadow-lg border-l-4 border-green-500 overflow-hidden hover:shadow-xl transition">
          <div class="bg-gradient-to-r from-green-900 to-green-800 p-4">
            <div class="flex items-center gap-2 mb-2">
              <span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-bold">POST</span>
              <code class="text-cyan-300 font-mono text-sm">/api/productos</code>
            </div>
            <p class="text-gray-300 text-sm">Crear Producto</p>
          </div>
          <div class="p-4 space-y-2">
            <input v-model="newProduct.nombre" placeholder="Nombre" class="w-full px-2 py-1 rounded bg-gray-900 border border-gray-700 text-white text-xs" />
            <input v-model="newProduct.sku" placeholder="SKU" class="w-full px-2 py-1 rounded bg-gray-900 border border-gray-700 text-white text-xs" />
            <input v-model.number="newProduct.precio" placeholder="Precio" type="number" class="w-full px-2 py-1 rounded bg-gray-900 border border-gray-700 text-white text-xs" />
            <button @click="callEndpoint('/api/productos', 'POST', newProduct)" :disabled="loading" class="w-full bg-green-500 hover:bg-green-600 disabled:opacity-50 text-white py-2 px-4 rounded font-semibold text-sm transition">
              {{ loading && activeEndpoint === '/api/productos-POST' ? '⏳ Creando...' : '➕ Crear' }}
            </button>
          </div>
        </div>

        <!-- Facturas GET -->
        <div class="bg-gray-800 rounded-xl shadow-lg border-l-4 border-indigo-500 overflow-hidden hover:shadow-xl transition">
          <div class="bg-gradient-to-r from-indigo-900 to-indigo-800 p-4">
            <div class="flex items-center gap-2 mb-2">
              <span class="bg-indigo-600 text-white px-2 py-1 rounded text-xs font-bold">GET</span>
              <code class="text-cyan-300 font-mono text-sm">/api/facturas</code>
            </div>
            <p class="text-gray-300 text-sm">Listar Facturas</p>
          </div>
          <div class="p-4 space-y-2">
            <button @click="callEndpoint('/api/facturas', 'GET')" :disabled="loading" class="w-full bg-indigo-500 hover:bg-indigo-600 disabled:opacity-50 text-white py-2 px-4 rounded font-semibold text-sm transition">
              {{ loading && activeEndpoint === '/api/facturas-GET' ? '⏳ Cargando...' : '▶ Probar' }}
            </button>
            <button @click="openInBrowser('/api/facturas')" class="w-full bg-gray-700 hover:bg-gray-600 text-gray-200 py-2 px-4 rounded font-semibold text-sm transition">
              🔗 Abrir
            </button>
          </div>
        </div>

        <!-- Facturas POST -->
        <div class="bg-gray-800 rounded-xl shadow-lg border-l-4 border-purple-500 overflow-hidden hover:shadow-xl transition">
          <div class="bg-gradient-to-r from-purple-900 to-purple-800 p-4">
            <div class="flex items-center gap-2 mb-2">
              <span class="bg-purple-600 text-white px-2 py-1 rounded text-xs font-bold">POST</span>
              <code class="text-cyan-300 font-mono text-sm">/api/facturas</code>
            </div>
            <p class="text-gray-300 text-sm">Crear Factura</p>
          </div>
          <div class="p-4 space-y-2">
            <input v-model="newFactura.cliente" placeholder="Cliente" class="w-full px-2 py-1 rounded bg-gray-900 border border-gray-700 text-white text-xs" />
            <input v-model="newFactura.folio" placeholder="Folio" class="w-full px-2 py-1 rounded bg-gray-900 border border-gray-700 text-white text-xs" />
            <input v-model.number="newFactura.monto" placeholder="Monto" type="number" class="w-full px-2 py-1 rounded bg-gray-900 border border-gray-700 text-white text-xs" />
            <button @click="callEndpoint('/api/facturas', 'POST', newFactura)" :disabled="loading" class="w-full bg-purple-500 hover:bg-purple-600 disabled:opacity-50 text-white py-2 px-4 rounded font-semibold text-sm transition">
              {{ loading && activeEndpoint === '/api/facturas-POST' ? '⏳ Creando...' : '➕ Crear' }}
            </button>
            <button @click="openInBrowser('/api/facturas')" class="w-full bg-gray-700 hover:bg-gray-600 text-gray-200 py-1 px-2 rounded font-semibold text-xs transition">
              🔗 Abrir
            </button>
          </div>
        </div>

        <!-- Usuarios GET -->
        <div class="bg-gray-800 rounded-xl shadow-lg border-l-4 border-pink-500 overflow-hidden hover:shadow-xl transition">
          <div class="bg-gradient-to-r from-pink-900 to-pink-800 p-4">
            <div class="flex items-center gap-2 mb-2">
              <span class="bg-pink-600 text-white px-2 py-1 rounded text-xs font-bold">GET</span>
              <code class="text-cyan-300 font-mono text-sm">/api/usuarios</code>
            </div>
            <p class="text-gray-300 text-sm">Listar Usuarios</p>
          </div>
          <div class="p-4 space-y-2">
            <button @click="callEndpoint('/api/usuarios', 'GET')" :disabled="loading" class="w-full bg-pink-500 hover:bg-pink-600 disabled:opacity-50 text-white py-2 px-4 rounded font-semibold text-sm transition">
              {{ loading && activeEndpoint === '/api/usuarios-GET' ? '⏳ Cargando...' : '▶ Probar' }}
            </button>
            <button @click="openInBrowser('/api/usuarios')" class="w-full bg-gray-700 hover:bg-gray-600 text-gray-200 py-2 px-4 rounded font-semibold text-sm transition">
              🔗 Abrir
            </button>
          </div>
        </div>

        <!-- Usuarios POST -->
        <div class="bg-gray-800 rounded-xl shadow-lg border-l-4 border-rose-500 overflow-hidden hover:shadow-xl transition">
          <div class="bg-gradient-to-r from-rose-900 to-rose-800 p-4">
            <div class="flex items-center gap-2 mb-2">
              <span class="bg-rose-600 text-white px-2 py-1 rounded text-xs font-bold">POST</span>
              <code class="text-cyan-300 font-mono text-sm">/api/usuarios</code>
            </div>
            <p class="text-gray-300 text-sm">Crear Usuario</p>
          </div>
          <div class="p-4 space-y-2">
            <input v-model="newUsuario.nombre" placeholder="Nombre" class="w-full px-2 py-1 rounded bg-gray-900 border border-gray-700 text-white text-xs" />
            <input v-model="newUsuario.email" placeholder="Email" class="w-full px-2 py-1 rounded bg-gray-900 border border-gray-700 text-white text-xs" />
            <input v-model="newUsuario.rol" placeholder="Rol" class="w-full px-2 py-1 rounded bg-gray-900 border border-gray-700 text-white text-xs" />
            <button @click="callEndpoint('/api/usuarios', 'POST', newUsuario)" :disabled="loading" class="w-full bg-rose-500 hover:bg-rose-600 disabled:opacity-50 text-white py-2 px-4 rounded font-semibold text-sm transition">
              {{ loading && activeEndpoint === '/api/usuarios-POST' ? '⏳ Creando...' : '➕ Crear' }}
            </button>
            <button @click="openInBrowser('/api/usuarios')" class="w-full bg-gray-700 hover:bg-gray-600 text-gray-200 py-1 px-2 rounded font-semibold text-xs transition">
              🔗 Abrir
            </button>
          </div>
        </div>
      </div>

      <!-- Response Viewer -->
      <div v-if="response || error" class="bg-gray-800 rounded-xl shadow-lg border border-gray-700 p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-bold text-white">📊 Respuesta JSON</h2>
          <button @click="clearResponse()" class="text-gray-400 hover:text-red-400 text-2xl">✕</button>
        </div>
        <div v-if="error" class="bg-red-900 border border-red-700 rounded p-3 mb-3">
          <p class="text-red-200 text-sm"><strong>❌ Error:</strong> {{ error }}</p>
        </div>
        <div class="bg-gray-900 rounded border border-gray-700 p-4 overflow-x-auto max-h-96">
          <pre v-if="response" class="text-cyan-300 font-mono text-xs whitespace-pre-wrap break-words">{{ JSON.stringify(response, null, 2) }}</pre>
        </div>
      </div>

      <!-- Info -->
      <div class="mt-8 bg-gradient-to-r from-cyan-900 to-blue-900 rounded-xl shadow-lg p-6 border border-cyan-700">
        <h3 class="text-lg font-bold text-cyan-300 mb-2">ℹ️ Endpoints Mock</h3>
        <ul class="text-gray-300 text-sm space-y-1">
          <li>✓ Sin autenticación requerida (token opcional)</li>
          <li>✓ Datos en memoria por sesión</li>
          <li>✓ Base: <code class="text-cyan-400 font-mono">http://localhost:3000/api</code></li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const globalToken = ref('')
const response = ref(null)
const error = ref(null)
const loading = ref(false)
const activeEndpoint = ref(null)

const newProduct = ref({ nombre: '', sku: '', precio: 0 })
const newFactura = ref({ cliente: '', folio: '', monto: 0 })
const newUsuario = ref({ nombre: '', email: '', rol: '' })

const generateMockToken = () => {
  globalToken.value = 'mock-token-' + Math.random().toString(36).substring(2, 15)
}

const callEndpoint = async (endpoint, method = 'GET', data = null) => {
  loading.value = true
  error.value = null
  response.value = null
  activeEndpoint.value = `${endpoint}-${method}`

  try {
    const options = { method, headers: {} }
    if (globalToken.value) {
      options.headers['Authorization'] = `Bearer ${globalToken.value.replace(/^Bearer\s+/i, '')}`
    }
    if (method === 'POST' && data) {
      options.headers['Content-Type'] = 'application/json'
      options.body = JSON.stringify(data)
    }

    const res = await fetch(endpoint, options)
    const json = await res.json()
    if (!res.ok) error.value = json.message || `HTTP ${res.status}`
    response.value = json
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

const clearResponse = () => {
  response.value = null
  error.value = null
  activeEndpoint.value = null
}

const openInBrowser = (path) => {
  const url = new URL(path, window.location.origin).toString()
  window.open(url, '_blank')
}
</script>
