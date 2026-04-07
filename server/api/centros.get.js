import { getDatabase } from '../utils/database'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async () => {
  try {
    const sql = getDatabase()
    const centros = await sql`
      SELECT id, nombre, direccion, ciudad, comuna, created_at
      FROM centros 
      WHERE deleted_at IS NULL 
      ORDER BY nombre
    `
    return { data: centros }
  } catch (error) {
    console.error('Error obteniendo centros:', error)
    return { data: [], error: error.message }
  }
})
