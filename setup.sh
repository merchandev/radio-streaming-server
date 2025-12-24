#!/bin/bash

# Setup Script for Radio Streaming Server
# Usage: ./setup.sh

# Color codes
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${GREEN}=== Radio Streaming Server Installer ===${NC}"

# 1. Check Requirements
if ! command -v docker &> /dev/null; then
    echo -e "${RED}Docker is not installed. Please install Docker first.${NC}"
    exit 1
fi

# 2. Get User Input
read -p "Enter your domain name (e.g., radio.example.com): " DOMAIN
read -p "Enter your email for SSL (e.g., admin@example.com): " EMAIL

if [ -z "$DOMAIN" ] || [ -z "$EMAIL" ]; then
    echo -e "${RED}Domain and Email are required.${NC}"
    exit 1
fi

echo -e "${GREEN}Configuring for domain: $DOMAIN${NC}"

# 3. Prepare Configs
# Replace localhost in nginx configs with actual domain
sed -i "s/server_name localhost;/server_name $DOMAIN;/g" config/nginx.conf
sed -i "s/server_name localhost;/server_name $DOMAIN;/g" config/nginx_init.conf
sed -i "s|/etc/letsencrypt/live/localhost|/etc/letsencrypt/live/$DOMAIN|g" config/nginx.conf

# 4. Bootstrap SSL
echo -e "${GREEN}Starting Nginx for SSL verification...${NC}"
cp config/nginx_init.conf config/nginx_running.conf
# We temporarily map nginx_running.conf to /etc/nginx/nginx.conf in this step? 
# Actually, docker-compose maps ./config/nginx.conf. 
# So we overwrite nginx.conf with init content first.
cp config/nginx.conf config/nginx.conf.bak
cp config/nginx_init.conf config/nginx.conf

docker-compose up -d nginx

echo -e "${GREEN}Requesting SSL Certificate...${NC}"
# Run certbot
docker-compose run --rm  certbot certonly --webroot --webroot-path /var/www/certbot -d $DOMAIN --email $EMAIL --rsa-key-size 4096 --agree-tos --no-eff-email

if [ $? -ne 0 ]; then
    echo -e "${RED}SSL Certificate generation failed! check your DNS and Firewall.${NC}"
    # Restore config
    mv config/nginx.conf.bak config/nginx.conf
    docker-compose down
    exit 1
fi

echo -e "${GREEN}SSL Certificate obtained!${NC}"

# 5. Finalize Setup
echo -e "${GREEN}Switching to Production SSL Configuration...${NC}"
docker-compose down

# Restore SSL config
mv config/nginx.conf.bak config/nginx.conf

# Start Full Stack
docker-compose up -d

echo -e "${GREEN}=== Deployment Complete ===${NC}"
echo -e "Stream URL (Icecast): http://$DOMAIN:8000/stream"
echo -e "Web Player: https://$DOMAIN"
echo -e "Admin Panel: http://$DOMAIN:8000/admin/"
echo -e "Source Password (Opticodec): mistream"
