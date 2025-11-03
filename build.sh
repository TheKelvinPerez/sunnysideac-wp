#!/bin/bash

##############################################################################
# Complete Automated Build Script for SunnySide247AC
#
# This script handles:
# - Building JavaScript/CSS assets with Vite
# - Fixing permission issues
# - Generating static HTML
# - Updating asset hashes
# - Setting correct ownership/permissions
# - Clearing caches
#
# Usage: ./build.sh
##############################################################################

set -e  # Exit on any error

# Configuration
PROJECT_ROOT="/var/www/sunnyside247ac_com"
THEME_DIR="${PROJECT_ROOT}/wp-content/themes/sunnysideac"
BUILD_LOG="${PROJECT_ROOT}/logs/build.log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Create log directory if it doesn't exist
mkdir -p "$(dirname "$BUILD_LOG")"

# Logging functions
log() {
    local timestamp=$(date +'%Y-%m-%d %H:%M:%S')
    echo -e "${GREEN}[${timestamp}]${NC} $1" | tee -a "$BUILD_LOG"
}

error() {
    local timestamp=$(date +'%Y-%m-%d %H:%M:%S')
    echo -e "${RED}[ERROR]${NC} ${timestamp} $1" | tee -a "$BUILD_LOG"
    exit 1
}

warn() {
    local timestamp=$(date +'%Y-%m-%d %H:%M:%S')
    echo -e "${YELLOW}[WARNING]${NC} ${timestamp} $1" | tee -a "$BUILD_LOG"
}

info() {
    local timestamp=$(date +'%Y-%m-%d %H:%M:%S')
    echo -e "${BLUE}[INFO]${NC} ${timestamp} $1" | tee -a "$BUILD_LOG"
}

# Check if running as root/sudo
check_permissions() {
    if [[ $EUID -ne 0 ]]; then
        error "This script must be run as root or with sudo"
    fi
}

# Fix build tool permissions
fix_build_permissions() {
    log "🔧 Fixing build tool permissions..."

    local vite_bin="${THEME_DIR}/node_modules/.bin/vite"
    local vite_js="${THEME_DIR}/node_modules/vite/bin/vite.js"
    local esbuild_bin="${THEME_DIR}/node_modules/@esbuild/linux-x64/bin/esbuild"
    local update_script="${THEME_DIR}/scripts/update-static-assets.sh"

    local fixed_count=0

    if [[ -f "$vite_bin" ]] && [[ ! -x "$vite_bin" ]]; then
        chmod +x "$vite_bin" && ((fixed_count++))
    fi

    if [[ -f "$vite_js" ]] && [[ ! -x "$vite_js" ]]; then
        chmod +x "$vite_js" && ((fixed_count++))
    fi

    if [[ -f "$esbuild_bin" ]] && [[ ! -x "$esbuild_bin" ]]; then
        chmod +x "$esbuild_bin" && ((fixed_count++))
    fi

    if [[ -f "$update_script" ]] && [[ ! -x "$update_script" ]]; then
        chmod +x "$update_script" && ((fixed_count++))
    fi

    if [[ $fixed_count -gt 0 ]]; then
        log "✅ Fixed permissions for $fixed_count build tools"
    else
        log "✅ All build tools already have correct permissions"
    fi
}

# Check if node modules exist
check_node_modules() {
    if [[ ! -d "${THEME_DIR}/node_modules" ]]; then
        log "📦 Installing node modules..."
        cd "$THEME_DIR" && npm install || error "Failed to install node modules"
    else
        log "✅ Node modules already present"
    fi
}

# Build JavaScript/CSS assets
build_assets() {
    log "🏗️  Building JavaScript and CSS assets..."

    cd "$THEME_DIR"

    # Build with timeout to prevent hanging
    if timeout 120 /usr/bin/npm run build >> "$BUILD_LOG" 2>&1; then
        log "✅ Assets built successfully"

        # Verify build output
        if [[ -f "${THEME_DIR}/dist/.vite/manifest.json" ]]; then
            local css_count=$(find "${THEME_DIR}/dist/assets" -name "*.css" | wc -l)
            local js_count=$(find "${THEME_DIR}/dist/assets" -name "*.js" | wc -l)
            log "   Generated $css_count CSS files and $js_count JavaScript files"
        else
            warn "Build completed but manifest.json not found"
        fi
    else
        error "Asset build failed or timed out. Check $BUILD_LOG for details."
    fi
}

# Set correct ownership for built assets
set_asset_ownership() {
    log "🔐 Setting correct ownership for built assets..."

    if chown -R www-data:www-data "${THEME_DIR}/dist" 2>/dev/null; then
        log "✅ Set ownership for dist directory"
    else
        warn "Could not set ownership for dist directory"
    fi

    if find "${THEME_DIR}/dist" -type f -exec chmod 644 {} \; 2>/dev/null; then
        log "✅ Set correct permissions for asset files"
    else
        warn "Could not set permissions for asset files"
    fi
}

# Generate static HTML using PHP generator
generate_static_html() {
    log "📄 Generating static HTML using PHP generator..."

    local php_generator="${PROJECT_ROOT}/.deployment/generate-static-homepage.php"
    local temp_file="${PROJECT_ROOT}/index.html.tmp"

    if [[ ! -f "$php_generator" ]]; then
        error "PHP generator not found at $php_generator"
    fi

    # Clear any existing temp file
    rm -f "$temp_file"

    # Generate with timeout and capture output
    if timeout 60 php "$php_generator" >> "$BUILD_LOG" 2>&1; then
        log "✅ PHP generation completed successfully"

        if [[ -f "$temp_file" ]] && [[ -s "$temp_file" ]]; then
            local file_size=$(stat -c%s "$temp_file" 2>/dev/null || echo "unknown")
            local line_count=$(wc -l < "$temp_file" 2>/dev/null || echo "unknown")
            log "   Generated static HTML: $file_size bytes, $line_count lines"

            # Validate HTML structure
            if grep -q "<!DOCTYPE html\|<html" "$temp_file" && grep -q "</html>" "$temp_file"; then
                log "✅ HTML structure validated"
            else
                error "Generated HTML appears to be incomplete"
            fi
        else
            error "PHP generator did not create output file or file is empty"
        fi
    else
        error "PHP generator failed. Check $BUILD_LOG for details."
    fi
}

# Deploy static HTML to production
deploy_static_html() {
    log "🚀 Deploying static HTML to production..."

    local static_file="${PROJECT_ROOT}/index.html"
    local temp_file="${PROJECT_ROOT}/index.html.tmp"

    if [[ ! -f "$temp_file" ]]; then
        error "Temp file not found: $temp_file"
    fi

    # Backup current static HTML
    if [[ -f "$static_file" ]]; then
        local backup_file="${static_file}.backup.$(date +%s)"
        cp "$static_file" "$backup_file" && log "✅ Created backup: $(basename "$backup_file")"
    fi

    # Deploy temp file to production
    if cp "$temp_file" "$static_file"; then
        log "✅ Static HTML deployed successfully"

        # Set correct ownership and permissions
        chown www-data:www-data "$static_file" && log "✅ Set ownership for static HTML"
        chmod 644 "$static_file" && log "✅ Set permissions for static HTML"

        # Clean up temp file
        rm -f "$temp_file" && log "✅ Cleaned up temp file"
    else
        error "Failed to deploy static HTML"
    fi
}

# Update asset hashes in static HTML
update_asset_hashes() {
    log "🔄 Updating asset hashes in static HTML..."

    local update_script="${THEME_DIR}/scripts/update-static-assets.sh"

    if [[ -f "$update_script" ]]; then
        if bash "$update_script" >> "$BUILD_LOG" 2>&1; then
            log "✅ Asset hashes updated successfully"
        else
            warn "Asset hash update failed, but build continues"
        fi
    else
        warn "Asset hash update script not found at $update_script"
    fi
}

# Clear caches
clear_caches() {
    log "🧹 Clearing system caches..."

    # Clear Redis cache
    if redis-cli FLUSHALL >> "$BUILD_LOG" 2>&1; then
        log "✅ Redis cache cleared"
    else
        warn "Could not clear Redis cache"
    fi

    # Reload Caddy web server
    if systemctl reload caddy >> "$BUILD_LOG" 2>&1; then
        log "✅ Caddy reloaded"
    else
        warn "Could not reload Caddy"
    fi

    # Restart PHP-FPM
    if systemctl restart php8.3-fpm >> "$BUILD_LOG" 2>&1; then
        log "✅ PHP-FPM restarted"
    else
        warn "Could not restart PHP-FPM"
    fi
}

# Verify dynamic deployment
verify_dynamic_deployment() {
    log "🔍 Verifying dynamic deployment..."

    local verification_passed=true

    # Check WordPress is accessible
    if ! curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1/index.php | grep -q "200"; then
        warn "WordPress may not be responding correctly"
        verification_passed=false
    else
        log "✅ WordPress is responding correctly"
    fi

    # Check asset files exist
    local manifest_file="${THEME_DIR}/dist/.vite/manifest.json"
    if [[ -f "$manifest_file" ]]; then
        local main_js=$(jq -r '."src/main.js".file // empty' "$manifest_file" 2>/dev/null)
        local main_css=$(jq -r '."src/main.js".css[0] // empty' "$manifest_file" 2>/dev/null)

        if [[ -n "$main_js" ]] && [[ -f "${THEME_DIR}/dist/$main_js" ]]; then
            log "✅ JavaScript assets verified: $main_js"
        else
            warn "Main JavaScript file not found: $main_js"
        fi

        if [[ -n "$main_css" ]] && [[ -f "${THEME_DIR}/dist/$main_css" ]]; then
            log "✅ CSS assets verified: $main_css"
        else
            warn "Main CSS file not found: $main_css"
        fi
    else
        warn "Manifest file not found at $manifest_file"
    fi

    # Check permissions are correct
    if [[ -d "${THEME_DIR}/dist" ]]; then
        local owner=$(stat -c "%U:%G" "${THEME_DIR}/dist" 2>/dev/null)
        if [[ "$owner" == "www-data:www-data" ]]; then
            log "✅ Asset directory permissions correct"
        else
            warn "Asset directory permissions may need adjustment (current: $owner)"
        fi
    fi

    if [[ "$verification_passed" == true ]]; then
        log "✅ Dynamic deployment verification passed"
    else
        log "⚠️ Dynamic deployment verification completed with warnings"
    fi
}

# Main build function
main() {
    log "🎯 Starting dynamic build for SunnySide247AC"
    log "================================================"

    # Check permissions first
    check_permissions

    # Build steps for dynamic website
    check_node_modules
    fix_build_permissions
    build_assets
    set_asset_ownership
    clear_caches
    verify_dynamic_deployment

    log "================================================"
    log "🎉 Dynamic build completed successfully!"
    log ""
    log "📊 Build Summary:"
    log "   - JavaScript/CSS assets built"
    log "   - Permissions set correctly"
    log "   - Caches cleared"
    log "   - Dynamic website ready"
    log ""
    log "🌐 Your dynamic site is now live at: https://sunnyside247ac.com"
    log "📝 Full build log: $BUILD_LOG"
}

# Script entry point
if [[ "${BASH_SOURCE[0]}" == "${0}" ]]; then
    main "$@"
fi