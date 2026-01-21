import { getDatabase } from '~/server/utils/database'

export default defineEventHandler(async (event) => {
  try {
    const sql = getDatabase()
    const salidas = await sql`
      SELECT *
      FROM salidas
      WHERE deleted_at IS NULL
      ORDER BY fecha DESC, id DESC
    `
    
    return {
      success: true,
      data: salidas,
      message: 'Salidas de bodega cargadas desde PostgreSQL'
    }
  } catch (error) {
    console.error('Error obteniendo salidas:', error)
    return {
      success: false,
      data: [],
      error: error.message
    }
  }
})
