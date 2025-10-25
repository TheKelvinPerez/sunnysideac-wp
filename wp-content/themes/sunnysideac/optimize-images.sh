#!/bin/bash

# Image Optimization Script for Sunnyside AC
# Optimizes PNG, JPG, and creates WebP versions

THEME_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/assets"
ORIGINAL_DIR="$THEME_DIR/originals"
OPTIMIZED_DIR="$THEME_DIR/optimized"

echo "🚀 Starting image optimization for Sunnyside AC theme..."
echo "Theme directory: $THEME_DIR"

# Create directories if they don't exist
mkdir -p "$ORIGINAL_DIR"
mkdir -p "$OPTIMIZED_DIR"

# Counter for statistics
TOTAL_FILES=0
SAVED_BYTES=0

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

# Function to optimize image
optimize_image() {
    local input_file="$1"
    local filename=$(basename "$input_file")
    local extension="${filename##*.}"
    local filename_noext="${filename%.*}"

    echo "Processing: $filename"

    # Get original file size
    local original_size=$(stat -f%z "$input_file" 2>/dev/null || stat -c%s "$input_file" 2>/dev/null || echo 0)

    case "$(echo "$extension" | tr '[:upper:]' '[:lower:]')" in
        "png")
            # Optimize PNG and create WebP version
            if command -v optipng >/dev/null 2>&1; then
                optipng -o2 -quiet "$input_file"
            fi

            # Create WebP version
            convert "$input_file" -quality 85 -define webp:method=6 "$OPTIMIZED_DIR/${filename_noext}.webp" 2>/dev/null

            # Compress PNG with ImageMagick if needed
            convert "$input_file" -quality 95 -strip "$OPTIMIZED_DIR/$filename" 2>/dev/null
            ;;

        "jpg"|"jpeg")
            # Optimize JPEG and create WebP version
            convert "$input_file" -quality 85 -strip -interlace Plane "$OPTIMIZED_DIR/$filename" 2>/dev/null
            convert "$input_file" -quality 85 -define webp:method=6 "$OPTIMIZED_DIR/${filename_noext}.webp" 2>/dev/null
            ;;
    esac

    # Calculate space saved for optimized original
    if [[ -f "$OPTIMIZED_DIR/$filename" ]]; then
        local optimized_size=$(stat -f%z "$OPTIMIZED_DIR/$filename" 2>/dev/null || stat -c%s "$OPTIMIZED_DIR/$filename" 2>/dev/null || echo 0)
        local saved=$((original_size - optimized_size))

        if [[ $saved -gt 0 ]]; then
            SAVED_BYTES=$((SAVED_BYTES + saved))
            echo "  ✅ Saved $(format_bytes $saved) on original"
        fi
    fi

    # Calculate WebP savings
    if [[ -f "$OPTIMIZED_DIR/${filename_noext}.webp" ]]; then
        local webp_size=$(stat -f%z "$OPTIMIZED_DIR/${filename_noext}.webp" 2>/dev/null || stat -c%s "$OPTIMIZED_DIR/${filename_noext}.webp" 2>/dev/null || echo 0)
        local webp_saved=$((original_size - webp_size))

        if [[ $webp_saved -gt 0 ]]; then
            SAVED_BYTES=$((SAVED_BYTES + webp_saved))
            echo "  ✅ WebP version saves $(format_bytes $webp_saved)"
        fi
    fi

    TOTAL_FILES=$((TOTAL_FILES + 1))
}

# Export function for find command
export -f optimize_image
export -f format_bytes
export TOTAL_FILES SAVED_BYTES OPTIMIZED_DIR

# Find and process all images
echo ""
echo "📸 Finding images to optimize..."
while IFS= read -r -d '' file; do
    optimize_image "$file"
done < <(find "$THEME_DIR" -type f \( -name "*.png" -o -name "*.jpg" -o -name "*.jpeg" \) ! -path "*/optimized/*" ! -path "*/originals/*" -print0)

echo ""
echo "🎉 Optimization complete!"
echo "📊 Statistics:"
echo "   - Files processed: $TOTAL_FILES"
echo "   - Total space saved: $(format_bytes $SAVED_BYTES)"
echo ""
echo "📁 Optimized images are available in: $OPTIMIZED_DIR"
echo "💡 You can now replace original images with optimized versions"
echo "🌐 WebP versions are also available for modern browsers"