<?php
/**
 * Warranty Claim Form Handler
 *
 * Handles AJAX submission of warranty claim form
 */

/**
 * Handle warranty claim form submission via AJAX
 */
function sunnysideac_handle_warranty_claim_form() {
	// Verify nonce for security
	if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'warranty_claim_form_nonce' ) ) {
		wp_send_json_error( array( 'message' => 'Security verification failed.' ) );
	}

	// Sanitize and validate form data
	$first_name        = sanitize_text_field( $_POST['claim_first_name'] ?? '' );
	$last_name         = sanitize_text_field( $_POST['claim_last_name'] ?? '' );
	$email             = sanitize_email( $_POST['claim_email'] ?? '' );
	$phone             = sanitize_text_field( $_POST['claim_phone'] ?? '' );
	$address           = sanitize_text_field( $_POST['claim_address'] ?? '' );
	$equipment_type    = sanitize_text_field( $_POST['equipment_type'] ?? '' );
	$equipment_brand   = sanitize_text_field( $_POST['equipment_brand'] ?? '' );
	$install_date      = sanitize_text_field( $_POST['install_date'] ?? '' );
	$warranty_type     = sanitize_text_field( $_POST['warranty_type'] ?? '' );
	$issue_description = sanitize_textarea_field( $_POST['issue_description'] ?? '' );
	$urgent_service    = sanitize_text_field( $_POST['urgent_service'] ?? '' );

	// Validate required fields
	$required_fields = array( $first_name, $last_name, $email, $phone, $address, $equipment_type, $equipment_brand, $install_date, $warranty_type, $issue_description );
	foreach ( $required_fields as $field ) {
		if ( empty( $field ) ) {
			wp_send_json_error( array( 'message' => 'Please fill in all required fields.' ) );
		}
	}

	// Validate email format
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => 'Please enter a valid email address.' ) );
	}

	// Handle file upload
	$document_content = '';
	if ( isset( $_FILES['warranty_documents'] ) && $_FILES['warranty_documents']['error'] === UPLOAD_ERR_OK ) {
		$file = $_FILES['warranty_documents'];

		// Validate file type
		$allowed_types = array( 'application/pdf', 'image/jpeg', 'image/jpg', 'image/png' );
		if ( ! in_array( $file['type'], $allowed_types ) ) {
			wp_send_json_error( array( 'message' => 'Documents must be PDF, JPG, or PNG files.' ) );
		}

		// Validate file size (5MB max)
		if ( $file['size'] > 5 * 1024 * 1024 ) {
			wp_send_json_error( array( 'message' => 'Document file must be smaller than 5MB.' ) );
		}

		// Read file content
		$document_content = file_get_contents( $file['tmp_name'] );
		if ( $document_content === false ) {
			wp_send_json_error( array( 'message' => 'Error reading document file.' ) );
		}
	}

	// Create email content
	$to_email = SUNNYSIDE_EMAIL_ADDRESS;
	$subject  = 'Warranty Claim Request - ' . $first_name . ' ' . $last_name;
	$headers  = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . $first_name . ' ' . $last_name . ' <' . $email . '>' );

	$priority_text = $urgent_service === 'yes' ? 'URGENT - ' : '';
	$subject       = $priority_text . $subject;

	$email_body = '
		<h2>Warranty Claim Request</h2>
		<p><strong>Priority:</strong> ' . ( $urgent_service === 'yes' ? 'URGENT - Immediate attention required' : 'Normal' ) . '</p>
		<p><strong>Name:</strong> ' . esc_html( $first_name ) . ' ' . esc_html( $last_name ) . '</p>
		<p><strong>Email:</strong> ' . esc_html( $email ) . '</p>
		<p><strong>Phone:</strong> ' . esc_html( $phone ) . '</p>
		<p><strong>Service Address:</strong> ' . esc_html( $address ) . '</p>
		<hr>
		<h4>Equipment Information</h4>
		<p><strong>Equipment Type:</strong> ' . esc_html( $equipment_type ) . '</p>
		<p><strong>Equipment Brand:</strong> ' . esc_html( $equipment_brand ) . '</p>
		<p><strong>Installation Date:</strong> ' . esc_html( $install_date ) . '</p>
		<p><strong>Warranty Type:</strong> ' . esc_html( $warranty_type ) . '</p>
		<hr>
		<h4>Issue Description</h4>
		<p>' . nl2br( esc_html( $issue_description ) ) . '</p>';

	if ( ! empty( $document_content ) ) {
		$uploads     = wp_upload_dir();
		$filename    = sanitize_file_name( 'warranty_docs_' . $first_name . '_' . $last_name . '_' . time() . '.' . pathinfo( $_FILES['warranty_documents']['name'], PATHINFO_EXTENSION ) );
		$upload_file = $uploads['path'] . '/' . $filename;

		// Save the file
		if ( file_put_contents( $upload_file, $document_content ) !== false ) {
			$email_body .= '<p><strong>Documents:</strong> Attached (saved as: ' . esc_html( $filename ) . ')</p>';
		}
	}

	$email_body .= '
		<hr>
		<p><strong>Submitted:</strong> ' . date( 'F j, Y, g:i a' ) . '</p>
		<p><em>This warranty claim was submitted via the Sunnyside AC website.</em></p>';

	// Send email to company
	$company_email_sent = wp_mail( $to_email, $subject, $email_body, $headers );

	// Send confirmation email to customer
	if ( $company_email_sent ) {
		$confirmation_subject = 'We Received Your Warranty Claim - Sunnyside AC';
		$confirmation_body    = '
			<h2>Warranty Claim Received</h2>
			<p>Dear ' . esc_html( $first_name ) . ' ' . esc_html( $last_name ) . ',</p>
			<p>We have successfully received your warranty claim request for your ' . esc_html( $equipment_type ) . '.</p>
			<p>Our warranty team will review your claim and contact you within 24 hours to:</p>
			<ul>
				<li>Schedule a diagnostic visit if needed</li>
				<li>Review your warranty coverage</li>
				<li>Process any necessary parts orders</li>
				<li>Provide next steps and timeline</li>
			</ul>';

		if ( $urgent_service === 'yes' ) {
			$confirmation_body .= '<p><strong>Since you marked this as urgent, we will prioritize your claim and contact you as soon as possible.</strong></p>';
		}

		$confirmation_body .= "
			<p>If you need immediate assistance, please call us at:</p>
			<ul>
				<li>Phone: <a href='tel:" . esc_attr( SUNNYSIDE_TEL_HREF ) . "'>" . esc_html( SUNNYSIDE_PHONE_DISPLAY ) . '</a></li>
			</ul>
			<p>Best regards,<br>The Sunnyside AC Warranty Team</p>';

		wp_mail( $email, $confirmation_subject, $confirmation_body, array( 'Content-Type: text/html; charset=UTF-8' ) );
	}

	if ( $company_email_sent ) {
		wp_send_json_success( array( 'message' => 'Warranty claim submitted successfully!' ) );
	} else {
		wp_send_json_error( array( 'message' => 'Error submitting warranty claim. Please try again.' ) );
	}
}
add_action( 'wp_ajax_sunnysideac_handle_warranty_claim_form', 'sunnysideac_handle_warranty_claim_form' );
add_action( 'wp_ajax_nopriv_sunnysideac_handle_warranty_claim_form', 'sunnysideac_handle_warranty_claim_form' );

/**
 * Add nonce field to warranty claim form
 */
function sunnysideac_add_warranty_claim_form_nonce() {
	?>
	<input type="hidden" name="action" value="sunnysideac_handle_warranty_claim_form">
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'warranty_claim_form_nonce' ); ?>">
	<?php
}
add_action( 'wp_footer', 'sunnysideac_add_warranty_claim_form_nonce' );