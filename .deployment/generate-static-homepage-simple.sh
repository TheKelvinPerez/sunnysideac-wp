#!/bin/bash
set -e

##############################################################################
# Simple Static Homepage Generation Script
#
# This script generates a static index.html from WordPress without complex sed modifications.
##############################################################################

# Configuration
PROJECT_ROOT="/var/www/sunnyside247ac_com"
STATIC_HTML="${PROJECT_ROOT}/index.html"
TEMP_HTML="${STATIC_HTML}.tmp"
LOG_FILE="${PROJECT_ROOT}/logs/static-generation.log"
TIMESTAMP=$(date +'%Y-%m-%d %H:%M:%S')

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Create log directory if it doesn't exist
mkdir -p "$(dirname "$LOG_FILE")"

# Logging function
log() {
    echo -e "${GREEN}[$TIMESTAMP]${NC} $1" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a "$LOG_FILE"
    exit 1
}

warn() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a "$LOG_FILE"
}

log "=== Starting simple static homepage generation ==="

# Change to project root
cd "$PROJECT_ROOT" || error "Cannot change to project root"

# Backup current static HTML if it exists
if [ -f "$STATIC_HTML" ]; then
    cp "$STATIC_HTML" "${STATIC_HTML}.backup.$(date +%s)"
    log "✓ Backed up existing static HTML"
fi

log "Generating static homepage using PHP..."

# Use PHP to properly render WordPress and capture all JavaScript
PHP_GENERATOR="${PROJECT_ROOT}/.deployment/generate-static-homepage.php"
if [ -f "$PHP_GENERATOR" ]; then
    php "$PHP_GENERATOR" > "${PROJECT_ROOT}/logs/static-generation-output.log" 2>&1
    if [ $? -eq 0 ]; then
        log "✓ PHP generation completed successfully"
        # Move the generated file to our temp location
        if [ -f "$TEMP_HTML" ]; then
            log "✓ HTML file generated via PHP"
        else
            error "PHP generator did not create output file"
        fi
    else
        error "PHP generator failed. Check logs for details."
    fi
else
    error "PHP generator not found at $PHP_GENERATOR"
fi

# Verify the temp file was created and has content
if [ ! -f "$TEMP_HTML" ] || [ ! -s "$TEMP_HTML" ]; then
    error "HTML file was not created or is empty"
fi

# Check if the response looks like HTML (basic validation)
if ! grep -q "<!DOCTYPE html\|<html" "$TEMP_HTML"; then
    error "Response doesn't appear to be valid HTML"
fi

log "✓ HTML content validated"

# Move temp file to final location
mv "$TEMP_HTML" "$STATIC_HTML"
log "✓ Static homepage generated: $STATIC_HTML"

# Set proper ownership
chown www-data:www-data "$STATIC_HTML"
log "✓ Set correct ownership for static HTML"

# Set proper permissions
chmod 644 "$STATIC_HTML"
log "✓ Set correct permissions for static HTML"

# Get file size for logging
FILE_SIZE=$(stat -c%s "$STATIC_HTML" 2>/dev/null || echo "unknown")
log "✓ Static HTML file size: $FILE_SIZE bytes"

# No complex sed modifications needed - source files have been fixed directly

# Now update the asset hashes if the update script exists
ASSET_UPDATE_SCRIPT="${PROJECT_ROOT}/wp-content/themes/sunnysideac/scripts/update-static-assets.sh"
if [ -f "$ASSET_UPDATE_SCRIPT" ]; then
    log "Running asset hash update script..."
    bash "$ASSET_UPDATE_SCRIPT" || warn "Asset hash update script failed, but static HTML was generated"
fi

# Clear any remaining caches
log "Clearing web server caches..."

# Restart Caddy to ensure it picks up the new static file
systemctl reload caddy 2>/dev/null || warn "Could not reload Caddy"

# Flush Redis cache (in case it's caching the old version)
redis-cli FLUSHALL 2>/dev/null || warn "Could not flush Redis cache"

log "=== Static homepage generation completed successfully ==="
log "The homepage is now available as: $STATIC_HTML"
log ""
log "To verify: curl -s https://sunnyside247ac.com | head -10"
log ""