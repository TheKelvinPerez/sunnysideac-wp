<?php
/**
 * Set Featured Images for City Posts
 *
 * This script uploads city images from assets/city-images/ to WordPress media library
 * and sets them as featured images for corresponding city posts.
 */

// Add WordPress admin hook to run the script
add_action('admin_init', 'sunnysideac_set_city_featured_images');

function sunnysideac_set_city_featured_images() {
    // Only run if accessed with specific parameter
    if (!isset($_GET['set_city_featured_images']) || $_GET['set_city_featured_images'] !== 'true') {
        return;
    }

    // Check if user has proper capabilities
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    // Define cities and their image files
    $cities = array(
        'palm-beach' => 'palm-beach.jpg',
        'west-palm-beach' => 'west-palm-beach.jpg',
        'royal-palm-beach' => 'royal-palm-beach.jpg',
        'wellington' => 'wellington.jpg',
        'palm-springs' => 'palm-springs.jpg',
        'homestead' => 'homestead.jpg',
        'boca-raton' => 'boca-raton.jpg',
        'deerfield-beach' => 'deerfield-beach.jpg',
        'light-house-point' => 'light-house-point.jpg',
        'palmetto-bay' => 'palmetto-bay.jpg',
        'pompano-beach' => 'pompano-beach.jpg',
        'key-biscayne' => 'key-biscayne.jpg',
        'coral-springs' => 'coral-springs.jpg',
        'coral-gables' => 'coral-gables.jpg',
        'tamarac' => 'tamarac.jpg',
        'fort-lauderdale' => 'fort-lauderdale.jpg',
        'miami' => 'miami.jpg',
        'sunrise' => 'sunrise.jpg',
        'plantation' => 'plantation.jpg',
        'sunny-isles' => 'sunny-isles.jpg',
        'hialeah-lakes' => 'hialeah-lakes.jpg',
        'weston' => 'weston.jpg',
        'hollywood' => 'hollywood.jpg',
        'davie' => 'davie.jpg',
        'pembroke-park' => 'pembroke-park.jpg',
        'west-park' => 'west-park.jpg',
        'miami-lakes' => 'miami-lakes.jpg',
        'south-west-ranches' => 'south-west-ranches.jpg',
        'pembroke-pines' => 'pembroke-pines.jpg',
        'miramar' => 'miramar.jpg',
    );

    $theme_path = get_template_directory();
    $images_path = $theme_path . '/assets/city-images/';
    $success_count = 0;
    $error_count = 0;
    $results = array();

    echo '<div style="font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; background: #f9f9f9; border-radius: 8px;">';
    echo '<h1 style="color: #333; border-bottom: 2px solid #0073aa; padding-bottom: 10px;">Setting City Featured Images</h1>';

    foreach ($cities as $city_slug => $image_file) {
        $image_path = $images_path . $image_file;

        // Check if image file exists
        if (!file_exists($image_path)) {
            $results[] = "❌ Image file not found: {$image_file}";
            $error_count++;
            continue;
        }

        // Get city post by slug
        $city_post = get_page_by_path($city_slug, OBJECT, 'city');
        if (!$city_post) {
            $results[] = "❌ City post not found: {$city_slug}";
            $error_count++;
            continue;
        }

        // Check if featured image already exists
        if (has_post_thumbnail($city_post->ID)) {
            $results[] = "⚠️ {$city_post->post_title} already has a featured image";
            continue;
        }

        // Upload image to WordPress media library
        $file_array = array(
            'name'     => $image_file,
            'tmp_name' => $image_path,
            'error'    => 0,
            'size'     => filesize($image_path),
        );

        // Upload the file
        $upload = wp_handle_upload($file_array, array('test_form' => false));

        if (isset($upload['error'])) {
            $results[] = "❌ Upload error for {$image_file}: " . $upload['error'];
            $error_count++;
            continue;
        }

        // Prepare attachment data
        $attachment = array(
            'post_mime_type' => $upload['type'],
            'post_title'     => $city_post->post_title,
            'post_content'   => '',
            'post_status'    => 'inherit',
            'post_parent'    => $city_post->ID,
        );

        // Insert attachment
        $attachment_id = wp_insert_attachment($attachment, $upload['file'], $city_post->ID);

        if (is_wp_error($attachment_id)) {
            $results[] = "❌ Error creating attachment for {$image_file}: " . $attachment_id->get_error_message();
            $error_count++;
            continue;
        }

        // Generate attachment metadata
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
        wp_update_attachment_metadata($attachment_id, $attachment_data);

        // Set as featured image
        $result = set_post_thumbnail($city_post->ID, $attachment_id);

        if ($result) {
            $results[] = "✅ Successfully set featured image for {$city_post->post_title}";
            $success_count++;
        } else {
            $results[] = "❌ Failed to set featured image for {$city_post->post_title}";
            $error_count++;
        }
    }

    // Display results
    echo '<div style="margin: 20px 0;">';
    echo '<h2 style="color: #333;">Results Summary:</h2>';
    echo "<p><strong>✅ Success:</strong> {$success_count} cities</p>";
    echo "<p><strong>❌ Errors:</strong> {$error_count} cities</p>";
    echo '</div>';

    echo '<h3 style="color: #333;">Detailed Results:</h3>';
    echo '<ul style="list-style: none; padding: 0;">';
    foreach ($results as $result) {
        echo '<li style="margin: 5px 0; padding: 5px; background: white; border-left: 4px solid #ddd;">' . $result . '</li>';
    }
    echo '</ul>';

    echo '<p style="margin-top: 20px;"><a href="' . admin_url('edit.php?post_type=city') . '" class="button button-primary">View City Posts</a></p>';
    echo '</div>';

    // Stop execution after displaying results
    exit;
}