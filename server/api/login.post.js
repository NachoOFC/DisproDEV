import { defineEventHandler, readBody } from 'h3'

export default defineEventHandler(async (event) => {
  const body = await readBody(event)
  if (!body || !body.email) {
    return {
      statusCode: 400,
      message: 'Bad Request',
      errors: ['El campo email es requerido']
    }
  }
  
  // Mock token generation
  const token = 'mock-token-' + Math.random().toString(36).substr(2, 9)
  
  return {
    status: 'success',
    message: 'Autenticación exitosa',
    token: token,
    user: {
      id: 1,
      nombre: 'Demo User',
      email: body.email,
      role: 'admin'
    }
  }
})
