# Relay Cloud — Hetzner Deployment Guide

## Step 1: Create Hetzner Server

1. Go to [hetzner.com/cloud](https://www.hetzner.com/cloud)
2. Create a new project called **relay-cloud**
3. Add a new server:
   - **Image:** Ubuntu 24.04
   - **Type:** CX22 (2 vCPU, 4GB RAM, ~$6/mo)
   - **Location:** Choose closest to your users
   - **SSH Key:** Add your public key during setup
4. Note the server IP address

## Step 2: Initial Server Setup

SSH into your server and run the setup script:

```bash
ssh root@YOUR_SERVER_IP 'bash -s' < deploy/setup.sh
```

This installs:
- Nginx
- PHP 8.3 + FPM + extensions
- Composer
- Node.js 20 (via nvm)
- MySQL 8.0
- Go 1.22
- Certbot for SSL
- UFW firewall (ports 22, 80, 443, 6001)
- A `deploy` user for running the app

**Important:** Edit the script first to set a strong MySQL password.

## Step 3: Deploy the Relay Server (Go Binary)

Build the Relay server binary and copy it to the server:

```bash
# On your local machine (or CI)
cd /path/to/relay-server
GOOS=linux GOARCH=amd64 go build -o relay-server .
scp relay-server deploy@YOUR_SERVER_IP:/opt/relay/relay-server
```

Copy the environment file:

```bash
scp deploy/relay.env.example deploy@YOUR_SERVER_IP:/etc/relay/relay.env
```

Edit `/etc/relay/relay.env` on the server with your settings.

Install and start the systemd service:

```bash
sudo cp deploy/relay-server.service /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable relay-server
sudo systemctl start relay-server
```

Verify it's running:

```bash
sudo systemctl status relay-server
curl http://localhost:6001/health
```

## Step 4: Deploy the Laravel App

Clone the repo on the server:

```bash
sudo -u deploy bash
cd /var/www/relay-cloud
git clone git@github.com:YOUR_ORG/relay-cloud.git .
```

Copy and configure the environment:

```bash
cp deploy/.env.production.example .env
php artisan key:generate
```

Edit `.env` with your MySQL credentials, domain, and mail settings.

Run the deployment script:

```bash
bash deploy/deploy.sh
```

## Step 5: Configure Nginx

```bash
sudo cp deploy/nginx.conf /etc/nginx/sites-available/relay-cloud
sudo ln -s /etc/nginx/sites-available/relay-cloud /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

Replace `relay-cloud.yourdomain.com` and `relay.yourdomain.com` in the config with your actual domains.

## Step 6: SSL with Certbot

```bash
sudo certbot --nginx -d relay-cloud.yourdomain.com -d relay.yourdomain.com
```

Certbot will auto-renew. Verify:

```bash
sudo certbot renew --dry-run
```

## Step 7: GitHub Actions CI/CD

The workflow at `.github/workflows/deploy.yml` auto-deploys on push to `main`.

Add these secrets in your GitHub repo settings (**Settings > Secrets > Actions**):

| Secret | Value |
|--------|-------|
| `DEPLOY_HOST` | Your Hetzner server IP |
| `DEPLOY_USER` | `deploy` |
| `DEPLOY_KEY` | Contents of `~/.ssh/id_ed25519` (private key) |

Make sure the deploy user can run the necessary sudo commands. Add to `/etc/sudoers.d/deploy`:

```
deploy ALL=(ALL) NOPASSWD: /usr/bin/systemctl restart php8.3-fpm, /usr/bin/systemctl reload nginx
```

## Monitoring

Check Relay server logs:

```bash
sudo journalctl -u relay-server -f
```

Check Laravel logs:

```bash
tail -f /var/www/relay-cloud/storage/logs/laravel.log
```

Check Nginx logs:

```bash
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log
```
