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
<nav class="daikin-submenu bg-white rounded-[20px] p-4 md:p-6 shadow-sm sticky top-[80px] z-40"
     aria-label="Daikin products navigation"
     role="navigation">
	<div class="flex items-center justify-between mb-4">
		<h2 class="text-lg md:text-xl font-bold text-gray-900" role="heading" aria-level="3">
			<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
				Daikin Products
			</span>
		</h2>
		<a href="<?php echo esc_url( home_url( '/brands/daikin/' ) ); ?>"
		   class="text-sm text-gray-600 hover:text-[#e5462f] transition-colors duration-200"
		   aria-label="View Daikin brand overview">
			Overview
		</a>
	</div>

	<!-- Desktop: Horizontal scroll menu -->
	<div class="hidden md:block overflow-x-auto scrollbar-hide">
		<div class="flex gap-3 min-w-max pb-2">
			<?php foreach ( $daikin_products as $product ) : ?>
				<?php
				$product_url = home_url( '/daikin/' . $product['slug'] . '/' );
				$is_active   = ( $current_slug === $product['slug'] );

				// Build CSS classes
				$base_classes   = 'group flex items-center gap-3 px-4 py-3 rounded-[20px] transition-all duration-200 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2';
				$hover_classes  = 'hover:bg-gradient-to-br hover:from-orange-50 hover:to-orange-100 hover:shadow-md hover:scale-105';
				$active_classes = 'bg-gradient-to-br from-orange-100 to-orange-200 shadow-md';
				$normal_classes = 'bg-gray-50';

				$link_classes = $base_classes . ' ' . $hover_classes . ' ' . ( $is_active ? $active_classes : $normal_classes );
				?>
				<a href="<?php echo esc_url( $product_url ); ?>"
				   class="<?php echo esc_attr( $link_classes ); ?>"
				   <?php echo $is_active ? 'aria-current="page"' : ''; ?>
				   aria-label="<?php echo esc_attr( $product['name'] ); ?>">
					<!-- Icon -->
					<div class="flex-shrink-0">
						<div class="inline-flex items-center justify-center w-10 h-10 rounded-full <?php echo $is_active ? 'bg-gradient-to-br from-orange-300 to-orange-400' : 'bg-gradient-to-br from-orange-200 to-orange-300'; ?>">
							<svg class="h-5 w-5 <?php echo $is_active ? 'text-white' : 'text-orange-500'; ?> transition-colors duration-200"
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
					<div class="flex flex-col">
						<span class="text-sm font-bold <?php echo $is_active ? 'text-[#e5462f]' : 'text-gray-900 group-hover:text-[#e5462f]'; ?> transition-colors duration-200">
							<?php echo esc_html( $product['short_name'] ); ?>
						</span>
						<span class="text-xs text-gray-600 group-hover:text-gray-700 transition-colors duration-200">
							View Details
						</span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>

	<!-- Mobile: Grid layout -->
	<div class="md:hidden grid grid-cols-2 gap-3">
		<?php foreach ( $daikin_products as $product ) : ?>
			<?php
			$product_url = home_url( '/daikin/' . $product['slug'] . '/' );
			$is_active   = ( $current_slug === $product['slug'] );

			// Build CSS classes for mobile
			$base_classes   = 'group flex flex-col items-center gap-2 p-4 rounded-[20px] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2';
			$hover_classes  = 'hover:bg-gradient-to-br hover:from-orange-50 hover:to-orange-100 hover:shadow-md hover:scale-105';
			$active_classes = 'bg-gradient-to-br from-orange-100 to-orange-200 shadow-md';
			$normal_classes = 'bg-gray-50';

			$link_classes = $base_classes . ' ' . $hover_classes . ' ' . ( $is_active ? $active_classes : $normal_classes );
			?>
			<a href="<?php echo esc_url( $product_url ); ?>"
			   class="<?php echo esc_attr( $link_classes ); ?>"
			   <?php echo $is_active ? 'aria-current="page"' : ''; ?>
			   aria-label="<?php echo esc_attr( $product['name'] ); ?>">
				<!-- Icon -->
				<div class="inline-flex items-center justify-center w-12 h-12 rounded-full <?php echo $is_active ? 'bg-gradient-to-br from-orange-300 to-orange-400' : 'bg-gradient-to-br from-orange-200 to-orange-300'; ?>">
					<svg class="h-6 w-6 <?php echo $is_active ? 'text-white' : 'text-orange-500'; ?> transition-colors duration-200"
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

				<!-- Product Name -->
				<span class="text-sm font-bold text-center <?php echo $is_active ? 'text-[#e5462f]' : 'text-gray-900 group-hover:text-[#e5462f]'; ?> transition-colors duration-200">
					<?php echo esc_html( $product['short_name'] ); ?>
				</span>
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
