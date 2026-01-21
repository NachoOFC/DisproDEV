import { getDatabase } from '~/server/utils/database'

export default defineEventHandler(async (event) => {
  try {
    const body = await readBody(event)
    const { nombre, rut, contacto, telefono, email } = body

    if (!nombre || !rut) {
      return {
        success: false,
        message: 'Nombre y RUT son requeridos'
      }
    }

    const sql = getDatabase()
    const result = await sql`
      INSERT INTO proveedors (nombre, rut, contacto, telefono, email, created_at, updated_at)
      VALUES (${nombre}, ${rut}, ${contacto || null}, ${telefono || null}, ${email || null}, NOW(), NOW())
      RETURNING *
    `
    
    return {
      success: true,
      data: result[0],
      message: 'Proveedor creado exitosamente'
    }
  } catch (error) {
    console.error('Error creando proveedor:', error)
    return {
      success: false,
      error: error.message
    }
  }
})
