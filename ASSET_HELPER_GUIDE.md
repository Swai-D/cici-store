# Asset Helper Guide - CICI Store

## 🎯 Overview
Hii guide inakusaidia kutumia asset helper kwenye project yako kwa usahihi, haswa kwenye Railway deployment.

## 📁 Files Zilizobadilishwa

### 1. Layouts
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/guest.blade.php`

### 2. Helpers
- `app/Helpers/AssetHelper.php`
- `app/Providers/AppServiceProvider.php`

### 3. Scripts
- `scripts/fix-assets.sh`
- `package.json` (updated scripts)

## 🔧 Jinsi ya Kutumia

### Development Environment
Kwenye local development, Vite inatumika:
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

### Production Environment (Railway)
Kwenye production, direct assets zinatumiwa:
```blade
<link rel="stylesheet" href="{{ asset('build/assets/app-7dFzyK7f.css') }}">
<script src="{{ asset('build/assets/app-DNxiirP_.js') }}" defer></script>
```

## 🚀 Hatua za Kufanya Kazi Railway

### 1. Build Assets
```bash
npm run build:prod
```

### 2. Run Fix Script
```bash
chmod +x scripts/fix-assets.sh
./scripts/fix-assets.sh
```

### 3. Verify Assets
Hakikisha files zipo:
- `public/build/assets/app-7dFzyK7f.css`
- `public/build/assets/app-DNxiirP_.js`

## 📝 Jinsi ya Kuongeza Assets Mpya

### CSS Files
```blade
<!-- Development -->
@vite(['resources/css/custom.css'])

<!-- Production -->
<link rel="stylesheet" href="{{ asset('build/assets/custom-xyz123.css') }}">
```

### JavaScript Files
```blade
<!-- Development -->
@vite(['resources/js/custom.js'])

<!-- Production -->
<script src="{{ asset('build/assets/custom-xyz123.js') }}" defer></script>
```

## 🛠️ Custom Blade Directives

Unaweza kutumia custom directives:

```blade
@viteCss('resources/css/app.css')
@viteJs('resources/js/app.js')
@viteAsset('resources/css/app.css')
```

## 🔍 Troubleshooting

### CSS Hazionekani
1. Hakikisha `npm run build` imefanikiwa
2. Angalia kama `public/build/assets/` folder ipo
3. Clear caches: `php artisan view:clear`

### JavaScript Hazifanyi Kazi
1. Hakikisha script tag ina `defer` attribute
2. Angalia browser console kwa errors
3. Verify file path ni sahihi

### Railway Deployment Issues
1. Run fix script: `./scripts/fix-assets.sh`
2. Hakikisha environment variables zipo sahihi
3. Clear all caches

## 📊 Environment Detection

System inatumia environment detection:
- `local` au `development` → Vite
- `production` → Direct assets

## 🔄 Update Assets

Ikiwa unaongeza CSS/JS mpya:

1. **Development:**
   ```bash
   npm run dev
   ```

2. **Production:**
   ```bash
   npm run build:prod
   ```

3. **Railway:**
   - Push changes to GitHub
   - Railway ita-auto rebuild
   - Run fix script kwenye Railway console

## ✅ Best Practices

1. **Always use asset() helper** kwa static files
2. **Use @vite directive** kwa development
3. **Clear caches** baada ya changes
4. **Test both environments** kabla ya deployment
5. **Keep asset filenames updated** kwenye production

## 🆘 Support

Kama una changamoto:
1. Angalia browser console
2. Check Railway logs
3. Verify asset paths
4. Run fix script

---

**Asset helper yako sasa inafanya kazi vizuri kwenye development na production!** 🎉 