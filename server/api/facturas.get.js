import { getDatabase } from '../utils/database'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async () => {
  try {
    const sql = getDatabase()
    const facturas = await sql`
      SELECT 
        f.id, 
        f.numero, 
        c.nombre as cliente, 
        f.fecha, 
        f.estado, 
        f.total
      FROM factura_electronicas f
      LEFT JOIN clientes c ON f.cliente_id = c.id
      WHERE f.deleted_at IS NULL
      ORDER BY f.fecha DESC
    `
    return { data: facturas }
  } catch (error) {
    console.error('Error obteniendo facturas:', error)
    return { data: [], error: error.message }
  }
})
