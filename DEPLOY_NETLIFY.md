# 🚀 Deploy a Netlify

Este proyecto es un frontend Nuxt 3 + Vue 3 listo para desplegar en **Netlify**.

## Requisitos previos

- Cuenta de GitHub (ya tienes este repositorio)
- Cuenta de Netlify (gratis en https://netlify.com)
- Node.js y npm instalados

## Pasos para desplegar

### 1️⃣ Opción A: Deploy directo desde GitHub (Recomendado)

1. Ve a [https://app.netlify.com](https://app.netlify.com)
2. Haz clic en **"Add new site"** → **"Import an existing project"**
3. Selecciona **GitHub** como tu repositorio
4. Autoriza Netlify para acceder a tus repositorios de GitHub
5. Selecciona **NachoOFC/DisproDEV**
6. En las opciones de build:
   - **Build command**: `npm run build`
   - **Publish directory**: `.nuxt/dist`
7. Haz clic en **"Deploy site"**

Eso es todo! Netlify automáticamente:
- Detectará cambios en Git
- Ejecutará `npm install`
- Compilará tu proyecto con `npm run build`
- Desplegará el sitio en una URL como `your-site.netlify.app`

### 2️⃣ Opción B: Deploy manual con Netlify CLI

```bash
# Instalar Netlify CLI
npm install -g netlify-cli

# Login en tu cuenta Netlify
netlify login

# Compilar el proyecto
npm run build

# Deploy
netlify deploy --prod --dir=.nuxt/dist
```

## Configuración automática (Netlify.toml)

Crea un archivo `netlify.toml` en la raíz para automatizar la configuración:

```toml
[build]
  command = "npm run build"
  publish = ".nuxt/dist"

[build.environment]
  NODE_VERSION = "20"
```

## Verificar el deployment

Después del deploy:
- La URL de tu sitio aparecerá en la consola de Netlify
- Accede a tu sitio desde el navegador
- Verifica que todas las páginas funcionen correctamente

## Dominio personalizado

Una vez desplegado, puedes agregar tu propio dominio:
1. Ve a **Domain settings** en Netlify
2. Agrega tu dominio personalizado
3. Sigue las instrucciones para configurar el DNS

## Actualizaciones futuras

Cualquier cambio que hagas en tu rama `main` de GitHub se desplegará automáticamente en Netlify.

## Troubleshooting

**Error: "Build failed"**
- Revisa los logs de build en Netlify
- Asegúrate de que `npm install` y `npm run build` funcionen localmente

**Página en blanco**
- Verifica que `ssr: false` esté en `nuxt.config.ts`
- Revisa la consola del navegador (F12) para errores

**Rutas no funcionan**
- En Netlify, crea un archivo `public/_redirects`:
  ```
  /* /index.html 200
  ```

---

**¡Tu sitio está listo para producción!** 🎉
