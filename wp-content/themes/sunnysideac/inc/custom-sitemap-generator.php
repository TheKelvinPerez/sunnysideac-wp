<?php
/**
 * Custom Sitemap Generator for Sunnyside AC
 *
 * This class generates a comprehensive sitemap index that includes:
 * - Standard WordPress sitemaps (pages, posts, categories)
 * - Dynamic sitemaps (areas, brands, service-city combinations)
 * - Bypasses RankMath limitations for custom sitemap inclusion
 *
 * @package SunnysideAC
 */

class Sunnyside_Custom_Sitemap_Generator {

    /**
     * Initialize the custom sitemap system
     */
    public function __construct() {
        add_action('init', [$this, 'add_sitemap_rewrite_rules']);
        add_action('template_redirect', [$this, 'handle_sitemap_requests']);
    }

    /**
     * Add rewrite rules for our custom sitemaps
     */
    public function add_sitemap_rewrite_rules() {
        // Main sitemap index
        add_rewrite_rule(
            '^sitemap\.xml$',
            'index.php?custom_sitemap=index',
            'top'
        );

        // Individual sitemap handlers
        add_rewrite_rule(
            '^areas-sitemap\.xml$',
            'index.php?custom_sitemap=areas',
            'top'
        );

        add_rewrite_rule(
            '^brands-sitemap\.xml$',
            'index.php?custom_sitemap=brands',
            'top'
        );

        add_rewrite_rule(
            '^service-city-sitemap\.xml$',
            'index.php?custom_sitemap=service-city',
            'top'
        );
    }

    /**
     * Add custom query vars
     */
    public function add_query_vars($query_vars) {
        $query_vars[] = 'custom_sitemap';
        return $query_vars;
    }

    /**
     * Handle sitemap requests
     */
    public function handle_sitemap_requests() {
        $sitemap_type = get_query_var('custom_sitemap');

        if (!$sitemap_type) {
            return;
        }

        // Set proper headers
        header('Content-Type: application/xml; charset=utf-8');
        header('Cache-Control: public, max-age=300, must-revalidate'); // Cache for 5 minutes with revalidation
        header('Pragma: public');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 300) . ' GMT');

        try {
            switch ($sitemap_type) {
                case 'index':
                    $this->generate_sitemap_index();
                    break;
                case 'areas':
                    $this->generate_areas_sitemap();
                    break;
                case 'brands':
                    $this->generate_brands_sitemap();
                    break;
                case 'service-city':
                    $this->generate_service_city_sitemap();
                    break;
                default:
                    $this->generate_error_response('Invalid sitemap type');
                    break;
            }
        } catch (Exception $e) {
            $this->generate_error_response('Sitemap generation error: ' . $e->getMessage());
        }

        exit;
    }

    /**
     * Generate error response for debugging
     */
    private function generate_error_response($message) {
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<error>' . "\n";
        echo '  <message>' . esc_html($message) . '</message>' . "\n";
        echo '  <timestamp>' . date('c') . '</timestamp>' . "\n";
        echo '</error>';
    }

    /**
     * Generate the main sitemap index
     */
    private function generate_sitemap_index() {
        $base_url = home_url('/');
        $current_time = mysql2date('c', current_time('mysql'), false);

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<?xml-stylesheet type="text/xsl" href="' . $base_url . 'main-sitemap.xsl"?>' . "\n";
        echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Standard WordPress sitemaps
        $this->add_sitemap_to_index($base_url . 'page-sitemap.xml', $current_time);
        $this->add_sitemap_to_index($base_url . 'post-sitemap.xml', $current_time);
        $this->add_sitemap_to_index($base_url . 'category-sitemap.xml', $current_time);
        $this->add_sitemap_to_index($base_url . 'tag-sitemap.xml', $current_time);

        // Our custom dynamic sitemaps (add version to break cache)
        $version = date('YmdHis'); // Current timestamp as version
        $this->add_sitemap_to_index($base_url . 'areas-sitemap.xml?v=' . $version, $current_time);
        $this->add_sitemap_to_index($base_url . 'brands-sitemap.xml?v=' . $version, $current_time);

        // Service-city sitemap (may have multiple pages)
        $service_areas_count = defined('SUNNYSIDE_SERVICE_AREAS') ? count(SUNNYSIDE_SERVICE_AREAS) : 0;
        $services_count = wp_count_posts('service')->publish;
        $total_urls = $service_areas_count * $services_count;
        $max_entries = 2000;
        $pages_needed = (int) ceil($total_urls / $max_entries);

        for ($page = 1; $page <= $pages_needed; $page++) {
            $suffix = $page > 1 ? $page : '';
            $this->add_sitemap_to_index($base_url . 'service-city-sitemap' . $suffix . '.xml', $current_time);
        }

        echo '</sitemapindex>';
    }

    /**
     * Add a sitemap to the index
     */
    private function add_sitemap_to_index($loc, $lastmod) {
        echo '  <sitemap>' . "\n";
        echo '    <loc>' . esc_url($loc) . '</loc>' . "\n";
        echo '    <lastmod>' . esc_html($lastmod) . '</lastmod>' . "\n";
        echo '  </sitemap>' . "\n";
    }

    /**
     * Generate areas sitemap
     */
    private function generate_areas_sitemap() {
        $base_url = home_url('/');
        $current_time = mysql2date('c', current_time('mysql'), false);

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<?xml-stylesheet type="text/xsl" href="' . $base_url . 'main-sitemap.xsl"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $service_areas = defined('SUNNYSIDE_SERVICE_AREAS') ? SUNNYSIDE_SERVICE_AREAS : array();

        foreach ($service_areas as $area) {
            $url = $base_url . 'cities/' . sanitize_title($area) . '/';
            $this->add_url_to_sitemap($url, $current_time);
        }

        echo '</urlset>';
    }

    /**
     * Generate brands sitemap
     */
    private function generate_brands_sitemap() {
        $base_url = home_url('/');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<?xml-stylesheet type="text/xsl" href="' . $base_url . 'main-sitemap.xsl"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Get all published brands
        $brands = get_posts([
            'post_type' => 'brand',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        foreach ($brands as $brand) {
            $url = $base_url . 'brands/' . $brand->post_name . '/';
            $lastmod = mysql2date('c', $brand->post_modified_gmt, false);
            $this->add_url_to_sitemap($url, $lastmod);
        }

        // Add special Daikin URL if Daikin brand exists
        $daikin = get_page_by_path('daikin', OBJECT, 'brand');
        if ($daikin) {
            $url = $base_url . 'daikin/air-conditioners/';
            $lastmod = mysql2date('c', $daikin->post_modified_gmt, false);
            $this->add_url_to_sitemap($url, $lastmod);
        }

        echo '</urlset>';
    }

    /**
     * Generate service-city sitemap
     */
    private function generate_service_city_sitemap() {
        $base_url = home_url('/');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<?xml-stylesheet type="text/xsl" href="' . $base_url . 'main-sitemap.xsl"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Get all published cities and services
        $cities = get_posts([
            'post_type' => 'city',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        $services = get_posts([
            'post_type' => 'service',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        // Add main service pages first
        foreach ($services as $service) {
            $url = $base_url . 'services/' . $service->post_name . '/';
            $lastmod = mysql2date('c', $service->post_modified_gmt, false);
            $this->add_url_to_sitemap($url, $lastmod);
        }

        // Generate all city-service combinations
        foreach ($cities as $city) {
            foreach ($services as $service) {
                $url = $base_url . $city->post_name . '/' . $service->post_name . '/';
                $lastmod = max(
                    mysql2date('c', $city->post_modified_gmt, false),
                    mysql2date('c', $service->post_modified_gmt, false)
                );
                $this->add_url_to_sitemap($url, $lastmod);
            }
        }

        echo '</urlset>';
    }

    /**
     * Add a URL to the sitemap
     */
    private function add_url_to_sitemap($loc, $lastmod) {
        echo '  <url>' . "\n";
        echo '    <loc>' . esc_url($loc) . '</loc>' . "\n";
        echo '    <lastmod>' . esc_html($lastmod) . '</lastmod>' . "\n";
        echo '  </url>' . "\n";
    }
}

// Initialize the custom sitemap generator
new Sunnyside_Custom_Sitemap_Generator();