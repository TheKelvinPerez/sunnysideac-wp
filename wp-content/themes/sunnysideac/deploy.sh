#!/bin/bash

# -------------------------------
# Simple WordPress Deploy Script
# -------------------------------

# 1. Commit local changes if a message is given
if [ -z "$1" ]; then
  COMMIT_MSG="Auto-deploy commit"
else
  COMMIT_MSG="$1"
fi

git add .
git commit -m "$COMMIT_MSG" || echo "No changes to commit"

# 2. Push to production server
git push prod main || {
  echo "Git push failed"
  exit 1
}

# 3. Sync uploads (optional)
rsync -avz --delete ./web/wp-content/uploads/ deploy@5.161.93.195:/var/www/staging-sunnysideac/wp-content/uploads/

# 4. Import DB if a path is provided
if [ ! -z "$2" ]; then
  scp "$2" deploy@5.161.93.195:~/site.sql
  ssh deploy@5.161.93.195 "wp --path=/var/www/staging-sunnysideac db import ~/site.sql --allow-root && rm ~/site.sql"
fi

# 5. Reload PHP-FPM and Caddy on server
ssh deploy@5.161.93.195 "sudo systemctl reload php8.3-fpm && sudo caddy reload"

echo "✅ Deployment complete"
