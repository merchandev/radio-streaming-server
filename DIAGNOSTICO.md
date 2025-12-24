# 🔧 Diagnóstico del Servidor de Radio - Guía de Troubleshooting

## ✅ Verificación Paso a Paso

### 1. Verificar que los contenedores estén corriendo

En el panel de Hostinger, deberías ver:
- ✅ **icecast**: Running (puerto 8000)
- ✅ **nginx**: Running (puerto 80)
- ⚠️ **certbot**: Exited (NORMAL - solo corre cuando genera certificados)

---

### 2. Verificar que Opticodec esté conectado

En Opticodec deberías ver:
- Estado: **"Connected"**
- Encoding time corriendo (ej: 00:00:40, 00:01:23, etc.)
- URL: `72.62.86.94:8000/stream.aac`

Si NO dice "Connected":
- Verifica que el servidor sea: `72.62.86.94`
- Verifica que el puerto sea: `8000`
- Verifica que el mount point sea: `/stream.aac`
- Verifica que la contraseña sea: `mistream`

---

### 3. Verificar el Admin Panel de Icecast

Abre: **http://72.62.86.94/admin**

Credenciales:
- Usuario: `admin`
- Contraseña: `hackme`

Deberías ver:
```
Mount Point Sources:
  /stream.aac - Active
  Bitrate: 32kbps (o el que hayas configurado)
  Listeners: 0
```

Si NO ves el mount point `/stream.aac`, significa que Opticodec NO está conectado correctamente.

---

### 4. Probar el Stream Directamente

Abre en tu navegador: **http://72.62.86.94/stream.aac**

Resultados esperados:
- ✅ El navegador debería **descargar** o **reproducir** el archivo de audio
- ✅ Deberías escuchar el audio de Opticodec

Si sale "404 Not Found":
- Opticodec no está transmitiendo
- Verifica en el admin panel que el mount point exista

---

### 5. Probar el Widget

Abre: **http://72.62.86.94**

1. Presiona **F12** para abrir la consola del navegador
2. Haz clic en el botón **Play**
3. Revisa los mensajes en la consola:

**Mensajes correctos:**
```
Loading stream...
Stream ready to play
Stream playing successfully
```

**Mensajes de error:**
```
Stream error: ...
Error code: 4 (MEDIA_ERR_SRC_NOT_SUPPORTED)
```

Si ves error code 4:
- El stream no está disponible
- Opticodec no está transmitiendo
- Verifica el admin panel

---

## 🎯 URLs de Diagnóstico

Prueba estas URLs en orden:

| URL | Qué verifica | Resultado esperado |
|-----|--------------|-------------------|
| http://72.62.86.94 | Widget funcionando | Reproductor visual |
| http://72.62.86.94/stream.aac | Stream directo | Descarga/Reproduce audio |
| http://72.62.86.94/admin | Panel admin | Listado de mount points |
| http://72.62.86.94:8000 | Icecast directo | Página de status de Icecast |
| http://72.62.86.94:8000/stream.aac | Stream en Icecast | Descarga/Reproduce audio |

---

## 🔍 Problemas Comunes

### Problema: "No se puede reproducir el stream"

**Causa 1: Opticodec no está conectado**
- Solución: Revisa la configuración de Opticodec y presiona "Connect"

**Causa 2: Mount point incorrecto**
- Solución: En Opticodec, asegúrate que "Filename" sea: `stream.aac`

**Causa 3: Puerto bloqueado**
- Solución: Verifica que el puerto 8000 esté abierto en el firewall del VPS

### Problema: Admin panel da 404

**Causa: Nginx no está proxying correctamente**
- Solución: Verificar que nginx esté corriendo
- Prueba acceder directo: http://72.62.86.94:8000/admin

### Problema: Widget carga pero no reproduce

**Causa 1: CORS bloqueado**
- Solución: Abre la consola (F12) y busca errores de CORS
- Los headers CORS ya están configurados en nginx

**Causa 2: Formato no soportado**
- Solución: Asegúrate que Opticodec use AAC (no MP3)
- En Opticodec: Encoder Parameters → aacPlus Stereo 32kbps

---

## 📊 Logs para Debugging

### Ver logs de Nginx

En el panel de Hostinger:
1. Click en el contenedor **nginx**
2. Click en "Terminal" o "Logs"
3. Busca errores relacionados con proxy

### Ver logs de Icecast

En el panel de Hostinger:
1. Click en el contenedor **icecast**
2. Click en "Terminal" o "Logs"
3. Busca mensajes de "source connected" o "source disconnected"

---

## 🎙️ Configuración Correcta de Opticodec

```
Server: Icecast 2
URL: 72.62.86.94
Port: 8000
Filename: stream.aac
User: source
Password: mistream

Encoder: aacPlus Stereo 32kbps 44.1kHz
```

---

## ✅ Checklist Final

- [ ] Opticodec muestra "Connected"
- [ ] Admin panel muestra `/stream.aac` activo
- [ ] http://72.62.86.94/stream.aac descarga/reproduce
- [ ] Widget carga correctamente en http://72.62.86.94
- [ ] Consola del navegador no muestra errores
- [ ] Al presionar Play, el audio se reproduce

Si todos los ítems están marcados, tu radio está funcionando correctamente.
