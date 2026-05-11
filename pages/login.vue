<template>
  <div class="min-h-screen bg-gradient-to-b from-slate-900 to-slate-800 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
      <div class="bg-white rounded-2xl shadow-xl p-8">
        <div class="flex flex-col items-center text-center mb-6">
          <div class="w-16 h-16 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-2xl mb-3">
            AL
          </div>
          <h1 class="text-2xl font-bold text-slate-900">Iniciar sesión</h1>
          <p class="text-sm text-slate-500 mt-1">Sistema de Gestión ALOGIS</p>
        </div>

        <form class="space-y-4" @submit.prevent="handleLogin">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Correo Electrónico</label>
            <div class="relative">
              <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input
                v-model="email"
                type="email"
                required
                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Ingrese su correo electrónico"
                autocomplete="email"
              />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Contraseña</label>
            <div class="relative">
              <i class="fas fa-key absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="w-full pl-10 pr-12 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Ingrese su contraseña"
                autocomplete="current-password"
              />

              <button
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-blue-600 transition text-lg"
                :aria-label="showPassword ? 'Ocultar contraseña' : 'Ver contraseña'"
                @click="showPassword = !showPassword"
              >
                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" aria-hidden="true"></i>
              </button>
            </div>
          </div>

          <div v-if="error" class="text-sm text-red-600">
            {{ error }}
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-blue-700 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-800 transition-colors disabled:opacity-60"
          >
            <span v-if="!loading">Iniciar Sesión</span>
            <span v-else>Ingresando...</span>
          </button>
        </form>

        
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

definePageMeta({ layout: 'auth' })

const email = ref('')
const password = ref('')
const showPassword = ref(false)
const loading = ref(false)
const error = ref('')

const isProd = process.env.NODE_ENV === 'production'
const cookieOptions = {
  path: '/',
  // En producción, algunos navegadores bloquean cookies en previews/iframes si no son SameSite=None
  sameSite: isProd ? 'none' : 'lax',
  secure: isProd,
  // 7 días (en segundos)
  maxAge: 60 * 60 * 24 * 7
}

const authToken = useCookie('auth_token', cookieOptions)
const authUser = useCookie('auth_user', cookieOptions)

const handleLogin = async () => {
  error.value = ''
  loading.value = true

  try {
    const response = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: email.value, password: password.value })
    })

    const data = await response.json()

    if (!response.ok || data?.statusCode || data?.status !== 'success') {
      const msg = data?.errors?.[0] || data?.message || 'No se pudo iniciar sesión'
      throw new Error(msg)
    }

    authToken.value = data.token
    authUser.value = JSON.stringify(data.user || {})

    await navigateTo('/')
  } catch (e) {
    error.value = e?.message || String(e)
  } finally {
    loading.value = false
  }
}
</script>
