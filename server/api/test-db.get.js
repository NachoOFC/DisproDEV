import { getDatabase } from '../utils/database'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async () => {
  try {
    const sql = getDatabase()
    
    // Probar conexión y obtener estadísticas
    const tablesResult = await sql`
      SELECT COUNT(*) as total_tablas 
      FROM information_schema.tables 
      WHERE table_schema = 'public'
    `
    
    const productosResult = await sql`SELECT COUNT(*) as total FROM productos`
    const clientesResult = await sql`SELECT COUNT(*) as total FROM clientes`
    const usuariosResult = await sql`SELECT COUNT(*) as total FROM users`
    
    return {
      success: true,
      message: '✅ Conexión exitosa a Neon PostgreSQL',
      database: {
        totalTablas: parseInt(tablesResult[0].total_tablas),
        estadisticas: {
          productos: parseInt(productosResult[0].total),
          clientes: parseInt(clientesResult[0].total),
          usuarios: parseInt(usuariosResult[0].total)
        }
      },
      timestamp: new Date().toISOString()
    }
  } catch (error) {
    console.error('Error conectando a la base de datos:', error)
    return {
      success: false,
      message: '❌ Error de conexión',
      error: error.message,
      timestamp: new Date().toISOString()
    }
  }
})
