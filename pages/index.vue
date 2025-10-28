<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header/Logo -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 py-6">
        <h1 class="text-4xl font-bold text-[#039BE5]">🔵 ALOGIS - DisproDEV</h1>
        <p class="text-gray-600 mt-2">Sistema de Gestión de Distribución y Abastecimiento</p>
      </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Módulo Clientes -->
        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8">
          <div class="flex items-center mb-4">
            <div class="text-4xl mr-4">👥</div>
            <h2 class="text-2xl font-bold text-[#039BE5]">Módulo Cliente</h2>
          </div>
          <p class="text-gray-600 mb-6">
            Gestiona tus pedidos, requerimientos y seguimiento de envíos en tiempo real.
          </p>
          <NuxtLink 
            to="/cliente" 
            class="inline-block bg-[#039BE5] text-white px-6 py-3 rounded-lg hover:bg-[#0277BD] transition font-semibold"
          >
            Acceder Módulo Cliente
          </NuxtLink>
        </div>

        <!-- Módulo Compass/Admin -->
        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8">
          <div class="flex items-center mb-4">
            <div class="text-4xl mr-4">⚙️</div>
            <h2 class="text-2xl font-bold text-[#039BE5]">Módulo Compass</h2>
          </div>
          <p class="text-gray-600 mb-6">
            Administra empresas, centros, productos, usuarios y reportes del sistema.
          </p>
          <NuxtLink 
            to="/compass" 
            class="inline-block bg-[#039BE5] text-white px-6 py-3 rounded-lg hover:bg-[#0277BD] transition font-semibold"
          >
            Acceder Módulo Compass
          </NuxtLink>
        </div>
      </div>

      <!-- Info Section -->
      <div class="mt-12 bg-blue-50 border-l-4 border-[#039BE5] rounded p-6">
        <h3 class="text-xl font-bold text-[#039BE5] mb-3">Últimos Cambios</h3>
        <ul class="list-disc list-inside space-y-2 text-gray-700">
          <li>Módulo de Clientes - Gestión de pedidos y seguimiento</li>
          <li>Módulo de Compass - Administración del sistema</li>
          <li>Interfaz mejorada con Nuxt 3 y Vue 3</li>
          <li>Rendimiento optimizado para producción</li>
        </ul>
      </div>
    </main>
  </div>
</template>

<script setup>
</script>

