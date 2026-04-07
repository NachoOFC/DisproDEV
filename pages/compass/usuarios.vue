<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Gestión de Usuarios</h1>
        <p class="text-slate-600">Administra los usuarios del sistema</p>
      </div>

      <!-- Botón Nuevo Usuario -->
      <div class="mb-6">
        <button
          @click="mostrarFormulario = true"
          class="bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-colors"
        >
          <i class="fas fa-user-plus mr-2"></i>
          Nuevo Usuario
        </button>
      </div>

      <!-- Tabla de Usuarios -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-slate-100 border-b border-slate-300">
              <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Nombre</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Email</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Rol</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Centro/Empresa</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Estado</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Último Acceso</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="usuario in usuarios" :key="usuario.id" class="border-b border-slate-200 hover:bg-slate-50">
                <td class="px-6 py-3 text-sm font-semibold text-slate-900">{{ usuario.nombre }}</td>
                <td class="px-6 py-3 text-sm text-slate-900">{{ usuario.email }}</td>
                <td class="px-6 py-3 text-sm">
                  <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getRolClass(usuario.rol)]">
                    {{ usuario.rol }}
                  </span>
                </td>
                <td class="px-6 py-3 text-sm text-slate-900">{{ usuario.relacionado }}</td>
                <td class="px-6 py-3 text-sm">
                  <span :class="['px-3 py-1 rounded-full text-xs font-semibold', usuario.estado === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
                    {{ usuario.estado }}
                  </span>
                </td>
                <td class="px-6 py-3 text-sm text-slate-600">{{ usuario.ultimoAcceso }}</td>
                <td class="px-6 py-3 text-sm">
                  <button @click="editarUsuario(usuario.id)" class="text-blue-500 hover:text-blue-700 mr-3">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button @click="eliminarUsuario(usuario.id)" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="usuarios.length === 0" class="text-center py-8">
          <p class="text-slate-600 text-lg">No hay usuarios registrados</p>
        </div>
      </div>

      <!-- Modal Formulario -->
      <div v-if="mostrarFormulario" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-2xl w-full mx-4">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">{{ formularioTitulo }}</h2>
          
          <form @submit.prevent="guardarUsuario" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Nombre Completo</label>
                <input
                  v-model="formulario.nombre"
                  type="text"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                <input
                  v-model="formulario.email"
                  type="email"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
            </div>

            <div v-if="!formularioEditando" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Contraseña</label>
                <input
                  v-model="formulario.password"
                  type="password"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Confirmar Contraseña</label>
                <input
                  v-model="formulario.passwordConfirm"
                  type="password"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Rol</label>
                <select
                  v-model="formulario.rol"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                >
                  <option value="">-- Selecciona --</option>
                  <option value="Administrador">Administrador</option>
                  <option value="Gerente">Gerente</option>
                  <option value="Bodeguero">Bodeguero</option>
                  <option value="Cliente">Cliente</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Relacionado a</label>
                <select
                  v-model="formulario.relacionado"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                  required
                >
                  <option value="">-- Selecciona --</option>
                  <option value="Empresa A">Empresa A</option>
                  <option value="Empresa B">Empresa B</option>
                  <option value="Centro Santiago">Centro Santiago</option>
                  <option value="Centro Valparaíso">Centro Valparaíso</option>
                </select>
              </div>
            </div>

            <div>
              <label class="flex items-center">
                <input
                  v-model="formulario.activo"
                  type="checkbox"
                  class="w-4 h-4 text-blue-500 rounded focus:ring-2 focus:ring-blue-500"
                />
                <span class="ml-3 text-sm text-slate-700">Usuario Activo</span>
              </label>
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
import { ref, onMounted } from 'vue'

const mostrarFormulario = ref(false)
const formularioEditando = ref(null)
const loading = ref(false)

// Cargar desde PostgreSQL
const usuarios = ref([])

// Función para cargar usuarios desde la BD
const loadUsuarios = async () => {
  try {
    loading.value = true
    const response = await fetch('/api/usuarios')
    const data = await response.json()
    usuarios.value = data.data || []
  } catch (error) {
    console.error('Error cargando usuarios:', error)
  } finally {
    loading.value = false
  }
}

// Cargar al montar
onMounted(() => {
  loadUsuarios()
})

const formulario = ref({
  nombre: '',
  email: '',
  password: '',
  passwordConfirm: '',
  rol: '',
  relacionado: '',
  activo: true
})

const formularioTitulo = ref('Nuevo Usuario')

const editarUsuario = (id) => {
  const usuario = usuarios.value.find(u => u.id === id)
  if (usuario) {
    formularioEditando.value = id
    formulario.value = {
      nombre: usuario.nombre,
      email: usuario.email,
      password: '',
      passwordConfirm: '',
      rol: usuario.rol,
      relacionado: usuario.relacionado,
      activo: usuario.estado === 'Activo'
    }
    formularioTitulo.value = 'Editar Usuario'
    mostrarFormulario.value = true
  }
}

const guardarUsuario = () => {
  if (formularioEditando.value) {
    const index = usuarios.value.findIndex(u => u.id === formularioEditando.value)
    if (index !== -1) {
      usuarios.value[index] = {
        ...usuarios.value[index],
        nombre: formulario.value.nombre,
        email: formulario.value.email,
        rol: formulario.value.rol,
        relacionado: formulario.value.relacionado,
        estado: formulario.value.activo ? 'Activo' : 'Inactivo'
      }
    }
  } else {
    usuarios.value.push({
      id: Date.now(),
      nombre: formulario.value.nombre,
      email: formulario.value.email,
      rol: formulario.value.rol,
      relacionado: formulario.value.relacionado,
      estado: formulario.value.activo ? 'Activo' : 'Inactivo',
      ultimoAcceso: 'Nunca'
    })
  }
  
  cerrarFormulario()
}

const eliminarUsuario = (id) => {
  if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
    usuarios.value = usuarios.value.filter(u => u.id !== id)
  }
}

const cerrarFormulario = () => {
  mostrarFormulario.value = false
  formularioEditando.value = null
  formularioTitulo.value = 'Nuevo Usuario'
  formulario.value = {
    nombre: '',
    email: '',
    password: '',
    passwordConfirm: '',
    rol: '',
    relacionado: '',
    activo: true
  }
}

const getRolClass = (rol) => {
  const classes = {
    'Administrador': 'bg-red-100 text-red-800',
    'Gerente': 'bg-purple-100 text-purple-800',
    'Bodeguero': 'bg-orange-100 text-orange-800',
    'Cliente': 'bg-blue-100 text-blue-800'
  }
  return classes[rol] || 'bg-slate-100 text-slate-800'
}
</script>
