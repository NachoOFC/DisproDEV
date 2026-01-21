import { getDatabase } from '~/server/utils/database'

export default defineEventHandler(async (event) => {
  try {
    const sql = getDatabase()
    const notas = await sql`
      SELECT 
        nc.*,
        fe.numero as factura_numero,
        fe.cliente_id,
        c.nombre as cliente_nombre
      FROM nota_creditos nc
      LEFT JOIN factura_electronicas fe ON nc.factura_id = fe.id
      LEFT JOIN clientes c ON fe.cliente_id = c.id
      WHERE nc.deleted_at IS NULL
      ORDER BY nc.fecha DESC, nc.id DESC
    `
    
    return {
      success: true,
      data: notas,
      message: 'Notas de crédito cargadas desde PostgreSQL'
    }
  } catch (error) {
    console.error('Error obteniendo notas de crédito:', error)
    return {
      success: false,
      data: [],
      error: error.message
    }
  }
})
