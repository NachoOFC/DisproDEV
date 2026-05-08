import { getDatabase } from '../../utils/database'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async () => {
  try {
    const sql = getDatabase()

    const rows = await sql`
      SELECT
        gd.id,
        COALESCE(r.numero, gd.numero) AS pedido,
        gd.fecha AS fecha_despacho,
        NULL::date AS fecha_estimada,
        NULL::date AS fecha_entrega,
        NULL::text AS transporte,
        gd.estado
      FROM guia_despachos gd
      LEFT JOIN requerimientos r ON gd.requerimiento_id = r.id
      WHERE gd.deleted_at IS NULL
      ORDER BY gd.fecha DESC, gd.id DESC
    `

    const data = rows.map((e) => ({
      id: e.id,
      pedido: e.pedido,
      fechaDespacho: e.fecha_despacho,
      fechaEstimada: e.fecha_estimada,
      fechaEntrega: e.fecha_entrega,
      transporte: e.transporte,
      estado: e.estado || 'pendiente'
    }))

    return { success: true, data }
  } catch (error) {
    console.error('Error obteniendo reporte de entregas:', error)
    return { success: false, data: [], error: error.message }
  }
})
