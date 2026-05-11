import { executeQuery } from '../utils/database'
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
    
    if (!body || !body.cliente_id) {
      return {
        statusCode: 400,
        message: 'Bad Request',
        errors: ['El campo cliente_id es requerido']
      }
    }
    
    const result = await executeQuery(
      `INSERT INTO factura_electronicas (numero, cliente_id, fecha, total, estado, created_at, updated_at) 
       VALUES ($1, $2, $3, $4, $5, NOW(), NOW()) 
       RETURNING *`,
      [
        body.numero || `F-${Date.now()}`,
        body.cliente_id,
        body.fecha || getLocalISODate(),
        body.total || 0,
        body.estado || 'pendiente'
      ]
    )
    
    return { data: result[0] }
  } catch (error) {
    console.error('Error creando factura:', error)
    return {
      statusCode: 500,
      message: 'Error al crear factura',
      error: error.message
    }
  }
})
