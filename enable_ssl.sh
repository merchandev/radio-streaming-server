#!/bin/bash

# Script de Activación SSL para Radio Streaming
# Autor: Antigravity

echo "=============================================="
echo "🔒 ASISTENTE DE CONFIGURACIÓN SSL (HTTPS)"
echo "=============================================="
echo ""
echo "Este script configurará HTTPS para tu servidor de radio."
echo "REQUISITO: Debes tener un DOMINIO (ej. radio.tusitio.com) apuntando a esta IP."
echo ""

read -p "Ingresa tu dominio (ej. radio.tusitio.com): " DOMAIN

if [ -z "$DOMAIN" ]; then
    echo "❌ Error: Debes ingresar un dominio."
    exit 1
fi

echo ""
echo ">>> Paso 1: Verificando entorno Docker..."
docker-compose down
docker-compose up -d nginx

echo ""
echo ">>> Paso 2: Solicitando Certificado SSL para $DOMAIN..."
echo "    (Esto puede tardar unos segundos)"

docker-compose run --rm certbot certonly --webroot --webroot-path /var/www/certbot -d "$DOMAIN" --email admin@"$DOMAIN" --agree-tos --no-eff-email

if [ $? -ne 0 ]; then
    echo "❌ Error: Falló la obtención del certificado."
    echo "   Verifica que el dominio $DOMAIN apunte correctamente a la IP del servidor."
    echo "   Asegúrate de que no haya firewalls bloqueando el puerto 80."
    exit 1
fi

echo ""
echo ">>> Paso 3: Configurando Nginx con SSL..."

# Ruta de los certificados
CERT_PATH="/etc/letsencrypt/live/$DOMAIN/fullchain.pem"
KEY_PATH="/etc/letsencrypt/live/$DOMAIN/privkey.pem"

# Backup del config original
cp config/radio_nginx.conf config/radio_nginx.conf.bak

# Crear nueva configuración con SSL
cat > config/radio_nginx.conf <<EOF
user nginx;
worker_processes auto;
error_log /var/log/nginx/error.log warn;
pid /var/run/nginx.pid;

events {
    worker_connections 2048;
    use epoll;
    multi_accept on;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    log_format main '\$remote_addr - \$remote_user [\$time_local] "\$request" '
                    '\$status \$body_bytes_sent "\$http_referer" '
                    '"\$http_user_agent" "\$http_x_forwarded_for"';

    access_log /var/log/nginx/access.log main;

    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    gzip on;
    
    upstream icecast_backend {
        server icecast:8000;
        keepalive 32;
    }

    # SERVIDOR HTTP (Puerto 80)
    server {
        listen 80;
        server_name $DOMAIN _;

        location /.well-known/acme-challenge/ {
            root /var/www/certbot;
        }

        # Redirigir a HTTPS (Opcional, pero recomendado)
        # return 301 https://\$host\$request_uri;
        
        # Mantenemos soporte HTTP para reproductores viejos
        location / {
            root /usr/share/nginx/html;
            try_files /widget.html =404;
        }
        
        location /embed.html {
             root /usr/share/nginx/html;
             try_files /embed.html =404;
        }
        
        # Proxy Icecast normal
        location /radio.aac {
            proxy_pass http://icecast_backend;
            proxy_set_header Host \$host;
        }
    }

    # SERVIDOR HTTPS (Puerto 443)
    server {
        listen 443 ssl http2;
        server_name $DOMAIN;

        ssl_certificate $CERT_PATH;
        ssl_certificate_key $KEY_PATH;

        # Mejoras de Seguridad SSL
        ssl_session_timeout 1d;
        ssl_session_cache shared:SSL:50m;
        ssl_session_tickets off;
        ssl_protocols TLSv1.2 TLSv1.3;
        ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384;
        ssl_prefer_server_ciphers off;

        # Servir Widget y Embed en HTTPS
        location / {
            root /usr/share/nginx/html;
            try_files /widget.html =404;
        }
        
        location /embed.html {
             root /usr/share/nginx/html;
             try_files /embed.html =404;
        }
        
        location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
            root /usr/share/nginx/html;
        }

        # Proxy Reverso Seguro para Icecast
        location / {
            proxy_pass http://icecast_backend;
            proxy_http_version 1.1;
            proxy_set_header Host \$host;
            proxy_set_header X-Real-IP \$remote_addr;
            proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto https;
            
            # CORS Headers para que funcione en tu WordPress
            add_header Access-Control-Allow-Origin "*" always;
            add_header Access-Control-Allow-Methods "GET, OPTIONS";
        }
    }
}
EOF

echo ""
echo ">>> Paso 4: Reiniciando servicios..."
docker-compose restart nginx

echo ""
echo "✅ ¡ÉXITO! SSL Habilitado."
echo "Tu URL segura de stream es: https://$DOMAIN/radio.aac"
echo "Tu Player seguro está en: https://$DOMAIN/embed.html"
echo ""
echo "Ahora puedes usar esas URLs en tu WordPress y no tendrás problemas de Candado."
