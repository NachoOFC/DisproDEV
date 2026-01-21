import { getDatabase } from '../utils/database'
import { readBody } from 'h3'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async (event) => {
  try {
    const body = await readBody(event)
    
    if (!body || !body.cliente_id || !body.numero) {
      return {
        statusCode: 400,
        message: 'Bad Request',
        errors: ['Los campos cliente_id y numero son requeridos']
      }
    }
    
    const sql = getDatabase()
    const cliente_id = body.cliente_id
    const numero = body.numero
    const fecha = body.fecha || new Date().toISOString().split('T')[0]
    const total = body.total || 0
    const estado = body.estado || 'pendiente'
    
    const result = await sql`
      INSERT INTO requerimientos (cliente_id, numero, fecha, total, estado, created_at, updated_at) 
      VALUES (${cliente_id}, ${numero}, ${fecha}, ${total}, ${estado}, NOW(), NOW()) 
      RETURNING *
    `
    
    return { data: result[0], success: true }
  } catch (error) {
    console.error('Error creando requerimiento:', error)
    return { 
      statusCode: 500,
      message: 'Error al crear requerimiento',
      error: error.message 
    }
  }
})
