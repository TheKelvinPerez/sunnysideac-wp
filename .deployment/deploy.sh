#!/bin/bash
set -e

##############################################################################
# Production Deployment Script for Sunnyside AC WordPress Site
#
# This script is executed automatically when code is pushed to the 'prod' branch.
# It handles code updates, dependency installation, and asset building.
##############################################################################

# Configuration
PROJECT_ROOT="/var/www/sunnyside247ac_com"
THEME_PATH="${PROJECT_ROOT}/wp-content/themes/sunnysideac"
WP_PATH="${PROJECT_ROOT}"
LOG_FILE="${PROJECT_ROOT}/deploy.log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a "$LOG_FILE"
    exit 1
}

warn() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a "$LOG_FILE"
}

##############################################################################
# Pre-deployment checks
##############################################################################

log "=== Starting deployment ==="

# Check if we're in the right directory
if [ ! -d "$PROJECT_ROOT" ]; then
    error "Project root directory not found: $PROJECT_ROOT"
fi

cd "$PROJECT_ROOT" || error "Cannot change to project root"

##############################################################################
# Pull latest code
##############################################################################

log "Pulling latest code from prod branch..."
git --work-tree="$PROJECT_ROOT" --git-dir="${PROJECT_ROOT}/.git" checkout -f prod || error "Git checkout failed"

##############################################################################
# Install PHP dependencies (Composer)
##############################################################################

log "Installing Composer dependencies in theme..."
cd "$THEME_PATH" || error "Theme directory not found"

if [ -f "composer.json" ]; then
    composer install --no-dev --optimize-autoloader --no-interaction || error "Composer install failed"
    log "✓ Composer dependencies installed"
else
    warn "No composer.json found, skipping Composer install"
fi

##############################################################################
# Install Node dependencies and build assets
##############################################################################

log "Installing NPM dependencies and building assets..."

if [ -f "package.json" ]; then
    # Install dependencies
    npm ci --production=false || error "NPM install failed"
    log "✓ NPM dependencies installed"

    # Build production assets
    npm run build || error "NPM build failed"
    log "✓ Production assets built"
else
    warn "No package.json found, skipping NPM install and build"
fi

##############################################################################
# WordPress optimizations
##############################################################################

log "Flushing WordPress caches and rewrite rules..."
cd "$WP_PATH" || error "WordPress directory not found"

# Flush rewrite rules (important for custom post types)
wp rewrite flush --allow-root 2>/dev/null || warn "Could not flush rewrite rules (wp-cli may not be available)"

# Clear object cache if available
wp cache flush --allow-root 2>/dev/null || warn "Could not flush cache (wp-cli may not be available)"

##############################################################################
# File permissions (adjust based on your server setup)
##############################################################################

log "Setting file permissions..."

# Ensure web server can read files
find "$PROJECT_ROOT" -type f -exec chmod 644 {} \; || warn "Could not set file permissions"
find "$PROJECT_ROOT" -type d -exec chmod 755 {} \; || warn "Could not set directory permissions"

# Make sure uploads directory is writable
chmod -R 775 "${WP_PATH}/wp-content/uploads" 2>/dev/null || warn "Could not set uploads directory permissions"

##############################################################################
# Cleanup
##############################################################################

log "Cleaning up temporary files..."
cd "$THEME_PATH" || error "Theme directory not found"

# Remove development dependencies and caches
rm -rf node_modules/.cache 2>/dev/null || true
rm -rf .vite 2>/dev/null || true

##############################################################################
# Deployment complete
##############################################################################

log "=== Deployment completed successfully ==="
log "Theme path: $THEME_PATH"
log "WordPress path: $WP_PATH"
log ""
log "Next steps:"
log "  - Test the website: https://yourdomain.com"
log "  - If database changes were made, run: .deployment/db-sync.sh"
log ""
