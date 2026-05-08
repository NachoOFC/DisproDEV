import { getDatabase } from '../../utils/database'
import { defineEventHandler, getRouterParam, readBody } from 'h3'

export default defineEventHandler(async (event) => {
  try {
    const id =
      getRouterParam(event, 'id') ||
      event?.context?.params?.id ||
      event?.params?.id

    if (!id || String(id).trim() === '') {
      return {
        statusCode: 400,
        message: 'Bad Request',
        errors: ['El id es requerido']
      }
    }

    const body = await readBody(event)
    if (!body) {
      return {
        statusCode: 400,
        message: 'Bad Request',
        errors: ['Body requerido']
      }
    }

    const sql = getDatabase()

    const codigo = body.codigo
    const nombre = body.nombre
    const categoriaId = body.categoria_id ?? null
    const descripcion = body.descripcion ?? null
    const precio = body.precio ?? 0
    const stock = body.stock ?? 0
    const stockMinimo = body.stock_minimo ?? 0

    const result = await sql`
      UPDATE productos
      SET
        codigo = ${codigo},
        nombre = ${nombre},
        categoria_id = ${categoriaId},
        descripcion = ${descripcion},
        precio = ${precio},
        stock = ${stock},
        stock_minimo = ${stockMinimo},
        updated_at = NOW()
      WHERE id = ${id} AND deleted_at IS NULL
      RETURNING *
    `

    const producto = result?.[0]
    if (!producto) {
      return { statusCode: 404, message: 'Producto no encontrado' }
    }

    return { data: producto }
  } catch (error) {
    console.error('Error actualizando producto:', error)
    return {
      statusCode: 500,
      message: 'Error al actualizar producto',
      error: error.message
    }
  }
})
