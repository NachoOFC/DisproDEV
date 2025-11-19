import { getHeader } from 'h3'
import { createError } from 'h3'

export const requireAuth = (event) => {
  const auth = getHeader(event, 'authorization') || getHeader(event, 'Authorization')
  if (!auth || !auth.startsWith('Bearer ')) {
    throw createError({ statusCode: 401, statusMessage: 'Unauthorized' })
  }
  const token = auth.split(' ')[1]
  // En un backend real validarías el token. Aquí aceptamos cualquier token no vacío.
  if (!token || token.length < 1) {
    throw createError({ statusCode: 401, statusMessage: 'Unauthorized' })
  }
  // Opcional: devolver info del usuario mock
  return { id: 1, nombre: 'Mock User', token }
}
