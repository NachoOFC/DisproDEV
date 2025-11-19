<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <NuxtLink to="/" class="inline-flex items-center text-cyan-400 hover:text-cyan-300 mb-4">
          <i class="fas fa-arrow-left mr-2"></i>
          Volver al Inicio
        </NuxtLink>
        <h1 class="text-4xl font-bold text-white mb-2">🔌 Documentación API REST</h1>
        <p class="text-gray-400">Endpoints disponibles del sistema ALOGIS</p>
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
              ? 'bg-cyan-500 text-white shadow-lg'
              : 'bg-gray-700 text-gray-300 hover:bg-gray-600 border border-gray-600'
          ]"
        >
          <i :class="tab.icon" class="mr-2"></i>
          {{ tab.label }}
        </button>
      </div>

      <!-- Información General -->
      <div v-if="activeTab === 'intro'" class="bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-700 text-gray-300">
        <h2 class="text-2xl font-bold text-white mb-6">📚 Introducción API ALOGIS</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div>
            <h3 class="text-lg font-semibold text-cyan-400 mb-3">Características</h3>
            <ul class="space-y-2 text-sm">
              <li>✓ API RESTful con arquitectura modular</li>
              <li>✓ Formato de respuesta JSON</li>
              <li>✓ Autenticación Bearer Token</li>
              <li>✓ Endpoints independientes por módulo</li>
              <li>✓ Versionado: /api/v1/</li>
              <li>✓ Rate limiting incluido</li>
            </ul>
          </div>
          
          <div>
            <h3 class="text-lg font-semibold text-cyan-400 mb-3">Base URL</h3>
            <div class="bg-gray-900 p-3 rounded border border-gray-700 mb-4">
              <code class="text-cyan-300 font-mono text-sm">https://api.alogis.local/api/v1/</code>
            </div>
            
            <h3 class="text-lg font-semibold text-cyan-400 mb-3">Header de Autenticación</h3>
            <div class="bg-gray-900 p-3 rounded border border-gray-700">
              <code class="text-cyan-300 font-mono text-sm">Authorization: Bearer TOKEN_AQUI</code>
            </div>
          </div>
        </div>
      </div>

      <!-- Endpoints por Módulo -->
      <div v-else class="space-y-6">
        <div v-for="endpoint in endpointsActuales" :key="endpoint.id" class="bg-gray-800 rounded-xl shadow-lg border border-gray-700 overflow-hidden">
          <!-- Header del Endpoint -->
          <div class="bg-gradient-to-r from-gray-700 to-gray-600 p-4 flex items-center justify-between cursor-pointer hover:from-gray-600 hover:to-gray-500 transition"
            @click="endpoint.expanded = !endpoint.expanded">
            <div class="flex items-center gap-4">
              <span :class="[
                'px-3 py-1 rounded text-white text-sm font-bold',
                getMetodoColor(endpoint.metodo)
              ]">
                {{ endpoint.metodo }}
              </span>
              <code class="text-cyan-300 font-mono text-sm">{{ endpoint.ruta }}</code>
            </div>
            <i :class="['fas', endpoint.expanded ? 'fa-chevron-up' : 'fa-chevron-down']" class="text-gray-400"></i>
          </div>

          <!-- Contenido del Endpoint -->
          <div v-if="endpoint.expanded" class="p-6 space-y-6">
            <!-- Descripción -->
            <div>
              <h4 class="text-cyan-400 font-semibold mb-2">Descripción</h4>
              <p class="text-gray-300 text-sm">{{ endpoint.descripcion }}</p>
            </div>

            <!-- Parámetros -->
            <div v-if="endpoint.parametros">
              <h4 class="text-cyan-400 font-semibold mb-3">Parámetros</h4>
              <div class="bg-gray-900 rounded border border-gray-700 overflow-hidden">
                <table class="w-full text-sm">
                  <thead class="bg-gray-700">
                    <tr>
                      <th class="px-4 py-2 text-left text-cyan-300">Parámetro</th>
                      <th class="px-4 py-2 text-left text-cyan-300">Tipo</th>
                      <th class="px-4 py-2 text-left text-cyan-300">Descripción</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="param in endpoint.parametros" :key="param.nombre" class="border-t border-gray-700 hover:bg-gray-800">
                      <td class="px-4 py-2 text-cyan-400 font-mono">{{ param.nombre }}</td>
                      <td class="px-4 py-2 text-gray-400">{{ param.tipo }}</td>
                      <td class="px-4 py-2 text-gray-300">{{ param.descripcion }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Ejemplo de Solicitud -->
            <div>
              <h4 class="text-cyan-400 font-semibold mb-3">Ejemplo de Solicitud</h4>
              <div class="bg-gray-900 rounded border border-gray-700 p-4 overflow-x-auto">
                <pre class="text-cyan-300 font-mono text-xs"><code>{{ endpoint.ejemploSolicitud }}</code></pre>
              </div>
            </div>

            <!-- Ejemplo de Respuesta -->
            <div>
              <h4 class="text-cyan-400 font-semibold mb-3">Ejemplo de Respuesta (200 OK)</h4>
              <div class="bg-gray-900 rounded border border-gray-700 p-4 overflow-x-auto">
                <pre class="text-green-400 font-mono text-xs"><code>{{ JSON.stringify(endpoint.ejemploRespuesta, null, 2) }}</code></pre>
              </div>
            </div>

            <!-- Códigos de Estado -->
            <div>
              <h4 class="text-cyan-400 font-semibold mb-3">Códigos de Estado</h4>
              <div class="space-y-2">
                <div v-for="codigo in endpoint.codigos" :key="codigo.status" class="flex items-start gap-3">
                  <span :class="[
                    'px-3 py-1 rounded text-white text-xs font-bold min-w-fit',
                    codigo.status === 200 ? 'bg-green-600' : 
                    codigo.status === 201 ? 'bg-blue-600' :
                    codigo.status === 400 ? 'bg-yellow-600' :
                    codigo.status === 401 ? 'bg-orange-600' :
                    'bg-red-600'
                  ]">
                    {{ codigo.status }}
                  </span>
                  <div>
                    <p class="text-gray-300 font-semibold text-sm">{{ codigo.mensaje }}</p>
                    <p class="text-gray-400 text-xs">{{ codigo.descripcion }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Información de Autenticación -->
      <div class="mt-12 bg-gradient-to-r from-cyan-900 to-blue-900 rounded-xl shadow-lg p-6 border border-cyan-700">
        <h3 class="text-lg font-bold text-cyan-300 mb-4">🔐 Autenticación Bearer Token</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <p class="text-gray-300 text-sm mb-3">Todos los endpoints requieren un token Bearer en el header:</p>
            <div class="bg-gray-900 p-4 rounded border border-cyan-700">
              <code class="text-cyan-300 font-mono text-xs">
curl -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  https://api.alogis.local/api/v1/productos
              </code>
            </div>
          </div>
          <div>
            <p class="text-gray-300 text-sm mb-3">El token se obtiene al iniciar sesión:</p>
            <div class="bg-gray-900 p-4 rounded border border-cyan-700">
              <code class="text-cyan-300 font-mono text-xs">
POST /api/v1/login
{
  "email": "usuario@alogis.com",
  "password": "contraseña"
}
              </code>
            </div>
          </div>
        </div>
      </div>

      <!-- Status del Sistema -->
      <div class="mt-6 bg-gradient-to-r from-green-900 to-emerald-900 rounded-xl shadow-lg p-6 border border-green-700">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-bold text-green-300 mb-2">✅ Estado del Sistema API</h3>
            <p class="text-gray-300 text-sm">Todos los servicios operacionales</p>
          </div>
          <div class="text-right">
            <div class="text-4xl font-bold text-green-400">✓</div>
            <p class="text-sm text-green-300">En línea</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const activeTab = ref('intro')

const tabs = [
  { id: 'intro', label: 'Introducción', icon: 'fas fa-info-circle' },
  { id: 'productos', label: 'Productos', icon: 'fas fa-box' },
  { id: 'usuarios', label: 'Usuarios', icon: 'fas fa-users' },
  { id: 'facturas', label: 'Facturas', icon: 'fas fa-file-invoice' },
  { id: 'ordenes', label: 'Órdenes', icon: 'fas fa-cart-shopping' },
  { id: 'clientes', label: 'Clientes', icon: 'fas fa-user-tie' }
]

const endpoints = {
  productos: [
    {
      id: 1,
      metodo: 'GET',
      ruta: '/api/v1/productos',
      descripcion: 'Obtiene la lista de todos los productos disponibles en el sistema',
      parametros: [
        { nombre: 'page', tipo: 'integer', descripcion: 'Número de página (defecto: 1)' },
        { nombre: 'limit', tipo: 'integer', descripcion: 'Items por página (defecto: 10)' }
      ],
      ejemploSolicitud: `GET /api/v1/productos?page=1&limit=10
Authorization: Bearer YOUR_TOKEN`,
      ejemploRespuesta: {
        status: 'success',
        data: [
          { id: 1, codigo: 'PROD-001', nombre: 'Agua Filtrada 5L', precio: 2500, stock: 150 },
          { id: 2, codigo: 'PROD-002', nombre: 'Agua Destilada 5L', precio: 3000, stock: 89 }
        ],
        pagination: { page: 1, limit: 10, total: 239 }
      },
      codigos: [
        { status: 200, mensaje: 'OK', descripcion: 'Lista de productos obtenida correctamente' },
        { status: 401, mensaje: 'Unauthorized', descripcion: 'Token inválido o expirado' },
        { status: 500, mensaje: 'Internal Server Error', descripcion: 'Error del servidor' }
      ],
      expanded: false
    },
    {
      id: 2,
      metodo: 'POST',
      ruta: '/api/v1/productos',
      descripcion: 'Crea un nuevo producto en el catálogo',
      parametros: [
        { nombre: 'codigo', tipo: 'string', descripcion: 'Código único del producto' },
        { nombre: 'nombre', tipo: 'string', descripcion: 'Nombre del producto' },
        { nombre: 'precio', tipo: 'decimal', descripcion: 'Precio unitario' },
        { nombre: 'stock_minimo', tipo: 'integer', descripcion: 'Stock mínimo requerido' }
      ],
      ejemploSolicitud: `POST /api/v1/productos
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "codigo": "PROD-003",
  "nombre": "Agua Purificada 10L",
  "precio": 5000,
  "stock_minimo": 50
}`,
      ejemploRespuesta: {
        status: 'success',
        message: 'Producto creado exitosamente',
        data: { id: 3, codigo: 'PROD-003', nombre: 'Agua Purificada 10L', precio: 5000 }
      },
      codigos: [
        { status: 201, mensaje: 'Created', descripcion: 'Producto creado correctamente' },
        { status: 400, mensaje: 'Bad Request', descripcion: 'Datos inválidos o incompletos' },
        { status: 401, mensaje: 'Unauthorized', descripcion: 'Token inválido' },
        { status: 409, mensaje: 'Conflict', descripcion: 'El código del producto ya existe' }
      ],
      expanded: false
    }
  ],
  usuarios: [
    {
      id: 3,
      metodo: 'POST',
      ruta: '/api/v1/login',
      descripcion: 'Autentica un usuario y genera un token Bearer',
      parametros: [
        { nombre: 'email', tipo: 'string', descripcion: 'Email del usuario' },
        { nombre: 'password', tipo: 'string', descripcion: 'Contraseña del usuario' }
      ],
      ejemploSolicitud: `POST /api/v1/login
Content-Type: application/json

{
  "email": "usuario@alogis.com",
  "password": "MiContraseña123!"
}`,
      ejemploRespuesta: {
        status: 'success',
        message: 'Autenticación exitosa',
        token: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIn0...',
        user: { id: 1, nombre: 'Juan Pérez', email: 'usuario@alogis.com', role: 'admin' }
      },
      codigos: [
        { status: 200, mensaje: 'OK', descripcion: 'Autenticación correcta' },
        { status: 401, mensaje: 'Unauthorized', descripcion: 'Credenciales inválidas' },
        { status: 404, mensaje: 'Not Found', descripcion: 'Usuario no encontrado' }
      ],
      expanded: false
    },
    {
      id: 4,
      metodo: 'GET',
      ruta: '/api/v1/usuarios',
      descripcion: 'Obtiene la lista de usuarios del sistema (requiere role admin)',
      parametros: [
        { nombre: 'role', tipo: 'string', descripcion: 'Filtrar por rol (admin, bodeguero, cliente)' },
        { nombre: 'estado', tipo: 'string', descripcion: 'Filtrar por estado (activo, inactivo)' }
      ],
      ejemploSolicitud: `GET /api/v1/usuarios?role=bodeguero&estado=activo
Authorization: Bearer YOUR_TOKEN`,
      ejemploRespuesta: {
        status: 'success',
        data: [
          { id: 2, nombre: 'Carlos López', email: 'carlos@alogis.com', role: 'bodeguero', estado: 'activo' },
          { id: 3, nombre: 'Ana García', email: 'ana@alogis.com', role: 'bodeguero', estado: 'activo' }
        ]
      },
      codigos: [
        { status: 200, mensaje: 'OK', descripcion: 'Lista obtenida correctamente' },
        { status: 401, mensaje: 'Unauthorized', descripcion: 'Token inválido' },
        { status: 403, mensaje: 'Forbidden', descripcion: 'No tiene permisos de administrador' }
      ],
      expanded: false
    }
  ],
  facturas: [
    {
      id: 5,
      metodo: 'POST',
      ruta: '/api/v1/facturas',
      descripcion: 'Crea una nueva factura electrónica en el sistema',
      parametros: [
        { nombre: 'cliente_id', tipo: 'integer', descripcion: 'ID del cliente' },
        { nombre: 'items', tipo: 'array', descripcion: 'Array de items de la factura' },
        { nombre: 'fecha_vencimiento', tipo: 'date', descripcion: 'Fecha de vencimiento' }
      ],
      ejemploSolicitud: `POST /api/v1/facturas
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "cliente_id": 1,
  "fecha_vencimiento": "2024-11-30",
  "items": [
    {"producto_id": 1, "cantidad": 10, "precio_unitario": 2500},
    {"producto_id": 2, "cantidad": 5, "precio_unitario": 3000}
  ]
}`,
      ejemploRespuesta: {
        status: 'success',
        message: 'Factura emitida exitosamente',
        data: { id: 1, folio: 'F-2024-001', numero_factura: 'F1234567', monto_total: 40000, estado: 'emitida' }
      },
      codigos: [
        { status: 201, mensaje: 'Created', descripcion: 'Factura creada correctamente' },
        { status: 400, mensaje: 'Bad Request', descripcion: 'Datos inválidos' },
        { status: 401, mensaje: 'Unauthorized', descripcion: 'Token inválido' }
      ],
      expanded: false
    }
  ],
  ordenes: [
    {
      id: 6,
      metodo: 'GET',
      ruta: '/api/v1/ordenes-compra',
      descripcion: 'Obtiene todas las órdenes de compra registradas',
      parametros: [
        { nombre: 'estado', tipo: 'string', descripcion: 'Filtrar por estado (pendiente, completada, cancelada)' },
        { nombre: 'proveedor_id', tipo: 'integer', descripcion: 'Filtrar por proveedor' }
      ],
      ejemploSolicitud: `GET /api/v1/ordenes-compra?estado=pendiente
Authorization: Bearer YOUR_TOKEN`,
      ejemploRespuesta: {
        status: 'success',
        data: [
          { id: 1, numero: 'OC-2024-001', proveedor: 'TransLogis', monto: 500000, estado: 'pendiente' }
        ]
      },
      codigos: [
        { status: 200, mensaje: 'OK', descripcion: 'Órdenes obtenidas correctamente' },
        { status: 401, mensaje: 'Unauthorized', descripcion: 'Token inválido' }
      ],
      expanded: false
    }
  ],
  clientes: [
    {
      id: 7,
      metodo: 'GET',
      ruta: '/api/v1/clientes/:id',
      descripcion: 'Obtiene los detalles de un cliente específico',
      parametros: [
        { nombre: 'id', tipo: 'integer', descripcion: 'ID del cliente (en la URL)' }
      ],
      ejemploSolicitud: `GET /api/v1/clientes/1
Authorization: Bearer YOUR_TOKEN`,
      ejemploRespuesta: {
        status: 'success',
        data: {
          id: 1,
          nombre: 'Centro Santiago',
          rut: '60.123.456-7',
          email: 'contacto@centro-santiago.cl',
          telefono: '+56 2 2345 6789',
          ordenes_totales: 15,
          monto_total: 1250000
        }
      },
      codigos: [
        { status: 200, mensaje: 'OK', descripcion: 'Cliente obtenido correctamente' },
        { status: 404, mensaje: 'Not Found', descripcion: 'Cliente no encontrado' },
        { status: 401, mensaje: 'Unauthorized', descripcion: 'Token inválido' }
      ],
      expanded: false
    }
  ]
}

const endpointsActuales = ref([])

const actualizarEndpoints = () => {
  const map = { productos: 'productos', usuarios: 'usuarios', facturas: 'facturas', ordenes: 'ordenes', clientes: 'clientes' }
  endpointsActuales.value = endpoints[map[activeTab.value]] || []
}

const getMetodoColor = (metodo) => {
  const colors = {
    'GET': 'bg-blue-600',
    'POST': 'bg-green-600',
    'PUT': 'bg-yellow-600',
    'DELETE': 'bg-red-600',
    'PATCH': 'bg-purple-600'
  }
  return colors[metodo] || 'bg-gray-600'
}

// Watch para actualizar cuando cambia el tab
import { watch } from 'vue'
watch(() => activeTab.value, () => {
  actualizarEndpoints()
})

// Inicializar
actualizarEndpoints()
</script>
