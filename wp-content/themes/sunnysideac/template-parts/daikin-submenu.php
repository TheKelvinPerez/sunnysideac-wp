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

	<!-- 3-Column Grid of Product Cards -->
	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
		<?php foreach ( $daikin_products as $product ) : ?>
			<?php
			$product_url = home_url( '/brands/daikin/' . $product['slug'] );
			$is_active   = ( $current_slug === $product['slug'] );
			$image_path = 'assets/brand-images/' . $product['slug'] . '.jpg';
			?>
			<a href="<?php echo esc_url( $product_url ); ?>"
			   class="group block relative h-64 rounded-2xl overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-lg <?php echo $is_active ? 'ring-2 ring-orange-500 ring-offset-2' : ''; ?>"
			   style="background-image: url('<?php echo esc_url( sunnysideac_asset_url( $image_path ) ); ?>'); background-size: cover; background-position: center;"
			   <?php echo $is_active ? 'aria-current="page"' : ''; ?>
			   aria-label="<?php echo esc_attr( $product['name'] ); ?>">

				<!-- Gradient Overlay -->
				<div class="absolute inset-0 bg-gradient-to-br from-[#fb9939]/90 via-gray-500/50 to-transparent"></div>

				<!-- Content -->
				<div class="relative h-full flex flex-col justify-end p-6 text-center">
					<!-- Product Name -->
					<h3 class="text-xl font-bold text-white mb-2 transition-colors duration-300" role="heading" aria-level="4">
						<?php echo esc_html( $product['short_name'] ); ?>
					</h3>

					<p class="text-white/90 text-sm mb-3">
						View Details & Specs
					</p>

					<!-- Hover indicator -->
					<div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
						<svg class="w-5 h-5 text-white mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
						</svg>
					</div>
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
