<?php
/**
 * Footer Menu Helper Functions
 * Clean, manageable way to handle footer menus
 */

/**
 * Get footer menu configuration from JSON file
 */
function sunnysideac_get_footer_menu_config() {
    $config_file = get_template_directory() . '/config/footer-menu.json';

    if (!file_exists($config_file)) {
        return null;
    }

    $config_content = file_get_contents($config_file);
    $config = json_decode($config_content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('Footer menu config JSON error: ' . json_last_error_msg());
        return null;
    }

    return $config;
}

/**
 * Process URLs in menu configuration
 */
function sunnysideac_process_menu_links($links) {
    return array_map(function($link) {
        $link['href'] = home_url($link['href']);
        return $link;
    }, $links);
}

/**
 * Render footer menu from configuration (Columns 2 & 3)
 */
function sunnysideac_render_footer_menu_from_config() {
    $config = sunnysideac_get_footer_menu_config();

    if (!$config) {
        // Fallback to old method if config fails
        sunnysideac_footer_fallback_menu();
        return;
    }

    // Column 2: Services + Company
    echo '<div class="space-y-6">';

    // Services section
    if (isset($config['footer_sections']['column_2']['services'])) {
        $services = $config['footer_sections']['column_2']['services'];
        get_template_part('template-parts/footer-menu-section', null, [
            'title' => $services['title'],
            'links' => sunnysideac_process_menu_links($services['links'])
        ]);
    }

    // Company section
    if (isset($config['footer_sections']['column_2']['company'])) {
        $company = $config['footer_sections']['column_2']['company'];
        get_template_part('template-parts/footer-menu-section', null, [
            'title' => $company['title'],
            'links' => sunnysideac_process_menu_links($company['links'])
        ]);
    }

    echo '</div>';

    // Column 3: Service Areas + Brands
    echo '<div class="space-y-6">';

    // Service Areas section
    if (isset($config['footer_sections']['column_3']['service_areas'])) {
        $service_areas = $config['footer_sections']['column_3']['service_areas'];
        get_template_part('template-parts/footer-menu-section', null, [
            'title' => $service_areas['title'],
            'links' => sunnysideac_process_menu_links($service_areas['links'])
        ]);
    }

    // Brands section
    if (isset($config['footer_sections']['column_3']['brands'])) {
        $brands = $config['footer_sections']['column_3']['brands'];
        get_template_part('template-parts/footer-menu-section', null, [
            'title' => $brands['title'],
            'links' => sunnysideac_process_menu_links($brands['links'])
        ]);
    }

    echo '</div>';
}

/**
 * Render footer column 4 from configuration
 * Services on top, Contact Us on bottom
 */
function sunnysideac_render_footer_column_4() {
    $config = sunnysideac_get_footer_menu_config();

    if (!$config) {
        return;
    }

    echo '<div class="space-y-8">';

    // Services Quick Links section
    if (isset($config['footer_sections']['column_4']['services_quick'])) {
        $services_quick = $config['footer_sections']['column_4']['services_quick'];
        get_template_part('template-parts/footer-menu-section', null, [
            'title' => $services_quick['title'],
            'links' => sunnysideac_process_menu_links($services_quick['links'])
        ]);
    }

    // Contact Us section (with border-top divider)
    if (isset($config['footer_sections']['column_4']['contact'])) {
        $contact = $config['footer_sections']['column_4']['contact'];

        // Check if this is a contact_info type section
        if (isset($contact['type']) && $contact['type'] === 'contact_info') {
            get_template_part('template-parts/footer-contact-info', null, [
                'title' => $contact['title'],
                'class' => 'pt-6 border-t border-gray-200'
            ]);
        }
    }

    echo '</div>';
}

/**
 * Update the footer menu function to use the new approach
 */
function sunnysideac_footer_nav_menu_v2() {
    // Try JSON config first, then fallback to WordPress menu, then hardcoded
    if (function_exists('sunnysideac_render_footer_menu_from_config')) {
        sunnysideac_render_footer_menu_from_config();
    } elseif (has_nav_menu('footer')) {
        // Keep the WordPress menu as backup
        sunnysideac_footer_nav_menu();
    } else {
        sunnysideac_footer_fallback_menu();
    }
}