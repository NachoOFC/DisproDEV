<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-[#039BE5] to-cyan-500 shadow-lg sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <NuxtLink to="/" class="flex items-center gap-2 hover:opacity-90 transition">
          <div class="bg-white text-[#039BE5] font-bold px-2 py-1 rounded text-lg">AL</div>
          <span class="text-lg font-bold text-white hidden sm:inline">ALOGIS</span>
        </NuxtLink>
        <div class="hidden md:flex space-x-3 items-center">
          <NuxtLink
            to="/"
            title="Ir a Inicio"
            class="bg-blue-600 hover:bg-blue-700 text-white transition font-semibold w-10 h-10 rounded-full inline-flex items-center justify-center"
          >
            <i class="fas fa-home" aria-hidden="true"></i>
            <span class="sr-only">Inicio</span>
          </NuxtLink>
          <NuxtLink
            to="/cliente"
            title="Ir a Cliente"
            class="bg-blue-600 hover:bg-blue-700 text-white transition font-semibold w-10 h-10 rounded-full inline-flex items-center justify-center"
          >
            <i class="fas fa-user" aria-hidden="true"></i>
            <span class="sr-only">Cliente</span>
          </NuxtLink>
          <NuxtLink
            to="/compass"
            title="Ir a Admin"
            class="bg-blue-600 hover:bg-blue-700 text-white transition font-semibold w-10 h-10 rounded-full inline-flex items-center justify-center"
          >
            <i class="fas fa-user-shield" aria-hidden="true"></i>
            <span class="sr-only">Admin</span>
          </NuxtLink>

          <button
            type="button"
            title="Cerrar sesión"
            class="ml-3 bg-red-600 hover:bg-red-700 text-white transition font-semibold px-4 py-1.5 rounded-full"
            @click="handleLogout"
          >
            Cerrar sesión
          </button>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main>
      <slot />
    </main>

   

    <CommonToastContainer />
  </div>
</template>

<script setup>
const authToken = useCookie('auth_token', { path: '/' })
const authUser = useCookie('auth_user', { path: '/' })

const handleLogout = async () => {
  try {
    await $fetch('/api/logout', { method: 'POST' })
  } catch {
    // Si falla la API igual intentamos cerrar sesión local
  } finally {
    authToken.value = null
    authUser.value = null
    await navigateTo('/login')
  }
}
</script>
