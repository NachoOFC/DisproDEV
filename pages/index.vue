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
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Módulo Clientes -->
        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8 border-t-4 border-blue-500">
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
            Acceder
          </NuxtLink>
        </div>

        <!-- Módulo Compass/Admin -->
        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8 border-t-4 border-green-500">
          <div class="flex items-center mb-4">
            <div class="text-4xl mr-4">⚙️</div>
            <h2 class="text-2xl font-bold text-green-600">Módulo Compass</h2>
          </div>
          <p class="text-gray-600 mb-6">
            Administra empresas, centros, productos, usuarios y reportes del sistema.
          </p>
          <NuxtLink 
            to="/compass" 
            class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-semibold"
          >
            Acceder
          </NuxtLink>
        </div>

        <!-- Módulo Bodeguero -->
        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8 border-t-4 border-yellow-500">
          <div class="flex items-center mb-4">
            <div class="text-4xl mr-4">📦</div>
            <h2 class="text-2xl font-bold text-yellow-600">Módulo Bodeguero</h2>
          </div>
          <p class="text-gray-600 mb-6">
            Gestiona recepción, despacho, inventario y movimientos de productos.
          </p>
          <NuxtLink 
            to="/bodeguero" 
            class="inline-block bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition font-semibold"
          >
            Acceder
          </NuxtLink>
        </div>

        <!-- Módulo Facturación -->
        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8 border-t-4 border-red-500">
          <div class="flex items-center mb-4">
            <div class="text-4xl mr-4">📄</div>
            <h2 class="text-2xl font-bold text-red-600">Facturación</h2>
          </div>
          <p class="text-gray-600 mb-6">
            Emite facturas electrónicas y gestiona notas de crédito.
          </p>
          <NuxtLink 
            to="/facturacion" 
            class="inline-block bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition font-semibold"
          >
            Acceder
          </NuxtLink>
        </div>

        <!-- Módulo Transporte -->
        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8 border-t-4 border-blue-500">
          <div class="flex items-center mb-4">
            <div class="text-4xl mr-4">🚚</div>
            <h2 class="text-2xl font-bold text-blue-600">Guías de Despacho</h2>
          </div>
          <p class="text-gray-600 mb-6">
            Gestiona envíos, rastreo de entregas y guías de despacho.
          </p>
          <NuxtLink 
            to="/transporte" 
            class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold"
          >
            Acceder
          </NuxtLink>
        </div>

        <!-- Módulo Abastecimiento -->
        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8 border-t-4 border-purple-500">
          <div class="flex items-center mb-4">
            <div class="text-4xl mr-4">🏭</div>
            <h2 class="text-2xl font-bold text-purple-600">Abastecimiento</h2>
          </div>
          <p class="text-gray-600 mb-6">
            Gestiona órdenes de compra y proveedores del sistema.
          </p>
          <NuxtLink 
            to="/abastecimiento" 
            class="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition font-semibold"
          >
            Acceder
          </NuxtLink>
        </div>

        <!-- Módulo Ajustes -->
        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8 border-t-4 border-orange-500">
          <div class="flex items-center mb-4">
            <div class="text-4xl mr-4">⚙️</div>
            <h2 class="text-2xl font-bold text-orange-600">Ajustes de Inventario</h2>
          </div>
          <p class="text-gray-600 mb-6">
            Registra ajustes y correcciones de stock en los centros.
          </p>
          <NuxtLink 
            to="/ajustes" 
            class="inline-block bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition font-semibold"
          >
            Acceder
          </NuxtLink>
        </div>

        <!-- Módulo Cierre -->
        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8 border-t-4 border-indigo-500">
          <div class="flex items-center mb-4">
            <div class="text-4xl mr-4">🔒</div>
            <h2 class="text-2xl font-bold text-indigo-600">Cierre de Período</h2>
          </div>
          <p class="text-gray-600 mb-6">
            Cierra períodos contables y genera reportes finales.
          </p>
          <NuxtLink 
            to="/cierre" 
            class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold"
          >
            Acceder
          </NuxtLink>
        </div>

        <!-- Módulo Horarios -->
        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8 border-t-4 border-teal-500">
          <div class="flex items-center mb-4">
            <div class="text-4xl mr-4">🕐</div>
            <h2 class="text-2xl font-bold text-teal-600">Gestión de Horarios</h2>
          </div>
          <p class="text-gray-600 mb-6">
            Administra horarios de funcionamiento de centros.
          </p>
          <NuxtLink 
            to="/horarios" 
            class="inline-block bg-teal-600 text-white px-6 py-3 rounded-lg hover:bg-teal-700 transition font-semibold"
          >
            Acceder
          </NuxtLink>
        </div>

        <!-- Módulo Carga Inicial -->
        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-8 border-t-4 border-lime-500">
          <div class="flex items-center mb-4">
            <div class="text-4xl mr-4">📥</div>
            <h2 class="text-2xl font-bold text-lime-600">Carga de Datos</h2>
          </div>
          <p class="text-gray-600 mb-6">
            Importa datos maestros desde archivos CSV y Excel.
          </p>
          <NuxtLink 
            to="/carga-inicial" 
            class="inline-block bg-lime-600 text-white px-6 py-3 rounded-lg hover:bg-lime-700 transition font-semibold"
          >
            Acceder
          </NuxtLink>
        </div>
      </div>

      <!-- Info Section -->
      <div class="mt-12 bg-blue-50 border-l-4 border-[#039BE5] rounded p-6">
        <h3 class="text-xl font-bold text-[#039BE5] mb-3">Módulos Disponibles</h3>
        <ul class="list-disc list-inside space-y-2 text-gray-700 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
          <li>✅ Cliente - Pedidos y seguimiento</li>
          <li>✅ Compass - Administración</li>
          <li>✅ Bodeguero - Inventario y movimientos</li>
          <li>✅ Facturación - Facturas electrónicas</li>
          <li>✅ Transporte - Guías y rastreo</li>
          <li>✅ Abastecimiento - Compras a proveedores</li>
          <li>✅ Ajustes - Correcciones de inventario</li>
          <li>✅ Cierre - Cierre de períodos</li>
          <li>✅ Horarios - Gestión de horarios</li>
          <li>✅ Carga Inicial - Importar datos maestros</li>
        </ul>
      </div>
    </main>
  </div>
</template>

<script setup>
</script>

