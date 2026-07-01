# 🎙️ Configuración de Opticodec - ACTUALIZADA

## ⚠️ IMPORTANTE: NUEVA CONFIGURACIÓN

La plataforma ha sido completamente actualizada con puertos y credenciales mejoradas.

---

## 📡 Datos de Conexión para Opticodec

### Configuración del Servidor

| Campo | Valor |
|-------|-------|
| **Tipo de Servidor** | Icecast 2 |
| **Servidor** | `72.62.86.94` |
| **Puerto** | `8080` ⚠️ **CAMBIADO** |
| **Mount Point / Filename** | `radio.aac` ⚠️ **CAMBIADO** |
| **Usuario** | `source` |
| **Contraseña** | `mistream` ⚠️ **CAMBIADO** |

### Configuración del Encoder (Recomendada)

```
Formato: AAC
Bitrate: 64 kbps (32-128 kbps según calidad deseada)
Sample Rate: 44100 Hz
Canales: Stereo
```

---

## 🔧 Pasos en Opticodec

### 1. Abrir Opticodec

Ejecuta "Hi-Fi Internet Stream (Orban OPTICODEC-PC Encoder LE)"

### 2. Configurar Audio Input

```
Sound Device: [Tu micrófono o fuente de audio]
Encoder Parameters: aacPlus Stereo 64kbps 44.1kHz
```

### 3. Configurar Stream Description

```
Name: Radio En Vivo
Description: Transmisión en directo
Title: Radio Stream
Genre: Various
```

### 4. Configurar Destination Server

```
Server: Icecast 2
RTP: Generic MPEG

URL: 72.62.86.94
Port: 8080

Filename: radio.aac
User: source
Password: mistream
```

### 5. Conectar

1. Haz clic en el botón de inicio/connect
2. Deberías ver: **"Connected"**
3. El tiempo de encoding comenzará a correr

---

## ✅ Verificación de Conexión

Una vez conectado Opticodec:

### 1. Panel de Administración

Abre: **http://72.62.86.94/admin**

Credenciales:
- Usuario: `admin`
- Contraseña: `admin2024secure`

Deberías ver:
```
Mount Point Sources:
  /radio.aac - Active
  Listeners: 0
  Bitrate: 64kbps
```

### 2. Stream Directo

Abre: **http://72.62.86.94/radio.aac**

Tu navegador debería **reproducir** o **descargar** el stream de audio.

### 3. Widget Premium

Abre: **http://72.62.86.94**

- Haz clic en el botón **Play** (▶)
- El estado debe cambiar a "Transmitiendo en vivo"
- Deberías ver el ecualizador animándose
- Deberías escuchar tu transmisión

---

## 🔊 Configuraciones de Calidad

### Baja Latencia (Radio Hablada)
```
Bitrate: 32 kbps
Sample Rate: 44100 Hz
Formato: AAC-LC
```

### Calidad Estándar (Recomendado)
```
Bitrate: 64 kbps
Sample Rate: 44100 Hz
Formato: AAC-LC
```

### Alta Calidad (Música)
```
Bitrate: 128 kbps
Sample Rate: 44100 Hz
Formato: AAC-HE
```

---

## 🌐 URLs del Servidor

| Servicio | URL | Descripción |
|----------|-----|-------------|
| **Widget** | http://72.62.86.94 | Reproductor web premium |
| **Stream** | http://72.62.86.94/radio.aac | Stream de audio directo |
| **Admin** | http://72.62.86.94/admin | Panel de administración |
| **Status** | http://72.62.86.94/status.xsl | Estado del servidor |
| **Health** | http://72.62.86.94/health | Healthcheck de nginx |

---

## 🚨 Solución de Problemas

### Opticodec no conecta

**Verifica:**
- [ ] Servidor: `72.62.86.94`
- [ ] Puerto: `8080` (NO 8000)
- [ ] Filename: `radio.aac` (sin barra /)
- [ ] Contraseña: `mistream`
- [ ] Que tu PC tenga acceso a internet

### Conecta pero no se escucha

1. Abre la consola del navegador (F12)
2. Ve a http://72.62.86.94
3. Haz clic en Play
4. Revisa los mensajes en consola
5. Deberías ver: "Stream playing successfully"

Si ves errores:
- Verifica que Opticodec muestre "Connected"
- Verifica que el mount point aparezca en /admin
- Prueba acceder directo: http://72.62.86.94/radio.aac

### Widget dice "Reconectando..."

Esto significa que:
- Opticodec se desconectó
- No hay stream disponible en el servidor

**Solución:**
- Verifica que Opticodec esté en "Connected"
- El widget intentará reconectar automáticamente cada 5 segundos (hasta 10 veces)

---

## 📊 Monitoreo

### Ver listeners en tiempo real

1. Panel admin: http://72.62.86.94/admin
2. Busca la sección "Mount Point Statistics"
3. Verás:
   - Listeners actuales
   - Peak listeners
   - Bitrate
   - Tiempo conectado

### Logs del servidor

En Hostinger Docker Panel:
- Click en contenedor `icecast` → Logs
- Click en contenedor `nginx` → Logs

---

## 🎯 Configuración Completa de Ejemplo

```
=== OPTICODEC CONFIGURATION ===

Sound Device: Micrófono (High Definition Audio)
Encoder: aacPlus Stereo 64kbps 44.1kHz

Stream Description:
  Name: Mi Radio Online
  Description: Transmisión 24/7
  Genre: Pop/Rock
  
Destination Server:
  Server Type: Icecast 2
  RTP Type: Generic MPEG
  URL: 72.62.86.94
  Port: 8080
  Filename: radio.aac
  User: source
  Password: mistream
```

---

## 🔐 Credenciales del Sistema

### Icecast Source (Para transmitir)
- Usuario: `source`
- Contraseña: `mistream`

### Icecast Admin (Panel de control)
- Usuario: `admin`
- Contraseña: `admin2024secure`

### Icecast Relay (Para relay servers)
- Contraseña: `relay2024secure`

---

## 💡 Tips y Mejores Prácticas

1. **Mantén Opticodec conectado** - Si se desconecta, el widget auto-reconectará pero habrá silencio
2. **Monitorea el bitrate** - 64kbps es ideal para balance calidad/ancho de banda
3. **Usa el admin panel** - Para ver cuántos listeners tienes en tiempo real
4. **Conexión estable** - Asegúrate que tu PC tenga conexión estable a internet

---

## 📱 Integración en WordPress

Para embeber el widget en tu sitio WordPress:

```html
<iframe src="http://72.62.86.94/" 
        width="100%" 
        height="280" 
        frameborder="0" 
        scrolling="no"
        allow="autoplay">
</iframe>
```

O enlace directo al stream:
```
http://72.62.86.94/radio.aac
```

---

## ⚙️ Características de la Nueva Plataforma

✅ **Auto-Reconnect**: El widget reconecta automáticamente si hay interrupciones
✅ **Healthchecks**: Los contenedores se monitorean automáticamente
✅ **Escalable**: Soporta hasta 5000 oyentes simultáneos
✅ **Optimizado**: Baja latencia y alta calidad de audio
✅ **Robusto**: Se recupera automáticamente de errores
✅ **Premium UI**: Widget con diseño moderno y controles de volumen
