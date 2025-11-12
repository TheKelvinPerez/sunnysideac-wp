<?php
/**
 * AJAX Pagination for Cities Archive
 */

add_action('wp_ajax_cities_pagination', 'sunnysideac_cities_pagination_handler');
add_action('wp_ajax_nopriv_cities_pagination', 'sunnysideac_cities_pagination_handler');

function sunnysideac_cities_pagination_handler() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'cities_pagination_nonce')) {
        wp_die('Security check failed');
    }

    // Get current page
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

    // Setup city query
    $args = array(
        'post_type'      => 'city',
        'post_status'    => 'publish',
        'posts_per_page' => get_option('posts_per_page', 12),
        'paged'          => $page,
    );

    $cities_query = new WP_Query($args);

    ob_start();

    if ($cities_query->have_posts()) :
        while ($cities_query->have_posts()) : $cities_query->the_post();
            get_template_part(
                'template-parts/city-card',
                null,
                array(
                    'city_name' => get_the_title(),
                    'city_slug' => get_post_field('post_name', get_the_ID()),
                    'city_url'  => get_permalink(),
                    'card_size' => 'archive',
                )
            );
        endwhile;
        wp_reset_postdata();
    else :
        echo '<div class="col-span-full text-center py-12">';
        echo '<p class="text-xl text-gray-600">No service cities found.</p>';
        echo '</div>';
    endif;

    $html = ob_get_clean();

    // Generate pagination HTML
    $pagination_args = array(
        'current'   => $page,
        'total'     => $cities_query->max_num_pages,
        'mid_size'  => 2,
        'prev_text' => '←',
        'next_text' => '→',
        'type'      => 'array',
    );

    $pagination_links = paginate_links($pagination_args);
    $pagination_html = '';

    if ($pagination_links) {
        $pagination_html .= '<div class="flex justify-center gap-2">';
        foreach ($pagination_links as $link) {
            // Convert to AJAX pagination links
            $link_class = 'inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 text-gray-700 hover:bg-orange-50 hover:border-orange-300 hover:text-orange-600 transition-colors';

            // Check if current page
            if (strpos($link, 'current') !== false) {
                $link_class = 'inline-flex items-center justify-center w-10 h-10 rounded-lg bg-orange-500 text-white border border-orange-500';
            }

            // Extract page number
            if (preg_match('/page\/(\d+)/', $link, $matches)) {
                $page_num = $matches[1];
            } elseif (strpos($link, 'current') !== false) {
                $page_num = $page;
            } else {
                $page_num = 1;
            }

            $pagination_html .= '<a href="#" class="' . esc_attr($link_class) . '" onclick="loadCitiesPage(' . $page_num . '); return false;">' . strip_tags($link) . '</a>';
        }
        $pagination_html .= '</div>';
    }

    wp_send_json_success(array(
        'html'       => $html,
        'pagination' => $pagination_html,
    ));

    wp_die();
}