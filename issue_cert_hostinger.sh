#!/bin/bash

# Port 80 is required for Let's Encrypt HTTP-01 challenge.
# This script runs a temporary Certbot container bound to Host Port 80.

DOMAIN="streaming.monagasvision.com"
EMAIL="admin@monagasvision.com" # Change if needed

echo "=== Requesting SSL Certificate for $DOMAIN ==="
echo "Ensure no other service is using Port 80 on this VPS."

# Stop Nginx to ensure no conflicts (though it is on 8081, best to be safe/clean)
# docker-compose stop nginx

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
    docker-compose restart nginx
else
    echo "FAILURE: Could not obtain certificate."
    echo "Please check if Port 80 is open and accessible from the internet."
fi
