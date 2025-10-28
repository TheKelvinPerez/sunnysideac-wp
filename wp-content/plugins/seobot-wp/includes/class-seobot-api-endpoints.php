<?php
/**
 * SEObot API Endpoints Class
 *
 * @package SEObot API
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * API Endpoints class
 */
class SEObot_API_Endpoints
{
    /**
     * API namespace
     */
    const API_NAMESPACE = 'seobot/v1';

    /**
     * SEO Integration instance
     *
     * @var SEObot_API_SEO_Integration
     */
    private $seo;

    /**
     * Constructor
     *
     * @param SEObot_API_SEO_Integration $seo SEO integration instance
     */
    public function __construct($seo = null)
    {
        if ($seo) {
            $this->seo = $seo;
        } else {
            // Fallback if SEO integration is not provided
            $this->seo = new SEObot_API_SEO_Integration();
        }
    }

    /**
     * Check if unfiltered HTML should be allowed
     *
     * @return bool True if unfiltered HTML is allowed, false otherwise
     */
    private function should_allow_unfiltered_html()
    {
        // Always allow unfiltered HTML for SEObot API
        return true;
    }

    /**
     * Get categories
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function get_categories($request)
    {
        $categories = get_categories(array('hide_empty' => false));

        $result = array();
        foreach ($categories as $category) {
            $result[] = array(
                'id' => $category->term_id,
                'name' => $category->name,
                'slug' => $category->slug,
            );
        }

        return rest_ensure_response($result);
    }

    /**
     * Get posts
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function get_posts($request)
    {
        $post_type = $request->get_param('post_type');
        $status = $request->get_param('status');
        $slug = $request->get_param('slug');
        $per_page = $request->get_param('per_page');
        $fields = $request->get_param('_fields');

        // If no post type specified, use default from settings
        if (empty($post_type)) {
            $post_type = get_option('seobot_api_default_post_type', 'post');
        }

        // Verify post type exists and is public
        if (!post_type_exists($post_type)) {
            return new WP_Error(
                'rest_invalid_post_type',
                __('Invalid post type.', 'seobot-api'),
                array('status' => 400)
            );
        }

        // Only allow public post types
        $post_type_obj = get_post_type_object($post_type);
        if (!$post_type_obj->public) {
            return new WP_Error(
                'rest_forbidden_post_type',
                __('Cannot access non-public post type.', 'seobot-api'),
                array('status' => 403)
            );
        }

        // Set the default status based on post type
        if (empty($status) || $status === 'any') {
            if ($post_type === 'attachment') {
                $status = 'inherit';
            } else {
                $status = 'publish';
            }
        }

        // Set default per_page if not specified
        if (empty($per_page)) {
            $per_page = 10;
        }

        $args = array(
            'post_type' => $post_type,
            'posts_per_page' => $per_page,
            'post_status' => $status,
        );

        // If slug is provided, use it to find the post
        if (!empty($slug)) {
            // Use name parameter for exact slug match
            $args['name'] = sanitize_title($slug);
        }

        $query = new WP_Query($args);
        $posts = $query->posts;
        $result = array();

        foreach ($posts as $post) {
            $post_data = array(
                'id' => $post->ID,
                'date' => get_the_date('c', $post->ID),
                'slug' => $post->post_name,
                'type' => $post->post_type,
                'title' => array(
                    'rendered' => get_the_title($post->ID),
                    'raw' => $post->post_title,
                ),
                'content' => array(
                    'rendered' => apply_filters('the_content', $post->post_content),
                    'raw' => $post->post_content,
                ),
                'excerpt' => array(
                    'rendered' => get_the_excerpt($post->ID),
                    'raw' => $post->post_excerpt,
                ),
                'featured_media' => get_post_thumbnail_id($post->ID),
                'author' => $post->post_author,
                'comment_status' => $post->comment_status,
                'status' => $post->post_status,
                'link' => get_permalink($post->ID)
            );

            // Add categories for post types that support them
            if (is_object_in_taxonomy($post_type, 'category')) {
                $post_data['categories'] = wp_get_post_categories($post->ID);
            }

            // Add tags for post types that support them
            if (is_object_in_taxonomy($post_type, 'post_tag')) {
                $post_data['tags'] = wp_get_post_tags($post->ID, array('fields' => 'ids'));
            }

            // If post_type is attachment, add media details
            if ($post_type === 'attachment') {
                $attachment_url = wp_get_attachment_url($post->ID);
                $attachment_meta = wp_get_attachment_metadata($post->ID);

                // Get attachment size information
                $sizes = array();
                if (isset($attachment_meta['sizes'])) {
                    foreach ($attachment_meta['sizes'] as $size => $size_data) {
                        $image_src = wp_get_attachment_image_src($post->ID, $size);
                        if ($image_src) {
                            $sizes[$size] = array(
                                'url' => $image_src[0],
                                'width' => $image_src[1],
                                'height' => $image_src[2],
                            );
                        }
                    }
                }

                $post_data['source_url'] = $attachment_url;
                $post_data['media_details'] = array(
                    'width' => isset($attachment_meta['width']) ? $attachment_meta['width'] : 0,
                    'height' => isset($attachment_meta['height']) ? $attachment_meta['height'] : 0,
                    'file' => isset($attachment_meta['file']) ? $attachment_meta['file'] : '',
                    'sizes' => $sizes,
                );
                $post_data['mime_type'] = get_post_mime_type($post->ID);
            }

            // Add custom taxonomies
            $taxonomies = get_object_taxonomies($post_type, 'objects');
            foreach ($taxonomies as $taxonomy) {
                // Skip categories and tags as they are already handled
                if ($taxonomy->name === 'category' || $taxonomy->name === 'post_tag') {
                    continue;
                }

                if ($taxonomy->show_in_rest) {
                    $terms = wp_get_post_terms($post->ID, $taxonomy->name, array('fields' => 'ids'));
                    if (!is_wp_error($terms)) {
                        $post_data['taxonomies'][$taxonomy->name] = $terms;
                    }
                }
            }

            // Get post meta fields
            $post_meta = get_post_meta($post->ID);
            $meta_data = array();

            foreach ($post_meta as $meta_key => $meta_values) {
                // Skip protected fields that start with underscore
                if (substr($meta_key, 0, 1) === '_') {
                    continue;
                }

                // Add single value meta
                if (count($meta_values) === 1) {
                    $meta_data[$meta_key] = maybe_unserialize($meta_values[0]);
                } else {
                    // Add multi-value meta
                    $meta_data[$meta_key] = array_map('maybe_unserialize', $meta_values);
                }
            }

            $post_data['meta'] = $meta_data;

            // If _fields parameter is set, filter the fields
            if (!empty($fields)) {
                $fields_array = explode(',', $fields);
                foreach (array_keys($post_data) as $key) {
                    if (!in_array($key, $fields_array)) {
                        unset($post_data[$key]);
                    }
                }
            }

            $result[] = $post_data;
        }

        // Add pagination info
        $response = rest_ensure_response($result);
        $response->header('X-WP-Total', $query->found_posts);
        $response->header('X-WP-TotalPages', ceil($query->found_posts / $per_page));

        return $response;
    }

    /**
     * Delete post
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function delete_post($request)
    {
        $post_id = $request->get_param('id');
        $post_type = $request->get_param('post_type');

        // If no post type specified, use default from settings
        if (empty($post_type)) {
            $post_type = get_option('seobot_api_default_post_type', 'post');
        }

        // Check if post exists
        $post = get_post($post_id);
        if (!$post) {
            return new WP_Error(
                'rest_post_invalid_id',
                __('Invalid post ID.', 'seobot-api'),
                array('status' => 404)
            );
        }

        // For security, verify the post type matches the request
        if ($post_type !== 'any' && $post->post_type !== $post_type) {
            return new WP_Error(
                'rest_post_invalid_type',
                __('The post type does not match the requested type.', 'seobot-api'),
                array('status' => 400)
            );
        }

        // Store post information before deletion for response
        $previous = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'type' => $post->post_type,
            'slug' => $post->post_name,
        );

        // Delete post (force true to bypass trash)
        $force_delete = $request->get_param('force') ? true : false;
        $result = wp_delete_post($post_id, $force_delete);

        if (!$result) {
            return new WP_Error(
                'rest_cannot_delete',
                __('The post cannot be deleted.', 'seobot-api'),
                array('status' => 500)
            );
        }

        $response_data = array(
            'deleted' => true,
            'previous' => $previous,
        );

        if (!$force_delete && 'trash' === $result->post_status) {
            $response_data['trashed'] = true;
        }

        return rest_ensure_response($response_data);
    }

    /**
     * Update post
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function update_post($request)
    {
        $post_id = $request->get_param('id');
        $post_type = $request->get_param('post_type');

        // If no post type specified, use default from settings
        if (empty($post_type)) {
            $post_type = get_option('seobot_api_default_post_type', 'post');
        }

        // Check if post exists and is of the correct type
        $post = get_post($post_id);
        if (!$post) {
            return new WP_Error(
                'rest_post_invalid_id',
                __('Invalid post ID.', 'seobot-api'),
                array('status' => 404)
            );
        }

        // Get JSON data from request body
        $json_data = $request->get_json_params();

        // If no JSON data was provided, try to get data from POST parameters
        if (empty($json_data)) {
            $json_data = $request->get_params();
        }

        // Prepare post data with existing post values
        $post_data = array(
            'ID' => $post_id,
            'post_type' => $post->post_type,
        );

        // Update post data with provided values
        if (isset($json_data['title'])) {
            $post_data['post_title'] = sanitize_text_field($json_data['title']);
        }

        if (isset($json_data['content'])) {
            $allow_unfiltered_html = $this->should_allow_unfiltered_html();
            if ($allow_unfiltered_html) {
                // Allow unfiltered HTML including script tags
                $post_data['post_content'] = wp_unslash($json_data['content']);
            } else {
                // Use default WordPress sanitization (strips script tags)
                $post_data['post_content'] = wp_kses_post($json_data['content']);
            }
        }

        if (isset($json_data['excerpt'])) {
            $post_data['post_excerpt'] = sanitize_textarea_field($json_data['excerpt']);
        }

        if (isset($json_data['slug'])) {
            $post_data['post_name'] = sanitize_title($json_data['slug']);
        }

        if (isset($json_data['status'])) {
            $status = sanitize_text_field($json_data['status']);

            // Validate the status
            if (!in_array($status, array('publish', 'future', 'draft', 'pending', 'private'))) {
                return new WP_Error(
                    'rest_invalid_status',
                    __('Invalid post status.', 'seobot-api'),
                    array('status' => 400)
                );
            }

            $post_data['post_status'] = $status;
        }

        if (isset($json_data['author'])) {
            $author_id = absint($json_data['author']);

            // Check if the author exists
            if (!get_userdata($author_id)) {
                // Fallback to default author from settings
                $author_id = get_option('seobot_api_default_author');
            }
        } else {
            // Use default author from settings
            $author_id = get_option('seobot_api_default_author');
        }

        $post_data['post_author'] = $author_id;

        // Update post
        $result = wp_update_post($post_data, true);

        if (is_wp_error($result)) {
            return $result;
        }

        // Validate and set categories
        if (is_object_in_taxonomy($post->post_type, 'category')) {
            if (isset($json_data['categories'])) {
                $categories = array_map('absint', (array) $json_data['categories']);
                $valid_categories = array();
                // Validate categories
                foreach ($categories as $category_id) {
                    if (term_exists($category_id, 'category')) {
                        $valid_categories[] = $category_id;
                    }
                }

                // If no valid categories found, fallback to default
                if (empty($valid_categories)) {
                    $categories = array(get_option('seobot_api_default_category', 1));
                } else {
                    $categories = $valid_categories;
                }
            } else {
                // Fallback to default category from settings
                $categories = array(get_option('seobot_api_default_category', 1));
            }

            wp_set_post_categories($post_id, $categories);
        }

        // Update tags if provided
        if (isset($json_data['tags']) && is_object_in_taxonomy($post->post_type, 'post_tag')) {
            $tags = array_map('absint', (array) $json_data['tags']);

            // Validate tags
            foreach ($tags as $tag_id) {
                if (!term_exists($tag_id, 'post_tag')) {
                    return new WP_Error(
                        'rest_invalid_tag',
                        __('Invalid tag ID.', 'seobot-api'),
                        array('status' => 400)
                    );
                }
            }

            wp_set_post_tags($post_id, $tags);
        }

        // Update featured image if provided
        if (isset($json_data['featured_media'])) {
            $featured_media = absint($json_data['featured_media']);

            if ($featured_media === 0) {
                // Remove featured image
                delete_post_thumbnail($post_id);
            } else {
                // Validate the media
                $attachment = get_post($featured_media);
                if (!$attachment || $attachment->post_type !== 'attachment' || !wp_attachment_is_image($featured_media)) {
                    return new WP_Error(
                        'rest_invalid_featured_media',
                        __('Invalid featured media ID.', 'seobot-api'),
                        array('status' => 400)
                    );
                }

                // Set featured image
                set_post_thumbnail($post_id, $featured_media);
            }
        }

        // Handle custom taxonomies
        if (isset($json_data['taxonomies']) && is_array($json_data['taxonomies'])) {
            foreach ($json_data['taxonomies'] as $taxonomy => $terms) {
                if (!taxonomy_exists($taxonomy)) {
                    continue;
                }

                if (is_object_in_taxonomy($post->post_type, $taxonomy)) {
                    wp_set_object_terms($post_id, $terms, $taxonomy);
                }
            }
        }

        // Handle meta fields
        if (isset($json_data['meta']) && is_array($json_data['meta'])) {
            foreach ($json_data['meta'] as $meta_key => $meta_value) {
                update_post_meta($post_id, $meta_key, $meta_value);
            }
        }

        // Get updated post
        $updated_post = get_post($post_id);

        // Build response data
        $data = array(
            'id' => $updated_post->ID,
            'date' => get_the_date('c', $updated_post->ID),
            'slug' => $updated_post->post_name,
            'type' => $updated_post->post_type,
            'title' => array(
                'rendered' => get_the_title($updated_post->ID),
                'raw' => $updated_post->post_title,
            ),
            'content' => array(
                'rendered' => apply_filters('the_content', $updated_post->post_content),
                'raw' => $updated_post->post_content,
            ),
            'excerpt' => array(
                'rendered' => get_the_excerpt($updated_post->ID),
                'raw' => $updated_post->post_excerpt,
            ),
            'author' => $updated_post->post_author,
            'status' => $updated_post->post_status,
            'link' => get_permalink($updated_post->ID),
        );

        // Add categories for post types that support them
        if (is_object_in_taxonomy($updated_post->post_type, 'category')) {
            $data['categories'] = wp_get_post_categories($updated_post->ID);
        }

        // Add tags for post types that support them
        if (is_object_in_taxonomy($updated_post->post_type, 'post_tag')) {
            $data['tags'] = wp_get_post_tags($updated_post->ID, array('fields' => 'ids'));
        }

        // Add featured media if set
        if (has_post_thumbnail($updated_post->ID)) {
            $data['featured_media'] = get_post_thumbnail_id($updated_post->ID);
        }

        // Process SEO metadata if provided
        if (isset($json_data['seo_data']) && is_array($json_data['seo_data'])) {
            $this->seo->update_post_seo_metadata($post_id, $json_data['seo_data']);
        }

        return rest_ensure_response($data);
    }

    /**
     * Create post
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function create_post($request)
    {
        $json_data = $request->get_json_params();

        // If no JSON data was provided, try to get data from POST parameters
        if (empty($json_data)) {
            $json_data = $request->get_params();
        }

        // Get post data from request
        $title = isset($json_data['title']) ? sanitize_text_field($json_data['title']) : '';

        // Handle content sanitization based on settings
        if (isset($json_data['content'])) {
            $allow_unfiltered_html = $this->should_allow_unfiltered_html();
            if ($allow_unfiltered_html) {
                // Allow unfiltered HTML including script tags
                $content = wp_unslash($json_data['content']);
            } else {
                // Use default WordPress sanitization (strips script tags)
                $content = wp_kses_post($json_data['content']);
            }
        } else {
            $content = '';
        }

        $excerpt = isset($json_data['excerpt']) ? sanitize_textarea_field($json_data['excerpt']) : '';
        $slug = isset($json_data['slug']) ? sanitize_title($json_data['slug']) : '';
        $status = isset($json_data['status']) ? sanitize_text_field($json_data['status']) : 'draft';
        $categories = isset($json_data['categories']) ? array_map('absint', (array) $json_data['categories']) : array();
        $tags = isset($json_data['tags']) ? array_map('absint', (array) $json_data['tags']) : array();
        $featured_media = isset($json_data['featured_media']) ? absint($json_data['featured_media']) : 0;
        $post_type = isset($json_data['post_type']) ? sanitize_text_field($json_data['post_type']) : get_option('seobot_api_default_post_type', 'post');

        // Verify post type exists and is public
        if (!post_type_exists($post_type)) {
            return new WP_Error(
                'rest_invalid_post_type',
                __('Invalid post type.', 'seobot-api'),
                array('status' => 400)
            );
        }

        // Only allow public post types
        $post_type_obj = get_post_type_object($post_type);
        if (!$post_type_obj->public || !$post_type_obj->show_in_rest) {
            return new WP_Error(
                'rest_forbidden_post_type',
                __('Cannot create content with this post type.', 'seobot-api'),
                array('status' => 403)
            );
        }

        // Get author ID from request or use default
        if (isset($json_data['author'])) {
            $author_id = absint($json_data['author']);

            // Check if the author exists
            if (!get_userdata($author_id)) {
                // Fallback to default author from settings
                $author_id = get_option('seobot_api_default_author');
            }
        } else {
            // Use default author from settings
            $author_id = get_option('seobot_api_default_author');
        }

        // Check if the specified author exists
        if (!get_userdata($author_id)) {
            return new WP_Error(
                'rest_invalid_author',
                __('Invalid author ID.', 'seobot-api'),
                array('status' => 400)
            );
        }

        // Validate and set categories
        if (is_object_in_taxonomy($post_type, 'category')) {
            if (!empty($categories)) {
                $valid_categories = array();
                // Validate categories
                foreach ($categories as $category_id) {
                    if (term_exists($category_id, 'category')) {
                        $valid_categories[] = $category_id;
                    }
                }

                // If no valid categories found, fallback to default
                if (empty($valid_categories)) {
                    $categories = array(get_option('seobot_api_default_category', 1));
                } else {
                    $categories = $valid_categories;
                }
            } else {
                // Fallback to default category from settings
                $categories = array(get_option('seobot_api_default_category', 1));
            }
        }

        // Validate tags
        if (!empty($tags) && is_object_in_taxonomy($post_type, 'post_tag')) {
            foreach ($tags as $tag_id) {
                if (!term_exists($tag_id, 'post_tag')) {
                    return new WP_Error(
                        'rest_invalid_tag',
                        __('Invalid tag ID.', 'seobot-api'),
                        array('status' => 400)
                    );
                }
            }
        }

        // Validate the status
        if (!in_array($status, array('publish', 'future', 'draft', 'pending', 'private'))) {
            return new WP_Error(
                'rest_invalid_status',
                __('Invalid post status.', 'seobot-api'),
                array('status' => 400)
            );
        }

        // Validate the featured media
        if (!empty($featured_media)) {
            $attachment = get_post($featured_media);
            if (!$attachment || $attachment->post_type !== 'attachment' || !wp_attachment_is_image($featured_media)) {
                return new WP_Error(
                    'rest_invalid_featured_media',
                    __('Invalid featured media ID.', 'seobot-api'),
                    array('status' => 400)
                );
            }
        }

        // Create post array
        $post_data = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_excerpt' => $excerpt,
            'post_status' => $status,
            'post_author' => $author_id,
            'post_type' => $post_type,
        );

        // Add slug if provided
        if (!empty($slug)) {
            $post_data['post_name'] = $slug;
        }

        // Insert the post
        $post_id = wp_insert_post($post_data, true);

        // Check if post creation failed
        if (is_wp_error($post_id)) {
            return $post_id;
        }

        // Set categories if applicable
        if (!empty($categories) && is_object_in_taxonomy($post_type, 'category')) {
            wp_set_post_categories($post_id, $categories);
        }

        // Set tags if applicable
        if (!empty($tags) && is_object_in_taxonomy($post_type, 'post_tag')) {
            wp_set_post_tags($post_id, $tags);
        }

        // Set featured image if provided
        if (!empty($featured_media)) {
            set_post_thumbnail($post_id, $featured_media);
        }

        // Handle custom taxonomies
        if (isset($json_data['taxonomies']) && is_array($json_data['taxonomies'])) {
            foreach ($json_data['taxonomies'] as $taxonomy => $terms) {
                if (!taxonomy_exists($taxonomy)) {
                    continue;
                }

                if (is_object_in_taxonomy($post_type, $taxonomy)) {
                    wp_set_object_terms($post_id, $terms, $taxonomy);
                }
            }
        }

        // Handle meta fields
        if (isset($json_data['meta']) && is_array($json_data['meta'])) {
            foreach ($json_data['meta'] as $meta_key => $meta_value) {
                update_post_meta($post_id, $meta_key, $meta_value);
            }
        }

        // Build response data
        $post = get_post($post_id);
        $data = array(
            'id' => $post->ID,
            'date' => get_the_date('c', $post->ID),
            'slug' => $post->post_name,
            'type' => $post->post_type,
            'title' => array(
                'rendered' => get_the_title($post->ID),
                'raw' => $post->post_title,
            ),
            'content' => array(
                'rendered' => apply_filters('the_content', $post->post_content),
                'raw' => $post->post_content,
            ),
            'excerpt' => array(
                'rendered' => get_the_excerpt($post->ID),
                'raw' => $post->post_excerpt,
            ),
            'author' => $post->post_author,
            'status' => $post->post_status,
            'link' => get_permalink($post->ID),
        );

        // Add categories if applicable
        if (is_object_in_taxonomy($post_type, 'category')) {
            $data['categories'] = wp_get_post_categories($post->ID);
        }

        // Add tags if applicable
        if (is_object_in_taxonomy($post_type, 'post_tag')) {
            $data['tags'] = wp_get_post_tags($post->ID, array('fields' => 'ids'));
        }

        // Add featured media if set
        if (has_post_thumbnail($post->ID)) {
            $data['featured_media'] = get_post_thumbnail_id($post->ID);
        }

        $response = rest_ensure_response($data);
        $response->set_status(201);
        $response->header('Location', get_permalink($post_id));

        // Process SEO metadata if provided
        if (isset($json_data['seo']) && is_array($json_data['seo'])) {
            $this->seo->update_post_seo_metadata($post_id, $json_data['seo']);
        } elseif (isset($json_data['seo_data']) && is_array($json_data['seo_data'])) {
            // For backward compatibility with older versions
            $this->seo->update_post_seo_metadata($post_id, $json_data['seo_data']);
        }

        return $response;
    }

    /**
     * Upload media file
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function upload_media($request)
    {
        // Get uploaded file
        $files = $request->get_file_params();

        // Handle SEObot's incorrect Content-Type uploads
        if (empty($files['file']) && $this->is_seobot_request()) {
            $seobot_files = $this->handle_seobot_raw_upload($request);
            if (!empty($seobot_files['file'])) {
                $_FILES['file'] = $seobot_files['file'];
                $files = $seobot_files;
                error_log('SEObot: Successfully processed raw upload - ' . $seobot_files['file']['name']);
            } else {
                error_log('SEObot: Failed to process raw upload');
            }
        }

        if (empty($files['file'])) {
            return new WP_Error(
                'rest_upload_no_file',
                __('No file was uploaded.', 'seobot-api'),
                array('status' => 400)
            );
        }

        // Get all request parameters
        $params = $request->get_params();

        // These files need to be included as dependencies when on the front end
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        // Let WordPress handle the upload with default security settings
        $attachment_id = media_handle_upload('file', 0);

        if (is_wp_error($attachment_id)) {
            // Include debug info for SEObot uploads
            $error_data = array('status' => 500);
            if ($this->is_seobot_request()) {
                $error_data['debug'] = array(
                    'file_info' => isset($files['file']) ? $files['file'] : 'no file',
                    'error_code' => $attachment_id->get_error_code(),
                    'error_message' => $attachment_id->get_error_message()
                );
            }
            return new WP_Error(
                'rest_upload_error',
                $attachment_id->get_error_message(),
                $error_data
            );
        }

        // Update attachment title, caption, description, etc.
        $attachment_data = array(
            'ID' => $attachment_id,
        );

        if (isset($params['title'])) {
            $attachment_data['post_title'] = $params['title'];
        }

        if (isset($params['caption'])) {
            $attachment_data['post_excerpt'] = $params['caption'];
        }

        if (isset($params['description'])) {
            $attachment_data['post_content'] = $params['description'];
        }

        if (isset($params['alt_text'])) {
            update_post_meta($attachment_id, '_wp_attachment_image_alt', $params['alt_text']);
        }

        if (count($attachment_data) > 1) {
            wp_update_post($attachment_data);
        }

        // Get attachment data
        $attachment = get_post($attachment_id);
        $attachment_url = wp_get_attachment_url($attachment_id);
        $attachment_meta = wp_get_attachment_metadata($attachment_id);

        // Get attachment size information
        $sizes = array();
        if (isset($attachment_meta['sizes'])) {
            foreach ($attachment_meta['sizes'] as $size => $size_data) {
                $image_src = wp_get_attachment_image_src($attachment_id, $size);
                if ($image_src) {
                    $sizes[$size] = array(
                        'url' => $image_src[0],
                        'width' => $image_src[1],
                        'height' => $image_src[2],
                    );
                }
            }
        }

        $response = array(
            'id' => $attachment_id,
            'date' => get_the_date('c', $attachment_id),
            'slug' => $attachment->post_name,
            'type' => get_post_mime_type($attachment_id),
            'link' => get_attachment_link($attachment_id),
            'title' => array(
                'rendered' => $attachment->post_title,
                'raw' => $attachment->post_title,
            ),
            'source_url' => $attachment_url,
            'media_details' => array(
                'width' => isset($attachment_meta['width']) ? $attachment_meta['width'] : 0,
                'height' => isset($attachment_meta['height']) ? $attachment_meta['height'] : 0,
                'file' => isset($attachment_meta['file']) ? $attachment_meta['file'] : '',
                'sizes' => $sizes,
            ),
        );

        return rest_ensure_response($response);
    }

    /**
     * Get media items
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function get_media($request)
    {
        $search = $request->get_param('search');
        $per_page = (int) $request->get_param('per_page');
        $page = (int) $request->get_param('page');

        // Prepare query args
        $args = array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'posts_per_page' => $per_page > 0 ? $per_page : 20,
            'paged' => $page > 0 ? $page : 1,
        );

        // Handle search parameter
        if (!empty($search)) {
            $args['s'] = $search;
        }

        // Run the query
        $query = new WP_Query($args);

        $media_items = array();

        foreach ($query->posts as $attachment) {
            $attachment_url = wp_get_attachment_url($attachment->ID);
            $attachment_meta = wp_get_attachment_metadata($attachment->ID);

            // Get attachment size information
            $sizes = array();
            if (isset($attachment_meta['sizes'])) {
                foreach ($attachment_meta['sizes'] as $size => $size_data) {
                    $image_src = wp_get_attachment_image_src($attachment->ID, $size);
                    if ($image_src) {
                        $sizes[$size] = array(
                            'url' => $image_src[0],
                            'width' => $image_src[1],
                            'height' => $image_src[2],
                        );
                    }
                }
            }

            $media_items[] = array(
                'id' => $attachment->ID,
                'date' => get_the_date('c', $attachment->ID),
                'slug' => $attachment->post_name,
                'type' => get_post_mime_type($attachment->ID),
                'link' => get_attachment_link($attachment->ID),
                'title' => array(
                    'rendered' => $attachment->post_title,
                    'raw' => $attachment->post_title,
                ),
                'source_url' => $attachment_url,
                'media_details' => array(
                    'width' => isset($attachment_meta['width']) ? $attachment_meta['width'] : 0,
                    'height' => isset($attachment_meta['height']) ? $attachment_meta['height'] : 0,
                    'file' => isset($attachment_meta['file']) ? $attachment_meta['file'] : '',
                    'sizes' => $sizes,
                ),
            );
        }

        return rest_ensure_response($media_items);
    }

    /**
     * Check if this is a SEObot request
     */
    private function is_seobot_request() {
        return isset($_SERVER['HTTP_USER_AGENT']) &&
               strpos($_SERVER['HTTP_USER_AGENT'], 'SEObot') !== false;
    }

    /**
     * Handle SEObot's raw uploads with wrong Content-Type
     */
    private function handle_seobot_raw_upload($request) {
        // Check if SEObot sent image/jpeg content type
        if (isset($_SERVER['HTTP_CONTENT_TYPE']) &&
            strpos($_SERVER['HTTP_CONTENT_TYPE'], 'image/jpeg') !== false) {

            // Get raw request body
            $raw_data = file_get_contents('php://input');

            if (!empty($raw_data)) {
                // Extract filename from Content-Disposition header
                $filename = 'seobot-image.jpg';
                if (isset($_SERVER['HTTP_CONTENT_DISPOSITION'])) {
                    if (preg_match('/filename="?([^"]+)"?/', $_SERVER['HTTP_CONTENT_DISPOSITION'], $matches)) {
                        $filename = $matches[1];
                    }
                }

                // Validate that this is actually an image
                $image_info = getimagesizefromstring($raw_data);
                if ($image_info !== false) {
                    // Create temporary file
                    $tmp_name = tempnam(sys_get_temp_dir(), 'seobot_upload_');
                    if (file_put_contents($tmp_name, $raw_data) !== false) {
                        // Return properly formatted $_FILES array
                        return array(
                            'file' => array(
                                'name' => $filename,
                                'type' => $image_info['mime'],
                                'tmp_name' => $tmp_name,
                                'error' => UPLOAD_ERR_OK,
                                'size' => strlen($raw_data)
                            )
                        );
                    }
                }
            }
        }

        return array();
    }
}