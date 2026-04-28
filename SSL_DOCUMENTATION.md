# Documentación de Configuración SSL (Radio Streaming Server)

## Contexto del Problema
El servidor de radio (`radio-streaming-server`) requería habilitar HTTPS/SSL para que el reproductor web de WordPress no bloqueara el contenido mixto (`ERR_CERT_DATE_INVALID`). Sin embargo, el servidor VPS tenía el puerto `80` reservado para otro proyecto de streaming de TV (`mv-streaming`), lo que hacía imposible la validación automática estándar HTTP-01 mediante Certbot, ya que Let's Encrypt exige el puerto 80 para ese método.

## Solución Arquitectónica
Para evadir la restricción del puerto 80, se modificó el esquema de validación para utilizar el método **DNS-01**.

1. **Reemplazo de Certbot por acme.sh**: 
   Se incorporó el contenedor `acme` (usando la imagen `neilpang/acme.sh`) en el archivo `docker-compose.yml`, eliminando el servicio standalone anterior de `certbot` que causaba conflictos.
   
2. **Desafío Manual DNS-01**: 
   Debido a que `acme.sh` no posee soporte directo actualizado vía API para *Hostinger*, el desafío DNS se configuró en modo manual. Esto requería inyectar un código temporal en la zona DNS del proveedor para validar la autoría del dominio.

## Pasos para Renovación Futura
Dado que la validación es manual, Let's Encrypt solicitará repetir el desafío DNS cuando el certificado actual caduque (cada 60-90 días). Los pasos a seguir en el VPS son:

1. Acceder al directorio:
   ```bash
   cd ~/radio-streaming-server
   ```

2. Ejecutar la solicitud del certificado (esto arrojará un error `Incorrect TXT record` si el registro no coincide, pero arrojará un nuevo valor necesario). Anotar el valor mostrado junto a `TXT value`.
   ```bash
   docker run --rm -v $(pwd)/acme:/acme.sh neilpang/acme.sh --renew -d streaming.monagasvision.com --yes-I-know-dns-manual-mode-enough-go-ahead-please
   ```

3. Ir al panel de Hostinger -> Dominios -> Zonas DNS.
4. Buscar el registro TXT **`_acme-challenge.streaming`**.
5. Reemplazar el valor con el nuevo código arrojado por la consola.
6. **ESPERAR DE 3 A 5 MINUTOS** sin ejecutar nada en consola (esperar que el DNS se propague globalmente).
7. Ejecutar nuevamente el comando exacto del **Paso 2** para validar exitosamente (`Cert success`).
8. Ejecutar la instalación del certificado y el reinicio de Nginx:
   ```bash
   docker run --rm -v $(pwd)/acme:/acme.sh -v $(pwd)/certbot/conf:/etc/letsencrypt neilpang/acme.sh --install-cert -d streaming.monagasvision.com --key-file /etc/letsencrypt/live/streaming.monagasvision.com/privkey.pem --fullchain-file /etc/letsencrypt/live/streaming.monagasvision.com/fullchain.pem --cert-file /etc/letsencrypt/live/streaming.monagasvision.com/cert.pem
   
   docker compose restart nginx
   ```

## Accesos Críticos
- **Ruta del Proyecto VPS:** `cd ~/radio-streaming-server`
- **Dominio:** `streaming.monagasvision.com`
- **Credencial/Access Key provista:** `@UaJKCfpS,l/#'7p8Xn)`
- **Puertos:** `8443` (mapeado a `443` HTTPS para la radio).
