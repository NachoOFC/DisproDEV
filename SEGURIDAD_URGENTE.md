# 🚨 REMEDIACIÓN DE SEGURIDAD - CREDENCIALES EXPUESTAS

## 📌 Situación
**GitGuardian detectó credenciales de PostgreSQL expuestas en GitHub**
- **Repositorio:** NachoOFC/DisproDEV
- **Fecha:** 21 de enero de 2026, 03:09:54 UTC
- **Password expuesto:** `npg_1Ya3NkxeTDgG`
- **Archivos afectados:** `.env`, `.env.local`, `MIGRACION_COMPLETA.md`

## ✅ CHECKLIST DE REMEDIACIÓN (5 PASOS CRÍTICOS)

### **1. ✅ ROTAR CREDENCIALES EN NEON** (HACER PRIMERO)
1. Ve a [console.neon.tech](https://console.neon.tech)
2. Inicia sesión
3. Selecciona proyecto `neondb`
4. **Settings** → **Reset password**
5. **Copia la nueva contraseña generada**

### **2. ⏳ ACTUALIZAR ARCHIVOS LOCALES** (Copilot hará esto)
Una vez tengas la nueva contraseña, proporciónamela y actualizaré:
- [ ] `.env` - Nueva DATABASE_URL
- [ ] `.env.local` - Nueva DATABASE_URL
- [ ] `MIGRACION_COMPLETA.md` - Ocultar credenciales
- [ ] `server/utils/database.js` - Verificar que usa process.env.DATABASE_URL

### **3. ✅ PROTEGER ARCHIVOS SENSIBLES** (YA HECHO)
- [x] Agregado `.env` a `.gitignore`
- [x] Agregado `.env.production` a `.gitignore`

### **4. ⏳ LIMPIAR HISTORIAL DE GIT** (Hacer después de rotar)
```powershell
# OPCIÓN 1: BFG Repo-Cleaner (Recomendado - Más rápido)
# Descargar: https://rtyley.github.io/bfg-repo-cleaner/
java -jar bfg.jar --delete-files .env
java -jar bfg.jar --replace-text passwords.txt  # Archivo con "npg_1Ya3NkxeTDgG"
git reflog expire --expire=now --all
git gc --prune=now --aggressive

# OPCIÓN 2: git filter-repo (Alternativa)
pip install git-filter-repo
git filter-repo --path .env --invert-paths
git filter-repo --path MIGRACION_COMPLETA.md --invert-paths
git filter-repo --replace-text passwords.txt

# OPCIÓN 3: git filter-branch (Última opción - Más lento)
git filter-branch --force --index-filter \
  "git rm --cached --ignore-unmatch .env MIGRACION_COMPLETA.md" \
  --prune-empty --tag-name-filter cat -- --all
```

### **5. ⏳ FORZAR PUSH AL REPOSITORIO** (Última acción)
```powershell
# ADVERTENCIA: Esto reescribirá el historial de GitHub
git push origin --force --all
git push origin --force --tags
```

---

## 📋 VERIFICACIÓN POST-REMEDIACIÓN

- [ ] Nueva contraseña generada en Neon
- [ ] `.env` actualizado con nueva DATABASE_URL
- [ ] `.env.local` actualizado
- [ ] `MIGRACION_COMPLETA.md` sin credenciales
- [ ] `.env` agregado a `.gitignore` ✅
- [ ] Historial de Git limpio (sin credenciales antiguas)
- [ ] Push forzado a GitHub completado
- [ ] GitGuardian no muestra más alertas (verificar en 24-48h)
- [ ] Aplicación funcionando con nueva DATABASE_URL

---

## 🔒 MEDIDAS PREVENTIVAS FUTURAS

### **Nunca Commitear:**
- `.env` y `.env.*` (excepto `.env.example` sin valores reales)
- Archivos con credenciales, API keys, tokens
- Documentación con URLs completas de conexión

### **Usar siempre:**
- Variables de entorno (`process.env.VARIABLE`)
- `.env.example` con valores placeholder:
  ```
  DATABASE_URL=postgresql://usuario:password@host/database
  ```
- Secrets en servicios de deployment (Vercel Secrets, Netlify Env Vars)

### **Herramientas de prevención:**
- Pre-commit hooks con [git-secrets](https://github.com/awslabs/git-secrets)
- GitHub Secret Scanning habilitado (ya funciona - detectó este leak)
- [Talisman](https://github.com/thoughtworks/talisman) para hooks locales

---

## 📞 CONTACTO DE EMERGENCIA

Si alguien explotó las credenciales antes de rotarlas:
1. **Revisar logs de Neon:** console.neon.tech → Project → Monitoring
2. **Buscar conexiones sospechosas** (IPs desconocidas, queries maliciosas)
3. **Si hay acceso no autorizado:**
   - Rotar credenciales inmediatamente
   - Revisar integridad de datos (backups)
   - Considerar restaurar desde backup limpio
   - Documentar para reporte de incidentes

---

## ⏱️ TIEMPO ESTIMADO
- **Rotar password:** 2 minutos
- **Actualizar archivos:** 1 minuto (automático con Copilot)
- **Limpiar historial Git:** 5-10 minutos
- **Push forzado:** 2 minutos
- **TOTAL:** ~15-20 minutos

---

## 🎯 PRÓXIMOS PASOS AHORA

1. ✅ Archivo `.gitignore` actualizado (protege .env)
2. **TU TURNO:** Ve a Neon y rota el password
3. **DAME LA NUEVA PASSWORD:** Actualizaré todos los archivos automáticamente
4. **Yo haré:** Limpieza de historial Git + Push forzado

**¿Listo para rotar la contraseña? Dime cuando la tengas.**
