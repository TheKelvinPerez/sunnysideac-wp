<?php
/**
 * SEObot API Settings Class
 *
 * @package SEObot API
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Settings class for managing plugin options
 */
class SEObot_API_Settings
{
    /**
     * Settings group name
     */
    const SETTINGS_GROUP = 'seobot_api_settings';

    /**
     * Constructor
     */
    public function __construct()
    {
        // Nothing to do here, initialization happens in external methods
    }

    /**
     * Add admin menu for settings
     */
    public function add_admin_menu()
    {
        add_options_page(
            __('SEObot Settings', 'seobot-api'),
            __('SEObot', 'seobot-api'),
            'manage_options',
            'seobot-settings',
            array($this, 'settings_page')
        );
    }

    /**
     * Register plugin settings
     */
    public function register_settings()
    {
        // Register setting for API key
        register_setting(
            self::SETTINGS_GROUP,
            'seobot_api_key',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default' => '',
            )
        );

        // Register setting for default category
        register_setting(
            self::SETTINGS_GROUP,
            'seobot_api_default_category',
            array(
                'sanitize_callback' => 'absint',
                'default' => 1,
            )
        );

        // Register setting for default author
        register_setting(
            self::SETTINGS_GROUP,
            'seobot_api_default_author',
            array(
                'sanitize_callback' => 'absint',
                'default' => 1,
            )
        );

        // Register setting for default post type
        register_setting(
            self::SETTINGS_GROUP,
            'seobot_api_default_post_type',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default' => 'post',
            )
        );



        // Add hook to flush rewrite rules when settings are saved
        add_action('update_option_' . self::SETTINGS_GROUP, array($this, 'flush_rewrite_rules_flag'));

        // Add settings sections
        add_settings_section(
            'seobot_api_section_api',
            __('API Settings', 'seobot-api'),
            array($this, 'section_api_callback'),
            'seobot-settings'
        );

        add_settings_section(
            'seobot_api_section_defaults',
            __('Default Settings', 'seobot-api'),
            array($this, 'section_defaults_callback'),
            'seobot-settings'
        );

        // Add settings fields
        add_settings_field(
            'seobot_api_key',
            __('API Key', 'seobot-api'),
            array($this, 'api_key_callback'),
            'seobot-settings',
            'seobot_api_section_api'
        );

        add_settings_field(
            'seobot_api_base_url',
            __('API Base URL', 'seobot-api'),
            array($this, 'api_base_url_callback'),
            'seobot-settings',
            'seobot_api_section_api'
        );

        add_settings_field(
            'seobot_api_default_category',
            __('Default Category', 'seobot-api'),
            array($this, 'default_category_callback'),
            'seobot-settings',
            'seobot_api_section_defaults'
        );

        add_settings_field(
            'seobot_api_default_author',
            __('Default Author', 'seobot-api'),
            array($this, 'default_author_callback'),
            'seobot-settings',
            'seobot_api_section_defaults'
        );

        add_settings_field(
            'seobot_api_default_post_type',
            __('Default Post Type', 'seobot-api'),
            array($this, 'default_post_type_callback'),
            'seobot-settings',
            'seobot_api_section_defaults'
        );


    }

    /**
     * Set flag to flush rewrite rules when settings are saved
     */
    public function flush_rewrite_rules_flag()
    {
        update_option('seobot_api_flush_rewrite', true);
    }

    /**
     * Settings page content
     */
    public function settings_page()
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields(self::SETTINGS_GROUP);
                do_settings_sections('seobot-settings');
                submit_button(__('Save Settings', 'seobot-api'));
                ?>
            </form>

            <div class="seobot-api-instructions">
                <h2><?php _e('API Usage Instructions', 'seobot-api'); ?></h2>
                <p><?php _e('Below are examples of how to use the SEObot API endpoints:', 'seobot-api'); ?></p>

                <h3><?php _e('Authentication', 'seobot-api'); ?></h3>
                <p><?php _e('All API requests require authentication using Basic Auth with your API key as the username (password can be anything).', 'seobot-api'); ?>
                </p>
                <pre>Authorization: Basic <?php echo base64_encode(get_option('seobot_api_key') . ':password'); ?></pre>

                <p><?php _e('Alternative: If your server strips the Authorization header, you can use the X-SEObot-Auth header:', 'seobot-api'); ?>
                </p>
                <pre>X-SEObot-Auth: Basic <?php echo base64_encode(get_option('seobot_api_key') . ':password'); ?></pre>

                <h3><?php _e('API Endpoints', 'seobot-api'); ?></h3>
                <p><?php _e('The SEObot API works even when the WordPress REST API is disabled by security plugins.', 'seobot-api'); ?>
                </p>
                <ul>
                    <li><code>GET <?php echo esc_html(site_url('seobot/v1/categories')); ?></code> -
                        <?php _e('List categories', 'seobot-api'); ?>
                    </li>
                    <li><code>GET <?php echo esc_html(site_url('seobot/v1/post')); ?></code> -
                        <?php _e('List posts (default post type)', 'seobot-api'); ?>
                    </li>
                    <li><code>GET <?php echo esc_html(site_url('seobot/v1/post')); ?>?post_type=page</code> -
                        <?php _e('List custom post types (example: pages)', 'seobot-api'); ?>
                    </li>
                    <li><code>POST <?php echo esc_html(site_url('seobot/v1/post')); ?></code> -
                        <?php _e('Create post (default post type)', 'seobot-api'); ?>
                    </li>
                    <li><code>POST <?php echo esc_html(site_url('seobot/v1/post')); ?></code> -
                        <?php _e('Create custom post type (specify post_type in body)', 'seobot-api'); ?>
                    </li>
                    <li><code>PUT <?php echo esc_html(site_url('seobot/v1/post/{id}')); ?></code> -
                        <?php _e('Update post', 'seobot-api'); ?>
                    </li>
                    <li><code>DELETE <?php echo esc_html(site_url('seobot/v1/post/{id}')); ?></code> -
                        <?php _e('Delete post', 'seobot-api'); ?>
                    </li>
                    <li><code>POST <?php echo esc_html(site_url('seobot/v1/media')); ?></code> -
                        <?php _e('Upload media', 'seobot-api'); ?>
                    </li>
                    <li><code>GET <?php echo esc_html(site_url('seobot/v1/media')); ?></code> -
                        <?php _e('List media', 'seobot-api'); ?>
                    </li>
                </ul>

                <h3><?php _e('Custom Post Types Support', 'seobot-api'); ?></h3>
                <p><?php _e('The API supports all public post types. You can specify the post type in the following ways:', 'seobot-api'); ?>
                </p>
                <ul>
                    <li><?php _e('Set a default post type in the settings above', 'seobot-api'); ?></li>
                    <li><?php _e('Add a post_type parameter to GET requests: ', 'seobot-api'); ?><code>?post_type=page</code>
                    </li>
                    <li><?php _e('Include a post_type field in the JSON body for POST/PUT requests', 'seobot-api'); ?></li>
                </ul>
                <p><?php _e('Example POST request body to create a custom post type:', 'seobot-api'); ?></p>
                <pre>{
                                                                                                          "title": "My Custom Post",
                                                                                                          "content": "Content goes here...",
                                                                                                          "post_type": "product",
                                                                                                          "status": "publish"
                                                                                                        }</pre>
            </div>
        </div>
        <?php
    }

    /**
     * API section callback
     */
    public function section_api_callback()
    {
        echo '<p>' . esc_html__('Configure your API settings below.', 'seobot-api') . '</p>';
    }

    /**
     * Defaults section callback
     */
    public function section_defaults_callback()
    {
        echo '<p>' . esc_html__('Set default values for new posts created through the API.', 'seobot-api') . '</p>';
    }

    /**
     * API key field callback
     */
    public function api_key_callback()
    {
        $api_key = get_option('seobot_api_key');

        echo '<input type="text" id="seobot_api_key" name="seobot_api_key" value="' . esc_attr($api_key) . '" class="regular-text" readonly />';
        echo '<p class="description">' . esc_html__('This is your API key for authentication.', 'seobot-api') . '</p>';
        echo '<button type="button" class="button button-secondary" id="seobot-regenerate-key">' . esc_html__('Regenerate API Key', 'seobot-api') . '</button>';

        // Add JavaScript for regenerating the API key
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#seobot-regenerate-key').on('click', function (e) {
                    e.preventDefault();
                    if (confirm('<?php echo esc_js(__('Are you sure you want to regenerate your API key? Any existing integrations will stop working.', 'seobot-api')); ?>')) {
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'seobot_regenerate_api_key',
                                nonce: '<?php echo wp_create_nonce('seobot_regenerate_api_key'); ?>'
                            },
                            success: function (response) {
                                if (response.success) {
                                    $('#seobot_api_key').val(response.data);
                                    alert('<?php echo esc_js(__('API key regenerated successfully.', 'seobot-api')); ?>');
                                } else {
                                    alert('<?php echo esc_js(__('Failed to regenerate API key.', 'seobot-api')); ?>');
                                }
                            }
                        });
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * API base URL field callback
     */
    public function api_base_url_callback()
    {
        $base_url = site_url('seobot/v1');
        echo '<input type="text" value="' . esc_attr($base_url) . '" class="regular-text" readonly />';
        echo '<p class="description">' . esc_html__('This is the base URL for API requests. This API will work even if the WordPress REST API is disabled.', 'seobot-api') . '</p>';
    }

    /**
     * Default category field callback
     */
    public function default_category_callback()
    {
        $default_category = get_option('seobot_api_default_category');
        $categories = get_categories(array('hide_empty' => false));

        echo '<select id="seobot_api_default_category" name="seobot_api_default_category">';
        foreach ($categories as $category) {
            echo '<option value="' . esc_attr($category->term_id) . '" ' . selected($default_category, $category->term_id, false) . '>' . esc_html($category->name) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . esc_html__('Select the default category for new posts created via the API.', 'seobot-api') . '</p>';
    }

    /**
     * Default author field callback
     */
    public function default_author_callback()
    {
        $default_author = get_option('seobot_api_default_author');
        $users = get_users(array('role__in' => array('administrator', 'editor', 'author')));

        echo '<select id="seobot_api_default_author" name="seobot_api_default_author">';
        foreach ($users as $user) {
            echo '<option value="' . esc_attr($user->ID) . '" ' . selected($default_author, $user->ID, false) . '>' . esc_html($user->display_name) . ' (' . esc_html($user->user_login) . ')</option>';
        }
        echo '</select>';
        echo '<p class="description">' . esc_html__('Select the default author for new posts created via the API.', 'seobot-api') . '</p>';
    }

    /**
     * Default post type field callback
     */
    public function default_post_type_callback()
    {
        $default_post_type = get_option('seobot_api_default_post_type', 'post');

        // Get all public post types
        $post_types = get_post_types(array('public' => true), 'objects');

        echo '<select id="seobot_api_default_post_type" name="seobot_api_default_post_type">';
        foreach ($post_types as $post_type) {
            // Skip media attachments as they're handled differently
            if ($post_type->name === 'attachment') {
                continue;
            }
            echo '<option value="' . esc_attr($post_type->name) . '" ' . selected($default_post_type, $post_type->name, false) . '>' . esc_html($post_type->label) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . esc_html__('Select the default post type for new content created via the API.', 'seobot-api') . '</p>';
    }


}