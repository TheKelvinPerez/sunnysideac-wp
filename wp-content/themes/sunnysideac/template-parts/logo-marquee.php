<?php
/**
 * Logo Marquee Component
 * Displays a continuous scrolling marquee of company logos
 */

// Define company logos
$company_logos = array(
	array(
		'id'      => 1,
		'name'    => 'Bryant',
		'src'     => get_template_directory_uri() . '/assets/optimized/Bryant-Logo.webp',
		'alt'     => 'Bryant HVAC logo',
		'website' => home_url( '/brands/bryant/' ),
	),
	array(
		'id'      => 2,
		'name'    => 'Carrier',
		'src'     => get_template_directory_uri() . '/assets/optimized/Carrier-Logo.webp',
		'alt'     => 'Carrier air conditioning logo',
		'website' => home_url( '/brands/carrier/' ),
	),
	array(
		'id'      => 3,
		'name'    => 'Goodman',
		'src'     => get_template_directory_uri() . '/assets/optimized/Goodman-Logo.webp',
		'alt'     => 'Goodman HVAC logo',
		'website' => home_url( '/brands/goodman/' ),
	),
	array(
		'id'      => 4,
		'name'    => 'Lennox',
		'src'     => get_template_directory_uri() . '/assets/optimized/Lennox-Logo.webp',
		'alt'     => 'Lennox HVAC logo',
		'website' => home_url( '/brands/lennox/' ),
	),
	array(
		'id'      => 5,
		'name'    => 'Rheem',
		'src'     => get_template_directory_uri() . '/assets/optimized/Rheem-Logo.webp',
		'alt'     => 'Rheem HVAC logo',
		'website' => home_url( '/brands/rheem/' ),
	),
	array(
		'id'      => 6,
		'name'    => 'Trane',
		'src'     => get_template_directory_uri() . '/assets/optimized/Trane-Logo.webp',
		'alt'     => 'Trane air conditioning logo',
		'website' => home_url( '/brands/trane/' ),
	),
	array(
		'id'      => 7,
		'name'    => 'Daikin',
		'src'     => get_template_directory_uri() . '/assets/optimized/daikin-logo.webp',
		'alt'     => 'Daikin HVAC logo',
		'website' => home_url( '/brands/daikin/' ),
	),
);

$icon_url = get_template_directory_uri() . '/assets/images/home-page/trusted-brands-section-icon.svg';
?>

<section
	class="w-full overflow-hidden rounded-[20px] bg-white py-12"
	aria-label="Trusted brands"
>
	<div class="mx-auto max-w-7xl px-6 md:px-8 lg:px-10">
		<header class="mb-8 text-center">
			<?php
			get_template_part(
				'template-parts/title',
				null,
				array(
					'icon'  => $icon_url,
					'title' => 'Brands We Service',
					'id'    => 'trusted-brands-heading',
				)
			);
			?>
		</header>

		<div class="relative overflow-hidden rounded-[20px]">
			<div
				id="logo-marquee-container"
				class="flex items-center"
				style="width: fit-content;"
				data-logos="<?php echo esc_attr( wp_json_encode( $company_logos ) ); ?>"
			>
				<!-- Logo instances will be dynamically added here -->
			</div>

			<!-- Left fade overlay -->
			<div
				class="pointer-events-none absolute top-0 left-0 z-10 h-full w-16 bg-gradient-to-r from-white to-transparent md:w-20 lg:w-24"
				aria-hidden="true"
			></div>

			<!-- Right fade overlay -->
			<div
				class="pointer-events-none absolute top-0 right-0 z-10 h-full w-16 bg-gradient-to-r from-transparent to-white md:w-20 lg:w-24"
				aria-hidden="true"
			></div>
		</div>
	</div>
</section>

<script>
/* Vanilla JavaScript implementation based on the guide */
(function() {
	// DOM elements
	const container = document.getElementById('logo-marquee-container');

	if (!container) {
		console.error('Logo marquee container not found');
		return;
	}

	// Get logo data from data attribute
	let originalLogos = [];
	try {
		const logosData = container.getAttribute('data-logos');
		if (logosData) {
			originalLogos = JSON.parse(logosData);
		}
	} catch (error) {
		console.error('Error parsing logo data:', error);
		return;
	}

	// State
	let translateX = 0;
	let isPaused = false;
	let logoInstances = [];
	let animationRef = null;
	let setCounter = 0;

	// Animation speed
	const speed = 0.8; // pixels per frame at 60fps

	// Measure actual logo width
	function getActualLogoWidth() {
		if (container.children.length > 0) {
			const firstLogo = container.children[0];
			const computedStyle = window.getComputedStyle(firstLogo);
			const width = firstLogo.offsetWidth;
			const marginLeft = parseInt(computedStyle.marginLeft) || 0;
			const marginRight = parseInt(computedStyle.marginRight) || 0;
			return width + marginLeft + marginRight;
		}
		return 136; // fallback (96px + 40px margins)
	}

	// Create logo element HTML
	function createLogoElement(instance) {
		const div = document.createElement('div');
		div.className = 'flex h-16 w-24 flex-shrink-0 cursor-pointer items-center justify-center transition-transform duration-300 hover:scale-110 md:h-20 md:w-32 lg:h-24 lg:w-40 ml-10';
		div.setAttribute('data-website', instance.logo.website);
		div.setAttribute('data-name', instance.logo.name);
		div.setAttribute('data-set-id', instance.setId);
		div.setAttribute('data-global-index', instance.globalIndex);
		div.setAttribute('title', 'View ' + instance.logo.name + ' HVAC services');

		div.innerHTML = '<img src="' + instance.logo.src + '" alt="' + instance.logo.alt + '" class="max-h-full max-w-full object-contain" loading="lazy" decoding="async" sizes="(max-width: 768px) 96px, (max-width: 1024px) 128px, 160px" />';

		return div;
	}

	// Create a set of logos
	function createLogoSet(setId) {
		return originalLogos.map(function(logo, index) {
			return {
				setId: setId,
				logo: logo,
				uniqueId: setId + '-' + logo.id + '-' + index,
				globalIndex: logoInstances.length + index
			};
		});
	}

	// Initialize with 2 sets of logos
	function initializeLogos() {
		// Start with 2 sets for optimal performance
		const set0 = createLogoSet('set-0');
		const set1 = createLogoSet('set-1');
		const initialSets = set0.concat(set1);

		logoInstances = initialSets;
		setCounter = 2;

		// Clear container and add initial logos
		container.innerHTML = '';
		logoInstances.forEach(function(instance) {
			const element = createLogoElement(instance);
			container.appendChild(element);
		});
	}

	// Handle logo click
	function handleLogoClick(website) {
		window.location.href = website;
	}

	// Animation function
	function animate() {
		if (!isPaused && container && logoInstances.length > 0) {
			const oldTranslateX = translateX;
			translateX -= speed;

			// Use actual measured logo width
			const actualLogoWidth = getActualLogoWidth();
			const containerWidth = container.parentElement.offsetWidth;
			const bufferZone = containerWidth * 0.4; // 40% buffer space

			// Check if we need to cycle individual elements
			if (translateX < -actualLogoWidth) {
				// Move the first element to the end
				const firstInstance = logoInstances.shift();
				if (firstInstance) {
					logoInstances.push(firstInstance);

					// Remove the first DOM element and recreate it at the end
					if (container.firstChild) {
						container.removeChild(container.firstChild);
					}

					// Reset translateX to account for the moved element
					translateX += actualLogoWidth;

					// Recreate and append the element at the end
					const element = createLogoElement(firstInstance);
					container.appendChild(element);
				}
			}

			// Apply transform
			container.style.transform = 'translateX(' + translateX + 'px)';
		}

		animationRef = requestAnimationFrame(animate);
	}

	// Add event listeners for pause on hover
	function addEventListeners() {
		container.addEventListener('click', function(e) {
			const logoElement = e.target.closest('[data-website]');
			if (logoElement) {
				const website = logoElement.getAttribute('data-website');
				handleLogoClick(website);
			}
		});

		container.addEventListener('mouseenter', function() {
			isPaused = true;
		});

		container.addEventListener('mouseleave', function() {
			isPaused = false;
		});

		// Touch events for mobile
		container.addEventListener('touchstart', function() {
			isPaused = true;
		}, { passive: true });

		container.addEventListener('touchend', function() {
			isPaused = false;
		}, { passive: true });

		// Pause when tab is not visible
		document.addEventListener('visibilitychange', function() {
			isPaused = document.hidden;
		});
	}

	// Initialize
	function init() {
		initializeLogos();
		addEventListeners();

		// Start animation
		animationRef = requestAnimationFrame(animate);
	}

	// Start when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}

	// Cleanup on page unload
	window.addEventListener('beforeunload', function() {
		if (animationRef) {
			cancelAnimationFrame(animationRef);
		}
	});

	// Expose for debugging
	window.__logoMarquee = {
		getState: function() {
			return {
				translateX: translateX,
				isPaused: isPaused,
				logoInstances: logoInstances.length,
				setCounter: setCounter
			};
		},
		pause: function() {
			isPaused = true;
		},
		resume: function() {
			isPaused = false;
		},
		reset: function() {
			translateX = 0;
			initializeLogos();
		}
	};
})();
</script>
