import { getDatabase } from '../utils/database'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async () => {
  try {
    const sql = getDatabase()
    const productos = await sql`
      SELECT
        p.id,
        p.codigo,
        p.nombre,
        p.descripcion,
        p.precio,
        p.stock,
        p.stock_minimo,
        p.categoria_id,
        c.nombre AS categoria
      FROM productos p
      LEFT JOIN categorias_productos c
        ON c.id = p.categoria_id
       AND c.deleted_at IS NULL
      WHERE p.deleted_at IS NULL
      ORDER BY p.id
    `
    return { data: productos }
  } catch (error) {
    console.error('Error obteniendo productos:', error)
    return { data: [], error: error.message }
  }
})
