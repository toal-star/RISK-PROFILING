#!/usr/bin/env bash
set -euo pipefail

EC2_HOST="13.220.32.186"
EC2_USER="ubuntu"
EC2_KEY="$HOME/.ssh/snap-keypair.pem"
APP_DIR="/var/www/html"

echo "Deploying to $EC2_HOST..."

ssh -i "$EC2_KEY" -o StrictHostKeyChecking=no "$EC2_USER@$EC2_HOST" bash -s << 'ENDSSH'
  set -euo pipefail

  cd /var/www/html

  echo "--- Pulling latest code ---"
  git pull origin main

  echo "--- Installing PHP dependencies ---"
  composer install --no-dev --optimize-autoloader --no-interaction

  echo "--- Running migrations ---"
  php artisan migrate --force

  echo "--- Clearing & caching config ---"
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache

  echo "--- Restarting web server ---"
  sudo systemctl restart nginx
  sudo systemctl restart php8.5-fpm

ENDSSH

echo "Deploy complete."
