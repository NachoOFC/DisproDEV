import { getDatabase } from '../../utils/database'
import { defineEventHandler, getRouterParam, readBody } from 'h3'

const ALLOWED_ESTADOS = new Set([
  'pendiente',
  'aprobada',
  'rechazada',
  'entregada',
  'reenviada',
  // Compatibilidad por si alguna UI manda estas variantes
  'aprobado',
  'rechazado',
  'entregado'
])

export default defineEventHandler(async (event) => {
  try {
    const id =
      getRouterParam(event, 'id') ||
      event?.context?.params?.id ||
      event?.params?.id

    if (!id || String(id).trim() === '' || String(id) === 'undefined') {
      return {
        statusCode: 400,
        message: 'Bad Request',
        errors: ['El parámetro id es requerido']
      }
    }

    const body = await readBody(event)
    const estadoRaw = body?.estado

    if (!estadoRaw) {
      return {
        statusCode: 400,
        message: 'Bad Request',
        errors: ['El campo estado es requerido']
      }
    }

    const estado = String(estadoRaw).toLowerCase().trim()

    if (!ALLOWED_ESTADOS.has(estado)) {
      return {
        statusCode: 400,
        message: 'Bad Request',
        errors: [`Estado inválido: ${estado}`]
      }
    }

    const sql = getDatabase()

    const current = await sql`
      SELECT id, estado
      FROM requerimientos
      WHERE id = ${id} AND deleted_at IS NULL
      LIMIT 1
    `

    if (!current?.length) {
      return { statusCode: 404, message: 'Requerimiento no encontrado' }
    }

    const currentEstado = String(current[0]?.estado || '').toLowerCase().trim()

    if (estado === 'reenviada' && currentEstado !== 'rechazada' && currentEstado !== 'rechazado') {
      return {
        statusCode: 409,
        message: 'Conflict',
        errors: ['Solo se puede reenviar cuando el requerimiento está rechazado']
      }
    }

    const result = await sql`
      UPDATE requerimientos
      SET estado = ${estado}, updated_at = NOW()
      WHERE id = ${id} AND deleted_at IS NULL
      RETURNING *
    `

    if (!result?.length) {
      return { statusCode: 404, message: 'Requerimiento no encontrado' }
    }

    return { success: true, data: result[0] }
  } catch (error) {
    console.error('Error actualizando requerimiento:', error)
    return {
      statusCode: 500,
      message: 'Error al actualizar requerimiento',
      error: error.message
    }
  }
})
