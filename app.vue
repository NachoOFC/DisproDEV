<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-[#039BE5] to-cyan-500 shadow-lg sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <NuxtLink to="/" class="flex items-center gap-3 hover:opacity-90 transition">
          <img src="/img/LogoAlogis.jpeg" alt="ALOGIS" class="h-10 w-auto object-contain">
          <span class="text-lg font-bold text-white hidden sm:inline">ALOGIS</span>
        </NuxtLink>
        <div class="hidden md:flex space-x-8 items-center">
          <NuxtLink to="/" class="text-white hover:text-cyan-100 transition font-medium">Inicio</NuxtLink>
          <NuxtLink to="/cliente" class="text-white hover:text-cyan-100 transition font-medium">Cliente</NuxtLink>
          <NuxtLink to="/compass" class="text-white hover:text-cyan-100 transition font-medium">Admin</NuxtLink>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main>
      <NuxtPage />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 mt-16 py-6">
      <div class="max-w-7xl mx-auto px-4 text-center text-sm">
        <p>© 2024 ALOGIS - Proyecto de Titulación | Powered by Nuxt 3 + Vue 3</p>
      </div>
    </footer>
  </div>
</template>

