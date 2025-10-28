# Quick Start Guide

Fast reference for daily deployment tasks.

## Initial Setup (One Time)

```bash
# 1. Make scripts executable
chmod +x .deployment/*.sh

# 2. Add production remote
git remote add production root@sunnysideac:/var/www/sunnysideac.git

# 3. Create prod branch
git checkout -b prod
git push production prod

# 4. SSH to server and install hook
ssh root@sunnysideac
cp /var/www/sunnysideac/.deployment/post-receive /var/www/sunnysideac.git/hooks/post-receive
chmod +x /var/www/sunnysideac.git/hooks/post-receive
exit

# 5. Push database to production
./.deployment/db-sync.sh push
```

## Daily Workflow

### Deploy Code Changes

```bash
# Work on main branch
git checkout main
# ... make changes ...
git add .
git commit -m "Your changes"
git push origin main

# Deploy to production
git checkout prod
git merge main
git push production prod  # 🚀 Auto-deploys!
```

### Alternative: Direct Deploy from Main

```bash
# If you want to skip the prod branch
git checkout prod
git merge main --ff-only
git push production prod
```

## Database Operations

### Pull Production Data to Local

```bash
./.deployment/db-sync.sh pull
```

Use this to work with real production data locally.

### Push Local Data to Production (Rare!)

```bash
./.deployment/db-sync.sh push
```

⚠️  Only use when you've made content changes locally that need to go live.

## Common Tasks

### View Deployment Logs

```bash
# View latest deployment log
ssh root@sunnysideac "bash /var/www/sunnyside247ac_com/.deployment/view-logs.sh latest"

# List all deployment logs (last 7 days)
ssh root@sunnysideac "bash /var/www/sunnyside247ac_com/.deployment/view-logs.sh list"

# Follow deployment in real-time
ssh root@sunnysideac "bash /var/www/sunnyside247ac_com/.deployment/view-logs.sh tail"

# Show deployment statistics
ssh root@sunnysideac "bash /var/www/sunnyside247ac_com/.deployment/view-logs.sh stats"
```

Logs are automatically:
- Timestamped (e.g., `deploy-20251028-023456.log`)
- Stored in `/var/www/sunnyside247ac_com/logs/deployments/`
- Cleaned up after 7 days

### Manual Deployment

```bash
# If automatic deployment fails
ssh root@sunnysideac "cd /var/www/sunnysideac && bash .deployment/deploy.sh"
```

### Sync Media Files

```bash
# Pull uploads from production
rsync -avz --progress root@sunnysideac:/var/www/sunnysideac/app/public/wp-content/uploads/ ./app/public/wp-content/uploads/

# Push uploads to production
rsync -avz --progress ./app/public/wp-content/uploads/ root@sunnysideac:/var/www/sunnysideac/app/public/wp-content/uploads/
```

### Rollback Deployment

```bash
# Option 1: From local
git checkout prod
git reset --hard HEAD~1
git push production prod --force

# Option 2: On server
ssh root@sunnysideac
cd /var/www/sunnysideac
git checkout HEAD~1
bash .deployment/deploy.sh
```

### Clear All Caches

```bash
ssh root@sunnysideac "cd /var/www/sunnysideac/app/public && wp cache flush --allow-root && wp rewrite flush --allow-root"
```

## Troubleshooting

### Deployment Not Triggering

```bash
# Check hook is installed and executable
ssh root@sunnysideac "ls -la /var/www/sunnysideac.git/hooks/post-receive"
```

### Build Errors

```bash
# SSH to server and manually run steps
ssh root@sunnysideac
cd /var/www/sunnysideac/app/public/wp-content/themes/sunnysideac
npm ci
npm run build
```

### Permission Issues

```bash
ssh root@sunnysideac "chown -R www-data:www-data /var/www/sunnysideac && chmod -R 755 /var/www/sunnysideac"
```

## What Gets Deployed

✅ **Auto-deployed on push**:
- All code (PHP, JS, CSS)
- Theme templates
- Plugin updates
- Configuration files

✅ **Auto-built on push**:
- Vite production assets
- Composer dependencies
- NPM dependencies

❌ **Manual sync required**:
- Database (use db-sync.sh)
- Media uploads (use rsync)
- Environment files (.env)

## Pro Tips

1. **Always test locally first**: Use DDEV to test changes before deploying
2. **Keep main and prod in sync**: Merge main → prod regularly
3. **Database backups are automatic**: Check `.deployment/db-backups/` for safety
4. **Use prod branch for releases**: Never push directly to prod without testing
5. **Watch deployment logs**: Catch issues immediately with tail -f

## Emergency Contact

If deployment breaks production:

```bash
# Quick rollback
git checkout prod
git reset --hard HEAD~1
git push production prod --force

# Or restore database backup
ddev wp db import .deployment/db-backups/prod_backup_TIMESTAMP.sql
./.deployment/db-sync.sh push
```

## File Locations

- **Local scripts**: `.deployment/`
- **Server scripts**: `/var/www/sunnysideac/.deployment/`
- **Git repo**: `/var/www/sunnysideac.git/`
- **Live site**: `/var/www/sunnysideac/app/public/`
- **Deployment logs**: `/var/www/sunnysideac/deploy.log`

## Need More Help?

Read the full documentation: `.deployment/README.md`
