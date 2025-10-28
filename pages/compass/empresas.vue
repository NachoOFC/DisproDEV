<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Gestión de Empresas</h1>
        <p class="text-slate-600">Administra todas las empresas del sistema</p>
      </div>

      <!-- Botón de Nueva Empresa -->
      <div class="mb-6">
        <button
          @click="mostrarFormulario = true"
          class="bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-colors"
        >
          <i class="fas fa-plus mr-2"></i>
          Nueva Empresa
        </button>
      </div>

      <!-- Tabla de Empresas -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-slate-100 border-b border-slate-300">
              <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Nombre</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">RUT</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Email</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Teléfono</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Estado</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="empresa in empresas" :key="empresa.id" class="border-b border-slate-200 hover:bg-slate-50">
                <td class="px-6 py-3 text-sm font-semibold text-slate-900">{{ empresa.nombre }}</td>
                <td class="px-6 py-3 text-sm text-slate-900">{{ empresa.rut }}</td>
                <td class="px-6 py-3 text-sm text-slate-900">{{ empresa.email }}</td>
                <td class="px-6 py-3 text-sm text-slate-900">{{ empresa.telefono }}</td>
                <td class="px-6 py-3 text-sm">
                  <span :class="['px-3 py-1 rounded-full text-xs font-semibold', empresa.estado === 'Activa' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
                    {{ empresa.estado }}
                  </span>
                </td>
                <td class="px-6 py-3 text-sm">
                  <button @click="editarEmpresa(empresa.id)" class="text-blue-500 hover:text-blue-700 mr-3">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button @click="eliminarEmpresa(empresa.id)" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="empresas.length === 0" class="text-center py-8">
          <p class="text-slate-600 text-lg">No hay empresas registradas</p>
        </div>
      </div>

      <!-- Modal Formulario -->
      <div v-if="mostrarFormulario" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-2xl w-full mx-4">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">{{ formularioTitulo }}</h2>
          
          <form @submit.prevent="guardarEmpresa" class="space-y-4">
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
                <label class="block text-sm font-medium text-slate-700 mb-2">RUT</label>
                <input
                  v-model="formulario.rut"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                <input
                  v-model="formulario.email"
                  type="email"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Teléfono</label>
                <input
                  v-model="formulario.telefono"
                  type="tel"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Dirección</label>
                <input
                  v-model="formulario.direccion"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Estado</label>
                <select
                  v-model="formulario.estado"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
                  <option value="Activa">Activa</option>
                  <option value="Inactiva">Inactiva</option>
                </select>
              </div>
            </div>

            <div class="flex gap-3 pt-6 border-t border-slate-200">
              <button
                type="button"
                @click="mostrarFormulario = false"
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
import { ref } from 'vue'

const mostrarFormulario = ref(false)
const formularioEditando = ref(null)

const empresas = ref([
  { id: 1, nombre: 'Empresa A', rut: '12.345.678-9', email: 'contacto@empresaa.cl', telefono: '+56 9 1234 5678', direccion: 'Calle 1, Santiago', estado: 'Activa' },
  { id: 2, nombre: 'Empresa B', rut: '98.765.432-1', email: 'contacto@empresab.cl', telefono: '+56 9 8765 4321', direccion: 'Calle 2, Valparaíso', estado: 'Activa' },
])

const formulario = ref({
  nombre: '',
  rut: '',
  email: '',
  telefono: '',
  direccion: '',
  estado: 'Activa'
})

const formularioTitulo = ref('Nueva Empresa')

const editarEmpresa = (id) => {
  const empresa = empresas.value.find(e => e.id === id)
  if (empresa) {
    formularioEditando.value = id
    formulario.value = { ...empresa }
    formularioTitulo.value = 'Editar Empresa'
    mostrarFormulario.value = true
  }
}

const guardarEmpresa = () => {
  if (formularioEditando.value) {
    const index = empresas.value.findIndex(e => e.id === formularioEditando.value)
    if (index !== -1) {
      empresas.value[index] = {
        ...empresas.value[index],
        ...formulario.value
      }
    }
  } else {
    empresas.value.push({
      id: Date.now(),
      ...formulario.value
    })
  }
  
  cerrarFormulario()
}

const eliminarEmpresa = (id) => {
  if (confirm('¿Estás seguro de que deseas eliminar esta empresa?')) {
    empresas.value = empresas.value.filter(e => e.id !== id)
  }
}

const cerrarFormulario = () => {
  mostrarFormulario.value = false
  formularioEditando.value = null
  formularioTitulo.value = 'Nueva Empresa'
  formulario.value = {
    nombre: '',
    rut: '',
    email: '',
    telefono: '',
    direccion: '',
    estado: 'Activa'
  }
}
</script>
