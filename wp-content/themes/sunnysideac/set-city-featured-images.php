<?php
/**
 * Script to set featured images for all city posts
 * This script matches city slugs with images in assets/city-images/
 */

// Get all published cities
$cities = get_posts(array(
    'post_type' => 'city',
    'post_status' => 'publish',
    'numberposts' => -1,
    'fields' => 'ids', // Only get IDs for efficiency
));

$theme_path = get_template_directory();
$city_images_path = $theme_path . '/assets/city-images/';
$success_count = 0;
$error_count = 0;
$already_set_count = 0;

echo "Setting featured images for cities...\n";
echo "Theme path: " . $theme_path . "\n";
echo "City images path: " . $city_images_path . "\n\n";

foreach ($cities as $city_id) {
    $city_title = get_the_title($city_id);
    $city_slug = get_post_field('post_name', $city_id);
    $image_file = $city_slug . '.jpg';
    $image_path = $city_images_path . $image_file;

    echo "Processing: {$city_title} ({$city_slug}) - ID: {$city_id}\n";

    // Check if city already has a featured image
    if (has_post_thumbnail($city_id)) {
        echo "  ✓ Already has featured image\n";
        $already_set_count++;
        continue;
    }

    // Check if image file exists
    if (!file_exists($image_path)) {
        echo "  ✗ Image file not found: {$image_file}\n";
        $error_count++;
        continue;
    }

    // Read the image file
    $image_data = file_get_contents($image_path);
    if ($image_data === false) {
        echo "  ✗ Failed to read image file: {$image_file}\n";
        $error_count++;
        continue;
    }

    // Upload the image to WordPress media library
    $filename = basename($image_path);
    $upload_file = wp_upload_bits($filename, null, $image_data);

    if (isset($upload_file['error']) && $upload_file['error'] != 0) {
        echo "  ✗ Upload error: " . $upload_file['error'] . "\n";
        $error_count++;
        continue;
    }

    // Check if the attachment already exists to avoid duplicates
    $existing_attachment = get_posts(array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image/jpeg',
        'post_status' => 'inherit',
        'meta_query' => array(
            array(
                'key' => '_wp_attached_file',
                'value' => $upload_file['file'],
            ),
        ),
        'numberposts' => 1,
        'fields' => 'ids',
    ));

    if (!empty($existing_attachment)) {
        $attachment_id = $existing_attachment[0];
        echo "  ✓ Using existing attachment ID: {$attachment_id}\n";
    } else {
        // Insert the attachment
        $wp_filetype = wp_check_filetype($filename, null);
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $city_id);

        if ($attachment_id === 0 || is_wp_error($attachment_id)) {
            echo "  ✗ Failed to create attachment\n";
            $error_count++;
            continue;
        }

        // Generate attachment metadata
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
        wp_update_attachment_metadata($attachment_id, $attachment_data);

        echo "  ✓ Created new attachment ID: {$attachment_id}\n";
    }

    // Set as featured image
    $result = set_post_thumbnail($city_id, $attachment_id);
    if ($result) {
        echo "  ✓ Set as featured image\n";
        $success_count++;
    } else {
        echo "  ✗ Failed to set as featured image\n";
        $error_count++;
    }

    echo "\n";
}

echo "=== Summary ===\n";
echo "Successfully set: {$success_count} cities\n";
echo "Already had images: {$already_set_count} cities\n";
echo "Errors: {$error_count} cities\n";
echo "Total processed: " . count($cities) . " cities\n";