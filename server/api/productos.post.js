import { getDatabase } from '../utils/database'
import { readBody } from 'h3'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async (event) => {
  try {
    const body = await readBody(event)
    
    if (!body || !body.nombre) {
      return {
        statusCode: 400,
        message: 'Bad Request',
        errors: ['El campo nombre es requerido']
      }
    }
    
    const sql = getDatabase()
    const codigo = body.codigo || `PROD-${Date.now()}`
    const nombre = body.nombre
    const descripcion = body.descripcion || null
    const precio = body.precio || 0
    const stock = body.stock || 0
    
    const result = await sql`
      INSERT INTO productos (codigo, nombre, descripcion, precio, stock, created_at, updated_at) 
      VALUES (${codigo}, ${nombre}, ${descripcion}, ${precio}, ${stock}, NOW(), NOW()) 
      RETURNING *
    `
    
    return { data: result[0] }
  } catch (error) {
    console.error('Error creando producto:', error)
    return { 
      statusCode: 500,
      message: 'Error al crear producto',
      error: error.message 
    }
  }
})
