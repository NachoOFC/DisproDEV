import { executeQuery } from '../../utils/database'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async (event) => {
  try {
    const { params } = event
    const id = params.id
    
    const result = await executeQuery(
      'SELECT id, name, email, created_at FROM users WHERE id = $1 AND deleted_at IS NULL',
      [id]
    )
    
    const usuario = result[0]
    
    if (!usuario) {
      return { statusCode: 404, message: 'Usuario no encontrado' }
    }
    
    return { data: usuario }
  } catch (error) {
    console.error('Error obteniendo usuario:', error)
    return {
      statusCode: 500,
      message: 'Error al obtener usuario',
      error: error.message
    }
  }
})
