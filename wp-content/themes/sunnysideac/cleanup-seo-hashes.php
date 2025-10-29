<?php
/**
 * One-time script to clean SEO bot hashes from existing blog posts
 * Run this script once via WP-CLI: wp eval-file cleanup-seo-hashes.php
 */

// Include WordPress
require_once('wp-config.php');

// Call the bulk clean function
$cleaned_count = sunnysideac_bulk_clean_seo_hashes();

echo "Successfully cleaned {$cleaned_count} blog posts from SEO bot hashes.\n";