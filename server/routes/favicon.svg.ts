import { readFileSync } from 'fs'
import { join } from 'path'
import { defineEventHandler } from 'h3'

export default defineEventHandler((event) => {
  const faviconPath = join(process.cwd(), 'public/favicon.svg')
  
  try {
    const faviconData = readFileSync(faviconPath, 'utf-8')
    event.node.res.setHeader('Content-Type', 'image/svg+xml')
    event.node.res.setHeader('Cache-Control', 'public, max-age=31536000, immutable')
    return faviconData
  } catch (error) {
    console.error('Error reading favicon:', error)
    event.node.res.statusCode = 404
    return 'Not Found'
  }
})
