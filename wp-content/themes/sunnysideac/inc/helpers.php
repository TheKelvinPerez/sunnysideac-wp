<?php
/**
 * Helper Functions
 * Utility functions used throughout the theme
 */

/**
 * Get asset URL helper function
 *
 * @param string $path Path relative to theme directory
 * @return string Full URL to asset
 */
function sunnysideac_asset_url($path) {
    return get_template_directory_uri() . '/' . ltrim($path, '/');
}