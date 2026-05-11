import { getDatabase } from '../utils/database'
import { readBody } from 'h3'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async (event) => {
  try {
    const body = await readBody(event)

    const getLocalISODate = (d = new Date()) => {
      const year = d.getFullYear()
      const month = String(d.getMonth() + 1).padStart(2, '0')
      const day = String(d.getDate()).padStart(2, '0')
      return `${year}-${month}-${day}`
    }
    
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
    const fecha = body.fecha || getLocalISODate()
    const total = body.total || 0
    const estado = body.estado || 'pendiente'
    
    const nombre = body.nombre || `Requerimiento ${numero}`
    
    const result = await sql`
      INSERT INTO requerimientos (cliente_id, numero, nombre, fecha, total, estado, created_at, updated_at) 
      VALUES (${cliente_id}, ${numero}, ${nombre}, ${fecha}, ${total}, ${estado}, NOW(), NOW()) 
      RETURNING *
    `
    
    return { data: result[0], success: true }
  } catch (error) {
    console.error('Error creando requerimiento:', error.message)
    console.error('Stack:', error.stack)
    return { 
      statusCode: 500,
      message: 'Error al crear requerimiento',
      error: error.message 
    }
  }
})
