import { create } from '../utils/mockData'
import { readBody } from 'h3'
import { defineEventHandler } from 'h3'

export default defineEventHandler(async (event) => {
  const body = await readBody(event)
  if (!body || !body.cliente) {
    return {
      statusCode: 400,
      message: 'Bad Request',
      errors: ['El campo cliente es requerido']
    }
  }
  const nuevo = create('facturas', body)
  return { data: nuevo }
})
