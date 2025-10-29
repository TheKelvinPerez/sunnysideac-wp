<?php
/**
 * Navigation Functions and Custom Walker
 *
 * Handles WordPress menu integration with custom mega menu dropdowns
 */

/**
 * Custom Walker for Main Navigation
 * Handles mega menu dropdowns for Services and Areas
 * Uses constants from inc/constants.php to build dropdown content
 */
class Sunnyside_Nav_Walker extends Walker_Nav_Menu {

	private $parent_title = '';
	private $is_mega_menu = false;

	/**
	 * Start Level - outputs the dropdown container
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			// Check if parent is Services, Cities, or Brands by checking the walker's stored parent title
			if ( $this->is_mega_menu ) {
				$menu_type = $this->parent_title;

				if ( $menu_type === 'services' ) {
					$dropdown_class = 'services-dropdown';
				} elseif ( $menu_type === 'cities' ) {
					$dropdown_class = 'service-areas-dropdown';
				} else {
					$dropdown_class = 'brands-dropdown';
				}

				// Output mega menu container
				$output .= '<div class="fixed top-[210px] left-1/2 -translate-x-1/2 z-[9999] w-[900px] max-w-[95vw] rounded-[20px] border-2 border-[#e6d4b8] bg-white shadow-[0_8px_25px_rgba(0,0,0,0.15)] overflow-hidden hidden ' . $dropdown_class . '">';

				// Header
				if ( $menu_type === 'services' ) {
					$output .= '<div class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4">';
					$output .= '<div class="text-2xl font-bold text-white " role="heading" aria-level="4">Our Services</div>';
					$output .= '<p class="text-sm text-white/90 mt-1 font-normal ">Professional HVAC Solutions for Your Comfort</p>';
					$output .= '</div>';
					$output .= '<div class="p-6">';
					$output .= '<div class="grid grid-cols-3 gap-6 mb-6">';

					// Output services from constants grouped by category
					$this->output_services_mega_menu( $output );

				} elseif ( $menu_type === 'cities' ) {
					$output .= '<div class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4">';
					$output .= '<div class="flex items-center justify-between">';
					$output .= '<div>';
					$output .= '<div class="text-2xl font-bold text-white " role="heading" aria-level="4">Cities We Serve</div>';
					$output .= '<p class="text-sm text-white/90 mt-1 font-normal ">Proudly Serving South Florida</p>';
					$output .= '</div>';
					$output .= '<div class="text-white/80">';
					$output .= '<svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24">';
					$output .= '<path d="M19 12h-2V9h-2V6h-2V4h-2V2h-2v2H7v2H5v2H3v2H1v2h2v2h2v2h2v2h2v2h2v2h2v-2h2v-2h2v-2h2v-2h2v-2h2V12zm-4 4h-2v2h-2v-2h-2v-2H7v-2h2v-2h2V8h2v2h2v2h2v2h2v2z"/>';
					$output .= '</svg>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '<div class="p-6">';
					$output .= '<div class="grid grid-cols-4 gap-2 mb-6">';

					// Output areas from constants
					$this->output_areas_mega_menu( $output );
				} else {
					// Brands mega menu
					$output .= '<div class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4">';
					$output .= '<div class="flex items-center justify-between">';
					$output .= '<div>';
					$output .= '<div class="text-2xl font-bold text-white " role="heading" aria-level="4">Brands We Service</div>';
					$output .= '<p class="text-sm text-white/90 mt-1 font-normal ">Expert Service for Top HVAC Brands</p>';
					$output .= '</div>';
					$output .= '<div class="text-white/80">';
					$output .= '<svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
					$output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />';
					$output .= '</svg>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '<div class="p-6">';
					$output .= '<div class="grid grid-cols-3 gap-6 mb-6">';

					// Output brands from constants
					$this->output_brands_mega_menu( $output );
				}

				// Don't output anything else - we'll handle the closing in end_lvl
			} else {
				// Regular dropdown (not used in current design, but keep for compatibility)
				$output .= '<ul class="sub-menu hidden">';
			}
		}
	}

	/**
	 * Output services mega menu content from constants
	 */
	private function output_services_mega_menu( &$output ) {
		if ( ! defined( 'SUNNYSIDE_SERVICES_BY_CATEGORY' ) ) {
			return;
		}

		$service_categories = SUNNYSIDE_SERVICES_BY_CATEGORY;

		foreach ( $service_categories as $category_key => $services ) {
			$category_label = ucwords( str_replace( '_', ' ', $category_key ) );

			// Start category column
			$output .= '<div class="space-y-1.5">';
			$output .= '<h4 class="text-xs font-bold uppercase tracking-wide bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent] mb-2">' . esc_html( $category_label ) . '</h4>';

			// Output services in this category
			foreach ( $services as $service_name ) {
				$service_slug = sanitize_title( $service_name );
				$service_url  = home_url( sprintf( SUNNYSIDE_SERVICE_URL_PATTERN, $service_slug ) );
				$icon_path    = sunnysideac_get_service_icon( $service_name );

				$output .= '<a href="' . esc_url( $service_url ) . '" class="flex items-start gap-2 p-2 rounded-[20px] transition-all duration-200 hover:bg-[#ffc549] hover:scale-105 hover:shadow-md focus:bg-[#ffc549] focus:outline-none group" aria-label="Navigate to ' . esc_attr( $service_name ) . '">';
				$output .= '<div class="h-4 w-4 flex">';
				$output .= '<svg class="h-4 w-4 text-gray-600 group-hover:text-[#e5462f] transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
				$output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="' . esc_attr( $icon_path ) . '" />';
				$output .= '</svg>';
				$output .= '</div>';
				$output .= '<span class=" text-sm font-medium text-black group-hover:text-[#e5462f] transition-colors duration-200">' . esc_html( $service_name ) . '</span>';
				$output .= '</a>';
			}

			// End category column
			$output .= '</div>';
		}
	}

	/**
	 * Output cities mega menu content from constants
	 */
	private function output_areas_mega_menu( &$output ) {
		if ( ! defined( 'SUNNYSIDE_PRIORITY_CITIES' ) ) {
			return;
		}

		$priority_cities = SUNNYSIDE_PRIORITY_CITIES;

		// Get current city for active state
		$current_city_name = '';
		if ( is_singular( 'city' ) ) {
			$current_city_name = get_the_title();
		} elseif ( is_post_type_archive( 'city' ) ) {
			// On cities archive, don't highlight any specific city
			$current_city_name = '';
		}

		foreach ( $priority_cities as $city ) {
			$city_slug = sanitize_title( $city );
			$city_url  = home_url( sprintf( SUNNYSIDE_CITY_URL_PATTERN, $city_slug ) );

			// Check if this is the active city
			$is_active = ( $current_city_name === $city );

			// Build CSS classes
			$base_classes   = 'flex items-center gap-2 p-2 rounded-[20px] transition-all duration-200 focus:outline-none group';
			$hover_classes  = 'hover:bg-[#ffc549] hover:scale-105 hover:shadow-md focus:bg-[#ffc549]';
			$active_classes = 'bg-[#ffc549] shadow-md scale-105';

			$css_classes = $base_classes . ' ' . $hover_classes;
			if ( $is_active ) {
				$css_classes .= ' ' . $active_classes;
			}

			$output .= '<a href="' . esc_url( $city_url ) . '" class="' . esc_attr( $css_classes ) . '" aria-label="Navigate to ' . esc_attr( $city ) . ' city" ' . ( $is_active ? 'aria-current="page"' : '' ) . '>';
			$output .= '<div class="h-4 w-4 flex-shrink-0">';
			$output .= '<svg class="h-4 w-4 transition-colors duration-200 ' . ( $is_active ? 'text-[#e5462f]' : 'text-gray-600 group-hover:text-[#e5462f]' ) . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
			$output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />';
			$output .= '</svg>';
			$output .= '</div>';
			$output .= '<span class=" text-sm font-medium transition-colors duration-200 ' . ( $is_active ? 'text-[#e5462f]' : 'text-black group-hover:text-[#e5462f]' ) . '">' . esc_html( $city ) . '</span>';
			$output .= '</a>';
		}
	}

	/**
	 * End Level - closes the dropdown container
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			if ( $this->is_mega_menu ) {
				$menu_type = $this->parent_title;

				// Close grid
				$output .= '</div>';

				// Add "View All" CTA
				$output .= '<div class="pt-4 border-t-2 border-[#e6d4b8]">';
				$output .= '<a href="' . esc_url( home_url( '/' . $menu_type ) ) . '" class="flex items-center justify-center gap-2 rounded-[20px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-3 text-center font-bold text-white text-base transition-all duration-200 hover:scale-105 hover:shadow-lg focus:scale-105 focus:outline-none ">';

				if ( $menu_type === 'services' ) {
					$output .= 'View All HVAC Services';
				} elseif ( $menu_type === 'cities' ) {
					$output .= 'View All Cities';
				} else {
					$output .= 'View All Brands';
				}

				$output .= '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';
				$output .= '</a>';
				$output .= '</div>';

				// Close padding div and main container
				$output .= '</div></div>';

				// Reset mega menu flag
				$this->is_mega_menu = false;
			} else {
				$output .= '</ul>';
			}
		}
	}

	/**
	 * Start Element - outputs individual menu items
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {

		// Top level items
		if ( $depth === 0 ) {
			$has_dropdown = in_array( 'menu-item-has-children', $item->classes );
			$item_lower   = strtolower( $item->title );

			// Check if this is a mega menu item (Services, Cities, or Brands)
			$is_mega_menu_item = ( $item_lower === 'services' || $item_lower === 'cities' || $item_lower === 'brands' );

			// Check if this menu item should be active
			$is_active = false;
			if ( $item_lower === 'cities' && ( is_singular( 'city' ) || is_post_type_archive( 'city' ) ) ) {
				$is_active = true;
			} elseif ( $item_lower === 'brands' && ( is_singular( 'brand' ) || is_post_type_archive( 'brand' ) ) ) {
				$is_active = true;
			}

			// Store for use in start_lvl
			if ( $is_mega_menu_item && $has_dropdown ) {
				$this->is_mega_menu = true;
				$this->parent_title = $item_lower;
			}

			$output .= '<li role="none">';

			if ( $is_mega_menu_item && $has_dropdown ) {
				// Services, Cities, or Brands mega menu item
				if ( $item_lower === 'services' ) {
					$container_id = 'services-dropdown-container';
					$btn_class    = 'services-dropdown-btn';
				} elseif ( $item_lower === 'cities' ) {
					$container_id = 'service-areas-dropdown-container';
					$btn_class    = 'service-areas-dropdown-btn';
				} else {
					$container_id = 'brands-dropdown-container';
					$btn_class    = 'brands-dropdown-btn';
				}
				$chevron_icon = get_template_directory_uri() . '/assets/images/logos/navigation-chevron-down.svg';

				// Build CSS classes for the menu item
				$menu_item_classes = 'inline-flex cursor-pointer items-center gap-1 rounded-full px-6 py-3 transition-colors duration-200 focus:ring-2 focus:ring-[#ffc549] focus:ring-offset-2 focus:outline-none nav-item';
				if ( $is_active ) {
					$menu_item_classes .= ' bg-[#ffc549]';
				} else {
					$menu_item_classes .= ' hover:bg-[#ffc549] bg-[#fde0a0]';
				}

				$output .= '<div class="relative" id="' . $container_id . '">';
				$output .= '<div class="' . esc_attr( $menu_item_classes ) . '" data-item="' . esc_attr( $item->title ) . '" role="menuitem" aria-haspopup="true" aria-expanded="false" aria-label="' . esc_attr( $item->title ) . ' menu" ' . ( $is_active ? 'aria-current="page"' : '' ) . '>';
				$output .= '<a href="' . esc_url( $item->url ) . '" class=" text-lg font-medium whitespace-nowrap transition-colors duration-200 ' . ( $is_active ? 'text-[#e5462f]' : 'text-black hover:text-black focus:text-black' ) . '">' . esc_html( $item->title ) . '</a>';
				$output .= '<button class="ml-1 border-none bg-transparent p-0 focus:outline-none ' . $btn_class . '" aria-label="Toggle ' . $item_lower . ' dropdown">';
				$output .= '<img src="' . esc_url( $chevron_icon ) . '" alt="" class="h-4 w-4 text-current transition-transform duration-200 chevron-icon" role="presentation" loading="lazy" decoding="async" />';
				$output .= '</button>';
				$output .= '</div>';
				// Mega menu dropdown will be added by start_lvl()
			} else {
				// Regular menu item
				$output .= '<button class="cursor-pointer rounded-full px-6 py-3 transition-colors duration-200 hover:bg-[#ffc549] focus:ring-2 focus:ring-[#ffc549] focus:ring-offset-2 focus:outline-none bg-[#fde0a0] nav-item" data-item="' . esc_attr( $item->title ) . '" data-href="' . esc_url( $item->url ) . '" role="menuitem" aria-label="Navigate to ' . esc_attr( $item->title ) . '">';
				$output .= '<span class=" text-lg font-medium whitespace-nowrap text-black">' . esc_html( $item->title ) . '</span>';
				$output .= '</button>';
			}
		}
		// We don't output child items here - they're generated from constants in the mega menu
	}

	/**
	 * End Element - closes individual menu items
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$item_lower        = strtolower( $item->title );
			$has_dropdown      = in_array( 'menu-item-has-children', $item->classes );
			$is_mega_menu_item = ( $item_lower === 'services' || $item_lower === 'cities' || $item_lower === 'brands' );

			if ( $is_mega_menu_item && $has_dropdown ) {
				$output .= '</div>'; // Close relative container
			}

			$output .= '</li>';
		}
		// Child items don't need closing tags (no wrapping element)
	}

	/**
	 * Output brands mega menu content from constants
	 */
	private function output_brands_mega_menu( &$output ) {
		if ( ! defined( 'SUNNYSIDE_BRANDS' ) || ! defined( 'SUNNYSIDE_DAIKIN_PRODUCTS' ) ) {
			return;
		}

		$brands          = SUNNYSIDE_BRANDS;
		$daikin_products = SUNNYSIDE_DAIKIN_PRODUCTS;

		// Get current brand for active state
		$current_brand_name = '';
		if ( is_singular( 'brand' ) ) {
			$current_brand_name = strtolower( get_the_title() );
		}

		foreach ( $brands as $brand_slug => $brand_name ) {
			$brand_url = home_url( sprintf( '/brands/%s/', $brand_slug ) );
			$is_active = ( strtolower( $brand_name ) === $current_brand_name );

			// Start brand column
			$output .= '<div class="space-y-1.5">';

			// Brand heading (clickable)
			$base_classes   = 'flex items-start gap-2 p-2 rounded-[20px] transition-all duration-200 focus:outline-none group';
			$hover_classes  = 'hover:bg-[#ffc549] hover:scale-105 hover:shadow-md focus:bg-[#ffc549]';
			$active_classes = 'bg-[#ffc549] shadow-md scale-105';

			$css_classes = $base_classes . ' ' . $hover_classes;
			if ( $is_active ) {
				$css_classes .= ' ' . $active_classes;
			}

			$output .= '<a href="' . esc_url( $brand_url ) . '" class="' . esc_attr( $css_classes ) . '" aria-label="Navigate to ' . esc_attr( $brand_name ) . ' brand" ' . ( $is_active ? 'aria-current="page"' : '' ) . '>';
			$output .= '<div class="h-4 w-4 flex-shrink-0 mt-0.5">';
			$output .= '<svg class="h-4 w-4 transition-colors duration-200 ' . ( $is_active ? 'text-[#e5462f]' : 'text-gray-600 group-hover:text-[#e5462f]' ) . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
			$output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />';
			$output .= '</svg>';
			$output .= '</div>';
			$output .= '<span class="text-sm font-bold transition-colors duration-200 ' . ( $is_active ? 'text-[#e5462f]' : 'text-black group-hover:text-[#e5462f]' ) . '">' . esc_html( $brand_name ) . '</span>';
			$output .= '</a>';

			// If Daikin, show sub-products
			if ( $brand_slug === 'daikin' ) {
				foreach ( $daikin_products as $product ) {
					$product_url = home_url( '/daikin/' . $product['slug'] . '/' );

					$output .= '<a href="' . esc_url( $product_url ) . '" class="flex items-start gap-2 p-2 pl-6 rounded-[20px] transition-all duration-200 hover:bg-[#ffe8cc] hover:scale-105 hover:shadow-sm focus:bg-[#ffe8cc] focus:outline-none group" aria-label="Navigate to ' . esc_attr( $product['name'] ) . '">';
					$output .= '<div class="h-3 w-3 flex-shrink-0 mt-0.5">';
					$output .= '<svg class="h-3 w-3 text-gray-500 group-hover:text-[#fb9939] transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
					$output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />';
					$output .= '</svg>';
					$output .= '</div>';
					$output .= '<span class="text-xs font-medium text-gray-700 group-hover:text-[#fb9939] transition-colors duration-200">' . esc_html( $product['short_name'] ) . '</span>';
					$output .= '</a>';
				}
			}

			// End brand column
			$output .= '</div>';
		}
	}
}

/**
 * Output desktop navigation menu
 * Now uses JSON config instead of WordPress menus
 */
function sunnysideac_desktop_nav_menu() {
	// Try JSON config first, then fallback
	if ( function_exists( 'sunnysideac_render_desktop_nav_from_config' ) ) {
		sunnysideac_render_desktop_nav_from_config();
	} else {
		sunnysideac_fallback_menu();
	}
}

/**
 * Fallback menu if no menu is assigned
 */
function sunnysideac_fallback_menu() {
	echo '<ul role="menubar" class="flex items-center gap-2 overflow-visible">';
	echo '<li><a href="' . home_url() . '" class="cursor-pointer rounded-full px-6 py-3 transition-colors duration-200 hover:bg-[#ffc549] bg-[#fde0a0]"><span class=" text-lg font-medium text-black">Home</span></a></li>';
	echo '<li><a href="' . home_url( '/services' ) . '" class="cursor-pointer rounded-full px-6 py-3 transition-colors duration-200 hover:bg-[#ffc549] bg-[#fde0a0]"><span class=" text-lg font-medium text-black">Services</span></a></li>';
	echo '<li><a href="' . home_url( '/contact' ) . '" class="cursor-pointer rounded-full px-6 py-3 transition-colors duration-200 hover:bg-[#ffc549] bg-[#fde0a0]"><span class=" text-lg font-medium text-black">Contact</span></a></li>';
	echo '</ul>';
}

/**
 * Output mobile navigation menu
 * Now uses JSON config instead of hardcoded values
 */
function sunnysideac_mobile_nav_menu() {
	// Try JSON config first, then fallback
	if ( function_exists( 'sunnysideac_render_mobile_nav_from_config' ) ) {
		sunnysideac_render_mobile_nav_from_config();
	} else {
		sunnysideac_mobile_nav_menu_fallback();
	}
}

/**
 * Custom Walker for Footer Navigation
 * Handles multi-section footer menu with custom structure
 * Creates specific layout: Services+Company in one column, Brands in another
 */
class Sunnyside_Footer_Nav_Walker extends Walker_Nav_Menu {

	private $sections        = array();
	private $current_section = null;

	/**
	 * Start Level - outputs the <ul> wrapper for child items
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '<ul class="space-y-2">';
		}
	}

	/**
	 * End Level - closes the <ul> wrapper
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '</ul>';
		}
	}

	/**
	 * Start Element - collects menu items and sections
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		// Top level items (section headings) - collect them first
		if ( $depth === 0 ) {
			$has_children = in_array( 'menu-item-has-children', $item->classes );

			// Store section info
			$this->sections[] = array(
				'title'        => $item->title,
				'has_children' => $has_children,
				'children'     => array(),
			);

			if ( $has_children ) {
				$this->current_section = count( $this->sections ) - 1;
			}
		} else {
			// Child items - add to current section
			if ( $this->current_section !== null ) {
				$this->sections[ $this->current_section ]['children'][] = $item;
			}
		}
	}

	/**
	 * End Element - closes individual menu items
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$this->current_section = null;
		}
	}

	/**
	 * Display elements - override to create custom structure
	 */
	public function walk( $elements, $max_depth, ...$args ) {
		// First, let the parent collect all the data
		parent::walk( $elements, $max_depth, ...$args );

		$output = '';

		// Reorganize sections according to our desired structure
		$services_section      = null;
		$company_section       = null;
		$brands_section        = null;
		$service_areas_section = null;

		foreach ( $this->sections as $section ) {
			$title_lower = strtolower( $section['title'] );
			if ( $title_lower === 'services' ) {
				$services_section = $section;
			} elseif ( $title_lower === 'company' ) {
				$company_section = $section;
			} elseif ( $title_lower === 'brands' ) {
				$brands_section = $section;
			} elseif ( $title_lower === 'service areas' || $title_lower === 'cities' ) {
				$service_areas_section = $section;
			}
		}

		// Column 2: Services at top, Company at bottom
		$output .= '<div class="space-y-6">';

		if ( $services_section ) {
			$output .= '<div>';
			$output .= '<div class="mb-4 text-xl font-semibold text-gray-900 sm:text-2xl" role="heading" aria-level="4">' . esc_html( $services_section['title'] ) . '</div>';
			if ( ! empty( $services_section['children'] ) ) {
				$output .= '<ul class="space-y-2">';
				foreach ( $services_section['children'] as $child ) {
					$output .= '<li>';
					$output .= '<a href="' . esc_url( $child->url ) . '" class="font-normal text-gray-700 transition-colors duration-200 hover:text-[#fb9939] hover:underline focus:outline-2 focus:outline-blue-500"';
					if ( $child->target && $child->target !== '' ) {
						$output .= ' target="' . esc_attr( $child->target ) . '"';
					}
					if ( $child->xfn && $child->xfn !== '' ) {
						$output .= ' rel="' . esc_attr( $child->xfn ) . '"';
					}
					$output .= '>' . esc_html( $child->title ) . '</a>';
					$output .= '</li>';
				}
				$output .= '</ul>';
			}
			$output .= '</div>';
		}

		if ( $company_section ) {
			$output .= '<div>';
			$output .= '<div class="mb-4 text-xl font-semibold text-gray-900 sm:text-2xl" role="heading" aria-level="4">' . esc_html( $company_section['title'] ) . '</div>';
			if ( ! empty( $company_section['children'] ) ) {
				$output .= '<ul class="space-y-2">';
				foreach ( $company_section['children'] as $child ) {
					$output .= '<li>';
					$output .= '<a href="' . esc_url( $child->url ) . '" class="font-normal text-gray-700 transition-colors duration-200 hover:text-[#fb9939] hover:underline focus:outline-2 focus:outline-blue-500"';
					if ( $child->target && $child->target !== '' ) {
						$output .= ' target="' . esc_attr( $child->target ) . '"';
					}
					if ( $child->xfn && $child->xfn !== '' ) {
						$output .= ' rel="' . esc_attr( $child->xfn ) . '"';
					}
					$output .= '>' . esc_html( $child->title ) . '</a>';
					$output .= '</li>';
				}
				$output .= '</ul>';
			}
			$output .= '</div>';
		}

		$output .= '</div>';

		// Column 3: Service Areas & Brands
		$output .= '<div class="space-y-6">';

		// Service Areas / Cities section
		if ( $service_areas_section ) {
			$output .= '<div>';
			$output .= '<div class="mb-4 text-xl font-semibold text-gray-900 sm:text-2xl" role="heading" aria-level="4">' . esc_html( $service_areas_section['title'] ) . '</div>';
			if ( ! empty( $service_areas_section['children'] ) ) {
				$output .= '<ul class="space-y-2">';
				foreach ( $service_areas_section['children'] as $child ) {
					$output .= '<li>';
					$output .= '<a href="' . esc_url( $child->url ) . '" class="font-normal text-gray-700 transition-colors duration-200 hover:text-[#fb9939] hover:underline focus:outline-2 focus:outline-blue-500"';
					if ( $child->target && $child->target !== '' ) {
						$output .= ' target="' . esc_attr( $child->target ) . '"';
					}
					if ( $child->xfn && $child->xfn !== '' ) {
						$output .= ' rel="' . esc_attr( $child->xfn ) . '"';
					}
					$output .= '>' . esc_html( $child->title ) . '</a>';
					$output .= '</li>';
				}
				$output .= '</ul>';
			}
			$output .= '</div>';
		}

		// Brands section
		if ( $brands_section ) {
			$output .= '<div>';
			$output .= '<div class="mb-4 text-xl font-semibold text-gray-900 sm:text-2xl" role="heading" aria-level="4">' . esc_html( $brands_section['title'] ) . '</div>';
			if ( ! empty( $brands_section['children'] ) ) {
				$output .= '<ul class="space-y-2">';
				foreach ( $brands_section['children'] as $child ) {
					$output .= '<li>';
					$output .= '<a href="' . esc_url( $child->url ) . '" class="font-normal text-gray-700 transition-colors duration-200 hover:text-[#fb9939] hover:underline focus:outline-2 focus:outline-blue-500"';
					if ( $child->target && $child->target !== '' ) {
						$output .= ' target="' . esc_attr( $child->target ) . '"';
					}
					if ( $child->xfn && $child->xfn !== '' ) {
						$output .= ' rel="' . esc_attr( $child->xfn ) . '"';
					}
					$output .= '>' . esc_html( $child->title ) . '</a>';
					$output .= '</li>';
				}
				$output .= '</ul>';
			}
			$output .= '</div>';
		} else {
			// If no Brands section in menu, use fallback brands
			$brands_links = array(
				array(
					'name' => 'Daikin',
					'href' => home_url( '/brands/daikin' ),
				),
				array(
					'name' => 'Goodman',
					'href' => home_url( '/brands/goodman' ),
				),
				array(
					'name' => 'Rheem',
					'href' => home_url( '/brands/rheem' ),
				),
				array(
					'name' => 'Trane',
					'href' => home_url( '/brands/trane' ),
				),
				array(
					'name' => 'Carrier',
					'href' => home_url( '/brands/carrier' ),
				),
				array(
					'name' => 'All Brands',
					'href' => home_url( '/brands' ),
				),
			);

			$output .= '<div>';
			$output .= '<div class="mb-4 text-xl font-semibold text-gray-900 sm:text-2xl" role="heading" aria-level="4">Brands</div>';
			$output .= '<ul class="space-y-2">';
			foreach ( $brands_links as $link ) {
				$output .= '<li>';
				$output .= '<a href="' . esc_url( $link['href'] ) . '" class="font-normal text-gray-700 transition-colors duration-200 hover:text-[#fb9939] hover:underline focus:outline-2 focus:outline-blue-500">';
				$output .= esc_html( $link['name'] );
				$output .= '</a>';
				$output .= '</li>';
			}
			$output .= '</ul>';
			$output .= '</div>';
		}

		$output .= '</div>';

		// Reset data for next use
		$this->sections        = array();
		$this->current_section = null;

		return $output;
	}
}

/**
 * Output footer navigation menu
 */
function sunnysideac_footer_nav_menu() {
	// Check if footer menu is assigned
	if ( has_nav_menu( 'footer' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => '',
				'items_wrap'     => '%3$s',
				'walker'         => new Sunnyside_Footer_Nav_Walker(),
				'depth'          => 2,
				'fallback_cb'    => 'sunnysideac_footer_fallback_menu',
			)
		);
	} else {
		sunnysideac_footer_fallback_menu();
	}
}

/**
 * Fallback menu for footer if no menu is assigned
 * Maintains the same structure and styling as the hardcoded footer
 */
function sunnysideac_footer_fallback_menu() {
	$footer_links = array(
		'services' => array(
			'title' => 'Services',
			'links' => array(
				array(
					'name' => 'AC Repair',
					'href' => home_url( '/service/ac-repair' ),
				),
				array(
					'name' => 'AC Installation',
					'href' => home_url( '/service/ac-installation' ),
				),
				array(
					'name' => 'AC Maintenance',
					'href' => home_url( '/service/ac-maintenance' ),
				),
				array(
					'name' => 'Heating Services',
					'href' => home_url( '/service/heating-repair' ),
				),
				array(
					'name' => 'All HVAC Services',
					'href' => home_url( '/services' ),
				),
			),
		),
		'company'  => array(
			'title' => 'Company',
			'links' => array(
				array(
					'name' => 'About Us',
					'href' => home_url( '/about' ),
				),
				array(
					'name' => 'Financing',
					'href' => home_url( '/financing' ),
				),
				array(
					'name' => 'Maintenance Plan',
					'href' => home_url( '/maintenance-plan' ),
				),
				array(
					'name' => 'Careers',
					'href' => home_url( '/careers' ),
				),
			),
		),
		'brands'   => array(
			'title' => 'Brands',
			'links' => array(
				array(
					'name' => 'Daikin',
					'href' => home_url( '/brands/daikin' ),
				),
				array(
					'name' => 'Goodman',
					'href' => home_url( '/brands/goodman' ),
				),
				array(
					'name' => 'Rheem',
					'href' => home_url( '/brands/rheem' ),
				),
				array(
					'name' => 'Trane',
					'href' => home_url( '/brands/trane' ),
				),
				array(
					'name' => 'Carrier',
					'href' => home_url( '/brands/carrier' ),
				),
				array(
					'name' => 'All Brands',
					'href' => home_url( '/brands' ),
				),
			),
		),
	);

	// Column 2: Services at top, Company at bottom
	echo '<div class="space-y-6">';
	echo '<div>';
	echo '<div class="mb-4 text-xl font-semibold text-gray-900 sm:text-2xl" role="heading" aria-level="4">' . esc_html( $footer_links['services']['title'] ) . '</div>';
	echo '<ul class="space-y-2">';
	foreach ( $footer_links['services']['links'] as $link ) {
		echo '<li>';
		echo '<a href="' . esc_url( $link['href'] ) . '" class="font-normal text-gray-700 transition-colors duration-200 hover:text-[#fb9939] hover:underline focus:outline-2 focus:outline-blue-500">';
		echo esc_html( $link['name'] );
		echo '</a>';
		echo '</li>';
	}
	echo '</ul>';
	echo '</div>';

	echo '<div>';
	echo '<div class="mb-4 text-xl font-semibold text-gray-900 sm:text-2xl" role="heading" aria-level="4">' . esc_html( $footer_links['company']['title'] ) . '</div>';
	echo '<ul class="space-y-2">';
	foreach ( $footer_links['company']['links'] as $link ) {
		echo '<li>';
		echo '<a href="' . esc_url( $link['href'] ) . '" class="font-normal text-gray-700 transition-colors duration-200 hover:text-[#fb9939] hover:underline focus:outline-2 focus:outline-blue-500">';
		echo esc_html( $link['name'] );
		echo '</a>';
		echo '</li>';
	}
	echo '</ul>';
	echo '</div>';
	echo '</div>';

	// Column 3: Brands
	echo '<div class="space-y-6">';
	echo '<div>';
	echo '<div class="mb-4 text-xl font-semibold text-gray-900 sm:text-2xl" role="heading" aria-level="4">' . esc_html( $footer_links['brands']['title'] ) . '</div>';
	echo '<ul class="space-y-2">';
	foreach ( $footer_links['brands']['links'] as $link ) {
		echo '<li>';
		echo '<a href="' . esc_url( $link['href'] ) . '" class="font-normal text-gray-700 transition-colors duration-200 hover:text-[#fb9939] hover:underline focus:outline-2 focus:outline-blue-500">';
		echo esc_html( $link['name'] );
		echo '</a>';
		echo '</li>';
	}
	echo '</ul>';
	echo '</div>';
	echo '</div>';
}
