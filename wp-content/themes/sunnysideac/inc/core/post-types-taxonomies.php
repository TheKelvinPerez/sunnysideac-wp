<?php
/**
 * Custom Post Types and Taxonomies
 *
 * Registration of Cities, Services, Brands, and Service Categories
 */

// Register "City" post type
function register_city_cpt() {
	register_post_type(
		'city',
		array(
			'labels'       => array(
				'name'          => 'Cities',
				'singular_name' => 'City',
				'add_new_item'  => 'Add New City',
				'edit_item'     => 'Edit City',
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array(
				'slug'       => 'cities',
				'with_front' => false,
			),
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon'    => 'dashicons-location-alt',
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'register_city_cpt' );

// Register "Service" post type
function register_service_cpt() {
	register_post_type(
		'service',
		array(
			'labels'       => array(
				'name'          => 'Services',
				'singular_name' => 'Service',
				'add_new_item'  => 'Add New Service',
				'edit_item'     => 'Edit Service',
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array(
				'slug'       => 'services',
				'with_front' => false,
			),
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon'    => 'dashicons-hammer',
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'register_service_cpt' );

// Register "Brand" post type
function register_brand_cpt() {
	register_post_type(
		'brand',
		array(
			'labels'       => array(
				'name'          => 'Brands',
				'singular_name' => 'Brand',
				'add_new_item'  => 'Add New Brand',
				'edit_item'     => 'Edit Brand',
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'brands' ),
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon'    => 'dashicons-awards',
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'register_brand_cpt' );

// Register "Review" post type
function register_review_cpt() {
	register_post_type(
		'review',
		array(
			'labels'       => array(
				'name'          => 'Customer Reviews',
				'singular_name' => 'Customer Review',
				'menu_name'     => 'Reviews',
				'add_new'       => 'Add New Review',
				'add_new_item'  => 'Add New Review',
				'edit_item'     => 'Edit Review',
				'new_item'      => 'New Review',
				'view_item'     => 'View Review',
				'search_items'  => 'Search Reviews',
				'not_found'     => 'No reviews found',
				'not_found_in_trash' => 'No reviews found in trash',
				'all_items'     => 'All Reviews',
				'archives'      => 'Review Archives',
			),
			'public'       => true,
			'has_archive'  => true,
			'publicly_queryable' => true,
			'show_ui'      => true,
			'show_in_menu' => true,
			'query_var'    => true,
			'rewrite'      => array(
				'slug'       => 'review',
				'with_front' => false,
			),
			'capability_type' => 'post',
			'hierarchical'    => false,
			'menu_position'   => 25,
			'menu_icon'       => 'dashicons-star-filled',
			'supports'        => array( 'title', 'editor', 'custom-fields' ),
			'show_in_rest'    => true,
		)
	);
}
add_action( 'init', 'register_review_cpt' );

// Register Service Category taxonomy
function register_service_category_taxonomy() {
	register_taxonomy(
		'service_category',
		array( 'service' ),
		array(
			'labels'            => array(
				'name'          => 'Service Categories',
				'singular_name' => 'Service Category',
				'search_items'  => 'Search Service Categoreis',
				'all_items'     => 'All Service Categories',
				'parent_item'   => 'Parent Service Category:',
				'edit_item'     => 'Edit Service Category',
				'update_item'   => 'Update Service Category',
				'add_new_item'  => 'Add New Service Category',
				'new_item_name' => 'New Service Category Name',
				'menu_name'     => 'Categories',
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'       => 'service-category',
				'with_front' => false,
			),
			'show_in_rest'      => true,
		)
	);
}
add_action( 'init', 'register_service_category_taxonomy' );

/**
 * =========================================================================
 * CITY ↔ SERVICE CPT RELATIONSHIP (Meta-based, no taxonomy)
 * Using ACF Pro for field management
 * =========================================================================
 */

/**
 * Register ACF Field Group for Service → City relationship
 */
if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group(
		array(
			'key'                   => 'group_service_city_relationship',
			'title'                 => 'City Assignment',
			'fields'                => array(
				array(
					'key'           => 'field_service_city_id',
					'label'         => 'Cities',
					'name'          => '_service_city_id',
					'type'          => 'post_object',
					'instructions'  => 'Select the cities where this service is offered.',
					'required'      => 0,
					'post_type'     => array(
						0 => 'city',
					),
					'taxonomy'      => '',
					'allow_null'    => 1,
					'multiple'      => 1,
					'return_format' => 'id',
					'ui'            => 1,
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'service',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		)
	);

	/**
	 * City CPT - Local SEO Content Fields
	 */
	acf_add_local_field_group(
		array(
			'key'      => 'group_city_seo_content',
			'title'    => 'Local SEO Content',
			'fields'   => array(
				array(
					'key'          => 'field_city_neighborhoods',
					'label'        => 'Neighborhoods & Areas Served',
					'name'         => 'neighborhoods',
					'type'         => 'textarea',
					'instructions' => 'List specific neighborhoods, zip codes, or areas. This makes content unique per city.',
					'placeholder'  => 'Example: Downtown Miami, Brickell, Coral Way, Little Havana, Coconut Grove',
				),
				array(
					'key'          => 'field_city_climate_note',
					'label'        => 'Climate/Weather Note',
					'name'         => 'climate_note',
					'type'         => 'textarea',
					'instructions' => 'Local climate considerations for HVAC services.',
					'placeholder'  => 'Example: Miami\'s humid subtropical climate means AC systems work harder year-round...',
				),
				array(
					'key'          => 'field_city_service_area_note',
					'label'        => 'Service Area Details',
					'name'         => 'service_area_note',
					'type'         => 'wysiwyg',
					'instructions' => 'Additional details about serving this city (response time, local permits, etc.)',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				),
				array(
					'key'          => 'field_city_video_url',
					'label'        => 'City Video URL',
					'name'         => 'city_video_url',
					'type'         => 'url',
					'instructions' => 'YouTube or Vimeo URL for city-specific video (e.g., "HVAC Services in Miami"). HIGHLY recommended for local SEO!',
					'placeholder'  => 'https://www.youtube.com/watch?v=...',
				),
				array(
					'key'               => 'field_city_video_title',
					'label'             => 'Video Title',
					'name'              => 'city_video_title',
					'type'              => 'text',
					'instructions'      => 'Title of the video (for schema markup). Example: "HVAC Services in Miami, Florida"',
					'placeholder'       => 'HVAC Services in Miami, Florida',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_city_video_url',
								'operator' => '!=empty',
							),
						),
					),
				),
				array(
					'key'               => 'field_city_video_description',
					'label'             => 'Video Description',
					'name'              => 'city_video_description',
					'type'              => 'textarea',
					'instructions'      => 'Brief description of the video content (for schema markup)',
					'placeholder'       => 'Watch our HVAC technicians servicing homes and businesses throughout Miami...',
					'rows'              => 3,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_city_video_url',
								'operator' => '!=empty',
							),
						),
					),
				),
				array(
					'key'               => 'field_city_video_thumbnail',
					'label'             => 'Video Thumbnail (Optional)',
					'name'              => 'city_video_thumbnail',
					'type'              => 'image',
					'instructions'      => 'Custom thumbnail image (1280x720). If empty, will use video platform\'s default thumbnail.',
					'return_format'     => 'url',
					'preview_size'      => 'medium',
					'library'           => 'all',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_city_video_url',
								'operator' => '!=empty',
							),
						),
					),
				),
				array(
					'key'               => 'field_city_video_duration',
					'label'             => 'Video Duration',
					'name'              => 'city_video_duration',
					'type'              => 'text',
					'instructions'      => 'Duration in ISO 8601 format (e.g., PT2M30S for 2 minutes 30 seconds). Used for schema markup.',
					'placeholder'       => 'PT2M30S',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_city_video_url',
								'operator' => '!=empty',
							),
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'city',
					),
				),
			),
			'position' => 'normal',
		)
	);

	/**
	 * Service CPT - SEO Content Blocks
	 */
	acf_add_local_field_group(
		array(
			'key'      => 'group_service_seo_content',
			'title'    => 'Service Content Blocks',
			'fields'   => array(
				array(
					'key'          => 'field_service_description',
					'label'        => 'Service Description',
					'name'         => 'service_description',
					'type'         => 'wysiwyg',
					'instructions' => 'Main service description (applies to all cities)',
					'toolbar'      => 'full',
					'media_upload' => 1,
				),
				array(
					'key'          => 'field_service_benefits',
					'label'        => 'Key Benefits',
					'name'         => 'service_benefits',
					'type'         => 'repeater',
					'instructions' => 'List of benefits for this service',
					'layout'       => 'table',
					'button_label' => 'Add Benefit',
					'sub_fields'   => array(
						array(
							'key'   => 'field_benefit_text',
							'label' => 'Benefit',
							'name'  => 'benefit',
							'type'  => 'text',
						),
					),
				),
				array(
					'key'          => 'field_service_process',
					'label'        => 'Service Process/Steps',
					'name'         => 'service_process',
					'type'         => 'repeater',
					'instructions' => 'Step-by-step process',
					'layout'       => 'row',
					'button_label' => 'Add Step',
					'sub_fields'   => array(
						array(
							'key'   => 'field_process_step_title',
							'label' => 'Step Title',
							'name'  => 'title',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_process_step_description',
							'label' => 'Description',
							'name'  => 'description',
							'type'  => 'textarea',
							'rows'  => 3,
						),
					),
				),
				array(
					'key'          => 'field_service_faqs',
					'label'        => 'FAQs',
					'name'         => 'service_faqs',
					'type'         => 'repeater',
					'instructions' => 'Frequently asked questions about this service',
					'layout'       => 'row',
					'button_label' => 'Add FAQ',
					'sub_fields'   => array(
						array(
							'key'   => 'field_faq_question',
							'label' => 'Question',
							'name'  => 'question',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_faq_answer',
							'label' => 'Answer',
							'name'  => 'answer',
							'type'  => 'textarea',
							'rows'  => 4,
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'service',
					),
				),
			),
			'position' => 'normal',
		)
	);

	/**
	 * Review CPT - Review Details Field Group
	 */
	acf_add_local_field_group(
		array(
			'key'      => 'group_review_details',
			'title'    => 'Review Details',
			'fields'   => array(
				// Reviewer Name
				array(
					'key'          => 'field_reviewer_name',
					'label'        => 'Reviewer Name',
					'name'         => 'reviewer_name',
					'type'         => 'text',
					'instructions' => 'Enter the customer\'s full name.',
					'required'     => 1,
					'placeholder'  => 'John Smith',
					'maxlength'    => 100,
				),
				// Reviewer Email
				array(
					'key'          => 'field_reviewer_email',
					'label'        => 'Reviewer Email',
					'name'         => 'reviewer_email',
					'type'         => 'email',
					'instructions' => 'Enter the customer\'s email address.',
					'required'     => 1,
					'placeholder'  => 'john@example.com',
				),
				// Rating
				array(
					'key'          => 'field_rating',
					'label'        => 'Star Rating',
					'name'         => 'rating',
					'type'         => 'number',
					'instructions' => 'Select rating from 1-5 stars.',
					'required'     => 1,
					'min'          => 1,
					'max'          => 5,
					'step'         => 1,
					'placeholder'  => '5',
					'default_value' => 5,
				),
				// Service Relationship
				array(
					'key'          => 'field_service_relationship',
					'label'        => 'Service Used',
					'name'         => 'service_relationship',
					'type'         => 'post_object',
					'instructions' => 'Select the HVAC service that was provided.',
					'required'     => 1,
					'post_type'    => array(
						0 => 'service',
					),
					'taxonomy'     => '',
					'allow_null'   => 0,
					'multiple'     => 0,
					'return_format' => 'object',
					'ui'           => 1,
				),
				// City Relationship
				array(
					'key'          => 'field_city_relationship',
					'label'        => 'Service Location',
					'name'         => 'city_relationship',
					'type'         => 'post_object',
					'instructions' => 'Select the city where service was provided.',
					'required'     => 1,
					'post_type'    => array(
						0 => 'city',
					),
					'taxonomy'     => '',
					'allow_null'   => 0,
					'multiple'     => 0,
					'return_format' => 'object',
					'ui'           => 1,
				),
				// Review Status
				array(
					'key'          => 'field_review_status',
					'label'        => 'Review Status',
					'name'         => 'review_status',
					'type'         => 'select',
					'instructions' => 'Set the review status for display on frontend.',
					'required'     => 1,
					'choices'      => array(
						'pending' => 'Pending Approval',
						'approved' => 'Approved',
						'rejected' => 'Rejected',
					),
					'default_value' => 'pending',
					'allow_null'   => 0,
					'multiple'     => 0,
					'ui'           => 0,
					'ajax'         => 0,
					'placeholder'  => '',
				),
				// Submission Date
				array(
					'key'          => 'field_submission_date',
					'label'        => 'Submission Date',
					'name'         => 'submission_date',
					'type'         => 'date_time_picker',
					'instructions' => 'Date when the review was submitted.',
					'required'     => 0,
					'display_format' => 'Y-m-d H:i:s',
					'return_format'   => 'Y-m-d H:i:s',
					'first_day'       => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'review',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => 'Review details for customer testimonials including rating, service information, and reviewer contact details.',
		)
	);
}