import { readFileSync } from 'fs'
import { join } from 'path'

export default defineEventHandler((event) => {
  const url = event.node.req.url || ''
  
  // Interceptar CUALQUIER solicitud de favicon y servir el correcto
  if (url.includes('favicon.svg') || url.includes('favicon.ico')) {
    try {
      const faviconPath = join(process.cwd(), 'public/favicon.svg')
      const faviconData = readFileSync(faviconPath, 'utf-8')
      
      event.node.res.setHeader('Content-Type', 'image/svg+xml; charset=utf-8')
      event.node.res.setHeader('Cache-Control', 'no-cache, no-store, must-revalidate')
      event.node.res.setHeader('Pragma', 'no-cache')
      event.node.res.setHeader('Expires', '0')
      
      // Enviar el favicon SVG
      event.node.res.end(faviconData)
    } catch (error) {
      console.error('Favicon error:', error)
    }
  }
})
