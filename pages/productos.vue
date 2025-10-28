<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-bold text-gray-900">Productos</h1>
      <button class="bg-[#039BE5] text-white px-4 py-2 rounded-lg hover:bg-[#0277BD] transition">
        + Nuevo Producto
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div v-for="producto in data.productos.value" :key="producto.id" class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition">
        <h3 class="font-semibold text-gray-900">{{ producto.nombre }}</h3>
        <p class="text-sm text-gray-500 mt-2">SKU: {{ producto.sku }}</p>
        <p class="text-lg font-bold text-[#039BE5] mt-2">{{ producto.precio }}</p>
        <p class="text-sm text-gray-600">Stock: {{ producto.stock }}</p>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-xl font-bold mb-4">Vista de Tabla</h2>
      <DataTable :columns="columns" :rows="data.productos.value" />
    </div>
  </div>
</template>

<script setup>
import { useMockData } from '~/composables/useMockData'

const data = useMockData()
const columns = ['nombre', 'sku', 'precio', 'stock']
</script>
