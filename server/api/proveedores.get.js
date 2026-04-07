import { getDatabase } from '~/server/utils/database'

export default defineEventHandler(async (event) => {
  try {
    const sql = getDatabase()
    const proveedores = await sql`
      SELECT * FROM proveedors 
      WHERE deleted_at IS NULL 
      ORDER BY id DESC
    `
    
    return {
      success: true,
      data: proveedores,
      message: 'Proveedores cargados desde PostgreSQL'
    }
  } catch (error) {
    console.error('Error obteniendo proveedores:', error)
    return {
      success: false,
      data: [],
      error: error.message
    }
  }
})
