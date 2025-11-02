<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-[#039BE5] to-[#0277BD] text-white shadow-lg sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 py-2 flex justify-between items-center">
        <NuxtLink to="/" class="flex items-center gap-4 hover:opacity-90 transition">
          <img src="/img/LogoAlogis.jpeg" alt="ALOGIS" class="h-12 w-auto object-contain">
          <span class="text-xl font-bold hidden sm:inline">DisproDEV</span>
        </NuxtLink>
        <div class="hidden md:flex space-x-6">
          <NuxtLink to="/" class="hover:text-[#B3E5FC] transition">Inicio</NuxtLink>
          <NuxtLink to="/cliente" class="hover:text-[#B3E5FC] transition">Cliente</NuxtLink>
          <NuxtLink to="/compass" class="hover:text-[#B3E5FC] transition">Compass</NuxtLink>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main>
      <NuxtPage />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 mt-16 py-8">
      <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
          <div>
            <h3 class="font-bold text-white mb-4">ALOGIS</h3>
            <p class="text-sm">Sistema de Gestión de Distribución y Abastecimiento</p>
          </div>
          <div>
            <h3 class="font-bold text-white mb-4">Enlaces</h3>
            <ul class="text-sm space-y-2">
              <li><NuxtLink to="/" class="hover:text-white">Inicio</NuxtLink></li>
              <li><NuxtLink to="/cliente" class="hover:text-white">Cliente</NuxtLink></li>
              <li><NuxtLink to="/compass" class="hover:text-white">Admin</NuxtLink></li>
            </ul>
          </div>
          <div>
            <h3 class="font-bold text-white mb-4">Contacto</h3>
            <p class="text-sm">📧 soporte@dispro.com</p>
            <p class="text-sm">📞 +56 2 XXXX XXXX</p>
          </div>
        </div>
        <div class="border-t border-gray-700 pt-8 text-center text-sm">
          <p>© 2024 ALOGIS - Proyecto de Titulación | Powered by Nuxt 3 + Vue 3</p>
        </div>
      </div>
    </footer>
  </div>
</template>

