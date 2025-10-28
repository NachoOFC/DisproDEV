<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <NuxtLink to="/" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-4">
          <i class="fas fa-arrow-left mr-2"></i>
          Volver al Inicio
        </NuxtLink>
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Módulo Compass - Administración</h1>
        <p class="text-slate-600">Gestiona empresas, centros, productos, usuarios y reportes del sistema</p>
      </div>

      <!-- Quick Access Buttons - Admin Functions -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Empresas -->
        <NuxtLink to="/compass/empresas" class="group">
          <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all group-hover:scale-105">
            <div class="flex items-center justify-between mb-4">
              <div class="bg-blue-100 rounded-lg p-4">
                <i class="fas fa-building text-3xl text-blue-600"></i>
              </div>
              <i class="fas fa-arrow-right text-2xl text-slate-300 group-hover:text-blue-600 transition-colors"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Empresas</h3>
            <p class="text-slate-600 text-sm">Administra empresas del sistema</p>
          </div>
        </NuxtLink>

        <!-- Centros -->
        <NuxtLink to="/compass/centros" class="group">
          <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all group-hover:scale-105">
            <div class="flex items-center justify-between mb-4">
              <div class="bg-green-100 rounded-lg p-4">
                <i class="fas fa-map-marker-alt text-3xl text-green-600"></i>
              </div>
              <i class="fas fa-arrow-right text-2xl text-slate-300 group-hover:text-green-600 transition-colors"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Centros</h3>
            <p class="text-slate-600 text-sm">Gestiona centros de distribución</p>
          </div>
        </NuxtLink>

        <!-- Productos -->
        <NuxtLink to="/compass/productos" class="group">
          <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all group-hover:scale-105">
            <div class="flex items-center justify-between mb-4">
              <div class="bg-amber-100 rounded-lg p-4">
                <i class="fas fa-box text-3xl text-amber-600"></i>
              </div>
              <i class="fas fa-arrow-right text-2xl text-slate-300 group-hover:text-amber-600 transition-colors"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Productos</h3>
            <p class="text-slate-600 text-sm">Administra catálogo de productos</p>
          </div>
        </NuxtLink>

        <!-- Usuarios -->
        <NuxtLink to="/compass/usuarios" class="group">
          <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all group-hover:scale-105">
            <div class="flex items-center justify-between mb-4">
              <div class="bg-purple-100 rounded-lg p-4">
                <i class="fas fa-users text-3xl text-purple-600"></i>
              </div>
              <i class="fas fa-arrow-right text-2xl text-slate-300 group-hover:text-purple-600 transition-colors"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Usuarios</h3>
            <p class="text-slate-600 text-sm">Gestiona usuarios del sistema</p>
          </div>
        </NuxtLink>

        <!-- Reportes -->
        <NuxtLink to="/compass/reportes" class="group">
          <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all group-hover:scale-105">
            <div class="flex items-center justify-between mb-4">
              <div class="bg-red-100 rounded-lg p-4">
                <i class="fas fa-chart-line text-3xl text-red-600"></i>
              </div>
              <i class="fas fa-arrow-right text-2xl text-slate-300 group-hover:text-red-600 transition-colors"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Reportes</h3>
            <p class="text-slate-600 text-sm">Genera reportes del sistema</p>
          </div>
        </NuxtLink>

        <!-- Configuración -->
        <NuxtLink to="/compass/configuracion" class="group">
          <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all group-hover:scale-105">
            <div class="flex items-center justify-between mb-4">
              <div class="bg-slate-100 rounded-lg p-4">
                <i class="fas fa-cog text-3xl text-slate-600"></i>
              </div>
              <i class="fas fa-arrow-right text-2xl text-slate-300 group-hover:text-slate-600 transition-colors"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Configuración</h3>
            <p class="text-slate-600 text-sm">Ajustes del sistema</p>
          </div>
        </NuxtLink>
      </div>

      <!-- Info Card -->
      <div class="bg-blue-50 border-l-4 border-blue-500 rounded-xl p-6">
        <div class="flex items-start">
          <i class="fas fa-shield-alt text-2xl text-blue-600 mr-4 mt-1"></i>
          <div>
            <h3 class="font-bold text-slate-900 mb-2">Panel de Administración</h3>
            <p class="text-slate-600 text-sm mb-3">
              Este es un módulo de administración restringido. Desde aquí puedes gestionar todos los aspectos del sistema.
            </p>
            <ul class="text-slate-600 space-y-1 text-sm">
              <li>• Crear y editar empresas y centros de distribución</li>
              <li>• Administrar catálogo de productos</li>
              <li>• Gestionar usuarios y permisos</li>
              <li>• Generar reportes y análisis del sistema</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
</script>
