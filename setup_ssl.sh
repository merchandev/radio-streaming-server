#!/bin/bash
set -e

# Detectar dominio
DOMAIN="streaming.monagasvision.com"
EMAIL="tv@monagasvision.com"

# Cargar variables
if [ ! -f .env ]; then
    echo "❌ Error: Crea el archivo .env primero (usa .env.example como guia)"
    exit 1
fi
export $(grep -v '^#' .env | xargs)

# Asegurar carpetas
mkdir -p ./acme ./certbot/conf/live/${DOMAIN}

echo "🔄 Iniciando acme.sh..."
docker compose up -d acme

echo "📡 Emitiendo certificado via Hostinger DNS..."
docker compose exec acme --issue --dns dns_hostinger -d ${DOMAIN} --server letsencrypt --force

echo "💾 Instalando certificado..."
docker compose exec acme --install-cert -d ${DOMAIN} \
    --key-file /etc/letsencrypt/live/${DOMAIN}/privkey.pem \
    --fullchain-file /etc/letsencrypt/live/${DOMAIN}/fullchain.pem

echo "🚀 Reiniciando Nginx..."
docker compose restart nginx
echo "✅ ¡Listo!"
