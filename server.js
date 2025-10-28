import express from 'express'
import mysql from 'mysql2/promise'
import cors from 'cors'
import dotenv from 'dotenv'

dotenv.config()

const app = express()
const PORT = process.env.API_PORT || 3001

// Middleware
app.use(cors())
app.use(express.json())

// Configuración de BD
const dbConfig = {
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'dispro_db',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
}

// Pool de conexiones
const pool = mysql.createPool(dbConfig)

/**
 * Función auxiliar para ejecutar queries
 */
async function query(sql, values = []) {
  try {
    const connection = await pool.getConnection()
    const [results] = await connection.execute(sql, values)
    connection.release()
    return results
  } catch (error) {
    console.error('Database error:', error.message)
    return []
  }
}

/**
 * ENDPOINTS API
 */

// Health check
app.get('/health', (req, res) => {
  res.json({ status: 'OK', timestamp: new Date().toISOString() })
})

// GET Requerimientos
app.get('/api/data/requerimientos', async (req, res) => {
  try {
    const sql = `
      SELECT 
        id,
        numero,
        descripcion,
        estado_id,
        created_at
      FROM requerimientos
      LIMIT 100
    `
    const results = await query(sql)
    
    const data = results.map(req => ({
      id: req.id,
      numero: req.numero,
      descripcion: req.descripcion,
      estado: 'Pendiente',
      fecha: new Date(req.created_at).toISOString().split('T')[0]
    }))

    res.json({
      success: true,
      data: data,
      count: data.length
    })
  } catch (error) {
    res.status(500).json({
      success: false,
      error: error.message
    })
  }
})

// GET Productos
app.get('/api/data/productos', async (req, res) => {
  try {
    const sql = `
      SELECT 
        id,
        nombre,
        sku,
        precio,
        stock
      FROM productos
      LIMIT 100
    `
    const results = await query(sql)
    
    const data = results.map(prod => ({
      id: prod.id,
      nombre: prod.nombre,
      sku: prod.sku,
      precio: '$' + (prod.precio || 0).toLocaleString('es-CL'),
      stock: prod.stock || 0
    }))

    res.json({
      success: true,
      data: data,
      count: data.length
    })
  } catch (error) {
    res.status(500).json({
      success: false,
      error: error.message
    })
  }
})

// GET Reportes (simulado)
app.get('/api/data/reportes', async (req, res) => {
  try {
    const data = [
      {
        id: 1,
        titulo: `Reporte de Requerimientos - ${new Date().toLocaleDateString('es-CL', { month: 'long', year: 'numeric' })}`,
        fecha: new Date().toISOString().split('T')[0],
        descarga: 'PDF'
      },
      {
        id: 2,
        titulo: 'Análisis de Productos',
        fecha: new Date().toISOString().split('T')[0],
        descarga: 'XLSX'
      },
      {
        id: 3,
        titulo: 'Proyección de Stock',
        fecha: new Date().toISOString().split('T')[0],
        descarga: 'PDF'
      }
    ]

    res.json({
      success: true,
      data: data,
      count: data.length
    })
  } catch (error) {
    res.status(500).json({
      success: false,
      error: error.message
    })
  }
})

// GET Usuarios
app.get('/api/data/usuarios', async (req, res) => {
  try {
    const sql = `
      SELECT 
        id,
        name,
        email
      FROM users
      LIMIT 50
    `
    const results = await query(sql)
    
    const data = results.map(user => ({
      id: user.id,
      nombre: user.name,
      email: user.email,
      rol: 'Usuario',
      estado: 'Activo'
    }))

    res.json({
      success: true,
      data: data,
      count: data.length
    })
  } catch (error) {
    res.status(500).json({
      success: false,
      error: error.message
    })
  }
})

// Error handler
app.use((err, req, res, next) => {
  console.error(err.stack)
  res.status(500).json({
    success: false,
    error: 'Internal server error'
  })
})

// Iniciar servidor
app.listen(PORT, () => {
  console.log(`
╔═══════════════════════════════════════════╗
║  🚀 DisproDEV API Server                  ║
║  ✅ Running on http://localhost:${PORT}     ║
║  📊 Database: ${dbConfig.database}           ║
╚═══════════════════════════════════════════╝
  `)
})
