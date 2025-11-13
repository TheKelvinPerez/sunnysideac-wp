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
}