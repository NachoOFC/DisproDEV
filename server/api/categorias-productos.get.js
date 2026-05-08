import { getDatabase } from '../utils/database'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async () => {
  try {
    const sql = getDatabase()
    const categorias = await sql`
      SELECT id, nombre
      FROM categorias_productos
      WHERE deleted_at IS NULL
      ORDER BY nombre
    `
    return { data: categorias }
  } catch (error) {
    console.error('Error obteniendo categorías de productos:', error)
    return { data: [], error: error.message }
  }
})
