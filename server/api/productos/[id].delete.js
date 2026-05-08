import { getDatabase } from '../../utils/database'
import { defineEventHandler, getRouterParam } from 'h3'

export default defineEventHandler(async (event) => {
  try {
    const id =
      getRouterParam(event, 'id') ||
      event?.context?.params?.id ||
      event?.params?.id

    if (!id || String(id).trim() === '') {
      return {
        statusCode: 400,
        message: 'Bad Request',
        errors: ['El id es requerido']
      }
    }

    const sql = getDatabase()

    const result = await sql`
      UPDATE productos
      SET deleted_at = NOW(), updated_at = NOW()
      WHERE id = ${id} AND deleted_at IS NULL
      RETURNING id
    `

    if (!result?.[0]) {
      return { statusCode: 404, message: 'Producto no encontrado' }
    }

    return { success: true }
  } catch (error) {
    console.error('Error eliminando producto:', error)
    return {
      statusCode: 500,
      message: 'Error al eliminar producto',
      error: error.message
    }
  }
})
