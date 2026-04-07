import { executeQuery } from '../utils/database'
import { readBody } from 'h3'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async (event) => {
  try {
    const body = await readBody(event)
    
    if (!body || !body.email) {
      return {
        statusCode: 400,
        message: 'Bad Request',
        errors: ['El campo email es requerido']
      }
    }
    
    const result = await executeQuery(
      `INSERT INTO users (name, email, password, created_at, updated_at) 
       VALUES ($1, $2, $3, NOW(), NOW()) 
       RETURNING id, name, email, created_at`,
      [
        body.nombre || body.name || 'Usuario',
        body.email,
        body.password || '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
      ]
    )
    
    return { data: result[0] }
  } catch (error) {
    console.error('Error creando usuario:', error)
    return {
      statusCode: 500,
      message: 'Error al crear usuario',
      error: error.message
    }
  }
})
