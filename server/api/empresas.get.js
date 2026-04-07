import { getDatabase } from '../utils/database'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async () => {
  try {
    const sql = getDatabase()
    const empresas = await sql`
      SELECT id, razon_social, rut, giro, created_at
      FROM empresas 
      WHERE deleted_at IS NULL 
      ORDER BY razon_social
    `
    return { data: empresas }
  } catch (error) {
    console.error('Error obteniendo empresas:', error)
    return { data: [], error: error.message }
  }
})
