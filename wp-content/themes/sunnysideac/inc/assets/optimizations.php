<?php
/**
 * Asset Optimizations
 *
 * Cache headers, SVG protection, and other asset-related optimizations
 */

/**
 * Simple cache headers for theme assets (safe version)
 */
function sunnysideac_set_cache_headers() {
	// Only set headers for direct asset access
	if ( is_admin() || is_login() ) {
		return;
	}

	// Simple string check for theme assets
	$request_uri = $_SERVER['REQUEST_URI'] ?? '';
	$theme_name  = basename( get_template_directory() );

	if ( strpos( $request_uri, '/wp-content/themes/' . $theme_name . '/assets/' ) !== false ||
	strpos( $request_uri, '/wp-content/themes/' . $theme_name . '/dist/' ) !== false ) {

		if ( ! headers_sent() ) {
			header( 'Cache-Control: public, max-age=31536000, immutable' );
			header( 'Vary: Accept-Encoding' );
		}
	}
}
add_action( 'template_redirect', 'sunnysideac_set_cache_headers' );

/**
 * Remove complex performance hooks that might cause conflicts
 */
function sunnysideac_remove_complex_performance_hooks() {
	// Remove critical CSS functions
	remove_action( 'wp_head', 'sunnysideac_inline_critical_css', 1 );
	remove_action( 'wp_head', 'sunnysideac_preload_css', 2 );
	remove_action( 'wp_head', 'sunnysideac_preload_critical_js', 3 );

	// Remove performance hints that might conflict
	remove_action( 'wp_head', 'sunnysideac_add_performance_hints', 1 );
}
add_action( 'init', 'sunnysideac_remove_complex_performance_hooks', 5 );

/**
 * Add SVG protection script to prevent path removal
 */
function sunnysideac_add_svg_protection() {
	?>
	<script>
	// SVG Path Protection - Prevents SVG paths from being removed from DOM
	(function() {
		'use strict';

		// Store original SVG paths when page loads
		const originalPaths = new Map();

		// Function to capture only specific SVG path data (feature icons, not navigation)
		function captureOriginalPaths() {
			const paths = document.querySelectorAll('svg path[d]');
			paths.forEach((path, index) => {
				const d = path.getAttribute('d');
				const svg = path.closest('svg');

				// Only protect SVGs in feature sections, not navigation or UI elements
				if (d && d.trim() && shouldProtectSVG(svg, path)) {
					originalPaths.set(path, {
						d: d,
						originalHTML: path.outerHTML
					});
					// Add debugging attribute
					path.setAttribute('data-svg-protect', 'true');
					path.setAttribute('data-svg-index', index);
				}
			});
			console.log('SVG Protection: Captured', originalPaths.size, 'protected SVG paths');
		}

		// Function to determine if an SVG should be protected
		function shouldProtectSVG(svg, path) {
			if (!svg) return false;

			// Don't protect navigation-related SVGs
			const dontProtectSelectors = [
				'.nav',
				'.navigation',
				'.menu',
				'.hamburger',
				'.mobile-nav',
				'.header',
				'.burger',
				'.menu-toggle'
			];

			for (const selector of dontProtectSelectors) {
				if (svg.closest(selector)) {
					return false;
				}
			}

			// Protect SVGs in feature sections, cards, service areas, etc.
			const protectSelectors = [
				'.feature',
				'.service',
				'.icon',
				'.why-choose',
				'.benefit',
				'.process',
				'[class*="feature"]',
				'[class*="service"]',
				'[class*="icon"]'
			];

			for (const selector of protectSelectors) {
				if (svg.closest(selector)) {
					return true;
				}
			}

			// Also protect SVGs with complex paths (likely icons, not simple UI elements)
			const pathLength = path.getAttribute('d')?.length || 0;
			if (pathLength > 100) { // Complex paths are likely icons
				return true;
			}

			return false;
		}

		// Function to restore missing paths
		function restoreMissingPaths() {
			const paths = document.querySelectorAll('svg path[data-svg-protect="true"]');
			let restored = 0;

			paths.forEach(path => {
				const original = originalPaths.get(path);
				if (original && !path.getAttribute('d')) {
					// Restore the d attribute
					path.setAttribute('d', original.d);
					restored++;
					console.log('SVG Protection: Restored path for element', path);
				}
			});

			if (restored > 0) {
				console.log('SVG Protection: Restored', restored, 'missing SVG paths');
			}
		}

		// MutationObserver to detect and prevent path removal
		const observer = new MutationObserver((mutations) => {
			let needsRestoration = false;

			mutations.forEach((mutation) => {
				if (mutation.type === 'attributes' && mutation.attributeName === 'd') {
					const target = mutation.target;
					if (target.tagName === 'path' && target.hasAttribute('data-svg-protect')) {
						const original = originalPaths.get(target);
						if (original && !target.getAttribute('d')) {
							// IMMEDIATE restoration - don't wait
							target.setAttribute('d', original.d);
							needsRestoration = true;
							console.warn('SVG Protection: Detected removal of path d attribute - IMMEDIATELY restored', target);
						}
					}
				}

				// Check for removed path elements
				mutation.removedNodes.forEach((node) => {
					if (node.nodeType === Node.ELEMENT_NODE && node.tagName === 'path') {
						if (node.hasAttribute && node.hasAttribute('data-svg-protect')) {
							console.warn('SVG Protection: Detected removed path element - re-adding to parent', node);
							needsRestoration = true;

							// Try to re-insert the removed node back to its parent
							if (mutation.target && mutation.target.appendChild) {
								mutation.target.appendChild(node.cloneNode(true));
							}
						}
					}
				});

				// Detect optimizeDOM function calls and prevent them
				if (mutation.type === 'childList' && mutation.removedNodes.length > 0) {
					// Check if any SVG paths were removed by optimizeDOM
					let svgPathsRemoved = false;
					mutation.removedNodes.forEach((node) => {
						if (node.nodeType === Node.ELEMENT_NODE) {
							const paths = node.querySelectorAll ? node.querySelectorAll('path[data-svg-protect]') : [];
							if (paths.length > 0 || (node.tagName === 'path' && node.hasAttribute('data-svg-protect'))) {
								svgPathsRemoved = true;
							}
						}
					});

					if (svgPathsRemoved && !window.SVGProtection.optimizeDOMBlocked) {
						console.error('SVG Protection: optimizeDOM function detected removing SVG paths! Blocking further calls.');
						// Try to disable optimizeDOM if it exists
						if (window.optimizeDOM) {
							window.optimizeDOM = function() {
								console.warn('SVG Protection: Blocked optimizeDOM call to protect SVG paths');
								return;
							};
							window.SVGProtection.optimizeDOMBlocked = true;
						}
					}
				}
			});

			if (needsRestoration) {
				// Schedule additional restoration as backup
				setTimeout(restoreMissingPaths, 10);
				setTimeout(restoreMissingPaths, 100);
			}
		});

		// Start observing
		function startObserving() {
			observer.observe(document.body, {
				attributes: true,
				attributeOldValue: true,
				childList: true,
				subtree: true
			});
		}

		// Initialize when DOM is ready
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', () => {
				captureOriginalPaths();
				startObserving();
				// Initial restoration check
				setTimeout(restoreMissingPaths, 100);
			});
		} else {
			captureOriginalPaths();
			startObserving();
			setTimeout(restoreMissingPaths, 100);
		}

		// Also check periodically as a backup
		setInterval(restoreMissingPaths, 2000);

		// Aggressive protection - Override common DOM manipulation methods
		const originalRemoveChild = Node.prototype.removeChild;
		const originalRemoveAttribute = Element.prototype.removeAttribute;
		const originalSetAttribute = Element.prototype.setAttribute;

		Node.prototype.removeChild = function(child) {
			if (child.tagName === 'path' && child.hasAttribute('data-svg-protect')) {
				console.warn('SVG Protection: Blocked removeChild() call on protected SVG path', child);
				return child; // Don't actually remove it
			}
			return originalRemoveChild.call(this, child);
		};

		Element.prototype.removeAttribute = function(attrName) {
			if (this.tagName === 'path' && attrName === 'd' && this.hasAttribute('data-svg-protect')) {
				console.warn('SVG Protection: Blocked removeAttribute("d") call on protected SVG path', this);
				return; // Don't actually remove the attribute
			}
			return originalRemoveAttribute.call(this, attrName);
		};

		Element.prototype.setAttribute = function(name, value) {
			// Allow our own protection script to set attributes
			if (this.tagName === 'path' && name === 'd' && this.hasAttribute('data-svg-protect') && (!value || value.trim() === '')) {
				console.warn('SVG Protection: Blocked setAttribute("d", "") call on protected SVG path', this);
				return; // Don't allow clearing the d attribute
			}
			return originalSetAttribute.call(this, name, value);
		};

		// Override innerHTML to protect SVG paths
		const originalInnerHTML = Object.getOwnPropertyDescriptor(Element.prototype, 'innerHTML');
		Object.defineProperty(Element.prototype, 'innerHTML', {
			set: function(value) {
				if (this.tagName === 'path' && this.hasAttribute('data-svg-protect')) {
					console.warn('SVG Protection: Blocked innerHTML modification on protected SVG path', this);
					return;
				}
				return originalInnerHTML.set.call(this, value);
			},
			get: originalInnerHTML.get
		});

		// Expose for debugging
		window.SVGProtection = {
			originalPaths: originalPaths,
			restoreMissingPaths: restoreMissingPaths,
			captureOriginalPaths: captureOriginalPaths,
			disabled: false
		};
	})();
	</script>
	<?php
}
// SVG protection no longer needed since optimizeDOM was fixed
// add_action('wp_footer', 'sunnysideac_add_svg_protection');