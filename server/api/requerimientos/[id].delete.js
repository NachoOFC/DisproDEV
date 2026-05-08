import { getDatabase } from '../../utils/database'
import { getRouterParam } from 'h3'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async (event) => {
  try {
    const id = getRouterParam(event, 'id') || event?.context?.params?.id || event?.params?.id

    if (!id || id === 'undefined') {
      return {
        statusCode: 400,
        message: 'Bad Request',
        errors: ['El id es requerido']
      }
    }

    const sql = getDatabase()

    // Soft delete: marcar como eliminado sin eliminar de la BD
    const result = await sql`
      UPDATE requerimientos 
      SET deleted_at = NOW(), updated_at = NOW()
      WHERE id = ${id} AND deleted_at IS NULL
      RETURNING *
    `

    if (result.length === 0) {
      return {
        statusCode: 404,
        message: 'Not Found',
        errors: ['Requerimiento no encontrado']
      }
    }

    return { data: result[0], success: true }
  } catch (error) {
    console.error('Error eliminando requerimiento:', error.message)
    console.error('Stack:', error.stack)
    return {
      statusCode: 500,
      message: 'Error al eliminar requerimiento',
      error: error.message
    }
  }
})
