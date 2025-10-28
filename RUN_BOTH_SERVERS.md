# 🚀 Ejecutar DisproDEV Completo

## ⚙️ Arquitectura

El proyecto ahora consiste en dos servidores **SIN PHP**:

```
┌─────────────────────────────────────┐
│  Nuxt 3 Frontend (Vue 3)            │
│  Puerto: 3000                        │
│  http://localhost:3000              │
└──────────────────┬──────────────────┘
                   │ (HTTP Requests)
                   ↓
┌─────────────────────────────────────┐
│  Express API Backend (Node.js)      │
│  Puerto: 3001                        │
│  http://localhost:3001              │
│  Conectado a MariaDB                │
└─────────────────────────────────────┘
```

## 📋 Requisitos

### Frontend (Nuxt 3)
- Node.js 20+
- npm

### Backend (Express)
- Node.js 20+
- npm
- **MariaDB/MySQL** (ya instalado, necesario solo para DB)

## 🚀 Inicio Rápido

### Opción 1: Un comando para ambos servidores
```bash
cd c:\Users\ignac\OneDrive\Escritorio\repositorios\DisproDEV
npm run dev:all
```

### Opción 2: Dos terminales separadas (Recomendado)

#### Terminal 1: Express Backend
```bash
cd c:\Users\ignac\OneDrive\Escritorio\repositorios\DisproDEV
npm run dev:server
```

El servidor estará en: `http://localhost:3001`

#### Terminal 2: Nuxt Frontend
```bash
cd c:\Users\ignac\OneDrive\Escritorio\repositorios\DisproDEV
npm run dev
```

El frontend estará en: `http://localhost:3000`

## 🔌 Flujo de Datos

1. **Frontend solicita datos** (http://localhost:3000/productos)
2. **Nuxt hace request a API** (`http://localhost:3001/api/data/productos`)
3. **Express consulta BD** (MariaDB con tabla `productos`)
4. **Express retorna JSON** con los datos
5. **Nuxt renderiza** los datos en la página

## 📊 Endpoints API disponibles

| Endpoint | Método | Descripción |
|----------|--------|-------------|
| `/api/data/requerimientos` | GET | Lista de requerimientos de BD |
| `/api/data/productos` | GET | Catálogo de productos de BD |
| `/api/data/reportes` | GET | Lista de reportes simulados |
| `/api/data/usuarios` | GET | Listado de usuarios de BD |
| `/health` | GET | Health check del servidor |

## ⚙️ Variables de Entorno

Asegúrate que tu `.env` tenga la configuración de la BD:

```env
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
DB_NAME=mlinecl_alogis_dev
API_PORT=3001
```

## 🔍 Verificar que funciona

1. Terminal 1: `npm run dev:server`
   - Deberías ver: `🚀 DisproDEV API Server ✅ Running on http://localhost:3001`
   - Prueba: http://localhost:3001/health

2. Terminal 2: `npm run dev`
   - Abre http://localhost:3000 en el navegador
   - Deberías ver el dashboard de DisproDEV
   - Las tablas deberían mostrar datos de la BD

3. F12 → Console en el navegador
   - Sin errores de CORS
   - Sin errores de conexión

## 🐛 Troubleshooting

### "Cannot GET /api/data/requerimientos"
- Verifica que el servidor Express está corriendo en puerto 3001
- Revisa la consola de Express para errores

### "La tabla no existe"
- Verifica que tienes datos en la BD: `mlinecl_alogis_dev`
- Revisa que las tablas `requerimientos`, `productos`, `users` existen

### "CORS error"
- El servidor Express tiene CORS habilitado
- Verifica que URL Base sea correcta en `composables/useMockData.js`

### "Timeout al conectar a BD"
- Verifica credenciales en `.env`
- Asegúrate que MariaDB está corriendo
- Prueba: `mysql -u root -h localhost -p`

## 📝 Desarrollo

- **Backend Express**: Modifica `server.js`
- **Frontend Vue**: Modifica `/components/` y `/pages/`
- **Datos**: Se cargan automáticamente del composable `useMockData.js`
- **BD**: Los datos vienen directamente de MariaDB

## ✅ Stack Final (SIN PHP)

- Frontend: **Nuxt 3 + Vue 3 + Tailwind**
- Backend: **Express + Node.js**
- Database: **MariaDB**
- Deployment: **Netlify (Frontend) + Railway/Render (Backend)**

---

**¡Ya no necesitas PHP! Todo es JavaScript/Node.js y lista para Netlify!** 🎉

