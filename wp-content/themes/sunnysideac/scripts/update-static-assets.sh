#!/bin/bash

# Automated Static HTML Asset Update Script
# This script runs after Vite build and automatically updates the static HTML
# with the new hashed asset URLs

set -e  # Exit on any error

THEME_PATH="/var/www/sunnyside247ac_com/wp-content/themes/sunnysideac"
STATIC_HTML="/var/www/sunnyside247ac_com/index.html"
MANIFEST_PATH="$THEME_PATH/dist/.vite/manifest.json"

echo "🔧 Starting automated asset hash update..."

# Check if manifest exists
if [ ! -f "$MANIFEST_PATH" ]; then
    echo "❌ Manifest file not found at $MANIFEST_PATH"
    exit 1
fi

# Check if static HTML exists
if [ ! -f "$STATIC_HTML" ]; then
    echo "❌ Static HTML file not found at $STATIC_HTML"
    exit 1
fi

echo "📦 Reading manifest to get new asset hashes..."
# Extract the new asset hashes from manifest
NEW_CSS=$(jq -r '.["src/main.js"].css[0]' "$MANIFEST_PATH")
NEW_JS=$(jq -r '.["src/main.js"].file' "$MANIFEST_PATH")

# Remove "assets/" prefix if present to match expected format
NEW_CSS=$(echo "$NEW_CSS" | sed 's|^assets/||')
NEW_JS=$(echo "$NEW_JS" | sed 's|^assets/||')

if [ "$NEW_CSS" = "null" ] || [ "$NEW_JS" = "null" ]; then
    echo "❌ Could not extract asset hashes from manifest"
    exit 1
fi

echo "🔄 Updating static HTML with new asset hashes..."
echo "   New CSS: $NEW_CSS"
echo "   New JS:  $NEW_JS"

# Create backup of current static HTML
cp "$STATIC_HTML" "$STATIC_HTML.backup.$(date +%s)"
echo "✅ Created backup of static HTML"

# Extract old asset hashes from the static HTML
OLD_CSS=$(grep -o "dist/assets/main-[^'\"]*\.css" "$STATIC_HTML" | head -1 | sed 's|^dist/assets/||')
OLD_JS=$(grep -o "dist/assets/main-[^'\"]*\.js" "$STATIC_HTML" | head -1 | sed 's|^dist/assets/||')

echo "   Old CSS: $OLD_CSS"
echo "   Old JS:  $OLD_JS"

if [ -n "$OLD_CSS" ] && [ -n "$OLD_JS" ]; then
    # Update CSS references
    sed -i "s|dist/assets/$OLD_CSS|dist/assets/$NEW_CSS|g" "$STATIC_HTML"
    echo "✅ Updated CSS references: $OLD_CSS → $NEW_CSS"

    # Update JS references
    sed -i "s|dist/assets/$OLD_JS|dist/assets/$NEW_JS|g" "$STATIC_HTML"
    echo "✅ Updated JS references: $OLD_JS → $NEW_JS"

    # Verify updates
    CSS_CHECK=$(grep -c "dist/assets/$NEW_CSS" "$STATIC_HTML" || echo "0")
    JS_CHECK=$(grep -c "dist/assets/$NEW_JS" "$STATIC_HTML" || echo "0")

    if [ "$CSS_CHECK" -gt 0 ] && [ "$JS_CHECK" -gt 0 ]; then
        echo "✅ Successfully updated static HTML with new asset hashes"
        echo "   Found $CSS_CHECK CSS references and $JS_CHECK JS references"
    else
        echo "⚠️  Warning: Could not verify all updates in static HTML"
        echo "   CSS references found: $CSS_CHECK"
        echo "   JS references found: $JS_CHECK"
    fi
else
    echo "⚠️  Could not extract old asset hashes from static HTML"
    echo "   Attempting direct replacement..."

    # Fallback: Try to replace using WordPress URL patterns
    sed -i "s|https://sunnyside247ac.com/wp-content/themes/sunnysideac/dist/assets/main-[^'\"]*\.css|https://sunnyside247ac.com/wp-content/themes/sunnysideac/dist/assets/$NEW_CSS|g" "$STATIC_HTML"
    sed -i "s|https://sunnyside247ac.com/wp-content/themes/sunnysideac/dist/assets/main-[^'\"]*\.js|https://sunnyside247ac.com/wp-content/themes/sunnysideac/dist/assets/$NEW_JS|g" "$STATIC_HTML"

    # Also handle case where "assets/" might be duplicated
    sed -i "s|dist/assets/assets/main-|dist/assets/main-|g" "$STATIC_HTML"
    echo "✅ Completed fallback replacement"
fi

# Set proper ownership
chown www-data:www-data "$STATIC_HTML"
echo "✅ Set correct ownership for static HTML"

echo "🎉 Asset hash update completed successfully!"
echo "   The static HTML now references the new hashed assets."