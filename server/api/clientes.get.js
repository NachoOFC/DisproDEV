import { getDatabase } from '../utils/database'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async () => {
  try {
    const sql = getDatabase()
    const clientes = await sql`
      SELECT id, nombre, rut, contacto, telefono, email, direccion, created_at
      FROM clientes 
      WHERE deleted_at IS NULL 
      ORDER BY nombre
    `
    return { data: clientes }
  } catch (error) {
    console.error('Error obteniendo clientes:', error)
    return { data: [], error: error.message }
  }
})
