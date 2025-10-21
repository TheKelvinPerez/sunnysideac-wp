#!/bin/bash
# -------------------------------
# Fully Automated WordPress Deploy
# -------------------------------

# 1. Commit local changes
COMMIT_MSG="${1:-'Auto-deploy commit'}"
git add .
git commit -m "$COMMIT_MSG" || echo "No changes to commit"

# 2. Push to server (triggers post-receive checkout)
git push prod main || {
  echo "Git push failed"
  exit 1
}

# 3. Sync uploads
rsync -avz --delete ../wp-content/uploads/ deploy@5.161.93.195:/var/www/staging-sunnysideac/wp-content/uploads/

# 4. Import DB if provided
if [ ! -z "$2" ]; then
  scp "$2" deploy@5.161.93.195:~/site.sql
  ssh deploy@5.161.93.195 "wp --path=/var/www/staging-sunnysideac db import ~/site.sql --allow-root && rm ~/site.sql"
fi

# 5. Reload server services
ssh deploy@5.161.93.195 "sudo systemctl reload php8.3-fpm && sudo caddy reload"

echo "✅ Deployment complete!"
