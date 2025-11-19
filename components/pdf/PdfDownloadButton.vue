<template>
  <button
    @click="descargar"
    :class="[
      'flex items-center justify-center gap-2 px-4 py-2 rounded-lg font-semibold transition-all hover:scale-105',
      variantClass
    ]"
    :title="titulo"
  >
    <i class="fas fa-download"></i>
    {{ label }}
  </button>
</template>

<script setup>
const props = defineProps({
  label: {
    type: String,
    default: 'Descargar'
  },
  titulo: {
    type: String,
    default: 'Descargar archivo'
  },
  tipo: {
    type: String,
    default: 'primary', // primary, secondary, success, danger, warning
    validator: (value) => ['primary', 'secondary', 'success', 'danger', 'warning'].includes(value)
  },
  tamaño: {
    type: String,
    default: 'md', // sm, md, lg
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  data: {
    type: Object,
    required: true
  },
  generador: {
    type: Function,
    required: true
  }
})

const variantMap = {
  primary: 'bg-blue-500 text-white hover:bg-blue-600',
  secondary: 'bg-gray-500 text-white hover:bg-gray-600',
  success: 'bg-green-500 text-white hover:bg-green-600',
  danger: 'bg-red-500 text-white hover:bg-red-600',
  warning: 'bg-yellow-500 text-white hover:bg-yellow-600'
}

const variantClass = variantMap[props.tipo]

const descargar = () => {
  props.generador(props.data)
}
</script>
