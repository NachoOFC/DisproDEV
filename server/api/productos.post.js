import { create } from '../utils/mockData'
import { readBody } from 'h3'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async (event) => {
  const body = await readBody(event)
  if (!body || !body.nombre) {
    return {
      statusCode: 400,
      message: 'Bad Request',
      errors: ['El campo nombre es requerido']
    }
  }
  const nuevo = create('productos', body)
  return { data: nuevo }
})
