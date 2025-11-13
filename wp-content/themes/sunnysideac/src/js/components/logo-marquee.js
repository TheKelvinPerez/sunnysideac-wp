/**
 * Logo Marquee Component
 * Vanilla JavaScript implementation for continuous scrolling marquee of company logos
 */

export function initLogoMarquee() {
	// Debug mode - set to true to enable verbose logging
	const DEBUG = false;

	// DOM elements
	const container = document.getElementById('logo-marquee-container');

	if (!container) {
		console.error('[Logo Marquee] Container not found');
		return;
	}

	// Get logo data from data attribute
	let originalLogos = [];
	try {
		const logosData = container.getAttribute('data-logos');
		if (!logosData) {
			console.error('[Logo Marquee] No logo data found on container');
			return;
		}
		originalLogos = JSON.parse(logosData);

		if (!originalLogos || originalLogos.length === 0) {
			console.error('[Logo Marquee] Logo data is empty');
			return;
		}

		if (DEBUG) {
			console.log('[Logo Marquee] Loaded logos:', originalLogos.length);
		}
	} catch (error) {
		console.error('[Logo Marquee] Error parsing logo data:', error);
		return;
	}

	// State
	let translateX = 0;
	let isPaused = false;
	let logoInstances = [];
	let animationRef = null;
	let setCounter = 0;
	let frameCount = 0;
	let lastLogTime = 0;

	// Animation speed
	const speed = 0.8; // pixels per frame at 60fps

	// Cache for measured values to prevent forced reflows
	let cachedLogoWidth = null;
	let cachedContainerWidth = null;
	let resizeObserver = null;
	let lastResizeCheck = 0;

	// Measure actual logo width (cached to prevent forced reflows)
	function getActualLogoWidth() {
		// Return cached value if available
		if (cachedLogoWidth !== null) {
			return cachedLogoWidth;
		}

		if (container.children.length > 0) {
			// Batch all DOM reads together to prevent forced reflows
			const firstLogo = container.children[0];

			// Use requestAnimationFrame to schedule measurements safely
			const measureLogo = () => {
				const computedStyle = window.getComputedStyle(firstLogo);
				const width = firstLogo.offsetWidth;
				const marginLeft = parseInt(computedStyle.marginLeft) || 0;
				const marginRight = parseInt(computedStyle.marginRight) || 0;
				const totalWidth = width + marginLeft + marginRight;

				if (DEBUG) {
					console.log('[Logo Marquee] Measured logo width:', {
						width,
						marginLeft,
						marginRight,
						totalWidth
					});
				}

				cachedLogoWidth = totalWidth;
				return totalWidth;
			};

			return measureLogo();
		}
		cachedLogoWidth = 136; // fallback (96px + 40px margins)
		return cachedLogoWidth;
	}

	// Get container width (cached to prevent forced reflows)
	function getContainerWidth() {
		// Return cached value if available
		if (cachedContainerWidth !== null) {
			return cachedContainerWidth;
		}

		if (container && container.parentElement) {
			const width = container.parentElement.offsetWidth;
			cachedContainerWidth = width;
			return width;
		}
		return 800; // fallback width
	}

	// Setup ResizeObserver to invalidate cache when dimensions change
	function setupResizeObserver() {
		if (!window.ResizeObserver) {
			return; // ResizeObserver not supported
		}

		resizeObserver = new ResizeObserver(entries => {
			// Invalidate cache when container resizes
			cachedContainerWidth = null;
			cachedLogoWidth = null;
			if (DEBUG) {
				console.log('[Logo Marquee] Container resized, cache invalidated');
			}
		});

		// Observe container and parent for size changes
		if (container.parentElement) {
			resizeObserver.observe(container.parentElement);
		}
		if (container.children.length > 0) {
			resizeObserver.observe(container.children[0]);
		}
	}

	// Create logo element HTML
	function createLogoElement(instance) {
		const div = document.createElement('div');
		div.className = 'flex h-16 w-24 flex-shrink-0 cursor-pointer items-center justify-center transition-transform duration-300 hover:scale-110 md:h-20 md:w-32 lg:h-24 lg:w-40 ml-10';
		div.setAttribute('data-website', instance.logo.website);
		div.setAttribute('data-name', instance.logo.name);
		div.setAttribute('data-set-id', instance.setId);
		div.setAttribute('data-global-index', instance.globalIndex);
		div.setAttribute('title', `Visit ${instance.logo.name} website`);

		const img = document.createElement('img');
		img.src = instance.logo.src;
		img.alt = instance.logo.alt;
		img.className = 'max-h-full max-w-full object-contain';
		img.loading = 'lazy';
		img.decoding = 'async';
		img.sizes = '(max-width: 768px) 96px, (max-width: 1024px) 128px, 160px';

		div.appendChild(img);
		return div;
	}

	// Create a set of logos
	function createLogoSet(setId) {
		return originalLogos.map((logo, index) => ({
			setId,
			logo,
			uniqueId: `${setId}-${logo.id}-${index}`,
			globalIndex: logoInstances.length + index
		}));
	}

	// Initialize with 2 sets of logos
	function initializeLogos() {
		if (!originalLogos || originalLogos.length === 0) {
			console.error('[Logo Marquee] Cannot initialize - no logos available');
			return false;
		}

		// Start with 2 sets for optimal performance
		const initialSets = [
			...createLogoSet('set-0'),
			...createLogoSet('set-1'),
		];

		logoInstances = initialSets;
		setCounter = 2;

		// Clear container and add initial logos
		container.innerHTML = '';
		logoInstances.forEach((instance) => {
			const element = createLogoElement(instance);
			container.appendChild(element);
		});

		// Batch DOM measurements after DOM is updated to prevent forced reflows
		requestAnimationFrame(() => {
			if (DEBUG) {
				console.log('[Logo Marquee] Initialized with', logoInstances.length, 'logo instances');
				console.log('[Logo Marquee] Container width:', getContainerWidth());
				console.log('[Logo Marquee] Logo width:', getActualLogoWidth());
			}
		});

		return true;
	}

	// Handle logo click
	function handleLogoClick(website) {
		window.open(website, '_blank', 'noopener,noreferrer');
	}

	// Animation function
	function animate() {
		frameCount++;
		const currentTime = Date.now();

		if (!isPaused && container && logoInstances.length > 0) {
			const oldTranslateX = translateX;
			let didCycle = false;

			translateX -= speed;

			// Use cached measurements to prevent forced reflows
			const actualLogoWidth = getActualLogoWidth();
			const containerWidth = getContainerWidth();
			const bufferZone = containerWidth * 0.4; // 40% buffer space

			// Log every 300 frames (approximately every 5 seconds at 60fps)
			if (DEBUG && frameCount % 300 === 0) {
				console.log('[Logo Marquee] Animation state:', {
					translateX: translateX.toFixed(2),
					actualLogoWidth,
					containerWidth,
					isPaused,
					logoCount: logoInstances.length,
					frameCount
				});
			}

			// Check if we need to cycle individual elements
			if (translateX < -actualLogoWidth) {
				didCycle = true;

				if (DEBUG) {
					console.log('[Logo Marquee] Cycling logo:', {
						oldTranslateX: oldTranslateX.toFixed(2),
						newTranslateX: translateX.toFixed(2),
						actualLogoWidth,
						willResetTo: (translateX + actualLogoWidth).toFixed(2)
					});
				}

				// Move the first element to the end
				const firstInstance = logoInstances.shift();
				if (firstInstance) {
					logoInstances.push(firstInstance);

					// Batch DOM operations: remove first element
					if (container.firstChild) {
						container.removeChild(container.firstChild);
					}

					// Reset translateX to account for the moved element
					const beforeReset = translateX;
					translateX += actualLogoWidth;

					if (DEBUG && Math.abs(translateX - beforeReset - actualLogoWidth) > 0.1) {
						console.warn('[Logo Marquee] TranslateX reset mismatch:', {
							before: beforeReset.toFixed(2),
							after: translateX.toFixed(2),
							expected: (beforeReset + actualLogoWidth).toFixed(2)
						});
					}

					// Create and append the element at the end
					const element = createLogoElement(firstInstance);
					container.appendChild(element);
				}
			}

			// Apply transform (only DOM write in this frame)
			const transformValue = `translateX(${translateX}px)`;
			container.style.transform = transformValue;

			// Log if transform appears to be reset unexpectedly (but not during intentional cycles)
			if (DEBUG && !didCycle && frameCount > 60 && Math.abs(translateX - oldTranslateX + speed) > 0.5) {
				console.warn('[Logo Marquee] Unexpected translateX change detected:', {
					expected: (oldTranslateX - speed).toFixed(2),
					actual: translateX.toFixed(2),
					difference: Math.abs(translateX - (oldTranslateX - speed)).toFixed(2),
					frameCount
				});
			}
		}

		animationRef = requestAnimationFrame(animate);
	}

	// Add event listeners for pause on hover
	function addEventListeners() {
		container.addEventListener('click', function(e) {
			const logoElement = e.target.closest('[data-website]');
			if (logoElement) {
				const website = logoElement.getAttribute('data-website');
				if (DEBUG) {
					console.log('[Logo Marquee] Logo clicked:', logoElement.getAttribute('data-name'));
				}
				handleLogoClick(website);
			}
		});

		container.addEventListener('mouseenter', () => {
			if (DEBUG) console.log('[Logo Marquee] Mouse enter - pausing');
			isPaused = true;
		});

		container.addEventListener('mouseleave', () => {
			if (DEBUG) console.log('[Logo Marquee] Mouse leave - resuming');
			isPaused = false;
		});

		// Touch events for mobile
		container.addEventListener('touchstart', () => {
			if (DEBUG) console.log('[Logo Marquee] Touch start - pausing');
			isPaused = true;
		}, { passive: true });

		container.addEventListener('touchend', () => {
			if (DEBUG) console.log('[Logo Marquee] Touch end - resuming');
			isPaused = false;
		}, { passive: true });

		// Pause when tab is not visible
		document.addEventListener('visibilitychange', () => {
			const wasPaused = isPaused;
			isPaused = document.hidden;
			if (DEBUG && wasPaused !== isPaused) {
				console.log('[Logo Marquee] Visibility changed:', document.hidden ? 'hidden' : 'visible');
			}
		});

		// Add scroll event listener to detect if container is being scrolled
		container.parentElement.addEventListener('scroll', (e) => {
			if (DEBUG) {
				console.warn('[Logo Marquee] Scroll event detected on parent:', e.target.scrollLeft);
			}
		});

		// Check for any CSS transitions that might interfere
		const containerStyles = window.getComputedStyle(container);
		if (DEBUG) {
			console.log('[Logo Marquee] Container computed styles:', {
				transition: containerStyles.transition,
				transform: containerStyles.transform,
				willChange: containerStyles.willChange
			});

			// Warn if transitions are detected
			if (containerStyles.transition && containerStyles.transition !== 'none' && containerStyles.transition !== 'all 0s ease 0s') {
				console.warn('[Logo Marquee] ⚠️  CSS transition detected on container! This may cause visual glitches during logo cycling.');
			}
		}
	}

	// Initialize
	function init() {
		const success = initializeLogos();
		if (!success) {
			console.error('[Logo Marquee] Initialization failed - aborting');
			return;
		}

		addEventListeners();
		setupResizeObserver();

		// Start animation
		animationRef = requestAnimationFrame(animate);
	}

	// Ensure we wait for both DOM and container to be ready
	function safeInit() {
		// Double-check container still exists and has data
		const containerCheck = document.getElementById('logo-marquee-container');
		if (!containerCheck) {
			console.error('[Logo Marquee] Container disappeared before init');
			return;
		}

		const logosDataCheck = containerCheck.getAttribute('data-logos');
		if (!logosDataCheck) {
			console.error('[Logo Marquee] Logo data not available at init time');
			// Try again after a short delay
			setTimeout(() => {
				if (DEBUG) console.log('[Logo Marquee] Retrying initialization...');
				safeInit();
			}, 100);
			return;
		}

		init();
	}

	// Start when DOM is ready
	if (document.readyState === 'loading') {
		if (DEBUG) console.log('[Logo Marquee] Waiting for DOMContentLoaded');
		document.addEventListener('DOMContentLoaded', safeInit);
	} else {
		if (DEBUG) console.log('[Logo Marquee] DOM already loaded, initializing immediately');
		// Use setTimeout to ensure PHP has finished rendering
		setTimeout(safeInit, 0);
	}

	// Cleanup on page unload
	window.addEventListener('beforeunload', () => {
		if (animationRef) {
			if (DEBUG) console.log('[Logo Marquee] Cleaning up animation');
			cancelAnimationFrame(animationRef);
		}
		if (resizeObserver) {
			resizeObserver.disconnect();
			if (DEBUG) console.log('[Logo Marquee] ResizeObserver disconnected');
		}
	});

	// Expose for debugging
	window.__logoMarquee = {
		getState: () => ({
			translateX,
			isPaused,
			logoInstances: logoInstances.length,
			setCounter,
			frameCount,
			containerWidth: getContainerWidth(),
			logoWidth: getActualLogoWidth(),
			cachedLogoWidth,
			cachedContainerWidth
		}),
		pause: () => {
			console.log('[Logo Marquee] Manual pause');
			isPaused = true;
		},
		resume: () => {
			console.log('[Logo Marquee] Manual resume');
			isPaused = false;
		},
		reset: () => {
			console.log('[Logo Marquee] Manual reset - invalidating cache');
			translateX = 0;
			frameCount = 0;
			cachedLogoWidth = null;
			cachedContainerWidth = null;
			initializeLogos();
		},
		invalidateCache: () => {
			console.log('[Logo Marquee] Manual cache invalidation');
			cachedLogoWidth = null;
			cachedContainerWidth = null;
		},
		toggleDebug: () => {
			// This won't work dynamically since DEBUG is const, but helpful reminder
			console.log('[Logo Marquee] To toggle debug, set DEBUG constant at top of file');
		}
	};

	if (DEBUG) {
		console.log('[Logo Marquee] Debug API available at window.__logoMarquee');
		console.log('[Logo Marquee] Initialization complete');
	}
}
