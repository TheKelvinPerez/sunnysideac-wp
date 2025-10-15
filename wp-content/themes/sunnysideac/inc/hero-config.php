<?php
/**
 * Hero Section Configuration
 * Data and helper functions for the hero section
 */

/**
 * Get statistics data for hero section
 *
 * @return array Array of statistic data
 */
function sunnysideac_get_hero_statistics() {
    return [
        [
            'number' => '1.5K+',
            'description' => "Project Completed\nWith Excellence"
        ],
        [
            'number' => '2014',
            'description' => "Family-Owned\n& Operated Since"
        ],
        [
            'number' => '2.5K+',
            'description' => "Happy Customers\nServed"
        ]
    ];
}

/**
 * Get asset URL helper function
 *
 * @param string $path Path relative to theme directory
 * @return string Full URL to asset
 */
function sunnysideac_asset_url($path) {
    return get_template_directory_uri() . '/' . ltrim($path, '/');
}

/**
 * Get hero icon URLs
 *
 * @return array Array of icon URLs
 */
function sunnysideac_get_hero_icons() {
    $base_path = 'assets/icons/';
    return [
        'best_refreshed' => sunnysideac_asset_url($base_path . 'best-at-keeping-refreshed-icon.svg'),
        'mobile_best_refreshed' => sunnysideac_asset_url($base_path . 'mobile-best-refreshed-icon.svg'),
        'schedule_service' => sunnysideac_asset_url($base_path . 'schedule-service-now-icon.svg'),
        'call_us_now' => sunnysideac_asset_url($base_path . 'call-us-now-icon.svg'),
        'google' => sunnysideac_asset_url($base_path . 'google-icon.svg'),
        'star' => sunnysideac_asset_url($base_path . 'star-icon.svg'),
        'hero_line_break' => sunnysideac_asset_url($base_path . 'hero-line-break.svg')
    ];
}

/**
 * Get hero image URLs
 *
 * @return array Array of image URLs
 */
function sunnysideac_get_hero_images() {
    $base_path = 'assets/images/images/hero/';

    return [
        'hero_right' => sunnysideac_asset_url($base_path . 'hero-right-image.png'),
        'mobile_hero' => sunnysideac_asset_url($base_path . 'mobile-hero-image.png'),
        'review_photos' => [
            sunnysideac_asset_url($base_path . 'review_photo_1.png'),
            sunnysideac_asset_url($base_path . 'review_photo_2.png'),
            sunnysideac_asset_url($base_path . 'review_photo_3.png'),
            sunnysideac_asset_url($base_path . 'review_photo_4.png')
        ]
    ];
}

/**
 * Render star rating
 *
 * @param int $count Number of stars
 * @param string $class Additional CSS classes
 */
function sunnysideac_render_stars($count = 5, $class = 'h-3 w-3 sm:h-4 sm:w-4 lg:h-5 lg:w-5') {
    $icons = sunnysideac_get_hero_icons();
    for ($i = 0; $i < $count; $i++) {
        echo '<img src="' . esc_url($icons['star']) . '" alt="Star" class="' . esc_attr($class) . '" loading="lazy" decoding="async" />';
    }
}
