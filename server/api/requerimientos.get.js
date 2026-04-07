import { getDatabase } from '../utils/database'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async () => {
  try {
    const sql = getDatabase()
    const requerimientos = await sql`
      SELECT 
        r.id, 
        r.numero,
        r.fecha, 
        r.total,
        r.estado,
        c.nombre as cliente,
        r.created_at
      FROM requerimientos r
      LEFT JOIN clientes c ON r.cliente_id = c.id
      WHERE r.deleted_at IS NULL 
      ORDER BY r.fecha DESC
    `
    return { data: requerimientos }
  } catch (error) {
    console.error('Error obteniendo requerimientos:', error)
    return { data: [], error: error.message }
  }
})
