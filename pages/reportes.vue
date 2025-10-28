<template>
  <div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-900">Reportes</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div v-for="reporte in data.reportes.value" :key="reporte.id" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <h3 class="font-semibold text-gray-900 text-lg">{{ reporte.titulo }}</h3>
        <p class="text-gray-500 text-sm mt-2">{{ reporte.fecha }}</p>
        <button class="mt-4 bg-[#039BE5] text-white px-4 py-2 rounded-lg hover:bg-[#0277BD] transition text-sm">
          📥 Descargar {{ reporte.descarga }}
        </button>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-xl font-bold mb-4">Historial de Reportes</h2>
      <DataTable :columns="columns" :rows="data.reportes.value" />
    </div>
  </div>
</template>

<script setup>
import { useMockData } from '~/composables/useMockData'

const data = useMockData()
const columns = ['titulo', 'fecha', 'descarga']
</script>
