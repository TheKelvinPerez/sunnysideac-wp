<?php
/**
 * Performance Optimizations
 *
 * DOM size optimization, mobile-specific optimizations, and service worker cleanup
 */

/**
 * DOM size optimization for mobile performance
 */
function sunnysideac_optimize_dom_size() {
	?>
	<script>
		// Remove unnecessary DOM elements for mobile performance
		function optimizeDOM() {
			// Remove empty elements
			const emptyElements = document.querySelectorAll('*:empty:not(script):not(style):not(link):not(meta):not(br):not(input):not(img):not(hr):not(svg):not(path)');
			emptyElements.forEach(el => {
				if (el.children.length === 0 && el.textContent.trim() === '') {
					// Don't remove elements that have styling or specific attributes
					const hasStyling = el.className ||
										el.getAttribute('role') ||
										el.getAttribute('style') ||
										['H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'DIV', 'SPAN'].includes(el.tagName);
					if (!hasStyling) {
						el.remove();
					}
				}
			});

			// Remove duplicate navigation for mobile (keep only one)
			if (window.innerWidth <= 768) {
				const mobileNav = document.querySelector('#mobile-menu');
				const desktopNav = document.querySelector('#desktop-nav');
				if (mobileNav && desktopNav) {
					// Hide desktop nav on mobile
					desktopNav.style.display = 'none';
				}
			}

			// Lazy load non-critical sections
			const lazySections = document.querySelectorAll('[data-lazy-section]');
			if ('IntersectionObserver' in window) {
				const sectionObserver = new IntersectionObserver((entries) => {
					entries.forEach(entry => {
						if (entry.isIntersecting) {
							const section = entry.target;
							section.classList.remove('lazy-section');
							sectionObserver.unobserve(section);
						}
					});
				});

				lazySections.forEach(section => {
					section.classList.add('lazy-section');
					sectionObserver.observe(section);
				});
			}

			// Remove unused WordPress elements (but preserve SVGs)
			const wpElements = document.querySelectorAll('.wp-block-spacer, .has-background, .wp-block-group__inner-container:empty');
			wpElements.forEach(el => {
				if (el.children.length === 0 && el.textContent.trim() === '') {
					// Don't remove if this element contains SVGs
					if (!el.querySelector('svg')) {
						el.remove();
					}
				}
			});
		}

		// Run DOM optimization when DOM is loaded
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', optimizeDOM);
		} else {
			optimizeDOM();
		}

		// Also run after page load to catch dynamic content
		window.addEventListener('load', function() {
			setTimeout(optimizeDOM, 1000);
		});
	</script>

	<style>
		/* Lazy section styling */
		.lazy-section {
			opacity: 0;
			transform: translateY(20px);
			transition: opacity 0.3s ease, transform 0.3s ease;
		}

		.lazy-section:not(.lazy-section) {
			opacity: 1;
			transform: translateY(0);
		}

		/* Hide desktop navigation on mobile */
		@media (max-width: 768px) {
			#desktop-nav {
				display: none !important;
			}
		}

		/* Hide mobile navigation on desktop */
		@media (min-width: 769px) {
			#mobile-menu {
				display: none !important;
			}
		}
	</style>
	<?php
}
add_action('wp_footer', 'sunnysideac_optimize_dom_size', 50);

/**
 * Mobile-specific performance optimizations
 */
function sunnysideac_mobile_optimizations() {
	?>
	<script>
		// Mobile performance optimizations
		if (window.innerWidth <= 768) {
			// Reduce JavaScript execution on mobile
			function throttle(func, limit) {
				let inThrottle;
				return function() {
					const args = arguments;
					const context = this;
					if (!inThrottle) {
						func.apply(context, args);
						inThrottle = true;
						setTimeout(() => inThrottle = false, limit);
					}
				};
			}

			// Throttle scroll events on mobile
			if (window.addEventListener) {
				const originalAddEventListener = EventTarget.prototype.addEventListener;
				EventTarget.prototype.addEventListener = function(type, listener, options) {
					if (type === 'scroll') {
						listener = throttle(listener, 100);
					}
					return originalAddEventListener.call(this, type, listener, options);
				};
			}

			// Disable hover effects on touch devices
			if ('ontouchstart' in window) {
				document.documentElement.classList.add('touch-device');
			}

			// Reduce animation complexity on mobile
			const style = document.createElement('style');
			style.textContent = `
				@media (max-width: 768px) {
					*, *::before, *::after {
						animation-duration: 0.2s !important;
						transition-duration: 0.2s !important;
					}

					.hero-section {
						min-height: 50vh !important;
					}

					.logo-marquee {
						animation-duration: 20s !important;
					}
				}
			`;
			document.head.appendChild(style);
		}

		// Preload critical resources based on device type
		function preloadCriticalResources() {
			const isMobile = window.innerWidth <= 768;

			if (isMobile) {
				// Preload mobile-specific critical resources
				const mobileCSS = document.createElement('link');
				mobileCSS.rel = 'preload';
				mobileCSS.href = '<?php
				$manifest_path = get_template_directory() . "/dist/.vite/manifest.json";
				if (file_exists($manifest_path)) {
					$manifest = json_decode(file_get_contents($manifest_path), true);
					if (isset($manifest["src/main.js"]["css"][0])) {
						echo get_template_directory_uri() . "/dist/" . $manifest["src/main.js"]["css"][0];
					} else {
						echo get_template_directory_uri() . "/dist/assets/main.css"; // fallback
					}
				} else {
					echo get_template_directory_uri() . "/dist/assets/main.css"; // fallback
				}
			?>';
				mobileCSS.as = 'style';
				document.head.appendChild(mobileCSS);

				// Preload critical images for mobile
				const heroImage = document.querySelector('.hero-section img');
				if (heroImage && heroImage.dataset.src) {
					const imgPreload = document.createElement('link');
					imgPreload.rel = 'preload';
					imgPreload.as = 'image';
					imgPreload.href = heroImage.dataset.src;
					document.head.appendChild(imgPreload);
				}
			}
		}

		// Run preloading early
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', preloadCriticalResources);
		} else {
			preloadCriticalResources();
		}
	</script>
	<?php
}
add_action('wp_head', 'sunnysideac_mobile_optimizations', 1);

/**
 * Unregister Service Worker and clear all caches
 * This script will run on page load to clean up the old service worker
 * TODO: Remove this function after 1-2 weeks once all users' service workers are cleared
 */
function sunnysideac_unregister_service_worker() {
	?>
	<script>
		if ('serviceWorker' in navigator) {
			navigator.serviceWorker.getRegistrations().then(function(registrations) {
				for(let registration of registrations) {
					registration.unregister().then(function(success) {
						if (success) {
							console.log('✅ ServiceWorker unregistered successfully');
						}
					});
				}
			});

			// Clear all caches
			if ('caches' in window) {
				caches.keys().then(function(cacheNames) {
					return Promise.all(
						cacheNames.map(function(cacheName) {
							console.log('🗑️ Deleting cache:', cacheName);
							return caches.delete(cacheName);
						})
					);
				}).then(function() {
					console.log('✅ All caches cleared');
				});
			}
		}
	</script>
	<?php
}
add_action('wp_footer', 'sunnysideac_unregister_service_worker', 1);