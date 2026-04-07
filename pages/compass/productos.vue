<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Gestión de Productos</h1>
        <p class="text-slate-600">Administra el catálogo de productos del sistema</p>
      </div>

      <!-- Filtros y Búsqueda -->
      <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Buscar Producto</label>
            <input
              v-model="busqueda"
              type="text"
              placeholder="Nombre o código"
              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Categoría</label>
            <select
              v-model="filtroCategoria"
              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
              <option value="">-- Todas --</option>
              <option value="Repuestos">Repuestos</option>
              <option value="Accesorios">Accesorios</option>
              <option value="Consumibles">Consumibles</option>
            </select>
          </div>

          <div class="flex items-end">
            <button
              @click="mostrarFormulario = true"
              class="w-full bg-blue-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-600 transition-colors"
            >
              <i class="fas fa-plus mr-2"></i>
              Nuevo Producto
            </button>
          </div>
        </div>
      </div>

      <!-- Grid de Productos -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="producto in productosFiltrados"
          :key="producto.id"
          class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow"
        >
          <!-- Imagen Placeholder -->
          <div class="bg-gradient-to-br from-blue-400 to-cyan-400 h-40 flex items-center justify-center">
            <i class="fas fa-box text-6xl text-white opacity-30"></i>
          </div>

          <!-- Contenido -->
          <div class="p-4">
            <h3 class="font-bold text-slate-900 mb-2 truncate">{{ producto.nombre }}</h3>
            <p class="text-sm text-slate-600 mb-3">Código: {{ producto.codigo }}</p>

            <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
              <div>
                <p class="text-slate-600">Categoría</p>
                <p class="font-semibold text-slate-900">{{ producto.categoria }}</p>
              </div>
              <div>
                <p class="text-slate-600">Stock</p>
                <p class="font-semibold" :class="producto.stock > 10 ? 'text-green-600' : 'text-red-600'">
                  {{ producto.stock }} uds.
                </p>
              </div>
            </div>

            <div class="mb-4 pb-4 border-b border-slate-200">
              <p class="text-slate-600 text-sm mb-1">Precio</p>
              <p class="text-2xl font-bold text-blue-600">${{ formatCurrency(producto.precio) }}</p>
            </div>

            <div class="flex gap-2">
              <button
                @click="editarProducto(producto.id)"
                class="flex-1 text-blue-500 hover:text-blue-700 py-2 hover:bg-blue-50 rounded transition-colors"
              >
                <i class="fas fa-edit mr-1"></i>
                Editar
              </button>
              <button
                @click="eliminarProducto(producto.id)"
                class="flex-1 text-red-500 hover:text-red-700 py-2 hover:bg-red-50 rounded transition-colors"
              >
                <i class="fas fa-trash mr-1"></i>
                Eliminar
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="productosFiltrados.length === 0" class="text-center py-12">
        <i class="fas fa-box-open text-6xl text-slate-300 mb-4"></i>
        <p class="text-xl text-slate-600">No hay productos registrados</p>
      </div>

      <!-- Modal Formulario -->
      <div v-if="mostrarFormulario" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-2xl w-full mx-4">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">{{ formularioTitulo }}</h2>
          
          <form @submit.prevent="guardarProducto" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Nombre</label>
                <input
                  v-model="formulario.nombre"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Código</label>
                <input
                  v-model="formulario.codigo"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Categoría</label>
                <select
                  v-model="formulario.categoria"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                >
                  <option value="">-- Selecciona --</option>
                  <option value="Repuestos">Repuestos</option>
                  <option value="Accesorios">Accesorios</option>
                  <option value="Consumibles">Consumibles</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Precio</label>
                <input
                  v-model.number="formulario.precio"
                  type="number"
                  step="0.01"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Stock</label>
                <input
                  v-model.number="formulario.stock"
                  type="number"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Stock Mínimo</label>
                <input
                  v-model.number="formulario.stockMinimo"
                  type="number"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Descripción</label>
              <textarea
                v-model="formulario.descripcion"
                rows="3"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>

            <div class="flex gap-3 pt-6 border-t border-slate-200">
              <button
                type="button"
                @click="cerrarFormulario"
                class="flex-1 bg-slate-200 text-slate-700 py-2 rounded-lg font-semibold hover:bg-slate-300"
              >
                Cancelar
              </button>
              <button
                type="submit"
                class="flex-1 bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600"
              >
                Guardar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const mostrarFormulario = ref(false)
const formularioEditando = ref(null)
const busqueda = ref('')
const filtroCategoria = ref('')
const loading = ref(false)

// Cargar desde PostgreSQL
const productos = ref([])

// Función para cargar productos desde la BD
const loadProductos = async () => {
  try {
    loading.value = true
    const response = await fetch('/api/productos')
    const data = await response.json()
    productos.value = data.data || []
  } catch (error) {
    console.error('Error cargando productos:', error)
  } finally {
    loading.value = false
  }
}

// Cargar al montar
onMounted(() => {
  loadProductos()
})

const formulario = ref({
  nombre: '',
  codigo: '',
  categoria: '',
  precio: 0,
  stock: 0,
  stockMinimo: 0,
  descripcion: ''
})

const formularioTitulo = ref('Nuevo Producto')

const productosFiltrados = computed(() => {
  return productos.value.filter(p => {
    const coincideBusqueda = p.nombre.toLowerCase().includes(busqueda.value.toLowerCase()) || 
                            p.codigo.toLowerCase().includes(busqueda.value.toLowerCase())
    const coincideCategoria = filtroCategoria.value === '' || p.categoria === filtroCategoria.value
    return coincideBusqueda && coincideCategoria
  })
})

const editarProducto = (id) => {
  const producto = productos.value.find(p => p.id === id)
  if (producto) {
    formularioEditando.value = id
    formulario.value = { ...producto }
    formularioTitulo.value = 'Editar Producto'
    mostrarFormulario.value = true
  }
}

const guardarProducto = () => {
  if (formularioEditando.value) {
    const index = productos.value.findIndex(p => p.id === formularioEditando.value)
    if (index !== -1) {
      productos.value[index] = {
        ...productos.value[index],
        ...formulario.value
      }
    }
  } else {
    productos.value.push({
      id: Date.now(),
      ...formulario.value
    })
  }
  
  cerrarFormulario()
}

const eliminarProducto = (id) => {
  if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
    productos.value = productos.value.filter(p => p.id !== id)
  }
}

const cerrarFormulario = () => {
  mostrarFormulario.value = false
  formularioEditando.value = null
  formularioTitulo.value = 'Nuevo Producto'
  formulario.value = {
    nombre: '',
    codigo: '',
    categoria: '',
    precio: 0,
    stock: 0,
    stockMinimo: 0,
    descripcion: ''
  }
}

const formatCurrency = (value) => {
  return new Intl.NumberFormat('es-ES', { minimumFractionDigits: 0 }).format(value)
}
</script>
