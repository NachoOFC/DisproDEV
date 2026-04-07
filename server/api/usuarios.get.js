import { getDatabase } from '../utils/database'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async () => {
  try {
    const sql = getDatabase()
    const usuarios = await sql`SELECT id, name, email, created_at FROM users WHERE deleted_at IS NULL ORDER BY id`
    return { data: usuarios }
  } catch (error) {
    console.error('Error obteniendo usuarios:', error)
    return { data: [], error: error.message }
  }
})
