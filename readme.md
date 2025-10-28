# DisproDEV 🚀

> Sistema de gestión de distribución - Proyecto de Titulación

**DisproDEV** es una aplicación web moderna construida con **Nuxt 3 + Vue 3** para la gestión integral de requerimientos, productos y reportes de una empresa de distribución.

## ✨ Características

- 📦 **Gestión de Requerimientos**: Crea, consulta y monitorea requerimientos
- 🏪 **Catálogo de Productos**: Visualiza y administra productos con stock en tiempo real
- 📊 **Reportes Avanzados**: Genera y descarga reportes en PDF y Excel
- 🎨 **Interfaz Moderna**: Diseño limpio con paleta de colores celeste
- 📱 **Responsive Design**: Funciona perfectamente en desktop y mobile
- ⚡ **Rendimiento Optimizado**: SPA (Single Page Application) con Nuxt 3
- 🔒 **Seguridad**: Headers de seguridad, validación de entrada

## 🛠️ Stack Tecnológico

- **Frontend Framework**: Nuxt 3
- **UI Library**: Vue 3 (Composition API)
- **Styling**: Tailwind CSS
- **Icónos**: FontAwesome 6
- **HTTP Client**: Axios
- **Deployment**: Netlify

## 📋 Requisitos

- Node.js 20+
- npm o yarn
- Navegador moderno (Chrome, Firefox, Safari, Edge)

## 🚀 Instalación y uso

### 1. Clonar el repositorio

```bash
git clone https://github.com/NachoOFC/DisproDEV.git
cd DisproDEV
```

### 2. Instalar dependencias

```bash
npm install
```

### 3. Ejecutar servidor de desarrollo

```bash
npm run dev
```

El proyecto estará disponible en `http://localhost:3000`

### 4. Compilar para producción

```bash
npm run build
npm run preview
```

Esto generará la carpeta `.nuxt/dist/` lista para deploy.

## 📁 Estructura del Proyecto

```
DisproDEV/
├── pages/                    # Páginas de la aplicación (file-based routing)
│   ├── index.vue            # Dashboard / Inicio
│   ├── requerimientos.vue    # Gestión de requerimientos
│   ├── productos.vue         # Catálogo de productos
│   └── reportes.vue          # Reportes y descargas
├── components/              # Componentes reutilizables
│   └── DataTable.vue        # Tabla para mostrar datos
├── composables/             # Composables de Nuxt 3
│   └── useMockData.js       # Datos simulados
├── public/                  # Archivos estáticos
│   └── _redirects           # Configuración de rutas para Netlify
├── app.vue                  # Componente raíz
├── nuxt.config.ts           # Configuración de Nuxt
├── netlify.toml             # Configuración de Netlify
└── package.json             # Dependencias del proyecto
```

## � Paleta de Colores

| Color | Hex | Uso |
|-------|-----|-----|
| Celeste Principal | `#039BE5` | Headers, botones primarios |
| Celeste Oscuro | `#0277BD` | Hover states |
| Celeste Claro | `#B3E5FC` | Backgrounds, inputs |

## 🚢 Deployment

### En Netlify (Recomendado)

1. Ve a [https://netlify.com](https://netlify.com)
2. Conecta tu repositorio de GitHub
3. Configura el build:
   - **Build Command**: `npm run build`
   - **Publish Directory**: `.nuxt/dist`

Ver más detalles en [DEPLOY_NETLIFY.md](./DEPLOY_NETLIFY.md)

## 🔧 Scripts Disponibles

| Script | Descripción |
|--------|-------------|
| `npm run dev` | Inicia servidor de desarrollo |
| `npm run build` | Compila el proyecto para producción |
| `npm run generate` | Genera sitio estático (SSG) |
| `npm run preview` | Visualiza la compilación final |

## 👤 Autor

**Ignacio Ofc** (NachoOFC)
- 🐙 GitHub: [@NachoOFC](https://github.com/NachoOFC)

**Última actualización**: Octubre 28, 2025 | **Versión**: 1.0.0 (Nuxt 3)


### Tecnologías Utilizadas
- **Backend**: Laravel 9.x, PHP 8.1+
- **Frontend**: Vue.js, Bootstrap 5, Tailwind CSS
- **Base de Datos**: MySQL/MariaDB
- **Herramientas**: Composer, NPM, Webpack Mix
- **Testing**: PHPUnit

## 🎨 Diseño y UX

### Interfaz Moderna
- Diseño responsivo y mobile-first
- Paleta de colores celeste moderno (`#039BE5`)
- Tablas interactivas con estilos personalizados
- Componentes reutilizables
- Animaciones suaves y transiciones

### Estilos Implementados
```css
/* Colores Principales */
--color-primary: #039BE5;        /* Celeste vibrante */
--color-primary-light: #81D4FA;  /* Celeste medio */
--color-primary-lighter: #B3E5FC; /* Celeste claro */
```

Todos los estilos personalizados están disponibles en:
- 📄 `public/css/estilos.css` - Estilos principales
- 📄 `public/css/portfolio-styles.css` - Estilos para portafolio
- [Runtime Converter](http://runtimeconverter.com/)

## 🚀 Inicio Rápido

### Requisitos Previos
- PHP 8.1 o superior
- Composer
- Node.js y NPM
- MySQL/MariaDB 5.7+
- Git

### Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/NachoOFC/DisproDEV.git
cd DisproDEV
```

2. **Instalar dependencias PHP**
```bash
composer install
```

3. **Instalar dependencias Frontend**
```bash
npm install
```

4. **Configurar archivo .env**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurar base de datos**
```bash
# Editar .env con credenciales de BD
DB_HOST=127.0.0.1
DB_DATABASE=dispro_dev
DB_USERNAME=root
DB_PASSWORD=
```

6. **Ejecutar migraciones**
```bash
php artisan migrate
php artisan db:seed
```

7. **Compilar assets**
```bash
npm run dev
# O para producción:
npm run prod
```

8. **Iniciar servidor**
```bash
php artisan serve
# Acceder a: http://localhost:8000
```

## 📁 Estructura del Proyecto

```
DisproDEV/
├── app/                      # Modelos, controladores, middlewares
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Policies/
├── resources/
│   ├── views/               # Vistas Blade
│   │   └── layouts/
│   │   └── compass/
│   └── js/                  # Componentes Vue.js
├── routes/
│   ├── web.php
│   └── api.php
├── database/
│   ├── migrations/
│   └── seeds/
├── public/
│   ├── css/
│   │   ├── estilos.css
│   │   └── portfolio-styles.css
│   └── js/
└── config/                  # Configuración
```

## 👥 Roles y Permisos

El sistema cuenta con diferentes roles:
- **Administrador**: Acceso total
- **Compras**: Gestión de órdenes de pedido
- **Logística**: Planificación de despachos
- **Bodeguero**: Control de inventario
- **Usuario**: Consulta de información

## 🔧 Desarrollo

### Ejecutar Tests
```bash
php artisan test
# O específicos:
php artisan test tests/Feature/PedidosTest.php
```

### Compilar Assets en Desarrollo
```bash
npm run watch
```

### Modo Debug
En `.env`:
```
APP_DEBUG=true
APP_ENV=local
```

## 📞 Contacto

- GitHub: [@NachoOFC](https://github.com/NachoOFC)
- Portafolio: [Tu portafolio]

---

**Hecho con ❤️ por Nacho OFC**

**Versión**: 1.0.0  
**Última actualización**: Octubre 2025


## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
