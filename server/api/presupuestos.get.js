import { getDatabase } from '~/server/utils/database'

export default defineEventHandler(async (event) => {
  try {
    const sql = getDatabase()
    const presupuestos = await sql`
      SELECT 
        p.*,
        c.nombre as cliente_nombre
      FROM presupuestos p
      LEFT JOIN clientes c ON p.cliente_id = c.id
      WHERE p.deleted_at IS NULL
      ORDER BY p.fecha DESC, p.id DESC
    `
    
    return {
      success: true,
      data: presupuestos,
      message: 'Presupuestos cargados desde PostgreSQL'
    }
  } catch (error) {
    console.error('Error obteniendo presupuestos:', error)
    return {
      success: false,
      data: [],
      error: error.message
    }
  }
})
