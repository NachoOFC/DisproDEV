import { executeQuery } from '../utils/database'
import { defineEventHandler, readBody, setCookie } from 'h3'
import bcrypt from 'bcryptjs'

export default defineEventHandler(async (event) => {
  try {
    const body = await readBody(event)
    
    if (!body || !body.email || !body.password) {
      return {
        statusCode: 400,
        message: 'Bad Request',
        errors: ['Los campos email y password son requeridos']
      }
    }
    
    // Buscar usuario en BD
    const result = await executeQuery(
      'SELECT id, name, email, password FROM users WHERE email = $1 AND deleted_at IS NULL',
      [body.email]
    )
    
    if (result.length === 0) {
      return {
        statusCode: 401,
        message: 'Credenciales inválidas',
        errors: ['Usuario no encontrado']
      }
    }
    
    const user = result[0]

    const storedPassword = user.password
    const inputPassword = String(body.password)

    let validPassword = false
    if (typeof storedPassword === 'string' && storedPassword.startsWith('$2')) {
      validPassword = bcrypt.compareSync(inputPassword, storedPassword)
    } else {
      // Demo/simple: si no es hash bcrypt, comparamos texto plano
      validPassword = String(storedPassword || '') === inputPassword
    }

    if (!validPassword) {
      return {
        statusCode: 401,
        message: 'Credenciales inválidas',
        errors: ['Email o contraseña incorrectos']
      }
    }
    
    // Mock token generation (después implementar JWT real)
    const token = 'token-' + Math.random().toString(36).substr(2, 9)

    const isProd = process.env.NODE_ENV === 'production'
    const cookieOptions = {
      path: '/',
      sameSite: 'lax',
      secure: isProd,
      // 7 días (segundos)
      maxAge: 60 * 60 * 24 * 7
    }

    // Setear cookies en el response para que persistan en producción (Netlify)
    setCookie(event, 'auth_token', token, cookieOptions)
    setCookie(
      event,
      'auth_user',
      JSON.stringify({
        id: user.id,
        nombre: user.name,
        email: user.email,
        role: 'admin'
      }),
      cookieOptions
    )
    
    return {
      status: 'success',
      message: 'Autenticación exitosa',
      token: token,
      user: {
        id: user.id,
        nombre: user.name,
        email: user.email,
        role: 'admin'
      }
    }
  } catch (error) {
    console.error('Error en login:', error)
    return {
      statusCode: 500,
      message: 'Error al iniciar sesión',
      error: error.message
    }
  }
})
