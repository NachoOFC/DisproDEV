<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <NuxtLink to="/" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-4">
          <i class="fas fa-arrow-left mr-2"></i>
          Volver al Inicio
        </NuxtLink>
        <h1 class="text-4xl font-bold text-slate-900 mb-2">📊 Reportes y Descargas</h1>
        <p class="text-slate-600">Genera y descarga reportes en PDF</p>
      </div>

      <!-- Tabs -->
      <div class="mb-6 flex gap-4 flex-wrap">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="[
            'px-6 py-3 rounded-lg font-semibold transition-all',
            activeTab === tab.id
              ? 'bg-purple-500 text-white shadow-lg'
              : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'
          ]"
        >
          <i :class="tab.icon" class="mr-2"></i>
          {{ tab.label }}
        </button>
      </div>

      <!-- Contenido -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Reporte de Ventas -->
        <div v-if="activeTab === 'ventas'" class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-500">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-slate-900">Reporte de Ventas</h3>
            <i class="fas fa-chart-bar text-2xl text-blue-500"></i>
          </div>
          <p class="text-slate-600 mb-6">Resumen de todas las ventas y facturación del período</p>
          <div class="space-y-2 mb-6">
            <p class="text-sm text-slate-700"><strong>Período:</strong> Octubre 2024</p>
            <p class="text-sm text-slate-700"><strong>Total Facturado:</strong> $1,250,000</p>
            <p class="text-sm text-slate-700"><strong>Facturas Emitidas:</strong> 2</p>
          </div>
          <button
            @click="descargarReporte('ventas')"
            class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition font-semibold flex items-center justify-center gap-2"
          >
            <i class="fas fa-download"></i>
            Descargar PDF
          </button>
        </div>

        <!-- Reporte de Inventario -->
        <div v-if="activeTab === 'inventario'" class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-green-500">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-slate-900">Reporte de Inventario</h3>
            <i class="fas fa-boxes text-2xl text-green-500"></i>
          </div>
          <p class="text-slate-600 mb-6">Estado actual del stock en todos los centros</p>
          <div class="space-y-2 mb-6">
            <p class="text-sm text-slate-700"><strong>Centro Santiago:</strong> 150 productos</p>
            <p class="text-sm text-slate-700"><strong>Centro Valparaíso:</strong> 89 productos</p>
            <p class="text-sm text-slate-700"><strong>Stock Total:</strong> 239 productos</p>
          </div>
          <button
            @click="descargarReporte('inventario')"
            class="w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition font-semibold flex items-center justify-center gap-2"
          >
            <i class="fas fa-download"></i>
            Descargar PDF
          </button>
        </div>

        <!-- Reporte de Entregas -->
        <div v-if="activeTab === 'entregas'" class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-cyan-500">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-slate-900">Reporte de Entregas</h3>
            <i class="fas fa-truck text-2xl text-cyan-500"></i>
          </div>
          <p class="text-slate-600 mb-6">Resumen de guías de despacho y entregas realizadas</p>
          <div class="space-y-2 mb-6">
            <p class="text-sm text-slate-700"><strong>Guías Emitidas:</strong> 3</p>
            <p class="text-sm text-slate-700"><strong>Entregas Completadas:</strong> 2</p>
            <p class="text-sm text-slate-700"><strong>En Tránsito:</strong> 1</p>
          </div>
          <button
            @click="descargarReporte('entregas')"
            class="w-full bg-cyan-500 text-white py-2 px-4 rounded-lg hover:bg-cyan-600 transition font-semibold flex items-center justify-center gap-2"
          >
            <i class="fas fa-download"></i>
            Descargar PDF
          </button>
        </div>

        <!-- Reporte de Proveedores -->
        <div v-if="activeTab === 'proveedores'" class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-orange-500">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-slate-900">Reporte de Proveedores</h3>
            <i class="fas fa-industry text-2xl text-orange-500"></i>
          </div>
          <p class="text-slate-600 mb-6">Órdenes de compra y desempeño de proveedores</p>
          <div class="space-y-2 mb-6">
            <p class="text-sm text-slate-700"><strong>Proveedores Activos:</strong> 3</p>
            <p class="text-sm text-slate-700"><strong>Órdenes Pendientes:</strong> 2</p>
            <p class="text-sm text-slate-700"><strong>Órdenes Completadas:</strong> 5</p>
          </div>
          <button
            @click="descargarReporte('proveedores')"
            class="w-full bg-orange-500 text-white py-2 px-4 rounded-lg hover:bg-orange-600 transition font-semibold flex items-center justify-center gap-2"
          >
            <i class="fas fa-download"></i>
            Descargar PDF
          </button>
        </div>

        <!-- Reporte General -->
        <div v-if="activeTab === 'general'" class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-purple-500">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-slate-900">Reporte General</h3>
            <i class="fas fa-chart-pie text-2xl text-purple-500"></i>
          </div>
          <p class="text-slate-600 mb-6">Resumen consolidado de todas las operaciones</p>
          <div class="space-y-2 mb-6">
            <p class="text-sm text-slate-700"><strong>Período:</strong> Octubre 2024</p>
            <p class="text-sm text-slate-700"><strong>Centros Activos:</strong> 2</p>
            <p class="text-sm text-slate-700"><strong>Usuarios:</strong> 5</p>
          </div>
          <button
            @click="descargarReporte('general')"
            class="w-full bg-purple-500 text-white py-2 px-4 rounded-lg hover:bg-purple-600 transition font-semibold flex items-center justify-center gap-2"
          >
            <i class="fas fa-download"></i>
            Descargar PDF
          </button>
        </div>

        <!-- Reporte de Clientes -->
        <div v-if="activeTab === 'clientes'" class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-pink-500">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-slate-900">Reporte de Clientes</h3>
            <i class="fas fa-users text-2xl text-pink-500"></i>
          </div>
          <p class="text-slate-600 mb-6">Información y actividad de clientes registrados</p>
          <div class="space-y-2 mb-6">
            <p class="text-sm text-slate-700"><strong>Clientes Totales:</strong> 3</p>
            <p class="text-sm text-slate-700"><strong>Órdenes Registradas:</strong> 15</p>
            <p class="text-sm text-slate-700"><strong>Monto Total:</strong> $1,250,000</p>
          </div>
          <button
            @click="descargarReporte('clientes')"
            class="w-full bg-pink-500 text-white py-2 px-4 rounded-lg hover:bg-pink-600 transition font-semibold flex items-center justify-center gap-2"
          >
            <i class="fas fa-download"></i>
            Descargar PDF
          </button>
        </div>
      </div>

      <!-- Información General -->
      <div class="mt-12 bg-gradient-to-r from-purple-50 to-indigo-50 border-l-4 border-purple-500 rounded-lg p-6">
        <h3 class="text-lg font-bold text-purple-600 mb-3">📋 Información sobre Reportes</h3>
        <ul class="space-y-2 text-sm text-slate-700">
          <li>✓ Todos los reportes se generan en formato PDF</li>
          <li>✓ Los datos se obtienen de las operaciones registradas en el sistema</li>
          <li>✓ Es posible descargar múltiples reportes en la misma sesión</li>
          <li>✓ Los archivos se guardan con la fecha y hora de generación</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { generarPdfReporte } from '~/composables/usePdfGenerator'

const activeTab = ref('ventas')

const tabs = [
  { id: 'ventas', label: 'Ventas', icon: 'fas fa-chart-line' },
  { id: 'inventario', label: 'Inventario', icon: 'fas fa-boxes' },
  { id: 'entregas', label: 'Entregas', icon: 'fas fa-truck' },
  { id: 'proveedores', label: 'Proveedores', icon: 'fas fa-industry' },
  { id: 'clientes', label: 'Clientes', icon: 'fas fa-users' },
  { id: 'general', label: 'General', icon: 'fas fa-chart-pie' }
]

const reportes = {
  ventas: {
    titulo: 'REPORTE DE VENTAS',
    periodo: 'Octubre 2024',
    datos: [
      { label: 'Total Facturado', valor: '$1,250,000' },
      { label: 'Facturas Emitidas', valor: '2' },
      { label: 'Promedio por Factura', valor: '$625,000' }
    ]
  },
  inventario: {
    titulo: 'REPORTE DE INVENTARIO',
    periodo: 'Octubre 2024',
    datos: [
      { label: 'Centro Santiago', valor: '150 productos' },
      { label: 'Centro Valparaíso', valor: '89 productos' },
      { label: 'Stock Total', valor: '239 productos' }
    ]
  },
  entregas: {
    titulo: 'REPORTE DE ENTREGAS',
    periodo: 'Octubre 2024',
    datos: [
      { label: 'Guías Emitidas', valor: '3' },
      { label: 'Entregas Completadas', valor: '2' },
      { label: 'En Tránsito', valor: '1' }
    ]
  },
  proveedores: {
    titulo: 'REPORTE DE PROVEEDORES',
    periodo: 'Octubre 2024',
    datos: [
      { label: 'Proveedores Activos', valor: '3' },
      { label: 'Órdenes Pendientes', valor: '2' },
      { label: 'Órdenes Completadas', valor: '5' }
    ]
  },
  clientes: {
    titulo: 'REPORTE DE CLIENTES',
    periodo: 'Octubre 2024',
    datos: [
      { label: 'Clientes Totales', valor: '3' },
      { label: 'Órdenes Registradas', valor: '15' },
      { label: 'Monto Total', valor: '$1,250,000' }
    ]
  },
  general: {
    titulo: 'REPORTE GENERAL DEL SISTEMA',
    periodo: 'Octubre 2024',
    datos: [
      { label: 'Centros Activos', valor: '2' },
      { label: 'Usuarios', valor: '5' },
      { label: 'Empresas', valor: '3' }
    ]
  }
}

const descargarReporte = (tipo) => {
  const reporte = reportes[tipo]
  generarPdfReporte({
    titulo: reporte.titulo,
    periodo: reporte.periodo,
    datos: reporte.datos,
    tipo: tipo
  })
}
</script>
