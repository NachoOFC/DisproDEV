<template>
  <div class="min-h-screen bg-gradient-to-b from-slate-50 to-gray-100">
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Módulo Clientes -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-md hover:shadow-lg transition p-6 border-t-4 border-blue-500">
          <div class="text-center mb-4">
            <div class="text-4xl mb-2">👥</div>
            <h2 class="text-xl font-bold text-blue-600">Módulo Cliente</h2>
          </div>
          <p class="text-gray-600 mb-4 text-center text-sm">
            Gestiona tus pedidos, requerimientos y seguimiento de envíos en tiempo real.
          </p>
          <div class="flex justify-center">
            <NuxtLink 
              to="/cliente" 
              class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold text-base"
            >
              Acceder →
            </NuxtLink>
          </div>
        </div>

        <!-- Módulo Compass/Admin -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-t-4 border-green-500">
          <div class="text-center mb-4">
            <div class="text-4xl mb-2">⚙️</div>
            <h2 class="text-xl font-bold text-green-600">Módulo Compass</h2>
          </div>
          <p class="text-gray-600 mb-4 text-center text-sm">
            Administra empresas, centros, productos, usuarios y reportes del sistema.
          </p>
          <div class="flex justify-center">
            <NuxtLink 
              to="/compass" 
              class="inline-block bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-semibold text-base"
            >
              Acceder →
            </NuxtLink>
          </div>
        </div>

        <!-- Módulo Bodeguero -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-t-4 border-yellow-500">
          <div class="text-center mb-4">
            <div class="text-4xl mb-2">📦</div>
            <h2 class="text-xl font-bold text-yellow-600">Módulo Bodeguero</h2>
          </div>
          <p class="text-gray-600 mb-4 text-center text-sm">
            Gestiona recepción, despacho, inventario y movimientos de productos.
          </p>
          <div class="flex justify-center">
            <NuxtLink 
              to="/bodeguero" 
              class="inline-block bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition font-semibold text-base"
            >
              Acceder →
            </NuxtLink>
          </div>
        </div>

        <!-- Módulo Facturación -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-t-4 border-red-500">
          <div class="text-center mb-4">
            <div class="text-4xl mb-2">📄</div>
            <h2 class="text-xl font-bold text-red-600">Facturación</h2>
          </div>
          <p class="text-gray-600 mb-4 text-center text-sm">
            Emite facturas electrónicas y gestiona notas de crédito.
          </p>
          <div class="flex justify-center">
            <NuxtLink 
              to="/facturacion" 
              class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition font-semibold text-base"
            >
              Acceder →
            </NuxtLink>
          </div>
        </div>

        <!-- Módulo Transporte -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-t-4 border-cyan-500">
          <div class="text-center mb-4">
            <div class="text-4xl mb-2">🚚</div>
            <h2 class="text-xl font-bold text-cyan-600">Guías de Despacho</h2>
          </div>
          <p class="text-gray-600 mb-4 text-center text-sm">
            Gestiona entregas, rastreo de envíos y transportistas.
          </p>
          <div class="flex justify-center">
            <NuxtLink 
              to="/transporte" 
              class="inline-block bg-cyan-600 text-white px-6 py-2 rounded-lg hover:bg-cyan-700 transition font-semibold text-base"
            >
              Acceder →
            </NuxtLink>
          </div>
        </div>

        <!-- Módulo Abastecimiento -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-t-4 border-purple-500">
          <div class="text-center mb-4">
            <div class="text-4xl mb-2">🏭</div>
            <h2 class="text-xl font-bold text-purple-600">Abastecimiento</h2>
          </div>
          <p class="text-gray-600 mb-4 text-center text-sm">
            Gestiona órdenes de compra y proveedores del sistema.
          </p>
          <div class="flex justify-center">
            <NuxtLink 
              to="/abastecimiento" 
              class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition font-semibold text-base"
            >
              Acceder →
            </NuxtLink>
          </div>
        </div>

        <!-- Módulo Ajustes -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-t-4 border-orange-500">
          <div class="text-center mb-4">
            <div class="text-4xl mb-2">⚙️</div>
            <h2 class="text-xl font-bold text-orange-600">Ajustes de Inventario</h2>
          </div>
          <p class="text-gray-600 mb-4 text-center text-sm">
            Registra ajustes y correcciones de stock en los centros.
          </p>
          <div class="flex justify-center">
            <NuxtLink 
              to="/ajustes" 
              class="inline-block bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition font-semibold text-base"
            >
              Acceder →
            </NuxtLink>
          </div>
        </div>

        <!-- Módulo Cierre -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-t-4 border-indigo-500">
          <div class="text-center mb-4">
            <div class="text-4xl mb-2">🔒</div>
            <h2 class="text-xl font-bold text-indigo-600">Cierre de Período</h2>
          </div>
          <p class="text-gray-600 mb-4 text-center text-sm">
            Cierra períodos contables y genera reportes finales.
          </p>
          <div class="flex justify-center">
            <NuxtLink 
              to="/cierre" 
              class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-semibold text-base"
            >
              Acceder →
            </NuxtLink>
          </div>
        </div>

        <!-- Módulo Horarios -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-t-4 border-teal-500">
          <div class="text-center mb-4">
            <div class="text-4xl mb-2">🕐</div>
            <h2 class="text-xl font-bold text-teal-600">Gestión de Horarios</h2>
          </div>
          <p class="text-gray-600 mb-4 text-center text-sm">
            Administra horarios de funcionamiento de centros.
          </p>
          <div class="flex justify-center">
            <NuxtLink 
              to="/horarios" 
              class="inline-block bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition font-semibold text-base"
            >
              Acceder →
            </NuxtLink>
          </div>
        </div>

        <!-- Módulo Carga Inicial -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-t-4 border-lime-500">
          <div class="text-center mb-4">
            <div class="text-4xl mb-2">📥</div>
            <h2 class="text-xl font-bold text-lime-600">Carga de Datos</h2>
          </div>
          <p class="text-gray-600 mb-4 text-center text-sm">
            Importa datos maestros desde archivos CSV y Excel.
          </p>
          <div class="flex justify-center">
            <NuxtLink 
              to="/carga-inicial" 
              class="inline-block bg-lime-600 text-white px-6 py-2 rounded-lg hover:bg-lime-700 transition font-semibold text-base"
            >
              Acceder →
            </NuxtLink>
          </div>
        </div>

        <!-- Módulo Reportes -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-t-4 border-rose-500">
          <div class="text-center mb-4">
            <div class="text-4xl mb-2">📊</div>
            <h2 class="text-xl font-bold text-rose-600">Reportes</h2>
          </div>
          <p class="text-gray-600 mb-4 text-center text-sm">
            Descarga reportes y análisis en PDF de todas las operaciones.
          </p>
          <div class="flex justify-center">
            <NuxtLink 
              to="/reportes" 
              class="inline-block bg-rose-600 text-white px-6 py-2 rounded-lg hover:bg-rose-700 transition font-semibold text-base"
            >
              Acceder →
            </NuxtLink>
          </div>
        </div>

        <!-- Módulo API Docs -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-t-4 border-gray-800">
          <div class="text-center mb-4">
            <div class="text-4xl mb-2">🔌</div>
            <h2 class="text-xl font-bold text-gray-800">Documentación API</h2>
          </div>
          <p class="text-gray-600 mb-4 text-center text-sm">
            Endpoints REST disponibles y ejemplos de integración.
          </p>
          <div class="flex justify-center">
            <NuxtLink 
              to="/api-docs" 
              class="inline-block bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition font-semibold text-base"
            >
              Acceder →
            </NuxtLink>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
</script>

