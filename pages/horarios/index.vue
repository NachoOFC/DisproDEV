<template>
  <div class="min-h-screen bg-gradient-to-br from-teal-50 to-cyan-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <NuxtLink to="/" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-4">
          <i class="fas fa-arrow-left mr-2"></i>
          Volver al Inicio
        </NuxtLink>
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Gestión de Horarios</h1>
        <p class="text-slate-600">Administra horarios de funcionamiento de centros</p>
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
              ? 'bg-teal-500 text-white shadow-lg'
              : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'
          ]"
        >
          <i :class="tab.icon" class="mr-2"></i>
          {{ tab.label }}
        </button>
      </div>

      <!-- Contenido -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Editar Horarios -->
        <div v-if="activeTab === 'editar'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Editar Horarios</h2>
          
          <div class="mb-6">
            <label class="block text-sm font-medium text-slate-700 mb-2">Centro de Distribución</label>
            <select
              v-model="centrSeleccionado"
              class="w-full md:w-64 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500"
            >
              <option v-for="centro in centros" :key="centro" :value="centro">
                {{ centro }}
              </option>
            </select>
          </div>

          <form @submit.prevent="guardarHorarios" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div
                v-for="dia in diasSemana"
                :key="dia"
                class="border border-slate-200 rounded-lg p-4"
              >
                <div class="flex items-center justify-between mb-4">
                  <h3 class="font-semibold text-slate-900">{{ dia }}</h3>
                  <label class="flex items-center">
                    <input
                      type="checkbox"
                      v-model="horarios[centrSeleccionado][dia].activo"
                      class="mr-2"
                    />
                    <span class="text-sm text-slate-600">Abierto</span>
                  </label>
                </div>

                <div v-if="horarios[centrSeleccionado][dia].activo" class="space-y-3">
                  <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Hora Apertura</label>
                    <input
                      v-model="horarios[centrSeleccionado][dia].apertura"
                      type="time"
                      class="w-full px-3 py-2 border border-slate-300 rounded text-sm"
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Hora Cierre</label>
                    <input
                      v-model="horarios[centrSeleccionado][dia].cierre"
                      type="time"
                      class="w-full px-3 py-2 border border-slate-300 rounded text-sm"
                    />
                  </div>
                </div>

                <div v-else class="text-center py-4 bg-slate-50 rounded">
                  <p class="text-sm text-slate-600">Cerrado</p>
                </div>
              </div>
            </div>

            <div class="border-t pt-6">
              <h3 class="font-semibold text-slate-900 mb-4">Información Adicional</h3>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Teléfono de Contacto</label>
                <input
                  v-model="infoAdicional.telefono"
                  type="tel"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg"
                />
              </div>

              <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                <input
                  v-model="infoAdicional.email"
                  type="email"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg"
                />
              </div>

              <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Notas</label>
                <textarea
                  v-model="infoAdicional.notas"
                  rows="2"
                  placeholder="Información adicional o excepciones..."
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg"
                ></textarea>
              </div>
            </div>

            <div class="flex gap-3 pt-6 border-t border-slate-200">
              <button
                type="button"
                @click="activeTab = 'vista'"
                class="flex-1 bg-slate-200 text-slate-700 py-2 rounded-lg font-semibold hover:bg-slate-300"
              >
                Cancelar
              </button>
              <button
                type="submit"
                class="flex-1 bg-teal-500 text-white py-2 rounded-lg font-semibold hover:bg-teal-600"
              >
                <i class="fas fa-save mr-2"></i>
                Guardar Cambios
              </button>
            </div>
          </form>
        </div>

        <!-- Vista de Horarios -->
        <div v-if="activeTab === 'vista'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Horarios por Centro</h2>
          
          <div class="space-y-8">
            <div v-for="centro in centros" :key="centro" class="border border-slate-200 rounded-lg p-6">
              <h3 class="text-xl font-bold text-slate-900 mb-4">{{ centro }}</h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div v-for="dia in diasSemana" :key="dia" class="flex items-center p-3 bg-slate-50 rounded">
                  <i class="fas fa-clock text-teal-500 mr-3"></i>
                  <div class="flex-1">
                    <p class="text-sm font-semibold text-slate-900">{{ dia }}</p>
                    <p v-if="horarios[centro][dia].activo" class="text-xs text-slate-600">
                      {{ horarios[centro][dia].apertura }} - {{ horarios[centro][dia].cierre }}
                    </p>
                    <p v-else class="text-xs text-red-600 font-semibold">Cerrado</p>
                  </div>
                </div>
              </div>

              <div class="border-t pt-4">
                <p class="text-sm text-slate-600 mb-2">
                  <i class="fas fa-phone mr-2 text-teal-500"></i>
                  {{ infoAdicional.telefono }}
                </p>
                <p class="text-sm text-slate-600 mb-2">
                  <i class="fas fa-envelope mr-2 text-teal-500"></i>
                  {{ infoAdicional.email }}
                </p>
                <p v-if="infoAdicional.notas" class="text-sm text-slate-600">
                  <i class="fas fa-sticky-note mr-2 text-teal-500"></i>
                  {{ infoAdicional.notas }}
                </p>
              </div>

              <div class="mt-4 flex gap-2">
                <button @click="editar(centro)" class="text-teal-500 hover:text-teal-700 font-semibold">
                  <i class="fas fa-edit mr-1"></i> Editar
                </button>
                <button class="text-blue-500 hover:text-blue-700 font-semibold">
                  <i class="fas fa-download mr-1"></i> Descargar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Excepciones y Feriados -->
        <div v-if="activeTab === 'excepciones'" class="p-8">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">Excepciones y Feriados</h2>
          
          <button
            @click="mostrarFormExcepcion = !mostrarFormExcepcion"
            class="mb-6 bg-teal-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-teal-600"
          >
            <i class="fas fa-plus mr-2"></i> Agregar Excepción
          </button>

          <!-- Form Nueva Excepción -->
          <div v-if="mostrarFormExcepcion" class="mb-8 bg-teal-50 p-6 rounded-lg border border-teal-200">
            <h3 class="font-semibold text-slate-900 mb-4">Nueva Excepción</h3>
            
            <form @submit.prevent="agregarExcepcion" class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input
                  v-model="nuevaExcepcion.fecha"
                  type="date"
                  class="px-4 py-2 border border-slate-300 rounded-lg"
                  required
                />
                <input
                  v-model="nuevaExcepcion.descripcion"
                  type="text"
                  placeholder="Descripción (ej: Feriado Nacional)"
                  class="px-4 py-2 border border-slate-300 rounded-lg"
                  required
                />
                <select
                  v-model="nuevaExcepcion.tipo"
                  class="px-4 py-2 border border-slate-300 rounded-lg"
                >
                  <option value="Cerrado">Cerrado</option>
                  <option value="Horario Especial">Horario Especial</option>
                </select>
              </div>

              <div class="flex gap-3">
                <button
                  type="button"
                  @click="mostrarFormExcepcion = false"
                  class="flex-1 bg-slate-200 text-slate-700 py-2 rounded-lg font-semibold"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  class="flex-1 bg-teal-500 text-white py-2 rounded-lg font-semibold hover:bg-teal-600"
                >
                  Agregar
                </button>
              </div>
            </form>
          </div>

          <!-- Lista de Excepciones -->
          <div class="space-y-3">
            <div
              v-for="exc in excepciones"
              :key="exc.id"
              class="border border-slate-200 rounded-lg p-4 flex justify-between items-center"
            >
              <div>
                <p class="font-semibold text-slate-900">{{ exc.descripcion }}</p>
                <p class="text-sm text-slate-600">{{ formatDate(exc.fecha) }} - {{ exc.tipo }}</p>
              </div>
              <button class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>

          <div v-if="excepciones.length === 0" class="text-center py-8">
            <p class="text-slate-600">No hay excepciones registradas</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const activeTab = ref('vista')
const centrSeleccionado = ref('Centro Santiago')
const mostrarFormExcepcion = ref(false)

const tabs = [
  { id: 'vista', label: 'Vista Horarios', icon: 'fas fa-calendar-alt' },
  { id: 'editar', label: 'Editar', icon: 'fas fa-edit' },
  { id: 'excepciones', label: 'Excepciones', icon: 'fas fa-exclamation-circle' }
]

const centros = ['Centro Santiago', 'Centro Valparaíso']
const diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']

const horarios = ref({
  'Centro Santiago': {
    'Lunes': { activo: true, apertura: '08:00', cierre: '18:00' },
    'Martes': { activo: true, apertura: '08:00', cierre: '18:00' },
    'Miércoles': { activo: true, apertura: '08:00', cierre: '18:00' },
    'Jueves': { activo: true, apertura: '08:00', cierre: '18:00' },
    'Viernes': { activo: true, apertura: '08:00', cierre: '18:00' },
    'Sábado': { activo: true, apertura: '09:00', cierre: '14:00' },
    'Domingo': { activo: false, apertura: '00:00', cierre: '00:00' }
  },
  'Centro Valparaíso': {
    'Lunes': { activo: true, apertura: '07:30', cierre: '17:30' },
    'Martes': { activo: true, apertura: '07:30', cierre: '17:30' },
    'Miércoles': { activo: true, apertura: '07:30', cierre: '17:30' },
    'Jueves': { activo: true, apertura: '07:30', cierre: '17:30' },
    'Viernes': { activo: true, apertura: '07:30', cierre: '17:30' },
    'Sábado': { activo: false, apertura: '00:00', cierre: '00:00' },
    'Domingo': { activo: false, apertura: '00:00', cierre: '00:00' }
  }
})

const infoAdicional = ref({
  telefono: '+56 9 1234 5678',
  email: 'contacto@alogis.com',
  notas: 'Centro principal de operaciones'
})

const excepciones = ref([
  { id: 1, fecha: '2024-10-27', descripcion: 'Feriado Nacional', tipo: 'Cerrado' },
  { id: 2, fecha: '2024-12-25', descripcion: 'Navidad', tipo: 'Cerrado' },
])

const nuevaExcepcion = ref({
  fecha: '',
  descripcion: '',
  tipo: 'Cerrado'
})

const editar = (centro) => {
  centrSeleccionado.value = centro
  activeTab.value = 'editar'
}

const guardarHorarios = () => {
  alert('Horarios guardados exitosamente')
  activeTab.value = 'vista'
}

const agregarExcepcion = () => {
  excepciones.value.push({
    id: excepciones.value.length + 1,
    ...nuevaExcepcion.value
  })

  nuevaExcepcion.value = {
    fecha: '',
    descripcion: '',
    tipo: 'Cerrado'
  }

  mostrarFormExcepcion.value = false
  alert('Excepción agregada exitosamente')
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })
}
</script>
