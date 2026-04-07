import { getDatabase } from '~/server/utils/database'

export default defineEventHandler(async (event) => {
  try {
    const sql = getDatabase()
    const ajustes = await sql`
      SELECT 
        a.*,
        b.nombre as bidon_nombre,
        b.codigo as bidon_codigo
      FROM ajustes a
      LEFT JOIN bidons b ON a.bidon_id = b.id
      WHERE a.deleted_at IS NULL
      ORDER BY a.fecha_ingreso DESC, a.id DESC
    `
    
    return {
      success: true,
      data: ajustes,
      message: 'Ajustes de inventario cargados desde PostgreSQL'
    }
  } catch (error) {
    console.error('Error obteniendo ajustes:', error)
    return {
      success: false,
      data: [],
      error: error.message
    }
  }
})
