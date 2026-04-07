import { getDatabase } from '~/server/utils/database'

export default defineEventHandler(async (event) => {
  try {
    const sql = getDatabase()
    const entradas = await sql`
      SELECT *
      FROM entradas
      WHERE deleted_at IS NULL
      ORDER BY fecha DESC, id DESC
    `
    
    return {
      success: true,
      data: entradas,
      message: 'Entradas a bodega cargadas desde PostgreSQL'
    }
  } catch (error) {
    console.error('Error obteniendo entradas:', error)
    return {
      success: false,
      data: [],
      error: error.message
    }
  }
})
