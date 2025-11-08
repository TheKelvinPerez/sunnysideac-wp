<?php
/**
 * Custom Sitemap Generator for Sunnyside AC
 *
 * This class generates a comprehensive sitemap index that includes:
 * - Standard WordPress sitemaps (pages, posts, categories)
 * - Dynamic sitemaps (cities, brands, services, service-city combinations)
 * - Respects all custom redirects and URL rewrites
 * - Proper HTTP caching headers for search engine optimization
 *
 * @package SunnysideAC
 */

class Sunnyside_Custom_Sitemap_Generator {

    /**
     * Excluded service slugs that redirect elsewhere
     */
    private $excluded_service_slugs = array(
        'emergency-hvac',
        'emergency-ac',
        '24-hour-emergency',
        'emergency-service',
        'ductless-mini-splits', // Redirects to singular form
    );

    /**
     * Initialize the custom sitemap system
     */
    public function __construct() {
        add_action('init', [$this, 'add_sitemap_rewrite_rules']);
        add_action('template_redirect', [$this, 'handle_sitemap_requests']);
        add_filter('query_vars', [$this, 'add_query_vars']);
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
            '^cities-sitemap\.xml$',
            'index.php?custom_sitemap=cities',
            'top'
        );

        add_rewrite_rule(
            '^brands-sitemap\.xml$',
            'index.php?custom_sitemap=brands',
            'top'
        );

        add_rewrite_rule(
            '^services-sitemap\.xml$',
            'index.php?custom_sitemap=services',
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
                case 'cities':
                    $this->generate_cities_sitemap();
                    break;
                case 'brands':
                    $this->generate_brands_sitemap();
                    break;
                case 'services':
                    $this->generate_services_sitemap();
                    break;
                case 'service-city':
                    $this->generate_service_city_sitemap();
                    break;
                case 'page':
                    $this->generate_wordpress_sitemap('page');
                    break;
                case 'category':
                    $this->generate_wordpress_sitemap('category');
                    break;
                case 'post':
                    $this->generate_wordpress_sitemap('post');
                    break;
                case 'tag':
                    $this->generate_wordpress_sitemap('tag');
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
        $base_url = trailingslashit(home_url());
        $current_time = mysql2date('c', current_time('mysql'), false);

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Standard WordPress sitemaps
        $this->add_sitemap_to_index($base_url . 'page-sitemap.xml', $current_time);
        $this->add_sitemap_to_index($base_url . 'post-sitemap.xml', $current_time);
        $this->add_sitemap_to_index($base_url . 'category-sitemap.xml', $current_time);
        $this->add_sitemap_to_index($base_url . 'tag-sitemap.xml', $current_time);

        // Our custom dynamic sitemaps
        $this->add_sitemap_to_index($base_url . 'cities-sitemap.xml', $current_time);
        $this->add_sitemap_to_index($base_url . 'brands-sitemap.xml', $current_time);
        $this->add_sitemap_to_index($base_url . 'services-sitemap.xml', $current_time);
        $this->add_sitemap_to_index($base_url . 'service-city-sitemap.xml', $current_time);

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
     * Generate cities sitemap
     * Uses /cities/{city}/ URL pattern (not /areas/)
     */
    private function generate_cities_sitemap() {
        $base_url = trailingslashit(home_url());
        $current_time = mysql2date('c', current_time('mysql'), false);

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Get all published city posts
        $cities = get_posts([
            'post_type' => 'city',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        foreach ($cities as $city) {
            $url = $base_url . 'cities/' . $city->post_name . '/';
            $lastmod = mysql2date('c', $city->post_modified_gmt, false);
            $this->add_url_to_sitemap($url, $lastmod, '0.8', 'weekly');
        }

        echo '</urlset>';
    }

    /**
     * Generate brands sitemap
     */
    private function generate_brands_sitemap() {
        $base_url = trailingslashit(home_url());

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
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
            $this->add_url_to_sitemap($url, $lastmod, '0.7', 'monthly');
        }

        // Add special Daikin product pages if Daikin brand exists
        $daikin = get_page_by_path('daikin', OBJECT, 'brand');
        if ($daikin && defined('SUNNYSIDE_DAIKIN_PRODUCTS')) {
            $lastmod = mysql2date('c', $daikin->post_modified_gmt, false);

            // Add the special /daikin/air-conditioners/ URL
            $this->add_url_to_sitemap($base_url . 'daikin/air-conditioners/', $lastmod, '0.7', 'monthly');

            // Add all Daikin product pages from constants
            foreach (SUNNYSIDE_DAIKIN_PRODUCTS as $product) {
                $product_url = $base_url . 'daikin/' . $product['slug'] . '/';
                $this->add_url_to_sitemap($product_url, $lastmod, '0.7', 'monthly');
            }
        }

        echo '</urlset>';
    }

    /**
     * Generate services sitemap
     * Excludes services that redirect elsewhere
     */
    private function generate_services_sitemap() {
        $base_url = trailingslashit(home_url());

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Get all published services
        $services = get_posts([
            'post_type' => 'service',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        foreach ($services as $service) {
            // Skip services that redirect elsewhere
            if (in_array($service->post_name, $this->excluded_service_slugs)) {
                continue;
            }

            $url = $base_url . 'services/' . $service->post_name . '/';
            $lastmod = mysql2date('c', $service->post_modified_gmt, false);
            $this->add_url_to_sitemap($url, $lastmod, '0.9', 'weekly');
        }

        echo '</urlset>';
    }

    /**
     * Generate service-city sitemap
     * Uses root-level /{city}/{service}/ URL pattern
     * Excludes services that redirect elsewhere
     */
    private function generate_service_city_sitemap() {
        $base_url = trailingslashit(home_url());

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
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

        // Generate all city-service combinations using /{city}/{service}/ pattern
        foreach ($cities as $city) {
            foreach ($services as $service) {
                // Skip services that redirect elsewhere
                if (in_array($service->post_name, $this->excluded_service_slugs)) {
                    continue;
                }

                // Use root-level URL pattern: /{city}/{service}/
                $url = $base_url . $city->post_name . '/' . $service->post_name . '/';

                // Use the most recent modification date
                $city_time = strtotime($city->post_modified_gmt);
                $service_time = strtotime($service->post_modified_gmt);
                $latest_time = max($city_time, $service_time);
                $lastmod = mysql2date('c', gmdate('Y-m-d H:i:s', $latest_time), false);

                $this->add_url_to_sitemap($url, $lastmod, '0.8', 'weekly');
            }
        }

        echo '</urlset>';
    }

    /**
     * Add a URL to the sitemap with optional priority and changefreq
     */
    private function add_url_to_sitemap($loc, $lastmod, $priority = '0.5', $changefreq = 'monthly') {
        echo '  <url>' . "\n";
        echo '    <loc>' . esc_url($loc) . '</loc>' . "\n";
        echo '    <lastmod>' . esc_html($lastmod) . '</lastmod>' . "\n";
        echo '    <changefreq>' . esc_html($changefreq) . '</changefreq>' . "\n";
        echo '    <priority>' . esc_html($priority) . '</priority>' . "\n";
        echo '  </url>' . "\n";
    }

    /**
     * Generate standard WordPress sitemaps (pages, posts, categories, tags)
     */
    private function generate_wordpress_sitemap($post_type) {
        $base_url = trailingslashit(home_url());

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        if ($post_type === 'page') {
            // Generate pages sitemap
            $pages = get_posts([
                'post_type' => 'page',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'modified',
                'order' => 'DESC'
            ]);

            foreach ($pages as $page) {
                $url = get_permalink($page->ID);
                $lastmod = mysql2date('c', $page->post_modified_gmt, false);
                $priority = $page->ID == get_option('page_on_front') ? '1.0' : '0.8';
                $this->add_url_to_sitemap($url, $lastmod, $priority, 'weekly');
            }
        } elseif ($post_type === 'post') {
            // Generate posts sitemap
            $posts = get_posts([
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'modified',
                'order' => 'DESC'
            ]);

            foreach ($posts as $post) {
                $url = get_permalink($post->ID);
                $lastmod = mysql2date('c', $post->post_modified_gmt, false);
                $this->add_url_to_sitemap($url, $lastmod, '0.6', 'monthly');
            }
        } elseif ($post_type === 'category') {
            // Generate categories sitemap
            $categories = get_categories([
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => true
            ]);

            foreach ($categories as $category) {
                $url = get_category_link($category->term_id);
                // Use the most recent post in this category as lastmod
                $recent_post = get_posts([
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'category' => $category->term_id,
                    'posts_per_page' => 1,
                    'orderby' => 'modified',
                    'order' => 'DESC'
                ]);

                $lastmod = !empty($recent_post) ? mysql2date('c', $recent_post[0]->post_modified_gmt, false) : mysql2date('c', current_time('mysql'), false);
                $this->add_url_to_sitemap($url, $lastmod, '0.7', 'weekly');
            }
        } elseif ($post_type === 'tag') {
            // Generate tags sitemap
            $tags = get_tags([
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => true
            ]);

            foreach ($tags as $tag) {
                $url = get_tag_link($tag->term_id);
                // Use the most recent post in this tag as lastmod
                $recent_post = get_posts([
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'tag_id' => $tag->term_id,
                    'posts_per_page' => 1,
                    'orderby' => 'modified',
                    'order' => 'DESC'
                ]);

                $lastmod = !empty($recent_post) ? mysql2date('c', $recent_post[0]->post_modified_gmt, false) : mysql2date('c', current_time('mysql'), false);
                $this->add_url_to_sitemap($url, $lastmod, '0.5', 'monthly');
            }
        }

        echo '</urlset>';
    }
}

// Initialize the custom sitemap generator
new Sunnyside_Custom_Sitemap_Generator();