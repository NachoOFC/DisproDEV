# 🚀 Guía de Conexión a Base de Datos Neon

## ✅ Pasos Completados

1. ✅ Estructura de BD creada con `CREATE_TABLES.sql`
2. ✅ Cliente Neon instalado: `@neondatabase/serverless`
3. ✅ Endpoints API actualizados para usar PostgreSQL
4. ✅ Utilidad de base de datos creada

## 📝 Configuración Requerida

### 1. Obtener URL de Conexión de Neon

1. Ve a [https://console.neon.tech](https://console.neon.tech)
2. Selecciona tu proyecto
3. Haz clic en **"Connection Details"** o **"Connect"**
4. Copia la **Connection string** (debería verse así):
   ```
   postgresql://usuario:password@ep-xxx-xxx.us-east-2.aws.neon.tech/neondb?sslmode=require
   ```

### 2. Configurar Variables de Entorno

Edita el archivo `.env.local` y reemplaza con tu URL real:

```env
DATABASE_URL=tu_url_de_conexion_aqui
```

### 3. Insertar Datos de Prueba (Opcional)

En la consola de Neon o usando un cliente SQL, ejecuta:

```bash
# Copia el contenido de SEED_DATA.sql
# Pégalo en el SQL Editor de Neon
# Ejecuta el script
```

O usa el archivo `SEED_DATA.sql` directamente en Neon Console.

### 4. Probar la Conexión

```bash
# Inicia el servidor de desarrollo
npm run dev

# En tu navegador, visita:
# http://localhost:3000/api/test-db
```

Deberías ver algo como:
```json
{
  "success": true,
  "message": "✅ Conexión exitosa a Neon PostgreSQL",
  "database": {
    "totalTablas": 45,
    "estadisticas": {
      "productos": 5,
      "clientes": 3,
      "usuarios": 3
    }
  }
}
```

## 🧪 Endpoints Disponibles

### Productos
- `GET /api/productos` - Listar todos los productos
- `GET /api/productos/[id]` - Obtener producto por ID
- `POST /api/productos` - Crear nuevo producto

### Usuarios
- `GET /api/usuarios` - Listar usuarios
- `GET /api/usuarios/[id]` - Obtener usuario por ID

### Facturas
- `GET /api/facturas` - Listar facturas

### Test
- `GET /api/test-db` - Probar conexión a BD

## 📊 Estructura de la Base de Datos

Tu base de datos tiene **45 tablas** principales:

- 👥 `users` - Usuarios del sistema
- 📦 `productos` - Catálogo de productos
- 👔 `clientes` - Clientes
- 🏢 `empresas` - Empresas
- 📍 `centros` - Centros de distribución
- 📋 `requerimientos` - Órdenes de compra
- 💰 `presupuestos` - Presupuestos
- 📄 `factura_electronicas` - Facturas
- 🚚 `guia_despachos` - Guías de despacho
- Y más...

## ⚠️ Troubleshooting

### Error: "DATABASE_URL no está configurada"
- Asegúrate de tener el archivo `.env.local` con la URL correcta
- Reinicia el servidor: `Ctrl+C` y luego `npm run dev`

### Error: "Connection timeout"
- Verifica que tu URL de Neon sea correcta
- Asegura que incluya `?sslmode=require` al final

### Error: "table does not exist"
- Verifica que ejecutaste `CREATE_TABLES.sql` en Neon
- Revisa que las tablas se crearon correctamente en Neon Console

## 🎯 Próximos Pasos

1. ✅ Configurar `.env.local` con tu URL de Neon
2. ✅ Ejecutar `SEED_DATA.sql` para datos de prueba
3. ✅ Probar endpoint `/api/test-db`
4. ✅ Verificar que `/api/productos` funcione
5. 🔜 Actualizar más endpoints según necesites
6. 🔜 Implementar autenticación
7. 🔜 Crear más funcionalidades CRUD

## 📞 Soporte

Si tienes problemas:
1. Revisa los logs en la terminal
2. Verifica la consola del navegador (F12)
3. Comprueba que Neon esté activo en su dashboard
