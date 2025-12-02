<?php
/**
 * Review Form AJAX Handler
 *
 * Handles AJAX submission of customer reviews with proper validation,
 * sanitization, and creation of review posts with ACF fields.
 *
 * @package SunnysideAC
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register AJAX handlers for review submission
 */
add_action('wp_ajax_submit_review', 'sunnysideac_handle_review_submission');
add_action('wp_ajax_nopriv_submit_review', 'sunnysideac_handle_review_submission');

/**
 * Main review submission handler
 */
function sunnysideac_handle_review_submission() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'review_submission_nonce')) {
        wp_send_json_error([
            'message' => 'Security token expired. Please refresh the page and try again.'
        ]);
        wp_die();
    }

    // Check honeypot field for spam protection
    if (!empty($_POST['website'])) {
        // This is likely a bot, pretend it was successful but don't actually process
        wp_send_json_success([
            'message' => 'Thank you for your review! Your submission has been received and will be reviewed.'
        ]);
        wp_die();
    }

    // Validate required fields
    $required_fields = ['rating', 'review_content', 'reviewer_name', 'reviewer_email', 'service_id', 'city_id'];
    $missing_fields = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        wp_send_json_error([
            'message' => 'Please fill in all required fields.',
            'missing_fields' => $missing_fields
        ]);
        wp_die();
    }

    // Validate rating
    $rating = intval($_POST['rating']);
    if ($rating < 1 || $rating > 5) {
        wp_send_json_error([
            'message' => 'Please select a valid rating between 1 and 5 stars.'
        ]);
        wp_die();
    }

    // Validate email
    $reviewer_email = sanitize_email($_POST['reviewer_email']);
    if (!is_email($reviewer_email)) {
        wp_send_json_error([
            'message' => 'Please enter a valid email address.'
        ]);
        wp_die();
    }

    // Validate service and city exist
    $service_id = intval($_POST['service_id']);
    $city_id = intval($_POST['city_id']);

    if (!get_post($service_id) || get_post_type($service_id) !== 'service') {
        wp_send_json_error([
            'message' => 'Invalid service selected.'
        ]);
        wp_die();
    }

    if (!get_post($city_id) || get_post_type($city_id) !== 'city') {
        wp_send_json_error([
            'message' => 'Invalid city selected.'
        ]);
        wp_die();
    }

    // Sanitize input data
    $sanitized_data = [
        'reviewer_name'   => sanitize_text_field($_POST['reviewer_name']),
        'reviewer_email'  => $reviewer_email,
        'rating'          => $rating,
        'review_content'  => sanitize_textarea_field($_POST['review_content']),
        'service_id'      => $service_id,
        'city_id'         => $city_id,
        'submission_date' => current_time('mysql')
    ];

    // Create review post
    $post_id = wp_insert_post([
        'post_title'     => sprintf('Review by %s - %s', $sanitized_data['reviewer_name'], current_time('F j, Y')),
        'post_content'   => $sanitized_data['review_content'],
        'post_status'    => 'pending', // Reviews require moderation
        'post_type'      => 'review',
        'meta_input'     => [
            'reviewer_name'       => $sanitized_data['reviewer_name'],
            'reviewer_email'      => $sanitized_data['reviewer_email'],
            'rating'              => $sanitized_data['rating'],
            'service_relationship'=> $sanitized_data['service_id'],
            'city_relationship'   => $sanitized_data['city_id'],
            'review_status'       => 'pending',
            'submission_date'     => $sanitized_data['submission_date']
        ]
    ]);

    if (is_wp_error($post_id)) {
        error_log('Review submission error: ' . $post_id->get_error_message());
        wp_send_json_error([
            'message' => 'Unable to save review. Please try again later.'
        ]);
        wp_die();
    }

    // Log successful submission for debugging
    error_log("Review submitted successfully: Post ID {$post_id} by {$sanitized_data['reviewer_name']} ({$sanitized_data['reviewer_email']})");

    // Send email notifications (Phase 3 enhancement)
    sunnysideac_send_review_notification_emails($post_id, $sanitized_data);

    // Send success response
    wp_send_json_success([
        'message' => 'Thank you for your review! Your submission has been received and will be reviewed within 24-48 hours.',
        'post_id' => $post_id
    ]);

    wp_die();
}

/**
 * Send email notifications for new review submissions
 *
 * @param int   $post_id         The created review post ID
 * @param array $review_data     Sanitized review submission data
 */
function sunnysideac_send_review_notification_emails($post_id, $review_data) {
    // Get service and city post objects
    $service = get_post($review_data['service_id']);
    $city = get_post($review_data['city_id']);

    // Email to admin
    $admin_subject = sprintf(
        'New Customer Review Submitted: %s in %s',
        $service->post_title,
        $city->post_title
    );

    $admin_message = sprintf(
        "A new customer review has been submitted and is awaiting moderation.\n\n" .
        "Review Details:\n" .
        "----------------\n" .
        "Customer: %s (%s)\n" .
        "Rating: %d stars\n" .
        "Service: %s\n" .
        "Location: %s\n" .
        "Submitted: %s\n\n" .
        "Review Content:\n" .
        "%s\n\n" .
        "Review this submission in WordPress admin: %s\n",
        $review_data['reviewer_name'],
        $review_data['reviewer_email'],
        $review_data['rating'],
        $service->post_title,
        $city->post_title,
        $review_data['submission_date'],
        $review_data['review_content'],
        admin_url('post.php?post=' . $post_id . '&action=edit')
    );

    wp_mail(get_option('admin_email'), $admin_subject, $admin_message);

    // Email to customer (confirmation)
    $customer_subject = 'Thank You for Your Review - Sunnyside AC';

    $customer_message = sprintf(
        "Dear %s,\n\n" .
        "Thank you for taking the time to share your experience with Sunnyside AC. " .
        "We appreciate your feedback about our %s service in %s.\n\n" .
        "Your review has been submitted and will be reviewed by our team within 24-48 hours. " .
        "Once approved, it will be displayed on our website to help other customers make informed decisions.\n\n" .
        "We're committed to providing excellent HVAC service throughout South Florida and " .
        "your review helps us maintain our high standards.\n\n" .
        "If you have any immediate questions or need assistance, please don't hesitate to contact us.\n\n" .
        "Best regards,\n" .
        "The Sunnyside AC Team\n" .
        "Phone: %s\n" .
        "Web: %s\n",
        $review_data['reviewer_name'],
        $service->post_title,
        $city->post_title,
        SUNNYSIDE_PHONE_DISPLAY,
        home_url('/')
    );

    wp_mail($review_data['reviewer_email'], $customer_subject, $customer_message);
}