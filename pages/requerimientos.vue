<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-bold text-gray-900">Requerimientos</h1>
      <button class="bg-[#039BE5] text-white px-4 py-2 rounded-lg hover:bg-[#0277BD] transition">
        + Nuevo Requerimiento
      </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <DataTable :columns="columns" :rows="data.requerimientos.value" />
    </div>
  </div>
</template>

<script setup>
import { useMockData } from '~/composables/useMockData'

const data = useMockData()
const columns = ['numero', 'descripcion', 'estado', 'fecha']
</script>
