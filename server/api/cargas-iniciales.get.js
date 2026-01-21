import { getDatabase } from '~/server/utils/database'

export default defineEventHandler(async (event) => {
  try {
    const sql = getDatabase()
    const cargas = await sql`
      SELECT 
        ci.*,
        u.name as usuario_nombre
      FROM carga_inicials ci
      LEFT JOIN users u ON ci.usuario_id = u.id
      WHERE ci.deleted_at IS NULL
      ORDER BY ci.created_at DESC, ci.id DESC
    `
    
    return {
      success: true,
      data: cargas,
      message: 'Historial de cargas inicial cargado desde PostgreSQL'
    }
  } catch (error) {
    console.error('Error obteniendo cargas iniciales:', error)
    return {
      success: false,
      data: [],
      error: error.message
    }
  }
})
