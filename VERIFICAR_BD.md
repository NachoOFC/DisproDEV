# ✅ Cómo Verificar la Conexión a Base de Datos

## 🎯 Método 1: Navegador Web (MÁS FÁCIL)

### Paso 1: Asegúrate que el servidor esté corriendo
```bash
npm run dev
```

Deberías ver:
```
➜ Local:    http://localhost:3000/
```

### Paso 2: Prueba estos endpoints en tu navegador

1. **Test de Conexión:**
   ```
   http://localhost:3000/api/test-db
   ```
   ✅ Deberías ver JSON con "success": true

2. **Productos:**
   ```
   http://localhost:3000/api/productos
   ```
   ✅ Lista de productos desde PostgreSQL

3. **Usuarios:**
   ```
   http://localhost:3000/api/usuarios
   ```
   ✅ Lista de usuarios reales

4. **Clientes:**
   ```
   http://localhost:3000/api/clientes
   ```

5. **Empresas:**
   ```
   http://localhost:3000/api/empresas
   ```

6. **Centros:**
   ```
   http://localhost:3000/api/centros
   ```

## 🎯 Método 2: PowerShell

```powershell
# Test de conexión
Invoke-RestMethod -Uri "http://localhost:3000/api/test-db" | ConvertTo-Json -Depth 10

# Ver productos
Invoke-RestMethod -Uri "http://localhost:3000/api/productos" | ConvertTo-Json -Depth 10
```

## 🎯 Método 3: Página de Verificación Visual

Abre en tu navegador:
```
http://localhost:3000/test-db
```

Esta página muestra:
- ✅ Estado de conexión
- 📊 Estadísticas de tablas
- 📈 Cantidad de registros
- 🧪 Pruebas de endpoints
- 📦 Lista visual de productos

## ✅ ¿Qué deberías ver?

### Test DB exitoso:
```json
{
  "success": true,
  "message": "✅ Conexión exitosa a Neon PostgreSQL",
  "database": {
    "totalTablas": 45,
    "estadisticas": {
      "productos": 0,
      "clientes": 0,
      "usuarios": 0
    }
  }
}
```

Si ves `"success": true` = **¡FUNCIONÓ!** 🎉

## ❌ Si ves error "DATABASE_URL no está configurada"

1. **Verifica que `.env` tenga la URL:**
   ```bash
   cat .env | Select-String "DATABASE_URL"
   ```

2. **Reinicia el servidor:**
   ```bash
   # Presiona Ctrl+C en la terminal
   npm run dev
   ```

3. **Verifica que la URL sea correcta:**
   - Debe empezar con `postgresql://`
   - Debe terminar con `?sslmode=require`

## 📝 Insertar Datos de Prueba

Si tu BD está vacía (0 productos, 0 clientes):

1. Ve a [console.neon.tech](https://console.neon.tech)
2. SQL Editor
3. Copia y pega el contenido de `SEED_DATA.sql`
4. Ejecuta
5. Refresca los endpoints

## 🔧 Troubleshooting

| Error | Solución |
|-------|----------|
| "Cannot connect to server" | Servidor no está corriendo → `npm run dev` |
| "DATABASE_URL no está configurada" | Verifica `.env` y reinicia servidor |
| "Connection timeout" | Verifica URL de Neon en `.env` |
| "success": false | Revisa credenciales de Neon |
| 0 registros | Ejecuta `SEED_DATA.sql` en Neon Console |

## 🎉 Confirmación de Éxito

Estás conectado si ves:
- ✅ `/api/test-db` → `"success": true`
- ✅ `/api/productos` → `{"data": [...]}`  
- ✅ No hay errores en la consola del servidor
- ✅ Los datos persisten al reiniciar

## 📞 Comandos Útiles

```bash
# Ver logs del servidor
# (ya están en la terminal donde corriste npm run dev)

# Probar conexión rápidamente
Invoke-RestMethod http://localhost:3000/api/test-db

# Ver qué hay en productos
Invoke-RestMethod http://localhost:3000/api/productos
```
