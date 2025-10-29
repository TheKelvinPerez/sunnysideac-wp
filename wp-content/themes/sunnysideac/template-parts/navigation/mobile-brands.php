<?php
/**
 * Mobile Brands Section Template Part
 *
 * Renders the mobile navigation brands section with brand links and Daikin products.
 *
 * @package SunnysideAC
 *
 * Expected $args:
 * @var array  $brands             All brands to display (from constants)
 * @var array  $daikin_products    Daikin products to show under Daikin
 * @var string $current_brand_name Currently active brand name
 */

// Extract args
$brands             = $args['brands'] ?? [];
$daikin_products    = $args['daikin_products'] ?? [];
$current_brand_name = $args['current_brand_name'] ?? '';

// Safety check
if ( empty( $brands ) ) {
	return;
}

?>
<div class="mb-6">
	<div class="mb-3 border-b border-gray-200 pb-2 text-lg font-medium text-gray-800" role="heading" aria-level="4">Brands</div>
	<div class="space-y-1">
		<?php foreach ( $brands as $brand_key => $brand_name ) : ?>
			<?php
			$brand_slug = sanitize_title( $brand_name );
			$brand_url  = home_url( '/brands/' . $brand_slug );
			$is_active  = ( strtolower( $current_brand_name ) === strtolower( $brand_name ) );

			$link_classes = 'block w-full py-2 text-left transition-colors duration-200 mobile-brand-link';
			if ( $is_active ) {
				$link_classes .= ' text-[#fb9939] font-medium';
			} else {
				$link_classes .= ' text-gray-700 hover:text-[#fb9939]';
			}
			?>

			<?php if ( $brand_key === 'daikin' && ! empty( $daikin_products ) ) : ?>
				<!-- Daikin with expandable products -->
				<div>
					<a
						href="<?php echo esc_url( $brand_url ); ?>"
						class="<?php echo esc_attr( $link_classes ); ?> font-medium"
						<?php echo $is_active ? 'aria-current="page"' : ''; ?>
					>
						<?php echo esc_html( $brand_name ); ?>
						<span class="text-sm text-gray-500 ml-2">
							(<?php echo count( $daikin_products ); ?> products)
						</span>
					</a>

					<!-- Daikin Products Sub-items -->
					<div class="ml-3 mt-1 space-y-1">
						<?php foreach ( $daikin_products as $product ) : ?>
							<?php
							$product_url  = home_url( '/brands/daikin/' . $product['slug'] );
							$product_name = $product['short_name'] ?? $product['name'];

							$product_classes = 'block w-full py-1 pl-3 text-left text-sm transition-colors duration-200 mobile-product-link text-gray-600 hover:text-[#fb9939]';
							?>
							<a
								href="<?php echo esc_url( $product_url ); ?>"
								class="<?php echo esc_attr( $product_classes ); ?>"
							>
								↳ <?php echo esc_html( $product_name ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				</div>

			<?php else : ?>
				<!-- Regular Brand Item -->
				<a
					href="<?php echo esc_url( $brand_url ); ?>"
					class="<?php echo esc_attr( $link_classes ); ?>"
					<?php echo $is_active ? 'aria-current="page"' : ''; ?>
				>
					<?php echo esc_html( $brand_name ); ?>
				</a>
			<?php endif; ?>

		<?php endforeach; ?>

		<a
			href="<?php echo esc_url( home_url( '/brands' ) ); ?>"
			class="block w-full py-2 text-left font-medium text-[#fb9939] transition-colors duration-200 hover:text-[#e5462f]"
		>
			→ View All Brands
		</a>
	</div>
</div>