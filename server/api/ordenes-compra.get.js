import { getDatabase } from '~/server/utils/database'

export default defineEventHandler(async (event) => {
  try {
    const sql = getDatabase()
    const ordenes = await sql`
      SELECT 
        oc.*,
        p.nombre as proveedor_nombre
      FROM orden_compras oc
      LEFT JOIN proveedors p ON oc.proveedor_id = p.id
      WHERE oc.deleted_at IS NULL
      ORDER BY oc.fecha DESC, oc.id DESC
    `
    
    return {
      success: true,
      data: ordenes,
      message: 'Órdenes de compra cargadas desde PostgreSQL'
    }
  } catch (error) {
    console.error('Error obteniendo órdenes de compra:', error)
    return {
      success: false,
      data: [],
      error: error.message
    }
  }
})
