import { getDatabase } from '../../utils/database'
import { defineEventHandler } from 'h3'
import { getEventContext } from 'h3'

export default defineEventHandler(async (event) => {
  try {
    const { params } = event
    const id = params.id
    
    const sql = getDatabase()
    const result = await sql`SELECT * FROM productos WHERE id = ${id} AND deleted_at IS NULL`
    
    const producto = result[0]
    
    if (!producto) {
      return { statusCode: 404, message: 'Producto no encontrado' }
    }
    
    return { data: producto }
  } catch (error) {
    console.error('Error obteniendo producto:', error)
    return { 
      statusCode: 500, 
      message: 'Error al obtener producto',
      error: error.message 
    }
  }
})
