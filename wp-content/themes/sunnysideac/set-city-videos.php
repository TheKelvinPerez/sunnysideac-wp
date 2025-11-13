<?php
/**
 * Script to set video URLs for all city posts
 * This script sets the video URLs from SUNNYSIDE_CITY_VIDEOS constant as ACF fields
 */

// Get all published cities
$cities = get_posts(array(
    'post_type' => 'city',
    'post_status' => 'publish',
    'numberposts' => -1,
    'fields' => 'ids', // Only get IDs for efficiency
));

$city_videos = SUNNYSIDE_CITY_VIDEOS;
$success_count = 0;
$error_count = 0;
$already_set_count = 0;

echo "Setting video URLs for cities...\n";
echo "Total cities to process: " . count($cities) . "\n";
echo "Cities with videos: " . count($city_videos) . "\n\n";

foreach ($cities as $city_id) {
    $city_title = get_the_title($city_id);

    echo "Processing: {$city_title} - ID: {$city_id}\n";

    // Check if city already has a video URL
    $existing_video_url = get_field('city_video_url', $city_id);
    if ($existing_video_url) {
        echo "  ✓ Already has video URL: {$existing_video_url}\n";
        $already_set_count++;
        continue;
    }

    // Check if city has a video in the constants
    if (isset($city_videos[$city_title])) {
        $video_url = $city_videos[$city_title];

        // Set the video URL
        $result = update_field('city_video_url', $video_url, $city_id);

        if ($result) {
            echo "  ✓ Set video URL: {$video_url}\n";

            // Also set a default title and description
            $default_title = "HVAC Services in {$city_title}";
            $default_description = "Professional HVAC services for the {$city_title} community. Expert AC repair, installation, and maintenance from Sunnyside AC.";

            update_field('city_video_title', $default_title, $city_id);
            update_field('city_video_description', $default_description, $city_id);

            echo "  ✓ Set video title: {$default_title}\n";
            echo "  ✓ Set video description\n";

            $success_count++;
        } else {
            echo "  ✗ Failed to set video URL\n";
            $error_count++;
        }
    } else {
        echo "  ⚠ No video URL found for this city\n";
        $error_count++;
    }

    echo "\n";
}

echo "=== Summary ===\n";
echo "Successfully set: {$success_count} cities\n";
echo "Already had videos: {$already_set_count} cities\n";
echo "Errors/no video: {$error_count} cities\n";
echo "Total processed: " . count($cities) . " cities\n";