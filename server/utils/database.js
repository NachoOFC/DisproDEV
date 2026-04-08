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
 * Ejecuta una query SQL con Neon.
 * Soporta tanto tagged templates como consultas parametrizadas clasicas.
 * @param {string|Array} query - SQL en texto plano o arreglo de strings del template
 * @param {Array|any} values - Parametros de la consulta
 * @returns {Promise<Array>} Resultados de la query
 */
export async function executeQuery(query, values = []) {
  try {
    const sql = getDatabase()
    if (Array.isArray(query)) {
      const templateValues = Array.isArray(values) ? values : [values]
      const result = await sql(query, ...templateValues)
      return result
    }

    const params = Array.isArray(values) ? values : [values]
    const result = await sql.query(query, params)
    return result
  } catch (error) {
    console.error('Error ejecutando query:', error)
    throw error
  }
}
