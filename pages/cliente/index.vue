<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <NuxtLink to="/" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-4">
          <i class="fas fa-arrow-left mr-2"></i>
          Volver al Inicio
        </NuxtLink>
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Módulo Cliente</h1>
        <p class="text-slate-600">Gestiona tus órdenes, presupuestos y seguimiento de entregas</p>
      </div>

      <!-- Quick Access Buttons -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Requerimientos -->
        <NuxtLink to="/cliente/requerimientos" class="group">
          <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all group-hover:scale-105">
            <div class="flex items-center justify-between mb-4">
              <div class="text-5xl">📋</div>
              <i class="fas fa-arrow-right text-2xl text-slate-300 group-hover:text-blue-600 transition-colors"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Mis Órdenes de Pedido</h3>
            <p class="text-slate-600">Crea y gestiona tus órdenes de pedido</p>
          </div>
        </NuxtLink>

        <!-- Presupuesto -->
        <NuxtLink to="/cliente/presupuesto" class="group">
          <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all group-hover:scale-105">
            <div class="flex items-center justify-between mb-4">
              <div class="text-5xl">💰</div>
              <i class="fas fa-arrow-right text-2xl text-slate-300 group-hover:text-cyan-600 transition-colors"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Presupuesto / Cuenta Corriente</h3>
            <p class="text-slate-600">Revisa tu saldo disponible y estado de cuenta</p>
          </div>
        </NuxtLink>

        <!-- Seguimiento -->
        <NuxtLink to="/cliente/seguimiento" class="group">
          <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all group-hover:scale-105">
            <div class="flex items-center justify-between mb-4">
              <div class="text-5xl">🚚</div>
              <i class="fas fa-arrow-right text-2xl text-slate-300 group-hover:text-amber-600 transition-colors"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Seguimiento de Pedidos</h3>
            <p class="text-slate-600">Monitorea el estado de tus entregas en tiempo real</p>
          </div>
        </NuxtLink>

        <!-- Reportes -->
        <NuxtLink to="/cliente/reportes" class="group">
          <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all group-hover:scale-105">
            <div class="flex items-center justify-between mb-4">
              <div class="text-5xl">📊</div>
              <i class="fas fa-arrow-right text-2xl text-slate-300 group-hover:text-rose-600 transition-colors"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Reportes</h3>
            <p class="text-slate-600">Descarga reportes de tu actividad y facturación</p>
          </div>
        </NuxtLink>
      </div>

      <!-- Info Card -->
      <div class="mt-12 bg-blue-50 border-l-4 border-blue-500 rounded-xl p-6">
        <div class="flex items-start">
          <i class="fas fa-lightbulb text-2xl text-blue-600 mr-4 mt-1"></i>
          <div>
            <h3 class="font-bold text-slate-900 mb-2">Consejos útiles</h3>
            <ul class="text-slate-600 space-y-1 text-sm">
              <li>• Crea nuevas órdenes desde la sección "Mis Órdenes de Pedido"</li>
              <li>• Revisa tu cuenta corriente y realiza pagos en "Presupuesto"</li>
              <li>• Monitorea tus entregas en tiempo real en "Seguimiento"</li>
              <li>• Descarga reportes personalizados en "Reportes"</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
</script>
