# Installation Verification Guide

This comprehensive guide helps you verify that the Google One Tap Login package is correctly installed, configured, and functioning in your Laravel application.

## Quick Verification Checklist

Use this checklist to ensure everything is working correctly:

### ✅ Package Installation

- [ ] Package installed via Composer: `composer show sumaia/google-onetap-login`
- [ ] Service provider auto-discovered: `php artisan package:discover`
- [ ] Configuration published: `config/google-onetap.php` exists
- [ ] Migrations published and run: Check `database/migrations/` for Google fields migration

### ✅ Google Cloud Console Setup

- [ ] Google Cloud project created
- [ ] OAuth 2.0 credentials created
- [ ] Authorized JavaScript origins configured
- [ ] Authorized redirect URIs configured
- [ ] Client ID and Secret copied to `.env`

### ✅ Environment Configuration

- [ ] `GOOGLE_CLIENT_ID` set in `.env`
- [ ] `GOOGLE_CLIENT_SECRET` set in `.env`
- [ ] Configuration cache cleared: `php artisan config:clear`

### ✅ Database Setup

- [ ] Migration run successfully: `php artisan migrate:status`
- [ ] Users table has `google_id` column
- [ ] Users table has `avatar` column
- [ ] User model includes Google fields in `$fillable`

### ✅ Routes and Views

- [ ] Package routes registered: `php artisan route:list | grep google`
- [ ] Login page accessible: Visit `/login`
- [ ] Google One Tap modal appears on login page

## Detailed Verification Commands

### 1. Check Package Installation

```bash
# Verify package is installed
composer show sumaia/google-onetap-login

# Check service provider registration
php artisan package:discover | grep GoogleOneTap
```

### 2. Verify Configuration

```bash
# Check if config file exists
ls -la config/google-onetap.php

# View current configuration
php artisan config:show google-onetap
```

### 3. Check Database Setup

```bash
# Check migration status
php artisan migrate:status | grep google

# Verify table structure (MySQL example)
php artisan tinker
>>> Schema::hasColumn('users', 'google_id')
>>> Schema::hasColumn('users', 'avatar')
>>> exit
```

### 4. Test Routes

```bash
# List Google One Tap routes
php artisan route:list | grep google

# Expected routes:
# POST auth/google/callback
# GET  dashboard
# GET  login
# POST logout
```

### 5. Verify Environment Variables

```bash
# Check environment configuration
php artisan tinker
>>> config('google-onetap.client_id')
>>> config('google-onetap.client_secret')
>>> exit
```

## Manual Testing Steps

### 1. Test Login Page

1. Start your development server:
   ```bash
   php artisan serve
   ```

2. Visit: `http://localhost:8000/login`

3. Verify:
   - Page loads without errors
   - Google One Tap modal appears (if configured correctly)
   - No JavaScript console errors

### 2. Test Google One Tap Modal

1. Open browser developer tools (F12)
2. Visit the login page
3. Check for:
   - Google Sign-In script loads: `https://accounts.google.com/gsi/client`
   - No JavaScript errors in console
   - Google One Tap modal appears in top-right corner

### 3. Test Authentication Flow

1. Click on your Google account in the One Tap modal
2. Verify:
   - No errors in browser console
   - Request sent to `/auth/google/callback`
   - User redirected to dashboard on success

## Troubleshooting Common Issues

### Issue: Package Not Found

**Symptoms**: `composer require` fails or package not discovered

**Solutions**:
```bash
# Clear Composer cache
composer clear-cache

# Update Composer
composer self-update

# Verify package exists on Packagist
composer search sumaia/google-onetap-login
```

### Issue: Configuration Not Loading

**Symptoms**: Config values are null or default

**Solutions**:
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Re-publish configuration
php artisan vendor:publish --tag=google-onetap-config --force
```

### Issue: Migration Errors

**Symptoms**: Migration fails or columns already exist

**Solutions**:
```bash
# Check existing columns
php artisan tinker
>>> Schema::hasColumn('users', 'google_id')

# If columns exist, skip migration or modify it
# Edit the migration file to handle existing columns
```

### Issue: Routes Not Working

**Symptoms**: 404 errors on Google One Tap routes

**Solutions**:
```bash
# Clear route cache
php artisan route:clear

# Check if service provider is loaded
php artisan package:discover

# Manually register service provider if needed (config/app.php)
```

### Issue: Google One Tap Not Appearing

**Symptoms**: Modal doesn't show on login page

**Solutions**:
1. Check browser console for JavaScript errors
2. Verify Google Client ID is set correctly
3. Check authorized origins in Google Console
4. Ensure domain is authorized for Google One Tap

### Issue: Authentication Fails

**Symptoms**: Token verification fails or user not created

**Solutions**:
1. Verify Google Client Secret is correct
2. Check Laravel logs: `tail -f storage/logs/laravel.log`
3. Ensure User model has Google fields in `$fillable`
4. Check database connection and permissions

## Getting Help

If you're still experiencing issues after following this guide:

1. **Check the logs**: `storage/logs/laravel.log`
2. **Review documentation**: [README.md](README.md) and [INSTALLATION.md](INSTALLATION.md)
3. **Search existing issues**: [GitHub Issues](https://github.com/sumaia/google-onetap-login/issues)
4. **Create a new issue**: Include error messages, Laravel version, and steps to reproduce

## Success Indicators

Your installation is successful when:

- ✅ No errors during package installation
- ✅ Configuration file published and customizable
- ✅ Database migrations run successfully
- ✅ Google One Tap modal appears on login page
- ✅ Authentication flow completes without errors
- ✅ Users can sign in and are redirected to dashboard

Congratulations! Your Google One Tap Login package is now ready for production use.
