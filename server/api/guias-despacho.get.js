import { getDatabase } from '~/server/utils/database'

export default defineEventHandler(async (event) => {
  try {
    const sql = getDatabase()
    const guias = await sql`
      SELECT 
        gd.*,
        r.numero as requerimiento_numero,
        r.cliente_id,
        c.nombre as cliente_nombre
      FROM guia_despachos gd
      LEFT JOIN requerimientos r ON gd.requerimiento_id = r.id
      LEFT JOIN clientes c ON r.cliente_id = c.id
      WHERE gd.deleted_at IS NULL
      ORDER BY gd.fecha DESC, gd.id DESC
    `
    
    return {
      success: true,
      data: guias,
      message: 'Guías de despacho cargadas desde PostgreSQL'
    }
  } catch (error) {
    console.error('Error obteniendo guías de despacho:', error)
    return {
      success: false,
      data: [],
      error: error.message
    }
  }
})
