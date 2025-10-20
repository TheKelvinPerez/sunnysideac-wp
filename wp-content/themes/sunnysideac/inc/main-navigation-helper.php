<?php
/**
 * Main Navigation Helper Functions
 * Clean, manageable way to handle main navigation menus
 * Similar pattern to footer-menu-helper.php
 */

/**
 * Get main navigation configuration from JSON file
 */
function sunnysideac_get_main_nav_config() {
    $config_file = get_template_directory() . '/config/main-navigation.json';

    if (!file_exists($config_file)) {
        return null;
    }

    $config_content = file_get_contents($config_file);
    $config = json_decode($config_content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('Main navigation config JSON error: ' . json_last_error_msg());
        return null;
    }

    return $config;
}

/**
 * Process URLs in navigation configuration
 */
function sunnysideac_process_nav_links($links) {
    return array_map(function($link) {
        $link['href'] = home_url($link['href']);
        return $link;
    }, $links);
}

/**
 * Render desktop navigation from JSON configuration
 */
function sunnysideac_render_desktop_nav_from_config() {
    $config = sunnysideac_get_main_nav_config();

    if (!$config || !isset($config['desktop_nav'])) {
        // Fallback to old method if config fails
        sunnysideac_fallback_menu();
        return;
    }

    $nav_items = $config['desktop_nav'];

    echo '<ul role="menubar" class="flex items-center gap-2 overflow-visible">';

    foreach ($nav_items as $item) {
        echo '<li role="none">';

        if ($item['type'] === 'mega_menu') {
            // Mega menu item (Services or Areas)
            $container_id = $item['mega_menu_type'] === 'services' ? 'services-dropdown-container' : 'service-areas-dropdown-container';
            $btn_class = $item['mega_menu_type'] === 'services' ? 'services-dropdown-btn' : 'service-areas-dropdown-btn';
            $chevron_icon = get_template_directory_uri() . '/assets/images/images/logos/navigation-chevron-down.svg';

            echo '<div class="relative" id="' . esc_attr($container_id) . '">';
            echo '<div class="inline-flex cursor-pointer items-center gap-1 rounded-full px-6 py-3 transition-colors duration-200 hover:bg-[#ffc549] focus:ring-2 focus:ring-[#ffc549] focus:ring-offset-2 focus:outline-none bg-[#fde0a0] nav-item" data-item="' . esc_attr($item['title']) . '" role="menuitem" aria-haspopup="true" aria-expanded="false" aria-label="' . esc_attr($item['title']) . ' menu">';
            echo '<a href="' . esc_url(home_url($item['href'])) . '" class="[font-family:\'Inter-Medium\',Helvetica] text-lg font-medium whitespace-nowrap text-black hover:text-black focus:text-black">' . esc_html($item['title']) . '</a>';
            echo '<button class="ml-1 border-none bg-transparent p-0 focus:outline-none ' . esc_attr($btn_class) . '" aria-label="Toggle ' . esc_attr(strtolower($item['title'])) . ' dropdown">';
            echo '<img src="' . esc_url($chevron_icon) . '" alt="" class="h-4 w-4 text-current transition-transform duration-200 chevron-icon" role="presentation" loading="lazy" decoding="async" />';
            echo '</button>';
            echo '</div>';

            // Render mega menu dropdown
            if ($item['mega_menu_type'] === 'services') {
                sunnysideac_render_services_mega_menu();
            } else {
                sunnysideac_render_service_areas_mega_menu();
            }

            echo '</div>'; // Close relative container
        } else {
            // Regular link
            echo '<button class="cursor-pointer rounded-full px-6 py-3 transition-colors duration-200 hover:bg-[#ffc549] focus:ring-2 focus:ring-[#ffc549] focus:ring-offset-2 focus:outline-none bg-[#fde0a0] nav-item" data-item="' . esc_attr($item['title']) . '" data-href="' . esc_url(home_url($item['href'])) . '" role="menuitem" aria-label="Navigate to ' . esc_attr($item['title']) . '">';
            echo '<span class="[font-family:\'Inter-Medium\',Helvetica] text-lg font-medium whitespace-nowrap text-black">' . esc_html($item['title']) . '</span>';
            echo '</button>';
        }

        echo '</li>';
    }

    echo '</ul>';
}

/**
 * Render Services mega menu dropdown
 */
function sunnysideac_render_services_mega_menu() {
    if (!defined('SUNNYSIDE_SERVICES_BY_CATEGORY')) {
        return;
    }

    $service_categories = SUNNYSIDE_SERVICES_BY_CATEGORY;

    echo '<div class="fixed top-[210px] left-1/2 -translate-x-1/2 z-[9999] w-[900px] max-w-[95vw] rounded-[20px] border-2 border-[#e6d4b8] bg-white shadow-[0_8px_25px_rgba(0,0,0,0.15)] overflow-hidden hidden services-dropdown">';

    // Header
    echo '<div class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4">';
    echo '<h3 class="text-2xl font-bold text-white [font-family:\'Inter-Bold\',Helvetica]">Our Services</h3>';
    echo '<p class="text-sm text-white/90 mt-1 font-normal [font-family:\'Inter\',Helvetica]">Professional HVAC Solutions for Your Comfort</p>';
    echo '</div>';

    // Content
    echo '<div class="p-6">';
    echo '<div class="grid grid-cols-3 gap-6 mb-6">';

    foreach ($service_categories as $category_key => $services) {
        $category_label = ucwords(str_replace('_', ' ', $category_key));

        // Start category column
        echo '<div class="space-y-1.5">';
        echo '<h4 class="text-xs font-bold uppercase tracking-wide bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent] mb-2">' . esc_html($category_label) . '</h4>';

        // Output services in this category
        foreach ($services as $service_name) {
            $service_slug = sanitize_title($service_name);
            $service_url = home_url(sprintf(SUNNYSIDE_SERVICE_URL_PATTERN, $service_slug));
            $icon_path = sunnysideac_get_service_icon($service_name);

            echo '<a href="' . esc_url($service_url) . '" class="flex items-start gap-2 p-2 rounded-[20px] transition-all duration-200 hover:bg-[#ffc549] hover:scale-105 hover:shadow-md focus:bg-[#ffc549] focus:outline-none group" aria-label="Navigate to ' . esc_attr($service_name) . '">';
            echo '<div class="h-4 w-4 flex-shrink-0 mt-0.5">';
            echo '<svg class="h-4 w-4 text-gray-600 group-hover:text-[#e5462f] transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="' . esc_attr($icon_path) . '" />';
            echo '</svg>';
            echo '</div>';
            echo '<span class="[font-family:\'Inter-Medium\',Helvetica] text-sm font-medium text-black group-hover:text-[#e5462f] transition-colors duration-200">' . esc_html($service_name) . '</span>';
            echo '</a>';
        }

        // End category column
        echo '</div>';
    }

    echo '</div>'; // Close grid

    // Add "View All" CTA
    echo '<div class="pt-4 border-t-2 border-[#e6d4b8]">';
    echo '<a href="' . esc_url(home_url('/services')) . '" class="flex items-center justify-center gap-2 rounded-[20px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-3 text-center font-bold text-white text-base transition-all duration-200 hover:scale-105 hover:shadow-lg focus:scale-105 focus:outline-none [font-family:\'Inter-Bold\',Helvetica]">';
    echo 'View All HVAC Services';
    echo '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';
    echo '</a>';
    echo '</div>';

    echo '</div>'; // Close padding div
    echo '</div>'; // Close main container
}

/**
 * Render Service Areas mega menu dropdown
 */
function sunnysideac_render_service_areas_mega_menu() {
    if (!defined('SUNNYSIDE_PRIORITY_CITIES')) {
        return;
    }

    $priority_cities = SUNNYSIDE_PRIORITY_CITIES;

    echo '<div class="fixed top-[210px] left-1/2 -translate-x-1/2 z-[9999] w-[900px] max-w-[95vw] rounded-[20px] border-2 border-[#e6d4b8] bg-white shadow-[0_8px_25px_rgba(0,0,0,0.15)] overflow-hidden hidden service-areas-dropdown">';

    // Header
    echo '<div class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4">';
    echo '<div class="flex items-center justify-between">';
    echo '<div>';
    echo '<h3 class="text-2xl font-bold text-white [font-family:\'Inter-Bold\',Helvetica]">Service Areas</h3>';
    echo '<p class="text-sm text-white/90 mt-1 font-normal [font-family:\'Inter\',Helvetica]">Proudly Serving South Florida</p>';
    echo '</div>';
    echo '<div class="text-white/80">';
    echo '<svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24">';
    echo '<path d="M19 12h-2V9h-2V6h-2V4h-2V2h-2v2H7v2H5v2H3v2H1v2h2v2h2v2h2v2h2v2h2v2h2v-2h2v-2h2v-2h2v-2h2v-2h2V12zm-4 4h-2v2h-2v-2h-2v-2H7v-2h2v-2h2V8h2v2h2v2h2v2h2v2z"/>';
    echo '</svg>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    // Content
    echo '<div class="p-6">';
    echo '<div class="grid grid-cols-4 gap-2 mb-6">';

    foreach ($priority_cities as $city) {
        $city_slug = sanitize_title($city);
        $city_url = home_url(sprintf(SUNNYSIDE_CITY_URL_PATTERN, $city_slug));

        echo '<a href="' . esc_url($city_url) . '" class="flex items-center gap-2 p-2 rounded-[20px] transition-all duration-200 hover:bg-[#ffc549] hover:scale-105 hover:shadow-md focus:bg-[#ffc549] focus:outline-none group" aria-label="Navigate to ' . esc_attr($city) . ' service area">';
        echo '<div class="h-4 w-4 flex-shrink-0">';
        echo '<svg class="h-4 w-4 text-gray-600 group-hover:text-[#e5462f] transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />';
        echo '</svg>';
        echo '</div>';
        echo '<span class="[font-family:\'Inter-Medium\',Helvetica] text-sm font-medium text-black group-hover:text-[#e5462f] transition-colors duration-200">' . esc_html($city) . '</span>';
        echo '</a>';
    }

    echo '</div>'; // Close grid

    // Add "View All" CTA
    echo '<div class="pt-4 border-t-2 border-[#e6d4b8]">';
    echo '<a href="' . esc_url(home_url('/areas')) . '" class="flex items-center justify-center gap-2 rounded-[20px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-3 text-center font-bold text-white text-base transition-all duration-200 hover:scale-105 hover:shadow-lg focus:scale-105 focus:outline-none [font-family:\'Inter-Bold\',Helvetica]">';
    echo 'View All Service Areas';
    echo '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';
    echo '</a>';
    echo '</div>';

    echo '</div>'; // Close padding div
    echo '</div>'; // Close main container
}

/**
 * Render mobile navigation from JSON configuration
 */
function sunnysideac_render_mobile_nav_from_config() {
    $config = sunnysideac_get_main_nav_config();

    if (!$config || !isset($config['mobile_nav'])) {
        // Fallback to old method if config fails
        sunnysideac_mobile_nav_menu_fallback();
        return;
    }

    // Services Section
    echo '<div class="mb-6">';
    echo '<h3 class="mb-3 border-b border-gray-200 pb-2 text-lg font-medium text-gray-800">Services</h3>';
    echo '<div class="space-y-3">';

    // Get services from constants
    if (defined('SUNNYSIDE_SERVICES_BY_CATEGORY')) {
        $service_categories = SUNNYSIDE_SERVICES_BY_CATEGORY;
        foreach ($service_categories as $category_key => $services) {
            $category_label = ucwords(str_replace('_', ' ', $category_key));
            echo '<div class="mt-4 first:mt-0">';
            echo '<h4 class="mb-2 text-sm font-bold uppercase tracking-wide text-[#fb9939]">' . esc_html($category_label) . '</h4>';
            echo '</div>';

            foreach ($services as $service_name) {
                $service_url = home_url(sprintf(SUNNYSIDE_SERVICE_URL_PATTERN, sanitize_title($service_name)));
                echo '<a href="' . esc_url($service_url) . '" class="block w-full py-2 pl-3 text-left text-gray-700 transition-colors duration-200 hover:text-[#fb9939] mobile-service-link">' . esc_html($service_name) . '</a>';
            }
        }
    }

    echo '</div></div>';

    // Areas Section
    echo '<div class="mb-6">';
    echo '<h3 class="mb-3 border-b border-gray-200 pb-2 text-lg font-medium text-gray-800">Areas</h3>';
    echo '<div class="space-y-1">';

    if (defined('SUNNYSIDE_PRIORITY_CITIES')) {
        foreach (SUNNYSIDE_PRIORITY_CITIES as $city) {
            $city_url = home_url(sprintf(SUNNYSIDE_CITY_URL_PATTERN, sanitize_title($city)));
            echo '<a href="' . esc_url($city_url) . '" class="block w-full py-2 text-left text-gray-700 transition-colors duration-200 hover:text-[#fb9939] mobile-area-link">' . esc_html($city) . '</a>';
        }
    }

    echo '<a href="' . esc_url(home_url('/areas')) . '" class="block w-full py-2 text-left font-medium text-[#fb9939] transition-colors duration-200 hover:text-[#e5462f]">→ View All Areas</a>';
    echo '</div></div>';

    // Other Navigation Links from JSON
    echo '<div class="mb-6 space-y-1">';

    if (isset($config['mobile_nav']['main_links'])) {
        foreach ($config['mobile_nav']['main_links'] as $link) {
            echo '<button class="w-full border-b border-gray-200 py-2 text-left text-gray-700 hover:text-[#fb9939] mobile-nav-link" data-href="' . esc_url(home_url($link['href'])) . '">' . esc_html($link['title']) . '</button>';
        }
    }

    echo '</div>';
}

/**
 * Fallback for mobile navigation
 */
function sunnysideac_mobile_nav_menu_fallback() {
    // Same as old sunnysideac_mobile_nav_menu function
    echo '<div class="mb-6">';
    echo '<h3 class="mb-3 border-b border-gray-200 pb-2 text-lg font-medium text-gray-800">Navigation</h3>';
    echo '<div class="space-y-1">';

    $nav_items = [
        'Home' => '/',
        'Services' => '/services',
        'Projects' => '/projects',
        'Blog' => '/blog',
        'About' => '/about',
        'Contact Us' => '/contact',
    ];

    foreach ($nav_items as $title => $url) {
        echo '<button class="w-full border-b border-gray-200 py-2 text-left text-gray-700 hover:text-[#fb9939] mobile-nav-link" data-href="' . esc_url(home_url($url)) . '">' . esc_html($title) . '</button>';
    }

    echo '</div></div>';
}
