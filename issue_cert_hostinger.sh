#!/bin/bash

# Port 80 is required for Let's Encrypt HTTP-01 challenge.
# This script runs a temporary Certbot container bound to Host Port 80.

DOMAIN="streaming.monagasvision.com"
EMAIL="admin@monagasvision.com" # Change if needed

# Check for docker compose v2 or v1
if command -v docker-compose &> /dev/null; then
    COMPOSE_CMD="docker-compose"
else
    COMPOSE_CMD="docker compose"
fi

echo "=== Requesting SSL Certificate for $DOMAIN ==="
echo "Ensure no other service is using Port 80 on this VPS."

# Stop any existing containers using the compose command found
$COMPOSE_CMD stop nginx

# Force kill any process using port 80 (Apache/Nginx system services)
echo "Cleaning Port 80..."
fuser -k -9 80/tcp || true
systemctl stop nginx 2>/dev/null || true
systemctl stop apache2 2>/dev/null || true
docker rm -f certbot-temp 2>/dev/null || true

# Wait for ports to clear
sleep 5

# Run Standalone Certbot
# Maps Host:80 -> Container:80 directly
docker run -it --rm --name certbot-temp \
  -p 80:80 \
  -v "$(pwd)/certbot/conf:/etc/letsencrypt" \
  -v "$(pwd)/certbot/www:/var/www/certbot" \
  certbot/certbot certonly --standalone \
  -d $DOMAIN \
  --email $EMAIL \
  --agree-tos --no-eff-email --non-interactive

if [ $? -eq 0 ]; then
    echo "SUCCESS: Certificate obtained."
    echo "Restarting Nginx to apply SSL..."
    $COMPOSE_CMD up -d nginx
else
    echo "FAILURE: Could not obtain certificate."
    echo "Please check if Port 80 is open and accessible from the internet."
fi
