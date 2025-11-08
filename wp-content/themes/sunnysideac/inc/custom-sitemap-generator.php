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

        // Get all published city posts with service area prioritization
        $cities = get_posts([
            'post_type' => 'city',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        // Get primary service areas from constants for priority determination
        $primary_cities = ['miami', 'fort-lauderdale', 'hollywood', 'pembroke-pines', 'miramar'];

        foreach ($cities as $city) {
            $url = $base_url . 'cities/' . $city->post_name . '/';
            $lastmod = mysql2date('c', $city->post_modified_gmt, false);

            // Smart priority based on city importance and recency
            if (in_array($city->post_name, $primary_cities)) {
                $priority = '0.9'; // Primary service areas
                $changefreq = 'weekly';
            } else {
                $priority = '0.8'; // Other service areas
                $changefreq = 'weekly';
            }

            // Update frequency based on how recently the city page was modified
            $days_since_modified = (time() - strtotime($city->post_modified_gmt)) / (60 * 60 * 24);
            if ($days_since_modified <= 30) {
                $changefreq = 'weekly';
            } else {
                $changefreq = 'monthly';
            }

            $this->add_url_to_sitemap($url, $lastmod, $priority, $changefreq);
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

        // Priority brands based on market prominence
        $priority_brands = ['daikin', 'trane', 'carrier', 'goodman', 'lennox'];

        foreach ($brands as $brand) {
            $url = $base_url . 'brands/' . $brand->post_name . '/';
            $lastmod = mysql2date('c', $brand->post_modified_gmt, false);

            // Smart priority based on brand importance
            if (in_array(strtolower($brand->post_name), $priority_brands)) {
                $priority = '0.8'; // Major HVAC brands
                $changefreq = 'monthly';
            } else {
                $priority = '0.6'; // Other brands
                $changefreq = 'monthly';
            }

            $this->add_url_to_sitemap($url, $lastmod, $priority, $changefreq);
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

        // High-priority core HVAC services
        $core_services = ['air-conditioning', 'hvac-installation', 'hvac-repair', 'maintenance', 'emergency-ac-repair'];

        foreach ($services as $service) {
            // Skip services that redirect elsewhere
            if (in_array($service->post_name, $this->excluded_service_slugs)) {
                continue;
            }

            $url = $base_url . 'services/' . $service->post_name . '/';
            $lastmod = mysql2date('c', $service->post_modified_gmt, false);

            // Smart priority based on service importance and recency
            if (in_array($service->post_name, $core_services)) {
                $priority = '0.9'; // Core HVAC services
                $changefreq = 'weekly';
            } else {
                $priority = '0.7'; // Other services
                $changefreq = 'monthly';
            }

            // Update frequency based on how recently the service was modified
            $days_since_modified = (time() - strtotime($service->post_modified_gmt)) / (60 * 60 * 24);
            if ($days_since_modified <= 30) {
                $changefreq = 'weekly';
            } else {
                $changefreq = 'monthly';
            }

            $this->add_url_to_sitemap($url, $lastmod, $priority, $changefreq);
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

        // Priority cities and services for better SEO
        $primary_cities = ['miami', 'fort-lauderdale', 'hollywood', 'pembroke-pines', 'miramar'];
        $core_services = ['air-conditioning', 'hvac-installation', 'hvac-repair', 'maintenance', 'emergency-ac-repair'];

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

                // Smart priority based on city and service importance
                if (in_array($city->post_name, $primary_cities) && in_array($service->post_name, $core_services)) {
                    $priority = '0.9'; // Primary city + core service (highest priority)
                    $changefreq = 'weekly';
                } elseif (in_array($city->post_name, $primary_cities) || in_array($service->post_name, $core_services)) {
                    $priority = '0.8'; // Primary city OR core service
                    $changefreq = 'weekly';
                } else {
                    $priority = '0.7'; // Standard combinations
                    $changefreq = 'monthly';
                }

                // Update frequency based on recency
                $days_since_modified = (time() - $latest_time) / (60 * 60 * 24);
                if ($days_since_modified <= 7) {
                    $changefreq = 'weekly';
                } elseif ($days_since_modified <= 30) {
                    $changefreq = 'monthly';
                } else {
                    $changefreq = 'monthly';
                }

                $this->add_url_to_sitemap($url, $lastmod, $priority, $changefreq);
            }
        }

        echo '</urlset>';
    }

    /**
     * Add a URL to the sitemap with optional priority and changefreq
     */
    private function add_url_to_sitemap($loc, $lastmod, $priority = '0.5', $changefreq = 'monthly') {
        // Ensure lastmod is in ISO 8601 format (like your example: 2025-11-08T17:53:38.222Z)
        if (is_string($lastmod) && !preg_match('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $lastmod)) {
            $lastmod = mysql2date('c', $lastmod, false);
        }

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
            // Generate pages sitemap with smart priorities
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

                // Smart priority based on page type and importance
                if ($page->ID == get_option('page_on_front')) {
                    $priority = '1.0'; // Homepage - highest priority
                    $changefreq = 'weekly';
                } elseif (in_array($page->post_name, ['contact', 'about'])) {
                    $priority = '0.9'; // Core business pages
                    $changefreq = 'monthly';
                } elseif (in_array($page->post_name, ['blog', 'services'])) {
                    $priority = '0.8'; // Important section pages
                    $changefreq = 'weekly';
                } else {
                    $priority = '0.7'; // Standard pages
                    $changefreq = 'monthly';
                }

                $this->add_url_to_sitemap($url, $lastmod, $priority, $changefreq);
            }
        } elseif ($post_type === 'post') {
            // Generate posts sitemap with recency-based priorities
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

                // Priority based on recency (recent posts get higher priority)
                $days_since_modified = (time() - strtotime($post->post_modified_gmt)) / (60 * 60 * 24);
                if ($days_since_modified <= 7) {
                    $priority = '0.9'; // Very recent posts
                    $changefreq = 'weekly';
                } elseif ($days_since_modified <= 30) {
                    $priority = '0.8'; // Recent posts
                    $changefreq = 'monthly';
                } elseif ($days_since_modified <= 90) {
                    $priority = '0.7'; // Moderately recent
                    $changefreq = 'monthly';
                } else {
                    $priority = '0.6'; // Older posts
                    $changefreq = 'yearly';
                }

                $this->add_url_to_sitemap($url, $lastmod, $priority, $changefreq);
            }
        } elseif ($post_type === 'category') {
            // Generate categories sitemap with post-count-based priorities
            $categories = get_categories([
                'orderby' => 'count',
                'order' => 'DESC',
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

                // Priority based on number of posts in category
                if ($category->count >= 10) {
                    $priority = '0.8'; // Categories with many posts
                    $changefreq = 'weekly';
                } elseif ($category->count >= 5) {
                    $priority = '0.7'; // Categories with moderate posts
                    $changefreq = 'monthly';
                } else {
                    $priority = '0.6'; // Categories with few posts
                    $changefreq = 'monthly';
                }

                $this->add_url_to_sitemap($url, $lastmod, $priority, $changefreq);
            }
        } elseif ($post_type === 'tag') {
            // Generate tags sitemap with usage-based priorities
            $tags = get_tags([
                'orderby' => 'count',
                'order' => 'DESC',
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

                // Priority based on tag usage frequency
                if ($tag->count >= 10) {
                    $priority = '0.7'; // Frequently used tags
                    $changefreq = 'monthly';
                } else {
                    $priority = '0.5'; // Less used tags
                    $changefreq = 'monthly';
                }

                $this->add_url_to_sitemap($url, $lastmod, $priority, $changefreq);
            }
        }

        echo '</urlset>';
    }
}

// Initialize the custom sitemap generator
new Sunnyside_Custom_Sitemap_Generator();