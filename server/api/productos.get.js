import { list } from '../utils/mockData'
import { defineEventHandler } from 'h3'

export default defineEventHandler(() => {
  return { data: list('productos') }
})
