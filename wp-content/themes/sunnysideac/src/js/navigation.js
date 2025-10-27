/**
 * Navigation Module
 * Handles all interactive functionality for the main navigation
 * - Desktop mega menus (Services, Cities)
 * - Mobile navigation menu
 * - Call-to-action buttons
 * - Location selector
 */

// State variables
let activeMenuItem = 'Home';
let isServicesDropdownOpen = false;
let isServiceAreasDropdownOpen = false;
let isBrandsDropdownOpen = false;
let isMobileMenuOpen = false;
let selectedLocation = '';
let hoverTimeout = null;
let debugMode = false; // When true, disables hover/mouse events for manual control

// DOM elements (cached after initialization)
let elements = {};

/**
 * Cache DOM elements for performance
 */
function cacheDOMElements() {
	elements = {
		// Mobile menu elements
		mobileMenuToggle: document.getElementById('mobile-menu-toggle'),
		mobileMenu: document.getElementById('mobile-menu'),
		mobileMenuClose: document.getElementById('mobile-menu-close'),
		mobileMenuContent: document.getElementById('mobile-menu-content'),

		// Services dropdown elements
		servicesDropdownContainer: document.getElementById('services-dropdown-container'),
		servicesDropdown: document.querySelector('.services-dropdown'),
		servicesDropdownBtn: document.querySelector('.services-dropdown-btn'),
		servicesChevronIcon: document.getElementById('services-dropdown-container')?.querySelector('.chevron-icon'),

		// Service Areas dropdown elements
		serviceAreasDropdownContainer: document.getElementById('service-areas-dropdown-container'),
		serviceAreasDropdown: document.querySelector('.service-areas-dropdown'),
		serviceAreasDropdownBtn: document.querySelector('.service-areas-dropdown-btn'),
		serviceAreasChevronIcon: document.getElementById('service-areas-dropdown-container')?.querySelector('.chevron-icon'),

		// Brands dropdown elements
		brandsDropdownContainer: document.getElementById('brands-dropdown-container'),
		brandsDropdown: document.querySelector('.brands-dropdown'),
		brandsDropdownBtn: document.querySelector('.brands-dropdown-btn'),
		brandsChevronIcon: document.getElementById('brands-dropdown-container')?.querySelector('.chevron-icon'),

		// Location selector elements
		locationSelect: document.getElementById('location-select'),
		locationSelectBtn: document.getElementById('location-select-btn'),
		selectedLocationText: document.getElementById('selected-location-text'),

		// Call buttons
		mobileCallBtn: document.getElementById('mobile-call-btn'),
		mobileCallBtnHeader: document.getElementById('mobile-call-btn-header'),
		desktopCallBtn: document.getElementById('desktop-call-btn'),
	};
}

/**
 * Update active menu item styling
 * @param {string} itemName - Name of the menu item to set as active
 */
function updateActiveMenuItem(itemName) {
	activeMenuItem = itemName;
	document.querySelectorAll('.nav-item').forEach(item => {
		const itemData = item.dataset.item;

		// Find the text element (always a span now for all button types)
		const textElement = item.querySelector('span');

		if (itemData === itemName) {
			// Set active state
			item.classList.remove('bg-[#fde0a0]');
			item.classList.add('bg-[#ffc549]');
			item.setAttribute('aria-current', 'page');
			// Update text colors if text element exists
			if (textElement) {
				textElement.classList.remove('text-black');
				textElement.classList.add('text-[#e5462f]');
			}
		} else {
			// Set inactive state
			item.classList.remove('bg-[#ffc549]');
			item.classList.add('bg-[#fde0a0]');
			item.removeAttribute('aria-current');
			// Update text colors if text element exists
			if (textElement) {
				textElement.classList.remove('text-[#e5462f]');
				textElement.classList.add('text-black');
			}
		}
	});
}

/**
 * Handle call button clicks
 */
function handleCallUs() {
	const telHref = document.querySelector('[data-tel-href]')?.dataset.telHref || 'tel:+13059789382';
	window.location.href = telHref;
}

/**
 * Handle menu item clicks
 * @param {string} itemName - Name of the menu item
 * @param {string} href - URL to navigate to
 */
function handleMenuItemClick(itemName, href) {
	updateActiveMenuItem(itemName);
	if (itemName === 'Services') {
		// Toggle dropdown
		toggleServicesDropdown();
	} else if (itemName === 'Cities') {
		// Toggle dropdown
		toggleServiceAreasDropdown();
	} else if (itemName === 'Brands') {
		// Toggle dropdown
		toggleBrandsDropdown();
	} else {
		closeServicesDropdown();
		closeServiceAreasDropdown();
		closeBrandsDropdown();
		// Navigate to the page
		if (href && href !== '#') {
			window.location.href = href;
		}
	}
}

// ===== SERVICES DROPDOWN FUNCTIONS =====

function toggleServicesDropdown() {
	isServicesDropdownOpen = !isServicesDropdownOpen;
	updateServicesDropdown();
}

function openServicesDropdown() {
	// In debug mode, ignore hover events
	if (debugMode) return;

	if (hoverTimeout) {
		clearTimeout(hoverTimeout);
		hoverTimeout = null;
	}
	// Close other dropdowns when opening Services
	isServiceAreasDropdownOpen = false;
	updateServiceAreasDropdown();
	isBrandsDropdownOpen = false;
	updateBrandsDropdown();

	isServicesDropdownOpen = true;
	updateServicesDropdown();
}

function closeServicesDropdown() {
	// In debug mode, ignore hover events
	if (debugMode) return;

	isServicesDropdownOpen = false;
	updateServicesDropdown();
}

function delayedCloseServicesDropdown() {
	// In debug mode, ignore hover events
	if (debugMode) return;

	hoverTimeout = setTimeout(() => {
		closeServicesDropdown();
	}, 150);
}

function updateServicesDropdown() {
	const { servicesDropdown, servicesChevronIcon, servicesDropdownContainer } = elements;

	if (servicesDropdown) {
		if (isServicesDropdownOpen) {
			servicesDropdown.classList.remove('hidden');
			if (servicesChevronIcon) {
				servicesChevronIcon.style.transform = 'rotate(180deg)';
			}
			if (servicesDropdownContainer) {
				servicesDropdownContainer.setAttribute('aria-expanded', 'true');
			}
		} else {
			servicesDropdown.classList.add('hidden');
			if (servicesChevronIcon) {
				servicesChevronIcon.style.transform = 'rotate(0deg)';
			}
			if (servicesDropdownContainer) {
				servicesDropdownContainer.setAttribute('aria-expanded', 'false');
			}
		}
	}
}

// ===== SERVICE AREAS DROPDOWN FUNCTIONS =====

function toggleServiceAreasDropdown() {
	isServiceAreasDropdownOpen = !isServiceAreasDropdownOpen;
	updateServiceAreasDropdown();
}

function openServiceAreasDropdown() {
	// In debug mode, ignore hover events
	if (debugMode) return;

	if (hoverTimeout) {
		clearTimeout(hoverTimeout);
		hoverTimeout = null;
	}
	// Close other dropdowns when opening Cities
	isServicesDropdownOpen = false;
	updateServicesDropdown();
	isBrandsDropdownOpen = false;
	updateBrandsDropdown();

	isServiceAreasDropdownOpen = true;
	updateServiceAreasDropdown();
}

function closeServiceAreasDropdown() {
	// In debug mode, ignore hover events
	if (debugMode) return;

	isServiceAreasDropdownOpen = false;
	updateServiceAreasDropdown();
}

function delayedCloseServiceAreasDropdown() {
	// In debug mode, ignore hover events
	if (debugMode) return;

	hoverTimeout = setTimeout(() => {
		closeServiceAreasDropdown();
	}, 150);
}

function updateServiceAreasDropdown() {
	const { serviceAreasDropdown, serviceAreasChevronIcon, serviceAreasDropdownContainer } = elements;

	if (serviceAreasDropdown) {
		if (isServiceAreasDropdownOpen) {
			serviceAreasDropdown.classList.remove('hidden');
			if (serviceAreasChevronIcon) {
				serviceAreasChevronIcon.style.transform = 'rotate(180deg)';
			}
			if (serviceAreasDropdownContainer) {
				serviceAreasDropdownContainer.setAttribute('aria-expanded', 'true');
			}
		} else {
			serviceAreasDropdown.classList.add('hidden');
			if (serviceAreasChevronIcon) {
				serviceAreasChevronIcon.style.transform = 'rotate(0deg)';
			}
			if (serviceAreasDropdownContainer) {
				serviceAreasDropdownContainer.setAttribute('aria-expanded', 'false');
			}
		}
	}
}

// ===== BRANDS DROPDOWN FUNCTIONS =====

function toggleBrandsDropdown() {
	isBrandsDropdownOpen = !isBrandsDropdownOpen;
	updateBrandsDropdown();
}

function openBrandsDropdown() {
	// In debug mode, ignore hover events
	if (debugMode) return;

	if (hoverTimeout) {
		clearTimeout(hoverTimeout);
		hoverTimeout = null;
	}
	// Close other dropdowns when opening Brands
	isServicesDropdownOpen = false;
	updateServicesDropdown();
	isServiceAreasDropdownOpen = false;
	updateServiceAreasDropdown();

	isBrandsDropdownOpen = true;
	updateBrandsDropdown();
}

function closeBrandsDropdown() {
	// In debug mode, ignore hover events
	if (debugMode) return;

	isBrandsDropdownOpen = false;
	updateBrandsDropdown();
}

function delayedCloseBrandsDropdown() {
	// In debug mode, ignore hover events
	if (debugMode) return;

	hoverTimeout = setTimeout(() => {
		closeBrandsDropdown();
	}, 150);
}

function updateBrandsDropdown() {
	const { brandsDropdown, brandsChevronIcon, brandsDropdownContainer } = elements;

	if (brandsDropdown) {
		if (isBrandsDropdownOpen) {
			brandsDropdown.classList.remove('hidden');
			if (brandsChevronIcon) {
				brandsChevronIcon.style.transform = 'rotate(180deg)';
			}
			if (brandsDropdownContainer) {
				brandsDropdownContainer.setAttribute('aria-expanded', 'true');
			}
		} else {
			brandsDropdown.classList.add('hidden');
			if (brandsChevronIcon) {
				brandsChevronIcon.style.transform = 'rotate(0deg)';
			}
			if (brandsDropdownContainer) {
				brandsDropdownContainer.setAttribute('aria-expanded', 'false');
			}
		}
	}
}

// ===== MOBILE MENU FUNCTIONS =====

function toggleMobileMenu() {
	isMobileMenuOpen = !isMobileMenuOpen;
	updateMobileMenu();
}

function closeMobileMenu() {
	isMobileMenuOpen = false;
	updateMobileMenu();
}

function updateMobileMenu() {
	const { mobileMenu, mobileMenuToggle } = elements;

	if (mobileMenu && mobileMenuToggle) {
		if (isMobileMenuOpen) {
			mobileMenu.classList.remove('hidden');
			document.body.style.overflow = 'hidden';
			mobileMenuToggle.setAttribute('aria-expanded', 'true');
		} else {
			mobileMenu.classList.add('hidden');
			document.body.style.overflow = '';
			mobileMenuToggle.setAttribute('aria-expanded', 'false');
		}
	}
}

// ===== LOCATION SELECTOR FUNCTIONS =====

function handleLocationSelect(value) {
	selectedLocation = value;
	const { selectedLocationText } = elements;

	if (selectedLocationText) {
		selectedLocationText.textContent = value || 'SELECT A LOCATION';
	}

	// Navigate to city page if a location was selected
	if (value) {
		const citySlug = value.toLowerCase().replace(/\s+/g, '-');
		// Get cities base URL from data attribute
		const citiesBaseUrl = document.querySelector('[data-cities-base-url]')?.dataset.citiesBaseUrl || '/cities/';
		const cityUrl = citiesBaseUrl + citySlug;
		window.location.href = cityUrl;
	}
}

// ===== EVENT LISTENER SETUP =====

function setupDesktopNavigation() {
	document.querySelectorAll('.nav-item').forEach(item => {
		if (!item) return;
		const itemName = item.dataset.item;
		const href = item.dataset.href || item.querySelector('a')?.href;

		if (itemName === 'Services') {
			// Services dropdown toggle - entire button is clickable
			const { servicesDropdownContainer, servicesDropdown } = elements;

			// Add click listener to the entire button
			item.addEventListener('click', (e) => {
				e.preventDefault();
				e.stopPropagation();
				handleMenuItemClick(itemName, href);
			});

			// Hover events for services dropdown
			if (servicesDropdownContainer) {
				servicesDropdownContainer.addEventListener('mouseenter', openServicesDropdown);
				servicesDropdownContainer.addEventListener('mouseleave', delayedCloseServicesDropdown);
			}

			if (servicesDropdown) {
				servicesDropdown.addEventListener('mouseenter', () => {
					if (hoverTimeout) {
						clearTimeout(hoverTimeout);
						hoverTimeout = null;
					}
				});
				servicesDropdown.addEventListener('mouseleave', delayedCloseServicesDropdown);
			}
		} else if (itemName === 'Cities') {
			// Cities dropdown toggle - entire button is clickable
			const { serviceAreasDropdownContainer, serviceAreasDropdown } = elements;

			// Add click listener to the entire button
			item.addEventListener('click', (e) => {
				e.preventDefault();
				e.stopPropagation();
				handleMenuItemClick(itemName, href);
			});

			// Hover events for areas dropdown
			if (serviceAreasDropdownContainer) {
				serviceAreasDropdownContainer.addEventListener('mouseenter', openServiceAreasDropdown);
				serviceAreasDropdownContainer.addEventListener('mouseleave', delayedCloseServiceAreasDropdown);
			}

			if (serviceAreasDropdown) {
				serviceAreasDropdown.addEventListener('mouseenter', () => {
					if (hoverTimeout) {
						clearTimeout(hoverTimeout);
						hoverTimeout = null;
					}
				});
				serviceAreasDropdown.addEventListener('mouseleave', delayedCloseServiceAreasDropdown);
			}
		} else if (itemName === 'Brands') {
			// Brands dropdown toggle - entire button is clickable
			const { brandsDropdownContainer, brandsDropdown } = elements;

			// Add click listener to the entire button
			item.addEventListener('click', (e) => {
				e.preventDefault();
				e.stopPropagation();
				handleMenuItemClick(itemName, href);
			});

			// Hover events for brands dropdown
			if (brandsDropdownContainer) {
				brandsDropdownContainer.addEventListener('mouseenter', openBrandsDropdown);
				brandsDropdownContainer.addEventListener('mouseleave', delayedCloseBrandsDropdown);
			}

			if (brandsDropdown) {
				brandsDropdown.addEventListener('mouseenter', () => {
					if (hoverTimeout) {
						clearTimeout(hoverTimeout);
						hoverTimeout = null;
					}
				});
				brandsDropdown.addEventListener('mouseleave', delayedCloseBrandsDropdown);
			}
		} else {
			// Regular navigation items
			if (item) {
				// Mouse events
				item.addEventListener('click', () => {
					handleMenuItemClick(itemName, href);
				});

				// Keyboard events
				item.addEventListener('keydown', (event) => {
					if (event.key === 'Enter' || event.key === ' ') {
						event.preventDefault();
						handleMenuItemClick(itemName, href);
					}
				});
			}
		}
	});
}

function setupCallButtons() {
	const { mobileCallBtn, mobileCallBtnHeader, desktopCallBtn } = elements;

	if (mobileCallBtn) {
		mobileCallBtn.addEventListener('click', handleCallUs);
	}
	if (mobileCallBtnHeader) {
		mobileCallBtnHeader.addEventListener('click', handleCallUs);
	}
	if (desktopCallBtn) {
		desktopCallBtn.addEventListener('click', handleCallUs);
	}
}

function setupMobileMenu() {
	const { mobileMenuToggle, mobileMenuClose, mobileMenu, mobileMenuContent } = elements;

	// Mobile menu toggle
	if (mobileMenuToggle) {
		mobileMenuToggle.addEventListener('click', toggleMobileMenu);
	}
	if (mobileMenuClose) {
		mobileMenuClose.addEventListener('click', closeMobileMenu);
	}

	// Close mobile menu when clicking backdrop
	if (mobileMenu) {
		mobileMenu.addEventListener('click', closeMobileMenu);
	}
	if (mobileMenuContent) {
		mobileMenuContent.addEventListener('click', (e) => e.stopPropagation());
	}

	// Mobile navigation links
	document.querySelectorAll('.mobile-nav-link').forEach(link => {
		// Mouse events
		link.addEventListener('click', () => {
			const href = link.dataset.href;
			closeMobileMenu();
			if (href && href !== '#') {
				window.location.href = href;
			}
		});

		// Keyboard events
		link.addEventListener('keydown', (event) => {
			if (event.key === 'Enter' || event.key === ' ') {
				event.preventDefault();
				const href = link.dataset.href;
				closeMobileMenu();
				if (href && href !== '#') {
					window.location.href = href;
				}
			}
		});
	});

	// Mobile service links
	document.querySelectorAll('.mobile-service-link').forEach(link => {
		link.addEventListener('click', closeMobileMenu);
	});

	// Mobile cities links
	document.querySelectorAll('.mobile-area-link').forEach(link => {
		link.addEventListener('click', closeMobileMenu);
	});
}

function setupLocationSelector() {
	const { locationSelectBtn, locationSelect } = elements;

	if (locationSelectBtn && locationSelect) {
		locationSelectBtn.addEventListener('click', () => {
			locationSelect.focus();
			locationSelect.click();
		});

		locationSelect.addEventListener('change', (e) => {
			handleLocationSelect(e.target.value);
		});
	}
}

function setupGlobalEventListeners() {
	const { servicesDropdownContainer, serviceAreasDropdownContainer, brandsDropdownContainer } = elements;

	// Close dropdown when clicking outside
	document.addEventListener('mousedown', (event) => {
		if (servicesDropdownContainer && !servicesDropdownContainer.contains(event.target)) {
			closeServicesDropdown();
		}
		if (serviceAreasDropdownContainer && !serviceAreasDropdownContainer.contains(event.target)) {
			closeServiceAreasDropdown();
		}
		if (brandsDropdownContainer && !brandsDropdownContainer.contains(event.target)) {
			closeBrandsDropdown();
		}
	});

	// Handle escape key
	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape') {
			if (isMobileMenuOpen) {
				closeMobileMenu();
			}
			if (isServicesDropdownOpen) {
				closeServicesDropdown();
			}
			if (isServiceAreasDropdownOpen) {
				closeServiceAreasDropdown();
			}
			if (isBrandsDropdownOpen) {
				closeBrandsDropdown();
			}
		}
	});

	// Cleanup on page unload
	window.addEventListener('beforeunload', () => {
		document.body.style.overflow = '';
		if (hoverTimeout) {
			clearTimeout(hoverTimeout);
		}
	});
}

// ===== ACTIVE MENU ITEM DETECTION =====

function detectActiveMenuItem() {
	const currentPath = window.location.pathname;
	let detectedMenuItem = 'Home';

	if (currentPath.includes('/about')) {
		detectedMenuItem = 'About';
	} else if (currentPath.includes('/services')) {
		detectedMenuItem = 'Services';
	} else if (currentPath.includes('/cities')) {
		detectedMenuItem = 'Cities';
	} else if (currentPath.includes('/brands') || currentPath.includes('/daikin')) {
		detectedMenuItem = 'Brands';
	} else if (currentPath.includes('/projects')) {
		detectedMenuItem = 'Projects';
	} else if (currentPath.includes('/blog')) {
		detectedMenuItem = 'Blog';
	} else if (currentPath.includes('/contact')) {
		detectedMenuItem = 'Contact Us';
	}

	updateActiveMenuItem(detectedMenuItem);
}

// ===== DEBUGGING UTILITIES =====

/**
 * Setup debug utilities on window object for development
 */
function setupDebugUtilities() {
	window.navDebug = {
		// State getters
		getState: () => ({
			servicesDropdownOpen: isServicesDropdownOpen,
			serviceAreasDropdownOpen: isServiceAreasDropdownOpen,
			mobileMenuOpen: isMobileMenuOpen,
			selectedLocation: selectedLocation,
			debugMode: debugMode
		}),

		// Debug mode control
		enableDebugMode: () => {
			debugMode = true;
			console.log('🔴 DEBUG MODE ENABLED - Hover events are now DISABLED');
			console.log('   Only manual commands will open/close dropdowns');
			console.log('   Use navDebug.disableDebugMode() to restore normal behavior');
		},
		disableDebugMode: () => {
			debugMode = false;
			console.log('🟢 DEBUG MODE DISABLED - Hover events restored to normal');
		},

		// Manual state control for Services dropdown (bypasses debug mode check)
		openServices: () => {
			console.log('🔵 Debug: Opening Services dropdown (manual control)');
			debugMode = true;
			isServiceAreasDropdownOpen = false;
			updateServiceAreasDropdown();
			isServicesDropdownOpen = true;
			updateServicesDropdown();
		},
		closeServices: () => {
			console.log('🔵 Debug: Closing Services dropdown (manual control)');
			const wasDebugMode = debugMode;
			debugMode = false;
			isServicesDropdownOpen = false;
			updateServicesDropdown();
			debugMode = wasDebugMode;
		},
		toggleServices: () => {
			console.log('🔵 Debug: Toggling Services dropdown (manual control)');
			debugMode = true;
			if (!isServicesDropdownOpen) {
				isServiceAreasDropdownOpen = false;
				updateServiceAreasDropdown();
			}
			isServicesDropdownOpen = !isServicesDropdownOpen;
			updateServicesDropdown();
		},

		// Manual state control for Service Areas dropdown (bypasses debug mode check)
		openServiceAreas: () => {
			console.log('🟢 Debug: Opening Cities dropdown (manual control)');
			debugMode = true;
			isServicesDropdownOpen = false;
			updateServicesDropdown();
			isServiceAreasDropdownOpen = true;
			updateServiceAreasDropdown();
		},
		closeServiceAreas: () => {
			console.log('🟢 Debug: Closing Cities dropdown (manual control)');
			const wasDebugMode = debugMode;
			debugMode = false;
			isServiceAreasDropdownOpen = false;
			updateServiceAreasDropdown();
			debugMode = wasDebugMode;
		},
		toggleServiceAreas: () => {
			console.log('🟢 Debug: Toggling Cities dropdown (manual control)');
			debugMode = true;
			if (!isServiceAreasDropdownOpen) {
				isServicesDropdownOpen = false;
				updateServicesDropdown();
			}
			isServiceAreasDropdownOpen = !isServiceAreasDropdownOpen;
			updateServiceAreasDropdown();
		},

		// Force both open (bypassing mutual exclusivity for debugging)
		forceOpenBoth: () => {
			console.log('🔴 Debug: FORCING both dropdowns open (bypassing mutual exclusivity)');
			debugMode = true;
			isServicesDropdownOpen = true;
			isServiceAreasDropdownOpen = true;
			updateServicesDropdown();
			updateServiceAreasDropdown();
		},

		// Close all
		closeAll: () => {
			console.log('⭕ Debug: Closing all dropdowns');
			const wasDebugMode = debugMode;
			debugMode = false;
			isServicesDropdownOpen = false;
			isServiceAreasDropdownOpen = false;
			isMobileMenuOpen = false;
			updateServicesDropdown();
			updateServiceAreasDropdown();
			updateMobileMenu();
			debugMode = wasDebugMode;
		},

		// Get DOM elements for inspection
		getElements: () => elements,

		// Log current state
		logState: () => {
			const state = window.navDebug.getState();
			console.log('📊 Navigation State:', state);
			return state;
		},

		// Help message
		help: () => {
			console.log(`
🛠️  Navigation Debug Utilities Available:

📊 State Inspection:
	navDebug.getState()            - Get current state object
	navDebug.logState()            - Log current state to console
	navDebug.getElements()         - Get DOM element references

🔴 Debug Mode Control:
	navDebug.enableDebugMode()     - Disable hover events (manual control only)
	navDebug.disableDebugMode()    - Re-enable hover events (normal behavior)

🔵 Services Dropdown:
	navDebug.openServices()        - Open Services dropdown (auto-enables debug mode)
	navDebug.closeServices()       - Close Services dropdown
	navDebug.toggleServices()      - Toggle Services dropdown (auto-enables debug mode)

🟢 Cities Dropdown:
	navDebug.openServiceAreas()    - Open Cities dropdown (auto-enables debug mode)
	navDebug.closeServiceAreas()   - Close Cities dropdown
	navDebug.toggleServiceAreas()  - Toggle Cities dropdown (auto-enables debug mode)

🔴 Advanced:
	navDebug.forceOpenBoth()       - Force both dropdowns open (auto-enables debug mode)
	navDebug.closeAll()            - Close all dropdowns
	navDebug.help()                - Show this help message

Example usage for CSS debugging:
	navDebug.openServices()        // Opens Services & enables debug mode (stays open)
	// Now hover won't close it - only navDebug commands work
	navDebug.logState()            // Check current state
	navDebug.closeAll()            // Close everything
	navDebug.disableDebugMode()    // Restore normal hover behavior

Note: Opening any dropdown automatically enables debug mode to keep it open.
Use navDebug.disableDebugMode() when done debugging to restore normal behavior.
			`);
		}
	};

	// Log availability on page load
	console.log('🛠️  Navigation debug utilities loaded. Type navDebug.help() for available commands.');
}

// ===== INITIALIZATION =====

/**
 * Initialize navigation functionality
 * This is the main entry point called from main.js
 */
export function initNavigation() {
	// Cache DOM elements
	cacheDOMElements();

	// Setup all event listeners
	setupDesktopNavigation();
	setupCallButtons();
	setupMobileMenu();
	setupLocationSelector();
	setupGlobalEventListeners();

	// Detect and set active menu item based on current page
	detectActiveMenuItem();

	// Setup debug utilities (only in development)
	if (import.meta.env.DEV) {
		setupDebugUtilities();
	}

	console.log('✅ Navigation module initialized');
}

// Auto-initialize when DOM is ready (for standalone usage)
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initNavigation);
} else {
	initNavigation();
}
