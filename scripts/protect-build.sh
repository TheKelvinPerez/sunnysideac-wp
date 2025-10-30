#!/bin/bash

# Server-side script to ensure build script integrity
# Can be run via cron or manually to verify and fix build script

PROJECT_ROOT="/var/www/sunnyside247ac_com"
BUILD_SCRIPT="${PROJECT_ROOT}/build.sh"

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

warn() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if build script exists
check_build_script() {
    if [[ ! -f "$BUILD_SCRIPT" ]]; then
        error "Build script not found at $BUILD_SCRIPT"

        # Try to restore from git
        log "Attempting to restore from git..."
        cd "$PROJECT_ROOT"
        if git checkout HEAD -- build.sh 2>/dev/null; then
            log "✅ Restored build script from git"
            chmod +x "$BUILD_SCRIPT"
            log "✅ Set execute permissions"
        else
            error "Could not restore build script from git"
            return 1
        fi
    fi

    # Check if executable
    if [[ ! -x "$BUILD_SCRIPT" ]]; then
        warn "Build script missing execute permissions - fixing..."
        chmod +x "$BUILD_SCRIPT" && log "✅ Fixed execute permissions"
    fi

    return 0
}

# Verify build script content is not corrupted
verify_build_content() {
    # Check for essential functions
    if ! grep -q "main()" "$BUILD_SCRIPT"; then
        error "Build script appears to be corrupted (missing main function)"
        return 1
    fi

    if ! grep -q "build_assets" "$BUILD_SCRIPT"; then
        error "Build script appears to be corrupted (missing build_assets function)"
        return 1
    fi

    log "✅ Build script content verified"
    return 0
}

# Check git status
check_git_status() {
    cd "$PROJECT_ROOT"

    if git status --porcelain | grep -q " M build.sh"; then
        warn "Build script has uncommitted changes"

        # Check if changes are just permissions
        if git diff build.sh | grep -q "^old mode\|^new mode"; then
            log "Changes are only permission-related - safe to commit"
            git add build.sh && git commit -m "fix: Update build script permissions"
            log "✅ Committed permission changes"
        fi
    fi
}

# Main protection routine
main() {
    log "🔒 Running build script protection check..."

    if check_build_script && verify_build_content; then
        check_git_status
        log "✅ Build script protection check completed successfully"
        return 0
    else
        error "Build script protection check failed"
        return 1
    fi
}

# Run if executed directly
if [[ "${BASH_SOURCE[0]}" == "${0}" ]]; then
    main "$@"
fi