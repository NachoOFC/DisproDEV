import { getDatabase } from '~/server/utils/database'

export default defineEventHandler(async (event) => {
  try {
    const body = await readBody(event)
    const { proveedor_id, numero, fecha, total, estado } = body

    if (!proveedor_id || !numero || !fecha) {
      return {
        success: false,
        message: 'Proveedor, número y fecha son requeridos'
      }
    }

    const sql = getDatabase()
    const result = await sql`
      INSERT INTO orden_compras (proveedor_id, numero, fecha, total, estado, created_at, updated_at)
      VALUES (
        ${proveedor_id}, 
        ${numero}, 
        ${fecha}, 
        ${total || 0}, 
        ${estado || 'pendiente'}, 
        NOW(), 
        NOW()
      )
      RETURNING *
    `
    
    return {
      success: true,
      data: result[0],
      message: 'Orden de compra creada exitosamente'
    }
  } catch (error) {
    console.error('Error creando orden de compra:', error)
    return {
      success: false,
      error: error.message
    }
  }
})
