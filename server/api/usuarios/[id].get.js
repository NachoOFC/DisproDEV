import { findById } from '../../utils/mockData'
import { defineEventHandler } from 'h3'

export default defineEventHandler((event) => {
  const { params } = event
  const id = params.id
  const usuario = findById('usuarios', id)
  if (!usuario) {
    return { statusCode: 404, message: 'Usuario no encontrado' }
  }
  return { data: usuario }
})
