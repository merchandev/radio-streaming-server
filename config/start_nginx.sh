#!/bin/sh
set -e

# Check if we have any valid SSL certificate for the expected setup
# We search for fullchain.pem in the Let's Encrypt directory
if ls /etc/letsencrypt/live/*/fullchain.pem 1> /dev/null 2>&1; then
    echo "Found SSL certificates. Switching to Production SSL configuration."
    cp /etc/nginx/conf.d/radio_nginx.conf /etc/nginx/nginx.conf
else
    echo "WARN: No SSL certificates found in /etc/letsencrypt/live/. using HTTP-only initialization mode."
    echo "To enable SSL, ensure Certbot runs successfully. Nginx will stay up on HTTP (Port 80/8081) to allow validation."
    cp /etc/nginx/conf.d/nginx_init.conf /etc/nginx/nginx.conf
fi

# Hand off to the original Nginx entrypoint to handle envsubst and other startup tasks
exec /docker-entrypoint.sh "$@"
