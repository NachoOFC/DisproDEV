<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-[#039BE5] to-[#0277BD] text-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <NuxtLink to="/" class="text-2xl font-bold hover:text-[#B3E5FC] transition">
          DisproDEV
        </NuxtLink>
        <div class="hidden md:flex space-x-6">
          <NuxtLink to="/" class="hover:text-[#B3E5FC] transition">Inicio</NuxtLink>
          <NuxtLink to="/requerimientos" class="hover:text-[#B3E5FC] transition">Requerimientos</NuxtLink>
          <NuxtLink to="/productos" class="hover:text-[#B3E5FC] transition">Productos</NuxtLink>
          <NuxtLink to="/reportes" class="hover:text-[#B3E5FC] transition">Reportes</NuxtLink>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
      <NuxtPage />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 mt-16 py-8">
      <div class="max-w-7xl mx-auto px-4 text-center">
        <p>© 2024 DisproDEV - Proyecto de Titulación</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
</script>

<style scoped>
</style>
