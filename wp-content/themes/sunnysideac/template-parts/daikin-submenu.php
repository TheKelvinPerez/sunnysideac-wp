<?php
/**
 * Daikin Product Submenu
 *
 * Displays a horizontal navigation menu for all Daikin products
 * Used on Daikin brand page and individual Daikin product pages
 *
 * @package SunnysideAC
 */

// Get current page slug to highlight active item
$current_slug = '';
if ( is_page() ) {
	$current_slug = get_post_field( 'post_name', get_post() );
} elseif ( is_singular( 'brand' ) ) {
	$current_slug = 'daikin'; // Brand page
}

// Get Daikin products from constants
$daikin_products = defined( 'SUNNYSIDE_DAIKIN_PRODUCTS' ) ? SUNNYSIDE_DAIKIN_PRODUCTS : array();

if ( empty( $daikin_products ) ) {
	return; // Don't show submenu if no products defined
}
?>

<!-- Daikin Product Submenu -->
<nav class="daikin-submenu bg-white rounded-[20px] p-6 md:p-8 shadow-sm"
     aria-label="Daikin products navigation"
     role="navigation">
	<div class="text-center mb-8">
		<h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2" role="heading" aria-level="3">
			<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
				Daikin Products
			</span>
		</h2>
		<p class="text-gray-600 mb-4">Explore our complete range of Daikin HVAC systems</p>
		<a href="<?php echo esc_url( home_url( '/brands/daikin/' ) ); ?>"
		   class="inline-flex items-center text-sm text-[#e5462f] hover:text-[#fb9939] transition-colors duration-200 font-medium"
		   aria-label="View Daikin brand overview">
			<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
			</svg>
			Back to Daikin Overview
		</a>
	</div>

	<!-- 3-Column Grid of Square Cards -->
	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
		<?php foreach ( $daikin_products as $product ) : ?>
			<?php
			$product_url = home_url( '/daikin/' . $product['slug'] . '/' );
			$is_active   = ( $current_slug === $product['slug'] );

			// Build CSS classes for square cards
			$base_classes   = 'group flex flex-col items-center justify-center p-8 rounded-[24px] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 min-h-[200px]';
			$hover_classes  = 'hover:bg-gradient-to-br hover:from-orange-50 hover:to-orange-100 hover:shadow-lg hover:scale-105';
			$active_classes = 'bg-gradient-to-br from-orange-100 to-orange-200 shadow-lg';
			$normal_classes = 'bg-gray-50';

			$link_classes = $base_classes . ' ' . $hover_classes . ' ' . ( $is_active ? $active_classes : $normal_classes );
			?>
			<a href="<?php echo esc_url( $product_url ); ?>"
			   class="<?php echo esc_attr( $link_classes ); ?>"
			   <?php echo $is_active ? 'aria-current="page"' : ''; ?>
			   aria-label="<?php echo esc_attr( $product['name'] ); ?>">

				<!-- Large Icon -->
				<div class="mb-4">
					<div class="inline-flex items-center justify-center w-20 h-20 rounded-full <?php echo $is_active ? 'bg-gradient-to-br from-orange-300 to-orange-400' : 'bg-gradient-to-br from-orange-200 to-orange-300'; ?> shadow-md">
						<svg class="h-10 w-10 <?php echo $is_active ? 'text-white' : 'text-orange-500'; ?> transition-colors duration-300"
						     fill="none"
						     stroke="currentColor"
						     viewBox="0 0 24 24"
						     aria-hidden="true">
							<path stroke-linecap="round"
							      stroke-linejoin="round"
							      stroke-width="2"
							      d="<?php echo esc_attr( $product['icon'] ); ?>" />
						</svg>
					</div>
				</div>

				<!-- Product Name -->
				<div class="text-center">
					<h3 class="text-lg font-bold <?php echo $is_active ? 'text-[#e5462f]' : 'text-gray-900 group-hover:text-[#e5462f]'; ?> transition-colors duration-300 mb-2">
						<?php echo esc_html( $product['short_name'] ); ?>
					</h3>
					<p class="text-sm text-gray-600 group-hover:text-gray-700 transition-colors duration-300">
						View Details & Specs
					</p>
				</div>

				<!-- Hover indicator -->
				<div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
					<svg class="w-5 h-5 text-orange-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
					</svg>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
</nav>

<!-- Custom scrollbar hide CSS (inline for simplicity) -->
<style>
.scrollbar-hide::-webkit-scrollbar {
	display: none;
}
.scrollbar-hide {
	-ms-overflow-style: none;
	scrollbar-width: none;
}
</style>
