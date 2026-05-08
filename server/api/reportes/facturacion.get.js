import { getDatabase } from '../../utils/database'
import { defineEventHandler } from 'h3'

const isPaid = (estado) => {
  const s = String(estado || '').toLowerCase()
  return s === 'pagado' || s === 'pagada' || s === 'paid'
}

export default defineEventHandler(async () => {
  try {
    const sql = getDatabase()

    const rows = await sql`
      SELECT
        f.id,
        f.numero,
        f.fecha,
        f.total,
        f.estado,
        COALESCE(f.created_at, f.fecha::timestamp) AS created_at
      FROM factura_electronicas f
      WHERE f.deleted_at IS NULL
      ORDER BY f.fecha DESC, f.id DESC
    `

    const data = rows.map((f) => {
      const total = Number(f.total || 0)
      const pagado = isPaid(f.estado) ? total : 0
      const saldo = total - pagado

      return {
        id: f.id,
        numero: f.numero,
        fecha: f.fecha,
        total,
        pagado,
        saldo,
        estado: f.estado || 'pendiente',
        created_at: f.created_at
      }
    })

    return { success: true, data }
  } catch (error) {
    console.error('Error obteniendo reporte de facturación:', error)
    return { success: false, data: [], error: error.message }
  }
})
