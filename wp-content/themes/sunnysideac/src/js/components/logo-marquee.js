/**
 * Logo Marquee Component
 * Displays a continuous scrolling marquee of company logos
 */

export function initLogoMarquee() {
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
		div.setAttribute('title', `Visit ${instance.logo.name} website`);

		const img = document.createElement('img');
		img.src = instance.logo.src;
		img.alt = instance.logo.alt;
		img.className = 'max-h-full max-w-full object-contain';
		img.loading = 'lazy';
		img.decoding = 'async';
		img.sizes = '(max-width: 768px) 96px, (max-width: 1024px) 128px, 160px';
		img.width = 160;
		img.height = 80;

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
		// Start with 2 sets for optimal performance
		const set0 = createLogoSet('set-0');
		const set1 = createLogoSet('set-1');
		const initialSets = [...set0, ...set1];

		logoInstances = initialSets;
		setCounter = 2;

		// Clear container and add initial logos
		container.innerHTML = '';
		logoInstances.forEach((instance) => {
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
			translateX -= speed;

			// Use actual measured logo width
			const actualLogoWidth = getActualLogoWidth();

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
			container.style.transform = `translateX(${translateX}px)`;
		}

		animationRef = requestAnimationFrame(animate);
	}

	// Add event listeners for pause on hover
	function addEventListeners() {
		container.addEventListener('click', (e) => {
			const logoElement = e.target.closest('[data-website]');
			if (logoElement) {
				const website = logoElement.getAttribute('data-website');
				handleLogoClick(website);
			}
		});

		container.addEventListener('mouseenter', () => {
			isPaused = true;
		});

		container.addEventListener('mouseleave', () => {
			isPaused = false;
		});

		// Touch events for mobile
		container.addEventListener('touchstart', () => {
			isPaused = true;
		}, { passive: true });

		container.addEventListener('touchend', () => {
			isPaused = false;
		}, { passive: true });

		// Pause when tab is not visible
		document.addEventListener('visibilitychange', () => {
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

	// Start initialization
	init();

	// Cleanup on page unload
	window.addEventListener('beforeunload', () => {
		if (animationRef) {
			cancelAnimationFrame(animationRef);
		}
	});

	// Expose for debugging
	if (typeof window !== 'undefined') {
		window.__logoMarquee = {
			getState: () => ({
				translateX,
				isPaused,
				logoInstances: logoInstances.length,
				setCounter
			}),
			pause: () => {
				isPaused = true;
			},
			resume: () => {
				isPaused = false;
			},
			reset: () => {
				translateX = 0;
				initializeLogos();
			}
		};
	}

	console.log('✅ Logo marquee initialized');
}
