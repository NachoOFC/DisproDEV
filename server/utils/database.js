import { neon } from '@neondatabase/serverless'

let sql = null

/**
 * Obtiene la conexión a la base de datos Neon PostgreSQL
 * @returns {Function} Cliente SQL para ejecutar queries
 */
export function getDatabase() {
  if (!sql) {
    const databaseUrl = process.env.DATABASE_URL
    
    if (!databaseUrl) {
      throw new Error('DATABASE_URL no está configurada en las variables de entorno')
    }
    
    sql = neon(databaseUrl)
  }
  
  return sql
}

/**
 * Ejecuta una query SQL usando tagged templates (requerido por Neon)
 * @param {Array} strings - Strings del template
 * @param {Array} values - Valores a insertar
 * @returns {Promise<Array>} Resultados de la query
 */
export async function executeQuery(strings, ...values) {
  try {
    const sql = getDatabase()
    // Neon requiere que uses tagged templates: sql`SELECT * FROM table`
    const result = await sql(strings, ...values)
    return result
  } catch (error) {
    console.error('Error ejecutando query:', error)
    throw error
  }
}
