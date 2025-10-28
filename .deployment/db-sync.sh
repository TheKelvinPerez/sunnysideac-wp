#!/bin/bash
set -e

##############################################################################
# Database Sync Script - Local to Production
#
# This script helps you sync your database from local (DDEV) to production.
# It handles export, upload, import, and URL search/replace.
#
# Usage:
#   Can be run from anywhere in the project:
#   ./.deployment/db-sync.sh push    # Push local DB to production
#   ./.deployment/db-sync.sh pull    # Pull production DB to local
##############################################################################

# Get script directory and project root
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"

# Configuration
PROD_SERVER="root@sunnysideac"  # Your Tailscale SSH connection
PROD_WP_PATH="/var/www/sunnyside247ac_com"
PROD_URL="https://sunnyside247ac.com"

LOCAL_WP_PATH="$PROJECT_ROOT"
LOCAL_URL="https://sunnyside-ac.ddev.site"

BACKUP_DIR="$SCRIPT_DIR/db-backups"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

log() {
    echo -e "${GREEN}[DB-SYNC]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1"
    exit 1
}

warn() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

confirm() {
    read -p "$(echo -e ${YELLOW}$1${NC}) [y/N]: " -n 1 -r
    echo
    [[ $REPLY =~ ^[Yy]$ ]]
}

##############################################################################
# Push local database to production
##############################################################################

push_db() {
    log "=== Pushing local database to production ==="

    # Create backup directory
    mkdir -p "$BACKUP_DIR" || error "Failed to create backup directory: $BACKUP_DIR"

    # Change to project root for ddev commands
    cd "$PROJECT_ROOT" || error "Failed to change to project root"

    # Step 1: Create backup of production database
    warn "Creating backup of production database..."
    ssh "$PROD_SERVER" "cd $PROD_WP_PATH && wp db export - --allow-root" > "${BACKUP_DIR}/prod_backup_${TIMESTAMP}.sql" || error "Failed to backup production database"
    log "✓ Production backup saved to: ${BACKUP_DIR}/prod_backup_${TIMESTAMP}.sql"

    # Step 2: Export local database (use relative path for ddev)
    log "Exporting local database..."
    ddev wp db export ".deployment/db-backups/local_export_${TIMESTAMP}.sql" || error "Failed to export local database"
    log "✓ Local database exported"

    # Step 3: Confirm before proceeding
    warn "⚠️  This will REPLACE the production database with your local data!"
    if ! confirm "Are you sure you want to continue?"; then
        log "Aborted by user"
        exit 0
    fi

    # Step 4: Upload local database to production
    log "Uploading database to production..."
    scp "${BACKUP_DIR}/local_export_${TIMESTAMP}.sql" "${PROD_SERVER}:/tmp/db_import.sql" || error "Failed to upload database"
    log "✓ Database uploaded"

    # Step 5: Import database on production
    log "Importing database on production..."
    ssh "$PROD_SERVER" "cd $PROD_WP_PATH && wp db import /tmp/db_import.sql --allow-root" || error "Failed to import database"
    log "✓ Database imported"

    # Step 6: Search and replace URLs (handle multiple possible local URLs)
    log "Updating URLs in production database..."
    ssh "$PROD_SERVER" "cd $PROD_WP_PATH && \
        wp search-replace 'https://sunnyside-ac.ddev.site' '$PROD_URL' --all-tables --allow-root && \
        wp search-replace 'http://sunnyside-ac.ddev.site' '$PROD_URL' --all-tables --allow-root && \
        wp search-replace 'https://sunnyside-ac.local' '$PROD_URL' --all-tables --allow-root && \
        wp search-replace 'http://sunnyside-ac.local' '$PROD_URL' --all-tables --allow-root && \
        wp option update home '$PROD_URL' --allow-root && \
        wp option update siteurl '$PROD_URL' --allow-root" || warn "Search-replace had issues"
    log "✓ URLs updated"

    # Step 7: Flush cache
    log "Flushing WordPress cache..."
    ssh "$PROD_SERVER" "cd $PROD_WP_PATH && wp cache flush --allow-root && wp rewrite flush --allow-root" 2>/dev/null || warn "Could not flush cache"

    # Step 8: Cleanup
    ssh "$PROD_SERVER" "rm /tmp/db_import.sql" 2>/dev/null || true

    log "=== Database push completed successfully ==="
    log ""
    log "Backup locations:"
    log "  - Production backup: ${BACKUP_DIR}/prod_backup_${TIMESTAMP}.sql"
    log "  - Local export: ${BACKUP_DIR}/local_export_${TIMESTAMP}.sql"
    log ""
    warn "⚠️  Remember to test your production site!"
}

##############################################################################
# Pull production database to local
##############################################################################

pull_db() {
    log "=== Pulling production database to local ==="

    # Create backup directory
    mkdir -p "$BACKUP_DIR" || error "Failed to create backup directory: $BACKUP_DIR"

    # Change to project root for ddev commands
    cd "$PROJECT_ROOT" || error "Failed to change to project root"

    # Step 1: Create backup of local database
    warn "Creating backup of local database..."
    ddev wp db export ".deployment/db-backups/local_backup_${TIMESTAMP}.sql" || error "Failed to backup local database"
    log "✓ Local backup saved to: ${BACKUP_DIR}/local_backup_${TIMESTAMP}.sql"

    # Step 2: Export production database
    log "Exporting production database..."
    ssh "$PROD_SERVER" "cd $PROD_WP_PATH && wp db export - --allow-root" > "${BACKUP_DIR}/prod_export_${TIMESTAMP}.sql" || error "Failed to export production database"
    log "✓ Production database exported"

    # Step 3: Confirm before proceeding
    warn "⚠️  This will REPLACE your local database with production data!"
    if ! confirm "Are you sure you want to continue?"; then
        log "Aborted by user"
        exit 0
    fi

    # Step 4: Import database to local
    log "Importing database to local..."
    ddev wp db import ".deployment/db-backups/prod_export_${TIMESTAMP}.sql" || error "Failed to import database"
    log "✓ Database imported"

    # Step 5: Search and replace URLs
    log "Updating URLs in local database..."
    ddev wp search-replace "$PROD_URL" "$LOCAL_URL" --all-tables || warn "Search-replace had issues"

    # Explicitly set home and siteurl options to ensure correct local URL
    ddev wp option update home "$LOCAL_URL" || warn "Could not update home option"
    ddev wp option update siteurl "$LOCAL_URL" || warn "Could not update siteurl option"
    log "✓ URLs updated"

    # Step 6: Flush cache
    log "Flushing WordPress cache..."
    ddev wp cache flush && ddev wp rewrite flush || warn "Could not flush cache"

    log "=== Database pull completed successfully ==="
    log ""
    log "Backup locations:"
    log "  - Local backup: ${BACKUP_DIR}/local_backup_${TIMESTAMP}.sql"
    log "  - Production export: ${BACKUP_DIR}/prod_export_${TIMESTAMP}.sql"
    log ""
    log "✓ Your local site now has production data"
}

##############################################################################
# Main
##############################################################################

case "$1" in
    push)
        push_db
        ;;
    pull)
        pull_db
        ;;
    *)
        echo "Usage: $0 {push|pull}"
        echo ""
        echo "  push  - Push local database to production"
        echo "  pull  - Pull production database to local"
        echo ""
        exit 1
        ;;
esac
