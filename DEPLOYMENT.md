# CICI Store - Deployment Guide

## 🚀 Pre-Deployment Checklist

### 1. Environment Configuration
Create a `.env` file in the root directory with the following content:

```env
APP_NAME="CICI Store"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cici_store
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"

# Application Settings
APP_TIMEZONE=Africa/Dar_es_Salaam
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

### 2. Generate Application Key
```bash
php artisan key:generate
```

### 3. Database Setup
1. Create MySQL database named `cici_store`
2. Update database credentials in `.env`
3. Run migrations:
```bash
php artisan migrate
```
4. Seed the database:
```bash
php artisan db:seed
```

### 4. Storage Setup
```bash
php artisan storage:link
```

### 5. Set Proper Permissions
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 6. Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 7. Clear Caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔧 Server Requirements

- PHP >= 8.1
- MySQL >= 5.7 or MariaDB >= 10.2
- Composer
- Node.js & NPM
- Web server (Apache/Nginx)

## 📁 File Structure to Upload

Upload the entire project except:
- `node_modules/` (will be installed on server)
- `.git/` (if exists)
- `.env` (create on server)
- `storage/logs/` (create on server)

## 🛡️ Security Considerations

1. **Set APP_DEBUG=false** in production
2. **Use strong database passwords**
3. **Enable HTTPS** on your domain
4. **Set proper file permissions**
5. **Regular backups** of database and files

## 📊 Post-Deployment

1. Test all functionality:
   - User registration/login
   - Product management
   - Sales recording
   - Expense tracking
   - Reports generation

2. Monitor error logs:
   - Check `storage/logs/laravel.log`
   - Monitor server error logs

3. Performance optimization:
   - Enable OPcache
   - Configure Redis for caching (optional)
   - Set up CDN for assets (optional)

## 🆘 Troubleshooting

### Common Issues:
1. **500 Error**: Check storage permissions and APP_KEY
2. **Database Connection**: Verify DB credentials in `.env`
3. **Assets not loading**: Run `npm run build` and check `storage:link`
4. **Migration errors**: Ensure database exists and user has permissions

### Useful Commands:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 📞 Support

For issues related to:
- Laravel Framework: Check Laravel documentation
- Application Logic: Review code comments and documentation
- Server Issues: Contact your hosting provider 