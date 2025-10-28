# WordPress Deployment Pipeline

Lightweight, git-based deployment system for the Sunnyside AC WordPress site on Hetzner.

## Overview

This deployment system uses:
- **Git hooks** for automatic deployment
- **Tailscale SSH** for secure server access
- **WP-CLI** for database operations
- **Zero dependencies** on external CI/CD platforms

## Architecture

```
┌─────────────────┐         ┌──────────────────┐         ┌─────────────────┐
│                 │         │                  │         │                 │
│  Local Dev      │  Push   │  Git Server      │  Hook   │  Production     │
│  (DDEV)         │────────▶│  (Bare Repo)     │────────▶│  (Live Site)    │
│                 │         │                  │         │                 │
└─────────────────┘         └──────────────────┘         └─────────────────┘
                                     │
                                     │ Triggers
                                     ▼
                            ┌─────────────────┐
                            │  deploy.sh      │
                            │  - Git checkout │
                            │  - Composer     │
                            │  - NPM build    │
                            │  - WP optimiz.  │
                            └─────────────────┘
```

## Files

- **deploy.sh** - Main deployment script (runs on server)
- **post-receive** - Git hook that triggers deployment
- **db-sync.sh** - Database sync helper (local ⟷ production)
- **README.md** - This documentation

## Setup Instructions

### 1. Server Preparation

SSH into your production server via Tailscale:

```bash
ssh root@sunnysideac
```

#### A. Install Required Tools

```bash
# Install Composer (if not installed)
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php

# Install Node.js (if not installed) - using Node 20 LTS
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt-get install -y nodejs

# Verify installations
composer --version
node --version
npm --version
wp --version --allow-root
```

#### B. Set Up Directory Structure

```bash
# Create project directories
mkdir -p /var/www/sunnysideac
mkdir -p /var/www/sunnysideac.git

# Navigate to git directory
cd /var/www/sunnysideac.git

# Initialize bare git repository
git init --bare

# Set work tree
git config core.worktree /var/www/sunnysideac
```

#### C. Install Git Hook

```bash
# Navigate to hooks directory
cd /var/www/sunnysideac.git/hooks

# Download post-receive hook from your repo or copy it manually
# (You'll push this file first, then copy it here)

# Make it executable
chmod +x post-receive
```

### 2. Local Development Setup

On your local machine (in the project root):

#### A. Configure Deployment

Edit `.deployment/db-sync.sh` and update these variables:

```bash
PROD_SERVER="root@sunnysideac"        # Your Tailscale SSH hostname
PROD_WP_PATH="/var/www/sunnysideac/app/public"
PROD_URL="https://yourdomain.com"     # ⚠️  UPDATE THIS!
```

Edit `.deployment/deploy.sh` and update these variables if needed:

```bash
PROJECT_ROOT="/var/www/sunnysideac"
```

#### B. Make Scripts Executable

```bash
chmod +x .deployment/deploy.sh
chmod +x .deployment/db-sync.sh
chmod +x .deployment/post-receive
```

#### C. Add Production Remote

```bash
# Add production git remote
git remote add production root@sunnysideac:/var/www/sunnysideac.git

# Verify remotes
git remote -v
```

### 3. Initial Deployment

#### A. Create Production Branch

```bash
# Create and checkout prod branch
git checkout -b prod

# Push to production for the first time
git push production prod
```

This will:
- Push your code to the server
- Trigger the post-receive hook
- Run the deployment script
- Install dependencies and build assets

#### B. Manual Hook Setup (First Time Only)

On the server, manually copy the hook file:

```bash
ssh root@sunnysideac

# Copy post-receive hook from work tree to git hooks
cp /var/www/sunnysideac/.deployment/post-receive /var/www/sunnysideac.git/hooks/post-receive

# Make it executable
chmod +x /var/www/sunnysideac.git/hooks/post-receive
```

#### C. Push Database to Production

From your local machine:

```bash
# Export your local database to production
./.deployment/db-sync.sh push
```

This will:
- Backup production database (safety first!)
- Export your local database
- Upload and import to production
- Replace local URLs with production URLs
- Flush WordPress caches

### 4. Configure WordPress on Production

SSH to production and configure WordPress settings:

```bash
ssh root@sunnysideac
cd /var/www/sunnysideac/app/public

# Update site URL (if needed)
wp option update home 'https://yourdomain.com' --allow-root
wp option update siteurl 'https://yourdomain.com' --allow-root

# Flush permalinks
wp rewrite flush --allow-root

# Flush cache
wp cache flush --allow-root
```

## Daily Workflow

### Making Code Changes

```bash
# Work on main branch as usual
git checkout main

# Make your changes
# ... edit files ...

# Commit changes
git add .
git commit -m "Your commit message"

# Push to origin (backup)
git push origin main

# When ready to deploy, merge to prod branch
git checkout prod
git merge main

# Deploy to production
git push production prod
```

The deployment happens automatically via the git hook!

### Syncing Database

#### Pull Production DB to Local (Safe for Testing)

```bash
# Get latest production data locally
./.deployment/db-sync.sh pull
```

Use this when you want to work with real production data locally.

#### Push Local DB to Production (Dangerous!)

```bash
# Push your local database to production
./.deployment/db-sync.sh push
```

⚠️  **Warning**: This overwrites production data! Only use when:
- Deploying a new site
- You've made content changes locally that need to go live
- You understand the risks

**For most deployments, you don't need to sync the database!** Only code changes are deployed automatically.

## What Gets Deployed Automatically

When you `git push production prod`:

✅ **Automatically deployed**:
- PHP code (theme files, plugins)
- Template changes
- CSS/JS source files
- Configuration files

✅ **Automatically rebuilt**:
- Production assets (Vite builds CSS/JS)
- Composer dependencies installed
- NPM dependencies installed

✅ **Automatically optimized**:
- Rewrite rules flushed
- WordPress cache cleared
- File permissions set

❌ **NOT automatically deployed**:
- Database content (posts, pages, settings)
- Uploaded media files
- User accounts

## Advanced Usage

### Manual Deployment

If you need to deploy without using git:

```bash
ssh root@sunnysideac
cd /var/www/sunnysideac
bash .deployment/deploy.sh
```

### Viewing Deployment Logs

```bash
ssh root@sunnysideac
tail -f /var/www/sunnysideac/deploy.log
```

### Rolling Back a Deployment

```bash
ssh root@sunnysideac
cd /var/www/sunnysideac

# Find the commit you want to roll back to
git log --oneline -10

# Checkout that commit
git checkout <commit-hash>

# Run deployment
bash .deployment/deploy.sh
```

Or from local:

```bash
# Reset prod branch to previous commit
git checkout prod
git reset --hard HEAD~1

# Force push to production
git push production prod --force
```

### Restoring Database Backup

Backups are stored in `.deployment/db-backups/` locally.

To restore a backup:

```bash
# Restore local backup
ddev wp db import .deployment/db-backups/local_backup_TIMESTAMP.sql

# Restore production backup
ssh root@sunnysideac
cd /var/www/sunnysideac/app/public
wp db import /path/to/backup.sql --allow-root
```

## Troubleshooting

### Deployment fails with "permission denied"

```bash
# Check file ownership on server
ssh root@sunnysideac
chown -R www-data:www-data /var/www/sunnysideac
chmod -R 755 /var/www/sunnysideac
```

### Git hook not triggering

```bash
# Verify hook is executable
ssh root@sunnysideac
ls -la /var/www/sunnysideac.git/hooks/post-receive
chmod +x /var/www/sunnysideac.git/hooks/post-receive
```

### Composer or NPM fails

```bash
# SSH to server and run manually
ssh root@sunnysideac
cd /var/www/sunnysideac/app/public/wp-content/themes/sunnysideac

# Test Composer
composer install --no-dev --optimize-autoloader

# Test NPM
npm ci
npm run build
```

### Database sync fails

```bash
# Test SSH connection
ssh root@sunnysideac "wp --version --allow-root"

# Test WP-CLI on production
ssh root@sunnysideac "cd /var/www/sunnysideac/app/public && wp db check --allow-root"
```

### Assets not loading after deployment

```bash
# Clear all caches
ssh root@sunnysideac
cd /var/www/sunnysideac/app/public
wp cache flush --allow-root
wp rewrite flush --allow-root

# Rebuild assets manually
cd /var/www/sunnysideac/app/public/wp-content/themes/sunnysideac
npm run build
```

## Security Considerations

### SSH Key Authentication

Ensure you're using SSH keys, not passwords:

```bash
# Generate SSH key if you don't have one
ssh-keygen -t ed25519 -C "your_email@example.com"

# Copy to production server
ssh-copy-id root@sunnysideac
```

### File Permissions

The deploy script sets conservative permissions:
- Files: 644 (read/write for owner, read for group/others)
- Directories: 755 (rwx for owner, rx for group/others)
- Uploads: 775 (writable by web server)

### Sensitive Files

Ensure these are in `.gitignore`:
- `.env` files
- `wp-config.php` (if it contains secrets)
- Database backups (`.deployment/db-backups/`)

### Environment Variables

Create `.env` files on the server directly, don't commit them:

```bash
ssh root@sunnysideac
cd /var/www/sunnysideac/app/public/wp-content/themes/sunnysideac
nano .env
# Add your production environment variables
```

## Maintenance

### Cleaning Old Backups

Database backups can accumulate. Clean them periodically:

```bash
# Keep last 30 days of backups
find .deployment/db-backups -name "*.sql" -mtime +30 -delete
```

### Monitoring Deployments

Set up a simple monitoring script on the server:

```bash
# View recent deployments
ssh root@sunnysideac
tail -100 /var/www/sunnysideac/deploy.log | grep "deployment completed"
```

## Migration from All-in-One Migration

Since you previously used All-in-One Migration, here's how this compares:

| Feature | All-in-One Migration | This System |
|---------|---------------------|-------------|
| Code deployment | ❌ Manual | ✅ Automatic |
| Database sync | ✅ Plugin-based | ✅ WP-CLI based |
| Media files | ✅ Included | ❌ Manual (rsync) |
| Ease of use | ✅ GUI | 🔧 Terminal |
| Server requirements | 🔒 Often fails on hardened servers | ✅ Works with SSH |
| Speed | 🐌 Slow for large sites | ⚡ Fast |
| Version control | ❌ No | ✅ Full Git history |

### Syncing Media Files (if needed)

Media files are not automatically synced. If you need to sync uploads:

```bash
# Pull media from production to local
rsync -avz --progress root@sunnysideac:/var/www/sunnysideac/app/public/wp-content/uploads/ ./app/public/wp-content/uploads/

# Push media from local to production
rsync -avz --progress ./app/public/wp-content/uploads/ root@sunnysideac:/var/www/sunnysideac/app/public/wp-content/uploads/
```

## Support

If you encounter issues:

1. Check the deployment logs: `/var/www/sunnysideac/deploy.log`
2. Test SSH connection: `ssh root@sunnysideac`
3. Verify git setup: `git remote -v`
4. Run manual deployment: `bash .deployment/deploy.sh`

## References

- [Git Hooks Documentation](https://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks)
- [WP-CLI Database Commands](https://developer.wordpress.org/cli/commands/db/)
- [Tailscale SSH Documentation](https://tailscale.com/kb/1193/tailscale-ssh/)
