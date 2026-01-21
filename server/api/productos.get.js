import { getDatabase } from '../utils/database'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async () => {
  try {
    const sql = getDatabase()
    const productos = await sql`SELECT id, codigo, nombre, descripcion, precio, stock FROM productos WHERE deleted_at IS NULL ORDER BY id`
    return { data: productos }
  } catch (error) {
    console.error('Error obteniendo productos:', error)
    return { data: [], error: error.message }
  }
})
