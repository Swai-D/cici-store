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

# CICI Store - Railway Deployment Guide

## 🎯 Overview
Hii guide inakusaidia ku-deploy CICI Store kwenye Railway na kuhakikisha assets (CSS/JS) zinafanya kazi vizuri kwenye production.

## 🔧 Asset Helper Solution

### How It Works
Your project uses a custom Asset Helper that automatically switches between:
- **Development**: Uses Vite for hot reloading
- **Production**: Uses compiled assets from `public/build/`

### Key Components
1. **AssetHelper.php** - Handles asset URL generation
2. **Custom Blade Directives** - `@viteCss`, `@viteJs`, `@viteAsset`
3. **Fix Assets Script** - Ensures proper asset setup on Railway

## 🚀 Railway Deployment Steps

### 1. Pre-deployment Setup
```bash
# Build assets locally first
npm run build

# Run fix script
chmod +x scripts/fix-assets.sh
./scripts/fix-assets.sh

# Commit all changes including public/build/
git add .
git commit -m "Build assets for production"
git push
```

### 2. Railway Configuration
Railway will automatically:
- Install dependencies (`composer install`, `npm ci`)
- Build assets (`npm run build`)
- Run fix script (`./scripts/fix-assets.sh`)
- Cache Laravel configs
- Start the application

### 3. Environment Variables
Make sure these are set in Railway:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
```

## 🔍 Troubleshooting

### CSS Not Loading
1. **Check Railway logs** for build errors
2. **Verify assets exist**:
   ```bash
   ls -la public/build/assets/
   ```
3. **Run fix script manually** on Railway console:
   ```bash
   chmod +x scripts/fix-assets.sh
   ./scripts/fix-assets.sh
   ```

### JavaScript Not Working
1. **Check browser console** for 404 errors
2. **Verify manifest.json** exists and is valid
3. **Clear caches**:
   ```bash
   php artisan view:clear
   php artisan config:clear
   ```

### Build Failures
1. **Check Node.js version** compatibility
2. **Verify package.json** dependencies
3. **Check Vite configuration**

## 📁 File Structure After Deployment

```
public/
├── build/
│   ├── assets/
│   │   ├── app-7dFzyK7f.css
│   │   └── app-DNxiirP_.js
│   └── manifest.json
└── index.php
```

## 🔄 Updating Assets

### Adding New CSS/JS Files
1. **Add to Vite config**:
   ```javascript
   // vite.config.js
   input: [
       'resources/css/app.css',
       'resources/js/app.js',
       'resources/css/new-file.css'  // Add here
   ]
   ```

2. **Use in Blade templates**:
   ```blade
   @viteCss('resources/css/new-file.css')
   @viteJs('resources/js/new-file.js')
   ```

3. **Rebuild and deploy**:
   ```bash
   npm run build
   git add public/build/
   git commit -m "Add new assets"
   git push
   ```

## ✅ Verification Checklist

Before deploying, ensure:
- [ ] `npm run build` runs successfully
- [ ] `public/build/assets/` contains CSS and JS files
- [ ] `public/build/manifest.json` is valid JSON
- [ ] All layouts use `@viteCss` and `@viteJs` directives
- [ ] `.gitignore` doesn't exclude `public/build/`
- [ ] Fix script is executable and working

## 🆘 Common Issues

### "Assets not found" Error
- Run fix script on Railway console
- Check if `public/build/` directory exists
- Verify file permissions

### "Manifest not found" Error
- Ensure `npm run build` completed successfully
- Check if `manifest.json` exists in `public/build/`
- Clear Laravel caches

### "Vite not available" Error
- This is normal in production
- Asset helper will fall back to compiled assets
- Check if fallback assets exist

## 🎉 Success Indicators

Your deployment is successful when:
- ✅ CSS styles load properly
- ✅ JavaScript functionality works
- ✅ No 404 errors for assets
- ✅ Application looks identical to local development
- ✅ All interactive features work

---

**Your asset helper solution is now fully configured for Railway deployment!** 🚀 

## Common Issues and Solutions

### 1. Vite Manifest Not Found Error

**Error:** `Vite manifest not found at: /app/public/build/manifest.json`

**Cause:** This happens when Vite assets haven't been built for production or the manifest file is in the wrong location.

**Solution:**
1. Ensure `npm run build:prod` runs during deployment
2. The manifest file should be at `public/build/manifest.json`
3. For Vite 6.x, the manifest might be at `public/build/.vite/manifest.json` and needs to be copied

### 2. Asset Building Process

The deployment process should:
1. Install dependencies: `npm ci`
2. Build assets: `npm run build:prod`
3. Run the fix-assets script: `./scripts/fix-assets.sh`
4. Cache Laravel configurations

### 3. Environment Variables

Make sure these are set in Railway:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY` (Laravel encryption key)
- `APP_URL` (your Railway app URL)
- Database credentials

### 4. Troubleshooting

If assets still don't work:
1. Check Railway logs for build errors
2. Verify the `public/build/` directory exists
3. Ensure manifest.json is present
4. Check file permissions (755 for directories, 644 for files)

### 5. Manual Fix

If automatic deployment fails, you can manually fix by:
```bash
# SSH into Railway container
railway shell

# Build assets manually
npm run build:prod

# Run fix script
./scripts/fix-assets.sh

# Clear and recache
php artisan config:clear
php artisan config:cache
php artisan view:clear
php artisan view:cache
```

## File Structure After Build

After successful build, you should have:
```
public/build/
├── assets/
│   ├── app-[hash].css
│   └── app-[hash].js
└── manifest.json
```

## Railway Configuration

The `nixpacks.toml` file handles:
- Node.js and PHP installation
- Dependency installation
- Asset building
- Laravel optimization
- Server startup 