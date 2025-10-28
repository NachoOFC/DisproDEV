<template>
  <div class="overflow-x-auto">
    <table class="w-full border-collapse">
      <thead class="bg-gradient-to-r from-[#039BE5] to-[#0277BD] text-white">
        <tr>
          <th 
            v-for="column in columns" 
            :key="column"
            class="px-4 py-3 text-left font-semibold"
          >
            {{ column }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr 
          v-for="(row, index) in rows" 
          :key="index"
          class="border-b border-gray-200 hover:bg-[#E3F2FD] transition"
        >
          <td 
            v-for="column in columns" 
            :key="column"
            class="px-4 py-3 text-gray-700"
          >
            {{ row[column] }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  columns: {
    type: Array,
    required: true
  },
  rows: {
    type: Array,
    required: true
  }
})
</script>
