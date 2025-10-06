# Publishing Guide

This guide explains how to publish the Google One Tap Login package to GitHub and Packagist so other developers can install it with `composer require sumaia/google-onetap-login`.

## Prerequisites

Before publishing, ensure you have:

- A GitHub account
- A Packagist account (free at [packagist.org](https://packagist.org))
- Git installed locally
- The package is complete and tested

## Step 1: Prepare the Package

### 1.1 Verify Package Structure

Ensure your package has the correct structure:

```
packages/sumaia/google-onetap-login/
├── composer.json                    # Package definition
├── README.md                        # Documentation
├── INSTALLATION.md                  # Installation guide
├── INSTALLATION_VERIFICATION.md     # Installation verification guide
├── CHANGELOG.md                     # Version history
├── LICENSE.md                       # MIT License
├── src/
│   ├── GoogleOneTap.php            # Core service class
│   ├── Providers/
│   │   └── GoogleOneTapServiceProvider.php
│   ├── Http/Controllers/
│   │   └── GoogleOneTapController.php
│   └── View/Components/
│       └── GoogleOneTapButton.php
├── config/
│   └── google-onetap.php           # Configuration file
├── database/migrations/
│   └── 2024_01_01_000000_add_google_fields_to_users_table.php
├── resources/views/
│   ├── auth/
│   │   └── login.blade.php
│   ├── dashboard.blade.php
│   └── components/
│       └── google-onetap-button.blade.php
├── routes/
│   └── web.php
└── tests/                          # Test files
```

### 1.2 Update composer.json

Ensure your `composer.json` is complete:

```json
{
    "name": "sumaia/google-onetap-login",
    "description": "A Laravel package for seamless Google One Tap authentication integration",
    "keywords": [
        "laravel",
        "google",
        "one-tap",
        "authentication",
        "oauth",
        "login",
        "package"
    ],
    "type": "library",
    "license": "MIT",
    "authors": [
        {
            "name": "Sumaia",
            "email": "contact@sumaia.dev"
        }
    ],
    "require": {
        "php": "^8.1|^8.2|^8.3",
        "laravel/framework": "^10.0|^11.0|^12.0",
        "google/apiclient": "^2.15"
    },
    "require-dev": {
        "orchestra/testbench": "^8.0|^9.0",
        "phpunit/phpunit": "^10.0|^11.0"
    },
    "autoload": {
        "psr-4": {
            "Sumaia\\GoogleOneTapLogin\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Sumaia\\GoogleOneTapLogin\\Tests\\": "tests/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "Sumaia\\GoogleOneTapLogin\\Providers\\GoogleOneTapServiceProvider"
            ]
        }
    },
    "config": {
        "sort-packages": true
    },
    "minimum-stability": "stable",
    "prefer-stable": true
}
```

### 1.3 Create/Update Documentation

Ensure you have comprehensive documentation:

- **README.md**: Overview, features, installation, usage
- **INSTALLATION.md**: Detailed installation steps
- **INSTALLATION_VERIFICATION.md**: Installation verification and troubleshooting
- **CHANGELOG.md**: Version history and changes
- **LICENSE.md**: MIT license text

## Step 2: Create GitHub Repository

### 2.1 Create Repository on GitHub

1. Go to [GitHub](https://github.com) and sign in
2. Click "New repository"
3. Repository name: `google-onetap-login`
4. Description: "A Laravel package for seamless Google One Tap authentication integration"
5. Make it **Public** (required for Packagist)
6. Don't initialize with README (we already have one)
7. Click "Create repository"

### 2.2 Initialize Git and Push

From your package directory:

```bash
cd packages/sumaia/google-onetap-login

# Initialize git repository
git init

# Add all files
git add .

# Create initial commit
git commit -m "Initial release: Google One Tap Login package for Laravel"

# Add GitHub remote (replace with your GitHub username)
git remote add origin https://github.com/sumaia/google-onetap-login.git

# Push to GitHub
git branch -M main
git push -u origin main
```

### 2.3 Create Release Tag

Create a version tag for your first release:

```bash
# Create and push version tag
git tag v1.0.0
git push origin v1.0.0
```

## Step 3: Publish to Packagist

### 3.1 Register on Packagist

1. Go to [Packagist.org](https://packagist.org)
2. Click "Sign in with GitHub"
3. Authorize Packagist to access your GitHub account

### 3.2 Submit Your Package

1. Click "Submit" in the top navigation
2. Enter your GitHub repository URL:
   ```
   https://github.com/sumaia/google-onetap-login
   ```
3. Click "Check"
4. If validation passes, click "Submit"

### 3.3 Set Up Auto-Update (Recommended)

To automatically update Packagist when you push to GitHub:

1. Go to your package page on Packagist
2. Click "Settings" tab
3. Copy the webhook URL
4. Go to your GitHub repository
5. Go to Settings → Webhooks
6. Click "Add webhook"
7. Paste the Packagist webhook URL
8. Content type: `application/json`
9. Select "Just the push event"
10. Click "Add webhook"

## Step 4: Test Installation

Test that others can install your package:

```bash
# In a fresh Laravel project
composer require sumaia/google-onetap-login
```

## Step 5: Maintenance and Updates

### 5.1 Making Updates

When you make changes:

1. Update version in `composer.json` if needed
2. Update `CHANGELOG.md`
3. Commit and push changes
4. Create new release tag:
   ```bash
   git tag v1.0.1
   git push origin v1.0.1
   ```

### 5.2 Semantic Versioning

Follow [Semantic Versioning](https://semver.org/):

- **MAJOR** (v2.0.0): Breaking changes
- **MINOR** (v1.1.0): New features, backward compatible
- **PATCH** (v1.0.1): Bug fixes, backward compatible

## Step 6: Promote Your Package

### 6.1 Add Badges to README

Add these badges to your README.md:

```markdown
[![Latest Version on Packagist](https://img.shields.io/packagist/v/sumaia/google-onetap-login.svg?style=flat-square)](https://packagist.org/packages/sumaia/google-onetap-login)
[![Total Downloads](https://img.shields.io/packagist/dt/sumaia/google-onetap-login.svg?style=flat-square)](https://packagist.org/packages/sumaia/google-onetap-login)
[![License](https://img.shields.io/packagist/l/sumaia/google-onetap-login.svg?style=flat-square)](https://packagist.org/packages/sumaia/google-onetap-login)
```

### 6.2 Share Your Package

- Post on Laravel communities
- Share on social media
- Submit to Laravel package directories
- Write blog posts about your package

## Troubleshooting

### Common Issues

1. **Packagist validation fails**
   - Check composer.json syntax
   - Ensure all required fields are present
   - Verify PSR-4 autoloading paths

2. **Package not found after publishing**
   - Wait a few minutes for indexing
   - Check if repository is public
   - Verify package name in composer.json

3. **Auto-update not working**
   - Check webhook configuration
   - Verify webhook URL is correct
   - Check GitHub webhook delivery logs

## Best Practices

1. **Write comprehensive tests**
2. **Follow PSR standards**
3. **Use semantic versioning**
4. **Keep documentation updated**
5. **Respond to issues promptly**
6. **Follow Laravel package conventions**

## Support

If you encounter issues during publishing:

1. Check Packagist documentation
2. Review GitHub's package publishing guide
3. Ask for help in Laravel communities
4. Open issues on relevant repositories

Your package is now published and available for the Laravel community to use!
