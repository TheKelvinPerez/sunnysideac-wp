<?php
/**
 * WebP Image Support
 *
 * WebP detection and fallback functionality for mobile optimization
 */

/**
 * Add WebP support for mobile
 */
function sunnysideac_webp_support() {
	?>
	<script>
		// WebP detection and fallback
		function supportsWebP() {
			return new Promise(resolve => {
				const webP = new Image();
				webP.onload = webP.onerror = function() {
					resolve(webP.height === 2);
				};
				webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
			});
		}

		// Convert images to WebP on mobile if supported
		supportsWebP().then(supported => {
			if (supported && window.innerWidth <= 768) {
				const images = document.querySelectorAll('img[data-webp]');
				images.forEach(img => {
					const webpSrc = img.dataset.webp;
					if (webpSrc && img.src !== webpSrc) {
						img.src = webpSrc;
					}
				});
			}
		});
	</script>
	<?php
}
add_action('wp_footer', 'sunnysideac_webp_support', 1);