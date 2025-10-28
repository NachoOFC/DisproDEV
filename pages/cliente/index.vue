<template>
  <div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 py-6">
        <NuxtLink to="/" class="text-[#039BE5] hover:underline mb-4 inline-block">← Volver</NuxtLink>
        <h1 class="text-3xl font-bold text-[#039BE5]">Módulo Cliente</h1>
      </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 py-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <NuxtLink 
          to="/cliente/pedidos" 
          class="bg-gradient-to-br from-[#039BE5] to-[#0277BD] text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center"
        >
          <div class="text-3xl mb-2">📦</div>
          <h3 class="font-semibold">Mis Pedidos</h3>
        </NuxtLink>

        <NuxtLink 
          to="/cliente/requerimientos" 
          class="bg-gradient-to-br from-[#039BE5] to-[#0277BD] text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center"
        >
          <div class="text-3xl mb-2">📋</div>
          <h3 class="font-semibold">Requerimientos</h3>
        </NuxtLink>

        <NuxtLink 
          to="/cliente/seguimiento" 
          class="bg-gradient-to-br from-[#039BE5] to-[#0277BD] text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center"
        >
          <div class="text-3xl mb-2">🚚</div>
          <h3 class="font-semibold">Seguimiento</h3>
        </NuxtLink>

        <NuxtLink 
          to="/cliente/reportes" 
          class="bg-gradient-to-br from-[#039BE5] to-[#0277BD] text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center"
        >
          <div class="text-3xl mb-2">📊</div>
          <h3 class="font-semibold">Reportes</h3>
        </NuxtLink>
      </div>

      <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Bienvenido al módulo cliente</h2>
        <p class="text-gray-600 mb-4">
          Desde aquí puedes gestionar tus pedidos, hacer seguimiento de envíos y acceder a tus reportes.
        </p>
        <div class="bg-blue-50 border-l-4 border-[#039BE5] p-4 rounded">
          <p class="text-sm text-gray-700">
            <strong>Nota:</strong> Selecciona una opción del menú superior para comenzar.
          </p>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
</script>
