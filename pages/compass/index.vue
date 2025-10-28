<template>
  <div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 py-6">
        <NuxtLink to="/" class="text-[#039BE5] hover:underline mb-4 inline-block">← Volver</NuxtLink>
        <h1 class="text-3xl font-bold text-[#039BE5]">Módulo Compass - Administración</h1>
      </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 py-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <NuxtLink 
          to="/compass/empresas" 
          class="bg-gradient-to-br from-[#039BE5] to-[#0277BD] text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center"
        >
          <div class="text-3xl mb-2">🏢</div>
          <h3 class="font-semibold">Empresas</h3>
        </NuxtLink>

        <NuxtLink 
          to="/compass/centros" 
          class="bg-gradient-to-br from-[#039BE5] to-[#0277BD] text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center"
        >
          <div class="text-3xl mb-2">📍</div>
          <h3 class="font-semibold">Centros</h3>
        </NuxtLink>

        <NuxtLink 
          to="/compass/productos" 
          class="bg-gradient-to-br from-[#039BE5] to-[#0277BD] text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center"
        >
          <div class="text-3xl mb-2">📦</div>
          <h3 class="font-semibold">Productos</h3>
        </NuxtLink>

        <NuxtLink 
          to="/compass/usuarios" 
          class="bg-gradient-to-br from-[#039BE5] to-[#0277BD] text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center"
        >
          <div class="text-3xl mb-2">👥</div>
          <h3 class="font-semibold">Usuarios</h3>
        </NuxtLink>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <NuxtLink 
          to="/compass/reportes" 
          class="bg-gradient-to-br from-purple-400 to-purple-600 text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center"
        >
          <div class="text-3xl mb-2">📊</div>
          <h3 class="font-semibold">Reportes</h3>
        </NuxtLink>

        <NuxtLink 
          to="/compass/configuracion" 
          class="bg-gradient-to-br from-green-400 to-green-600 text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center"
        >
          <div class="text-3xl mb-2">⚙️</div>
          <h3 class="font-semibold">Configuración</h3>
        </NuxtLink>

        <NuxtLink 
          to="/compass/logs" 
          class="bg-gradient-to-br from-gray-400 to-gray-600 text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center"
        >
          <div class="text-3xl mb-2">📝</div>
          <h3 class="font-semibold">Logs</h3>
        </NuxtLink>
      </div>

      <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Panel de Administración</h2>
        <p class="text-gray-600 mb-4">
          Desde este módulo puedes administrar toda la aplicación: empresas, centros de distribución, 
          productos, usuarios y generar reportes del sistema.
        </p>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
          <p class="text-sm text-gray-700">
            <strong>Acceso restringido:</strong> Solo usuarios administradores pueden acceder a este módulo.
          </p>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
</script>
