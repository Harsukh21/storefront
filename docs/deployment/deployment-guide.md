# Deployment Guide

This guide covers the deployment pipeline and procedures for the Storefront application.

## Pre-Deployment Checklist

- [ ] All migrations have been tested locally
- [ ] Environment variables are configured for production
- [ ] Database backups are scheduled
- [ ] SSL certificates are configured
- [ ] Domain DNS is configured
- [ ] CDN is configured (if applicable)
- [ ] Queue workers are configured
- [ ] Scheduled tasks (cron) are configured

## Environment Configuration

### Required Environment Variables

```env
APP_NAME="Storefront"
APP_ENV=production
APP_KEY=base64:your-generated-key-here
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=storefront_prod
DB_USERNAME=your-db-user
DB_PASSWORD=your-secure-password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Deployment Steps

### 1. Server Preparation

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required PHP extensions
sudo apt install php8.4-fpm php8.4-pgsql php8.4-mbstring php8.4-xml php8.4-curl php8.4-zip php8.4-redis

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js (if needed for asset compilation)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### 2. Application Deployment

```bash
# Clone repository
git clone https://your-repo-url.git /var/www/storefront
cd /var/www/storefront

# Install dependencies
composer install --no-dev --optimize-autoloader

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure environment variables
nano .env

# Run migrations
php artisan migrate --force

# Seed database (optional, for initial setup)
php artisan db:seed

# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data /var/www/storefront
sudo chmod -R 755 /var/www/storefront
sudo chmod -R 775 /var/www/storefront/storage
sudo chmod -R 775 /var/www/storefront/bootstrap/cache
```

### 3. Web Server Configuration

#### Nginx Configuration

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com www.your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name your-domain.com www.your-domain.com;

    root /var/www/storefront/public;

    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 4. Queue Worker Configuration

Create `/etc/supervisor/conf.d/storefront-worker.conf`:

```ini
[program:storefront-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/storefront/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/storefront/storage/logs/worker.log
stopwaitsecs=3600
```

Start supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start storefront-worker:*
```

### 5. Scheduled Tasks

Add to crontab (`crontab -e`):

```bash
* * * * * cd /var/www/storefront && php artisan schedule:run >> /dev/null 2>&1
```

### 6. Post-Deployment Verification

```bash
# Check application status
php artisan about

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Verify queue workers
sudo supervisorctl status

# Check scheduled tasks
php artisan schedule:list
```

## Rollback Procedure

If deployment fails:

```bash
# Restore previous release
cd /var/www/storefront
git checkout previous-stable-tag

# Rollback migrations (if needed)
php artisan migrate:rollback --step=1

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Restart services
sudo systemctl restart php8.4-fpm
sudo supervisorctl restart storefront-worker:*
```

## Monitoring

- Application logs: `/var/www/storefront/storage/logs/laravel.log`
- Queue worker logs: `/var/www/storefront/storage/logs/worker.log`
- Nginx access logs: `/var/log/nginx/access.log`
- Nginx error logs: `/var/log/nginx/error.log`

## Security Checklist

- [ ] APP_DEBUG=false in production
- [ ] Strong database passwords
- [ ] SSL/TLS certificates installed
- [ ] Firewall configured (UFW/iptables)
- [ ] Regular security updates scheduled
- [ ] Database backups automated
- [ ] File permissions set correctly
- [ ] Rate limiting enabled
- [ ] CSRF protection enabled
- [ ] XSS protection headers configured

