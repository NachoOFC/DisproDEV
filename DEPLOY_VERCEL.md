# 🚀 GUÍA DE DESPLIEGUE EN VERCEL

## ✅ Pasos para desplegar DisproDEV en Vercel

### PASO 1: Preparar Base de Datos (Neon)

1. Ve a https://neon.tech
2. Crea una cuenta gratuita
3. Crea un nuevo proyecto PostgreSQL
4. Copia la connection string:
   ```
   postgres://username:password@host:5432/database?sslmode=require
   ```

### PASO 2: Preparar Variables de Entorno

1. Edita `.env.production` con tus valores reales:
   ```bash
   APP_KEY=base64:... (mantén la que generó Laravel)
   DB_HOST=tu-neon-host
   DB_PASSWORD=tu-neon-password
   ```

2. Genera una APP_KEY si no la tienes:
   ```bash
   php artisan key:generate
   ```

### PASO 3: Compilar Assets

```bash
# Instalar dependencias
npm install

# Compilar para producción
npm run prod
```

### PASO 4: Conectar a Vercel

1. Ve a https://vercel.com/dashboard
2. Click en "Add New Project"
3. Selecciona tu repositorio `DisproDEV`
4. Click en "Import"

### PASO 5: Configurar Variables de Entorno en Vercel

1. En el panel de Vercel, ve a **Settings → Environment Variables**
2. Agrega estas variables (copia de `.env.production`):

```
APP_NAME=DisproDEV
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-project.vercel.app
DB_CONNECTION=pgsql
DB_HOST=neon-host.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=your_username
DB_PASSWORD=your_password
DB_SSLMODE=require
```

3. Click en "Save"

### PASO 6: Configurar Build Settings

En Vercel:
- **Build Command**: `composer install && npm install && npm run prod`
- **Output Directory**: `public`
- Click en "Deploy"

### PASO 7: Ejecutar Migraciones

Una vez desplegado:

```bash
# Conectar a Vercel CLI
npm i -g vercel

# Acceder al proyecto
vercel

# Ejecutar comando en servidor
vercel env pull

# Luego correr migraciones
php artisan migrate --force
```

O usar SSH:
```bash
vercel ssh
cd /var/task
php artisan migrate --force
```

---

## 🗄️ Estructura del Proyecto para Vercel

```
DisproDEV/
├── api/
│   └── index.php              ← Entry point para Vercel
├── vercel.json                ← Configuración de Vercel
├── .env.production            ← Variables de prod
├── app/
├── public/                    ← Assets estáticos
├── resources/
├── routes/
├── database/
│   └── migrations/
└── package.json
```

---

## 🐛 Troubleshooting

### Error: "Cannot find module..."
```bash
vercel env pull
npm install
npm run prod
```

### Error en BD: "Connection refused"
- Verifica que Neon está activo
- Comprueba credenciales en `.env`
- Usa `sslmode=require` en connection string

### Migrations no se ejecutan
```bash
# Conectar a Vercel
vercel ssh

# Ejecutar dentro del servidor
cd /var/task
php artisan migrate --force --seed
```

### Assets no cargan
- Asegúrate de que `npm run prod` compiló correctamente
- Verifica que los archivos estén en `public/`
- Limpia caché: `php artisan config:clear`

---

## 📱 URLs Importantes

- **Vercel Dashboard**: https://vercel.com/dashboard
- **Neon Console**: https://console.neon.tech
- **Tu Aplicación**: `https://your-project.vercel.app`

---

## ⚠️ Notas Importantes

1. **NO incluyas** `.env` en Git (ya está en `.gitignore`)
2. **Siempre usa** `.env.production` para producción
3. **APP_KEY no debe estar en Git** - configúralo en Vercel UI
4. **SSL es obligatorio** con Neon (`sslmode=require`)
5. **Backup tu BD** regularmente

---

## 🎯 Resumen Rápido

```
1. Setup Neon DB ✓
2. Editar .env.production
3. npm run prod
4. Conectar GitHub a Vercel
5. Agregar variables env en Vercel UI
6. Deploy automático
7. Correr migraciones
8. ¡LISTO! 🎉
```

**Tiempo total: ~15 minutos**

---

**¿Preguntas?** Revisa los logs en Vercel: Dashboard → Deployments → Build Logs
