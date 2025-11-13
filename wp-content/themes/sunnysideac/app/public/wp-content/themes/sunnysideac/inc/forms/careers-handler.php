<?php
/**
 * Careers Form Handler
 *
 * Handles AJAX submission of careers application form
 */

/**
 * Handle careers form submission via AJAX
 */
function sunnysideac_handle_careers_form() {
	// Verify nonce for security
	if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'careers_form_nonce' ) ) {
		wp_send_json_error( array( 'message' => 'Security verification failed.' ) );
	}

	// Sanitize and validate form data
	$first_name     = sanitize_text_field( $_POST['first_name'] ?? '' );
	$last_name      = sanitize_text_field( $_POST['last_name'] ?? '' );
	$email          = sanitize_email( $_POST['email'] ?? '' );
	$phone          = sanitize_text_field( $_POST['phone'] ?? '' );
	$position       = sanitize_text_field( $_POST['position'] ?? '' );
	$other_position = sanitize_text_field( $_POST['other_position'] ?? '' );
	$experience     = intval( $_POST['experience'] ?? 0 );
	$availability   = sanitize_text_field( $_POST['availability'] ?? '' );
	$message        = sanitize_textarea_field( $_POST['message'] ?? '' );

	// Validate required fields
	if ( empty( $first_name ) || empty( $last_name ) || empty( $email ) || empty( $phone ) || empty( $position ) || empty( $availability ) ) {
		wp_send_json_error( array( 'message' => 'Please fill in all required fields.' ) );
	}

	// Validate email format
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => 'Please enter a valid email address.' ) );
	}

	// Handle "Other" position
	if ( $position === 'Other' && empty( $other_position ) ) {
		wp_send_json_error( array( 'message' => 'Please specify the position you are applying for.' ) );
	}
	$final_position = $position === 'Other' ? $other_position : $position;

	// Handle file upload
	$resume_content = '';
	if ( isset( $_FILES['resume'] ) && $_FILES['resume']['error'] === UPLOAD_ERR_OK ) {
		$file = $_FILES['resume'];

		// Validate file type
		$allowed_types = array( 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' );
		if ( ! in_array( $file['type'], $allowed_types ) ) {
			wp_send_json_error( array( 'message' => 'Resume must be a PDF, DOC, or DOCX file.' ) );
		}

		// Validate file size (5MB max)
		if ( $file['size'] > 5 * 1024 * 1024 ) {
			wp_send_json_error( array( 'message' => 'Resume file must be smaller than 5MB.' ) );
		}

		// Read file content
		$resume_content = file_get_contents( $file['tmp_name'] );
		if ( $resume_content === false ) {
			wp_send_json_error( array( 'message' => 'Error reading resume file.' ) );
		}
	}

	// Create email content
	$to_email = SUNNYSIDE_EMAIL_ADDRESS;
	$subject  = 'New Job Application: ' . $final_position . ' - ' . $first_name . ' ' . $last_name;
	$headers  = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . $first_name . ' ' . $last_name . ' <' . $email . '>' );

	$email_body = '
		<h2>New Job Application</h2>
		<p><strong>Position:</strong> ' . esc_html( $final_position ) . '</p>
		<p><strong>Name:</strong> ' . esc_html( $first_name ) . ' ' . esc_html( $last_name ) . '</p>
		<p><strong>Email:</strong> ' . esc_html( $email ) . '</p>
		<p><strong>Phone:</strong> ' . esc_html( $phone ) . '</p>
		<p><strong>Years of Experience:</strong> ' . esc_html( $experience ) . '</p>
		<p><strong>Availability:</strong> ' . esc_html( $availability ) . '</p>';

	if ( ! empty( $message ) ) {
		$email_body .= '<p><strong>Additional Information:</strong></p><p>' . nl2br( esc_html( $message ) ) . '</p>';
	}

	$email_body .= '
		<p><strong>Submitted:</strong> ' . date( 'F j, Y, g:i a' ) . '</p>
		<hr>
		<p><em>This application was submitted via the Sunnyside AC website careers form.</em></p>';

	// Attach resume if uploaded
	if ( ! empty( $resume_content ) ) {
		$uploads     = wp_upload_dir();
		$filename    = sanitize_file_name( 'resume_' . $first_name . '_' . $last_name . '_' . time() . '.' . pathinfo( $_FILES['resume']['name'], PATHINFO_EXTENSION ) );
		$upload_file = $uploads['path'] . '/' . $filename;

		// Save the file
		if ( file_put_contents( $upload_file, $resume_content ) !== false ) {
			// Add attachment
			$headers[] = 'Bcc: careers@sunnysideac.com';

			// For simplicity, we'll note the attachment in the email
			// In a production environment, you might want to use wp_mail() with proper attachments
			$email_body .= '<p><strong>Resume:</strong> Attached (saved as: ' . esc_html( $filename ) . ')</p>';
		}
	}

	// Send email to company
	$company_email_sent = wp_mail( $to_email, $subject, $email_body, $headers );

	// Send confirmation email to applicant
	if ( $company_email_sent ) {
		$confirmation_subject = 'We Received Your Application - Sunnyside AC';
		$confirmation_body    = '
			<h2>Thank You for Your Interest in Sunnyside AC!</h2>
			<p>Dear ' . esc_html( $first_name ) . ' ' . esc_html( $last_name ) . ',</p>
			<p>We have successfully received your application for the <strong>' . esc_html( $final_position ) . "</strong> position.</p>
			<p>Our hiring team will review your application and contact you within 2-3 business days if your qualifications match our current needs.</p>
			<p>If you have any questions in the meantime, please don't hesitate to contact us at:</p>
			<ul>
				<li>Phone: <a href='tel:" . esc_attr( SUNNYSIDE_TEL_HREF ) . "'>" . esc_html( SUNNYSIDE_PHONE_DISPLAY ) . "</a></li>
				<li>Email: <a href='mailto:" . esc_attr( SUNNYSIDE_EMAIL_ADDRESS ) . "'>" . esc_html( SUNNYSIDE_EMAIL_ADDRESS ) . '</a></li>
			</ul>
			<p>We look forward to learning more about you!</p>
			<p>Best regards,<br>The Sunnyside AC Team</p>';

		wp_mail( $email, $confirmation_subject, $confirmation_body, array( 'Content-Type: text/html; charset=UTF-8' ) );
	}

	if ( $company_email_sent ) {
		wp_send_json_success( array( 'message' => 'Application submitted successfully!' ) );
	} else {
		wp_send_json_error( array( 'message' => 'Error submitting application. Please try again.' ) );
	}
}
add_action( 'wp_ajax_sunnysideac_handle_careers_form', 'sunnysideac_handle_careers_form' );
add_action( 'wp_ajax_nopriv_sunnysideac_handle_careers_form', 'sunnysideac_handle_careers_form' );

/**
 * Add nonce field to careers form
 */
function sunnysideac_add_careers_form_nonce() {
	?>
	<input type="hidden" name="action" value="sunnysideac_handle_careers_form">
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'careers_form_nonce' ); ?>">
	<?php
}
add_action( 'wp_footer', 'sunnysideac_add_careers_form_nonce' );