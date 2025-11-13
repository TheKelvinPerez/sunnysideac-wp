<?php
/**
 * SEObot API SEO Integration Class
 *
 * @package SEObot API
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class for integrating with SEO plugins
 */
class SEObot_API_SEO_Integration {
    /**
     * Constructor
     */
    public function __construct() {
        // Nothing to do here
    }

    /**
     * Set SEO metadata for a post from API request data
     *
     * @param int $post_id Post ID
     * @param array $seo_data SEO data from API request
     * @return bool True if successful, false otherwise
     */
    public function set_seo_metadata($post_id, $seo_data) {
        if (empty($post_id) || empty($seo_data)) {
            return false;
        }

        // Check for popular SEO plugins and set metadata
        if ($this->is_yoast_active()) {
            $this->set_yoast_metadata($post_id, $seo_data);
        }

        if ($this->is_rank_math_active()) {
            $this->set_rank_math_metadata($post_id, $seo_data);
        }

        if ($this->is_all_in_one_seo_active()) {
            $this->set_all_in_one_seo_metadata($post_id, $seo_data);
        }

        return true;
    }

    /**
     * Update post SEO metadata (alias for set_seo_metadata)
     *
     * @param int $post_id Post ID
     * @param array $seo_data SEO data from API request
     * @return bool True if successful, false otherwise
     */
    public function update_post_seo_metadata($post_id, $seo_data) {
        return $this->set_seo_metadata($post_id, $seo_data);
    }

    /**
     * Check if Yoast SEO is active
     *
     * @return bool
     */
    private function is_yoast_active() {
        return defined('WPSEO_VERSION');
    }

    /**
     * Check if Rank Math SEO is active
     *
     * @return bool
     */
    private function is_rank_math_active() {
        return class_exists('RankMath');
    }

    /**
     * Check if All in One SEO is active
     *
     * @return bool
     */
    private function is_all_in_one_seo_active() {
        return defined('AIOSEO_VERSION');
    }

    /**
     * Set Yoast SEO metadata
     *
     * @param int $post_id Post ID
     * @param array $seo_data SEO data
     */
    private function set_yoast_metadata($post_id, $seo_data) {
        // Set title
        if (!empty($seo_data['title'])) {
            update_post_meta($post_id, '_yoast_wpseo_title', $seo_data['title']);
        }

        // Set meta description
        if (!empty($seo_data['description'])) {
            update_post_meta($post_id, '_yoast_wpseo_metadesc', $seo_data['description']);
        }

        // Set focus keyphrase
        if (!empty($seo_data['keywords'])) {
            update_post_meta($post_id, '_yoast_wpseo_focuskw', $seo_data['keywords']);
        }

        // Set canonical URL
        if (!empty($seo_data['canonical_url'])) {
            update_post_meta($post_id, '_yoast_wpseo_canonical', esc_url_raw($seo_data['canonical_url']));
        }

        // Set meta robots
        if (!empty($seo_data['robots'])) {
            if (isset($seo_data['robots']['noindex']) && $seo_data['robots']['noindex']) {
                update_post_meta($post_id, '_yoast_wpseo_meta-robots-noindex', 1);
            } else {
                update_post_meta($post_id, '_yoast_wpseo_meta-robots-noindex', 2);
            }

            if (isset($seo_data['robots']['nofollow']) && $seo_data['robots']['nofollow']) {
                update_post_meta($post_id, '_yoast_wpseo_meta-robots-nofollow', 1);
            } else {
                update_post_meta($post_id, '_yoast_wpseo_meta-robots-nofollow', 0);
            }
        }

        // Handle primary category
        if (isset($seo_data['primary_category'])) {
            $primary_category = absint($seo_data['primary_category']);

            // Validate the primary category
            if (term_exists($primary_category, 'category')) {
                update_post_meta($post_id, '_yoast_wpseo_primary_category', $primary_category);
            } else {
                // Fallback to default category from settings
                update_post_meta($post_id, '_yoast_wpseo_primary_category', get_option('seobot_api_default_category', 1));
            }
        } else {
            // Use default category from settings
            update_post_meta($post_id, '_yoast_wpseo_primary_category', get_option('seobot_api_default_category', 1));
        }
    }

    /**
     * Set Rank Math SEO metadata
     *
     * @param int $post_id Post ID
     * @param array $seo_data SEO data
     */
    private function set_rank_math_metadata($post_id, $seo_data) {
        // Set title
        if (!empty($seo_data['title'])) {
            update_post_meta($post_id, 'rank_math_title', $seo_data['title']);
        }

        // Set meta description
        if (!empty($seo_data['description'])) {
            update_post_meta($post_id, 'rank_math_description', $seo_data['description']);
        }

        // Set focus keyword
        if (!empty($seo_data['keywords'])) {
            update_post_meta($post_id, 'rank_math_focus_keyword', $seo_data['keywords']);
        }

        // Set canonical URL
        if (!empty($seo_data['canonical_url'])) {
            update_post_meta($post_id, 'rank_math_canonical_url', esc_url_raw($seo_data['canonical_url']));
        }

        // Set robots meta
        if (!empty($seo_data['robots'])) {
            $robots_meta = array();

            if (isset($seo_data['robots']['noindex']) && $seo_data['robots']['noindex']) {
                $robots_meta[] = 'noindex';
            } else {
                $robots_meta[] = 'index';
            }

            if (isset($seo_data['robots']['nofollow']) && $seo_data['robots']['nofollow']) {
                $robots_meta[] = 'nofollow';
            } else {
                $robots_meta[] = 'follow';
            }

            update_post_meta($post_id, 'rank_math_robots', $robots_meta);
        }
    }

    /**
     * Set All in One SEO metadata
     *
     * @param int $post_id Post ID
     * @param array $seo_data SEO data
     */
    private function set_all_in_one_seo_metadata($post_id, $seo_data) {
        // Set title
        if (!empty($seo_data['title'])) {
            update_post_meta($post_id, '_aioseo_title', $seo_data['title']);
        }

        // Set meta description
        if (!empty($seo_data['description'])) {
            update_post_meta($post_id, '_aioseo_description', $seo_data['description']);
        }

        // Set canonical URL
        if (!empty($seo_data['canonical_url'])) {
            update_post_meta($post_id, '_aioseo_canonical_url', esc_url_raw($seo_data['canonical_url']));
        }

        // Set robots meta
        if (!empty($seo_data['robots'])) {
            $robots_meta = array(
                'noindex' => isset($seo_data['robots']['noindex']) ? $seo_data['robots']['noindex'] : false,
                'nofollow' => isset($seo_data['robots']['nofollow']) ? $seo_data['robots']['nofollow'] : false,
            );

            update_post_meta($post_id, '_aioseo_robots_meta', $robots_meta);
        }

        // Set focus keyword
        if (!empty($seo_data['keywords'])) {
            update_post_meta($post_id, '_aioseo_keywords', $seo_data['keywords']);
        }
    }

    /**
     * Get SEO metadata for a post
     *
     * @param int $post_id Post ID
     * @return array SEO metadata
     */
    public function get_seo_metadata($post_id) {
        if (empty($post_id)) {
            return array();
        }

        $seo_data = array();

        // Get Yoast SEO metadata if active
        if ($this->is_yoast_active()) {
            $seo_data['yoast'] = array(
                'title' => get_post_meta($post_id, '_yoast_wpseo_title', true),
                'description' => get_post_meta($post_id, '_yoast_wpseo_metadesc', true),
                'focus_keyword' => get_post_meta($post_id, '_yoast_wpseo_focuskw', true),
                'canonical_url' => get_post_meta($post_id, '_yoast_wpseo_canonical', true),
                'robots' => array(
                    'noindex' => get_post_meta($post_id, '_yoast_wpseo_meta-robots-noindex', true),
                    'nofollow' => get_post_meta($post_id, '_yoast_wpseo_meta-robots-nofollow', true)
                )
            );
        }

        // Get Rank Math metadata if active
        if ($this->is_rank_math_active()) {
            $seo_data['rank_math'] = array(
                'title' => get_post_meta($post_id, 'rank_math_title', true),
                'description' => get_post_meta($post_id, 'rank_math_description', true),
                'focus_keyword' => get_post_meta($post_id, 'rank_math_focus_keyword', true),
                'canonical_url' => get_post_meta($post_id, 'rank_math_canonical_url', true),
                'robots' => get_post_meta($post_id, 'rank_math_robots', true)
            );
        }

        // Get All in One SEO metadata if active
        if ($this->is_all_in_one_seo_active()) {
            $seo_data['aioseo'] = array(
                'title' => get_post_meta($post_id, '_aioseo_title', true),
                'description' => get_post_meta($post_id, '_aioseo_description', true),
                'keywords' => get_post_meta($post_id, '_aioseo_keywords', true),
                'canonical_url' => get_post_meta($post_id, '_aioseo_canonical_url', true),
                'robots' => get_post_meta($post_id, '_aioseo_robots_meta', true)
            );
        }

        return $seo_data;
    }
}