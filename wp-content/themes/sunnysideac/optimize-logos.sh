#!/bin/bash

# Brand Logo Optimization Script for Sunnyside AC
# Optimizes only the brand logos used in the logo marquee

THEME_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/assets"
ORIGINAL_DIR="$THEME_DIR/originals"
OPTIMIZED_DIR="$THEME_DIR/optimized"

echo "🚀 Starting brand logo optimization for Sunnyside AC theme..."
echo "Theme directory: $THEME_DIR"

# Create directories if they don't exist
mkdir -p "$ORIGINAL_DIR"
mkdir -p "$OPTIMIZED_DIR"

# Brand logos from the logo marquee
BRAND_LOGOS=(
    "Bryant-Logo.png"
    "Carrier-Logo.png"
    "Goodman-Logo.png"
    "Lennox-Logo.png"
    "Rheem-Logo.png"
    "Trane-Logo.png"
    "daikin-logo.png"
)

# Function to format bytes
format_bytes() {
    local bytes=$1
    if [[ $bytes -lt 1024 ]]; then
        echo "${bytes}B"
    elif [[ $bytes -lt 1048576 ]]; then
        echo "$(( bytes / 1024 ))KB"
    else
        echo "$(( bytes / 1048576 ))MB"
    fi
}

# Function to optimize logo with high compression
optimize_logo() {
    local input_file="$1"
    local filename=$(basename "$input_file")
    local extension="${filename##*.}"
    local filename_noext="${filename%.*}"

    echo "Processing: $filename"

    # Get original file size
    local original_size=$(stat -f%z "$input_file" 2>/dev/null || stat -c%s "$input_file" 2>/dev/null || echo 0)

    case "$(echo "$extension" | tr '[:upper:]' '[:lower:]')" in
        "png")
            # Optimize PNG with maximum compression
            if command -v optipng >/dev/null 2>&1; then
                optipng -o7 -quiet "$input_file"  # Maximum optimization
            fi

            # Create highly compressed WebP version optimized for logos
            # Target size around 8-15KB, resize to reasonable display dimensions
            convert "$input_file" \
                -quality 60 \
                -define webp:method=6 \
                -define webp:target-size=12000 \
                -resize 320x113\> \
                "$OPTIMIZED_DIR/${filename_noext}.webp" 2>/dev/null

            # Compress PNG with ImageMagick
            convert "$input_file" \
                -quality 85 \
                -strip \
                -resize 320x113\> \
                "$OPTIMIZED_DIR/$filename" 2>/dev/null
            ;;

        "jpg"|"jpeg")
            # Optimize JPEG with high compression
            convert "$input_file" \
                -quality 75 \
                -strip \
                -interlace Plane \
                -resize 320x113\> \
                "$OPTIMIZED_DIR/$filename" 2>/dev/null

            convert "$input_file" \
                -quality 60 \
                -define webp:method=6 \
                -define webp:target-size=12000 \
                -resize 320x113\> \
                "$OPTIMIZED_DIR/${filename_noext}.webp" 2>/dev/null
            ;;
    esac

    # Calculate space saved
    if [[ -f "$OPTIMIZED_DIR/$filename" ]]; then
        local optimized_size=$(stat -f%z "$OPTIMIZED_DIR/$filename" 2>/dev/null || stat -c%s "$OPTIMIZED_DIR/$filename" 2>/dev/null || echo 0)
        local saved=$((original_size - optimized_size))

        if [[ $saved -gt 0 ]]; then
            echo "  ✅ Saved $(format_bytes $saved) on original"
        fi
    fi

    # Calculate WebP savings
    if [[ -f "$OPTIMIZED_DIR/${filename_noext}.webp" ]]; then
        local webp_size=$(stat -f%z "$OPTIMIZED_DIR/${filename_noext}.webp" 2>/dev/null || stat -c%s "$OPTIMIZED_DIR/${filename_noext}.webp" 2>/dev/null || echo 0)
        local webp_saved=$((original_size - webp_size))

        if [[ $webp_saved -gt 0 ]]; then
            echo "  ✅ WebP version saves $(format_bytes $webp_saved)"
        fi
    fi
}

# Process only the brand logos
echo ""
echo "📸 Optimizing brand logos for logo marquee..."
TOTAL_SAVED=0

for logo in "${BRAND_LOGOS[@]}"; do
    # Look for the logo in the company-logos directory
    logo_file="$THEME_DIR/images/company-logos/$logo"
    if [[ -f "$logo_file" ]]; then
        optimize_logo "$logo_file"
    else
        echo "  ⚠️  Logo not found: $logo_file"
    fi
done

echo ""
echo "🎉 Brand logo optimization complete!"
echo "📊 Optimized ${#BRAND_LOGOS[@]} brand logos"
echo "📁 Optimized logos are available in: $OPTIMIZED_DIR"
echo "💡 WebP versions are ready for modern browsers"