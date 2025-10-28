# 🌾 DisproDEV - Sistema de Logística y Abastecimiento

> Plataforma integral de gestión de pedidos, logística y abastecimiento para empresas de cultivo hidropónico.

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-9.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4.svg)](https://www.php.net)
[![Status](https://img.shields.io/badge/status-Active-brightgreen.svg)](#)

## 📋 Descripción

**DisproDEV** es un sistema web completo de logística y abastecimiento diseñado específicamente para empresas de cultivo hidropónico. Permite la gestión integral de:

- 📦 **Órdenes de pedido** y seguimiento en tiempo real
- 🏭 **Gestión de centros de cultivo** y abastecimiento
- 👥 **Control de usuarios** y permisos por rol
- 📊 **Reportes y análisis** de operaciones
- 🚚 **Planificación de despachos** y logística
- 💾 **Control de inventario** en bodegas
- 📄 **Generación de documentos** (guías de despacho, facturas electrónicas)

## 🎯 Características Principales

### Módulos Implementados
- **Gestión de Pedidos**: Crear, validar, procesar y despachar órdenes de compra
- **Logística Integrada**: Seguimiento completo del estado de envíos
- **Multi-empresa**: Soporte para múltiples empresas en una sola plataforma
- **Control de Acceso**: Sistema de roles y permisos granulares
- **Reportes Avanzados**: Análisis de datos con filtros y exportación
- **Facturación Electrónica**: Integración con sistema tributario

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
