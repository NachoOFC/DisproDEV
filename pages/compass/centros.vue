<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Gestión de Centros</h1>
        <p class="text-slate-600">Administra los centros de distribución</p>
      </div>

      <!-- Botón Nueva Sucursal -->
      <div class="mb-6">
        <button
          @click="mostrarFormulario = true"
          class="bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-colors"
        >
          <i class="fas fa-plus mr-2"></i>
          Nuevo Centro
        </button>
      </div>

      <!-- Tabla de Centros -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-slate-100 border-b border-slate-300">
              <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Nombre</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Empresa</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Ciudad</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Contacto</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Teléfono</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Estado</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="centro in centros" :key="centro.id" class="border-b border-slate-200 hover:bg-slate-50">
                <td class="px-6 py-3 text-sm font-semibold text-slate-900">{{ centro.nombre }}</td>
                <td class="px-6 py-3 text-sm text-slate-900">{{ centro.empresa }}</td>
                <td class="px-6 py-3 text-sm text-slate-900">{{ centro.ciudad }}</td>
                <td class="px-6 py-3 text-sm text-slate-900">{{ centro.contacto }}</td>
                <td class="px-6 py-3 text-sm text-slate-900">{{ centro.telefono }}</td>
                <td class="px-6 py-3 text-sm">
                  <span :class="['px-3 py-1 rounded-full text-xs font-semibold', centro.estado === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
                    {{ centro.estado }}
                  </span>
                </td>
                <td class="px-6 py-3 text-sm">
                  <button @click="editarCentro(centro.id)" class="text-blue-500 hover:text-blue-700 mr-3">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button @click="eliminarCentro(centro.id)" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="centros.length === 0" class="text-center py-8">
          <p class="text-slate-600 text-lg">No hay centros registrados</p>
        </div>
      </div>

      <!-- Modal Formulario -->
      <div v-if="mostrarFormulario" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-2xl w-full mx-4">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">{{ formularioTitulo }}</h2>
          
          <form @submit.prevent="guardarCentro" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Nombre Centro</label>
                <input
                  v-model="formulario.nombre"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Empresa</label>
                <select
                  v-model="formulario.empresa"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                >
                  <option value="">-- Selecciona --</option>
                  <option value="Empresa A">Empresa A</option>
                  <option value="Empresa B">Empresa B</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Ciudad</label>
                <input
                  v-model="formulario.ciudad"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Contacto</label>
                <input
                  v-model="formulario.contacto"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Teléfono</label>
                <input
                  v-model="formulario.telefono"
                  type="tel"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Estado</label>
                <select
                  v-model="formulario.estado"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
                  <option value="Activo">Activo</option>
                  <option value="Inactivo">Inactivo</option>
                </select>
              </div>
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
import { ref } from 'vue'

const mostrarFormulario = ref(false)
const formularioEditando = ref(null)

const centros = ref([
  { id: 1, nombre: 'Centro Santiago Sur', empresa: 'Empresa A', ciudad: 'Santiago', contacto: 'Juan Pérez', telefono: '+56 9 1234 5678', estado: 'Activo' },
  { id: 2, nombre: 'Centro Valparaíso', empresa: 'Empresa B', ciudad: 'Valparaíso', contacto: 'María García', telefono: '+56 9 8765 4321', estado: 'Activo' },
])

const formulario = ref({
  nombre: '',
  empresa: '',
  ciudad: '',
  contacto: '',
  telefono: '',
  estado: 'Activo'
})

const formularioTitulo = ref('Nuevo Centro')

const editarCentro = (id) => {
  const centro = centros.value.find(c => c.id === id)
  if (centro) {
    formularioEditando.value = id
    formulario.value = { ...centro }
    formularioTitulo.value = 'Editar Centro'
    mostrarFormulario.value = true
  }
}

const guardarCentro = () => {
  if (formularioEditando.value) {
    const index = centros.value.findIndex(c => c.id === formularioEditando.value)
    if (index !== -1) {
      centros.value[index] = {
        ...centros.value[index],
        ...formulario.value
      }
    }
  } else {
    centros.value.push({
      id: Date.now(),
      ...formulario.value
    })
  }
  
  cerrarFormulario()
}

const eliminarCentro = (id) => {
  if (confirm('¿Estás seguro de que deseas eliminar este centro?')) {
    centros.value = centros.value.filter(c => c.id !== id)
  }
}

const cerrarFormulario = () => {
  mostrarFormulario.value = false
  formularioEditando.value = null
  formularioTitulo.value = 'Nuevo Centro'
  formulario.value = {
    nombre: '',
    empresa: '',
    ciudad: '',
    contacto: '',
    telefono: '',
    estado: 'Activo'
  }
}
</script>
