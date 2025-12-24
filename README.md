# Guía de Despliegue del Servidor de Radio

Este proyecto levanta un servidor de radio profesional con **Icecast-KH** (baja latencia), **Nginx** (Proxy Seguro) y **Certbot** (SSL Automático) usando Docker.

## 🚀 Despliegue en VPS (Hostinger)

### 1. Requisitos Previos
- VPS con Ubuntu/Debian (Recomendado).
- IP Pública.
- Dominio (ej. `radio.misitio.com`) apuntando a la IP del VPS.

### 2. Subir Archivos
Sube toda la carpeta `radio-streaming-server` a tu VPS (puedes usar FileZilla o SCP).
```bash
scp -r radio-streaming-server root@tu-ip:/root/
```

### 3. Instalación Automática
Conéctate por SSH y ejecuta el script de instalación:
```bash
cd radio-streaming-server
chmod +x setup.sh
./setup.sh
```
El script te pedirá:
- **Dominio**: `radio.misitio.com`
- **Email**: Para el registro SSL.

### 4. Configurar tu Encoder (Opticodec / MB Recaster / OBS)
Usa los siguientes datos para transmitir:
- **Tipo de Servidor**: Icecast 2
- **Servidor**: `radio.misitio.com` (o la IP si no usaste SSL aún)
- **Puerto**: `8000` (El puerto interno de Icecast, aunque el oyente usa el 443)
- **Mount Point**: `/stream`
- **Contraseña (Source Password)**: `mistream` (Cámbiala en `config/icecast.xml` y reinicia si deseas).
- **Usuario**: `source` (A veces requerido, por defecto es este).

### 5. Integrar en WordPress
Copia el contenido de `widget.html` o usa un `<iframe>`:
```html
<iframe src="https://radio.misitio.com/widget.html" width="100%" height="100" frameborder="0" scrolling="no"></iframe>
```
O simplemente sube el código del widget a tu WordPress como bloque HTML personalizado.

## 🛠️ Comandos Útiles

- **Reiniciar todo**: `docker-compose restart`
- **Ver logs**: `docker-compose logs -f`
- **Cambiar contraseñas**: Edita `config/icecast.xml` y reinicia con `docker-compose down && docker-compose up -d`.

## 📂 Archivos
- `setup.sh`: Script maestro de instalación.
- `config/icecast.xml`: Configuración de la radio.
- `config/nginx.conf`: Configuración del proxy web y SSL.
- `widget.html`: Reproductor listo para usar.
