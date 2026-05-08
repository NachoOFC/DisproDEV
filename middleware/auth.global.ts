export default defineNuxtRouteMiddleware((to) => {
  // Permitir entrar al login sin sesión
  if (to.path === '/login') return

  const token = useCookie<string | null>('auth_token').value

  // Si no hay token, redirigir a login
  if (!token) {
    return navigateTo('/login')
  }
})
