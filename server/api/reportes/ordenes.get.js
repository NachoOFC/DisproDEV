import { getDatabase } from '../../utils/database'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async () => {
  try {
    const sql = getDatabase()

    const rows = await sql`
      SELECT
        r.id,
        r.numero,
        COALESCE(r.created_at, r.fecha::timestamp) AS fecha_creacion,
        COALESCE(r.updated_at, r.created_at, r.fecha::timestamp) AS ultima_actualizacion,
        r.estado,
        COALESCE(SUM(pr.cantidad), 0)::int AS cantidad_productos
      FROM requerimientos r
      LEFT JOIN producto_requerimiento pr ON pr.requerimiento_id = r.id
      WHERE r.deleted_at IS NULL
      GROUP BY r.id
      ORDER BY r.fecha DESC, r.id DESC
    `

    const data = rows.map((r) => ({
      id: r.id,
      numero: r.numero,
      fechaCreacion: r.fecha_creacion,
      ultimaActualizacion: r.ultima_actualizacion,
      estado: r.estado,
      cantidadProductos: r.cantidad_productos
    }))

    return { success: true, data }
  } catch (error) {
    console.error('Error obteniendo reporte de órdenes:', error)
    return { success: false, data: [], error: error.message }
  }
})
