import { defineEventHandler, setCookie } from 'h3'

export default defineEventHandler((event) => {
  const isProd = process.env.NODE_ENV === 'production'

  const cookieOptions = {
    path: '/',
    sameSite: 'lax',
    secure: isProd,
    expires: new Date(0),
    maxAge: 0
  }

  setCookie(event, 'auth_token', '', cookieOptions)
  setCookie(event, 'auth_user', '', cookieOptions)

  return {
    status: 'success',
    message: 'Sesión cerrada'
  }
})
