#!/usr/bin/env bash
set -euo pipefail

# Relay Cloud — Initial Server Setup
# Run on a fresh Ubuntu 24.04 Hetzner server as root
# Usage: ssh root@YOUR_SERVER_IP 'bash -s' < deploy/setup.sh

echo "==> Updating system packages..."
apt update && apt upgrade -y

echo "==> Installing Nginx..."
apt install -y nginx

echo "==> Installing PHP 8.3 and extensions..."
apt install -y software-properties-common
add-apt-repository -y ppa:ondrej/php
apt update
apt install -y php8.3-fpm php8.3-mysql php8.3-redis php8.3-mbstring \
    php8.3-xml php8.3-curl php8.3-zip php8.3-bcmath php8.3-sqlite3 \
    php8.3-gd php8.3-intl unzip curl git

echo "==> Installing Composer..."
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

echo "==> Installing Node.js 20 via nvm..."
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"
nvm install 20
nvm alias default 20

echo "==> Installing MySQL 8.0..."
apt install -y mysql-server
systemctl enable mysql
systemctl start mysql

echo "==> Creating MySQL database and user..."
mysql -e "CREATE DATABASE IF NOT EXISTS relay_cloud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS 'relay_cloud'@'localhost' IDENTIFIED BY 'CHANGE_ME_STRONG_PASSWORD';"
mysql -e "GRANT ALL PRIVILEGES ON relay_cloud.* TO 'relay_cloud'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

echo "==> Installing Go 1.22..."
wget -q https://go.dev/dl/go1.22.5.linux-amd64.tar.gz
rm -rf /usr/local/go
tar -C /usr/local -xzf go1.22.5.linux-amd64.tar.gz
rm go1.22.5.linux-amd64.tar.gz
echo 'export PATH=$PATH:/usr/local/go/bin' >> /etc/profile.d/go.sh

echo "==> Creating deploy user..."
if ! id "deploy" &>/dev/null; then
    adduser --disabled-password --gecos "" deploy
    usermod -aG www-data deploy
    mkdir -p /home/deploy/.ssh
    cp /root/.ssh/authorized_keys /home/deploy/.ssh/
    chown -R deploy:deploy /home/deploy/.ssh
    chmod 700 /home/deploy/.ssh
    chmod 600 /home/deploy/.ssh/authorized_keys
fi

echo "==> Setting up application directory..."
mkdir -p /var/www/relay-cloud
chown -R deploy:www-data /var/www/relay-cloud

echo "==> Setting up Relay server config directory..."
mkdir -p /etc/relay
chown deploy:deploy /etc/relay

echo "==> Configuring firewall..."
ufw --force reset
ufw default deny incoming
ufw default allow outgoing
ufw allow 22/tcp    # SSH
ufw allow 80/tcp    # HTTP
ufw allow 443/tcp   # HTTPS
ufw allow 6001/tcp  # Relay WebSocket
ufw --force enable

echo "==> Installing Certbot for SSL..."
apt install -y certbot python3-certbot-nginx

echo "==> Setup complete!"
echo ""
echo "Next steps:"
echo "  1. Change the MySQL password in the setup above"
echo "  2. Copy deploy/relay.env.example to /etc/relay/relay.env and configure"
echo "  3. Copy deploy/nginx.conf to /etc/nginx/sites-available/relay-cloud"
echo "  4. Enable the site: ln -s /etc/nginx/sites-available/relay-cloud /etc/nginx/sites-enabled/"
echo "  5. Remove default: rm /etc/nginx/sites-enabled/default"
echo "  6. Test nginx: nginx -t && systemctl reload nginx"
echo "  7. Get SSL: certbot --nginx -d relay-cloud.yourdomain.com -d relay.yourdomain.com"
echo "  8. Deploy the app: su - deploy && cd /var/www/relay-cloud && bash deploy/deploy.sh"
