import { getDatabase } from '~/server/utils/database'

export default defineEventHandler(async (event) => {
  try {
    const sql = getDatabase()
    const cierres = await sql`
      SELECT 
        c.*,
        ce.nombre as centro_nombre
      FROM cierres c
      LEFT JOIN centros ce ON c.centro_id = ce.id
      WHERE c.deleted_at IS NULL
      ORDER BY c.fecha_inicio DESC, c.id DESC
    `
    
    return {
      success: true,
      data: cierres,
      message: 'Cierres de período cargados desde PostgreSQL'
    }
  } catch (error) {
    console.error('Error obteniendo cierres:', error)
    return {
      success: false,
      data: [],
      error: error.message
    }
  }
})
