<?php
/**
 * Mobile Services Section Template Part
 *
 * Renders the mobile navigation services section with categories.
 *
 * @package SunnysideAC
 *
 * Expected $args:
 * @var array  $service_categories Categories of services (from constants)
 * @var string $current_service_name Currently active service name
 */

// Extract args
$service_categories   = $args['service_categories'] ?? [];
$current_service_name = $args['current_service_name'] ?? '';

// Safety check
if ( empty( $service_categories ) ) {
	return;
}

?>
<div class="mb-6">
	<div class="mb-3 border-b border-gray-200 pb-2 text-lg font-medium text-gray-800" role="heading" aria-level="4">Services</div>
	<div class="space-y-3">
		<?php foreach ( $service_categories as $category_key => $services ) : ?>
			<?php $category_label = ucwords( str_replace( '_', ' ', $category_key ) ); ?>
			<div class="mt-4 first:mt-0">
				<h4 class="mb-2 text-sm font-bold uppercase tracking-wide text-[#fb9939]">
					<?php echo esc_html( $category_label ); ?>
				</h4>

				<?php foreach ( $services as $service_name ) : ?>
					<?php
					$service_slug = sanitize_title( $service_name );
					$service_url  = home_url( sprintf( SUNNYSIDE_SERVICE_URL_PATTERN, $service_slug ) );
					$is_active    = ( $current_service_name === $service_name );

					$link_classes = 'block w-full py-2 pl-3 text-left transition-colors duration-200 mobile-service-link';
					if ( $is_active ) {
						$link_classes .= ' text-[#fb9939] font-medium';
					} else {
						$link_classes .= ' text-gray-700 hover:text-[#fb9939]';
					}
					?>
					<a
						href="<?php echo esc_url( $service_url ); ?>"
						class="<?php echo esc_attr( $link_classes ); ?>"
						<?php echo $is_active ? 'aria-current="page"' : ''; ?>
					>
						<?php echo esc_html( $service_name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
