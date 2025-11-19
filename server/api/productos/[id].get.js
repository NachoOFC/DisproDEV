import { findById } from '../../utils/mockData'
import { defineEventHandler } from 'h3'
import { getEventContext } from 'h3'

export default defineEventHandler((event) => {
  const { params } = event
  const id = params.id
  const producto = findById('productos', id)
  if (!producto) {
    return { statusCode: 404, message: 'Producto no encontrado' }
  }
  return { data: producto }
})
