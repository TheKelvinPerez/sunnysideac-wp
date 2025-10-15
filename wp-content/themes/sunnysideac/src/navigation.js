/**
 * Navigation JavaScript
 * Handles all interactive functionality for the main navigation
 */

document.addEventListener('DOMContentLoaded', function() {
	// Get constants from PHP (via data attributes or inline script)
	const TEL_HREF = document.querySelector('[data-tel-href]')?.dataset.telHref || 'tel:+13059789382';

	// State variables
	let activeMenuItem = 'Home';
	let isServicesDropdownOpen = false;
	let isMobileMenuOpen = false;
	let selectedLocation = '';
	let hoverTimeout = null;

	// DOM elements
	const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
	const mobileMenu = document.getElementById('mobile-menu');
	const mobileMenuClose = document.getElementById('mobile-menu-close');
	const mobileMenuContent = document.getElementById('mobile-menu-content');
	const servicesDropdownContainer = document.getElementById('services-dropdown-container');
	const servicesDropdown = document.querySelector('.services-dropdown');
	const servicesDropdownBtn = document.querySelector('.services-dropdown-btn');
	const chevronIcon = document.querySelector('.chevron-icon');
	const locationSelect = document.getElementById('location-select');
	const locationSelectBtn = document.getElementById('location-select-btn');
	const selectedLocationText = document.getElementById('selected-location-text');

	// Utility functions
	function updateActiveMenuItem(itemName) {
		activeMenuItem = itemName;
		document.querySelectorAll('.nav-item').forEach(item => {
			const itemData = item.dataset.item;
			if (itemData === itemName) {
				item.classList.remove('bg-[#fde0a0]');
				item.classList.add('bg-[#ffc549]');
			} else {
				item.classList.remove('bg-[#ffc549]');
				item.classList.add('bg-[#fde0a0]');
			}
		});
	}

	function handleCallUs() {
		window.location.href = TEL_HREF;
	}

	function handleMenuItemClick(itemName, href) {
		updateActiveMenuItem(itemName);
		if (itemName === 'Services') {
			// Toggle dropdown
			toggleServicesDropdown();
		} else {
			closeServicesDropdown();
			// Navigate to the page
			if (href && href !== '#') {
				window.location.href = href;
			}
		}
	}

	function toggleServicesDropdown() {
		isServicesDropdownOpen = !isServicesDropdownOpen;
		updateServicesDropdown();
	}

	function openServicesDropdown() {
		if (hoverTimeout) {
			clearTimeout(hoverTimeout);
			hoverTimeout = null;
		}
		isServicesDropdownOpen = true;
		updateServicesDropdown();
	}

	function closeServicesDropdown() {
		isServicesDropdownOpen = false;
		updateServicesDropdown();
	}

	function delayedCloseServicesDropdown() {
		hoverTimeout = setTimeout(() => {
			closeServicesDropdown();
		}, 150);
	}

	function updateServicesDropdown() {
		if (servicesDropdown) {
			if (isServicesDropdownOpen) {
				servicesDropdown.classList.remove('hidden');
				if (chevronIcon) {
					chevronIcon.style.transform = 'rotate(180deg)';
				}
				if (servicesDropdownContainer) {
					servicesDropdownContainer.setAttribute('aria-expanded', 'true');
				}
			} else {
				servicesDropdown.classList.add('hidden');
				if (chevronIcon) {
					chevronIcon.style.transform = 'rotate(0deg)';
				}
				if (servicesDropdownContainer) {
					servicesDropdownContainer.setAttribute('aria-expanded', 'false');
				}
			}
		}
	}

	function toggleMobileMenu() {
		isMobileMenuOpen = !isMobileMenuOpen;
		updateMobileMenu();
	}

	function closeMobileMenu() {
		isMobileMenuOpen = false;
		updateMobileMenu();
	}

	function updateMobileMenu() {
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

	function handleLocationSelect(value) {
		selectedLocation = value;
		if (selectedLocationText) {
			selectedLocationText.textContent = value || 'SELECT A LOCATION';
		}
	}

	// Event listeners for desktop navigation
	document.querySelectorAll('.nav-item').forEach(item => {
		const itemName = item.dataset.item;
		const href = item.dataset.href;

		if (itemName === 'Services') {
			// Services dropdown toggle
			if (servicesDropdownBtn) {
				servicesDropdownBtn.addEventListener('click', (e) => {
					e.preventDefault();
					e.stopPropagation();
					handleMenuItemClick(itemName, href);
				});
			}

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
		} else {
			// Regular navigation items
			item.addEventListener('click', () => {
				handleMenuItemClick(itemName, href);
			});
		}
	});

	// Call buttons
	const mobileCallBtn = document.getElementById('mobile-call-btn');
	const mobileCallBtnHeader = document.getElementById('mobile-call-btn-header');
	const desktopCallBtn = document.getElementById('desktop-call-btn');

	if (mobileCallBtn) {
		mobileCallBtn.addEventListener('click', handleCallUs);
	}
	if (mobileCallBtnHeader) {
		mobileCallBtnHeader.addEventListener('click', handleCallUs);
	}
	if (desktopCallBtn) {
		desktopCallBtn.addEventListener('click', handleCallUs);
	}

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
		link.addEventListener('click', () => {
			const href = link.dataset.href;
			closeMobileMenu();
			if (href && href !== '#') {
				window.location.href = href;
			}
		});
	});

	// Mobile service links
	document.querySelectorAll('.mobile-service-link').forEach(link => {
		link.addEventListener('click', closeMobileMenu);
	});

	// Location select
	if (locationSelectBtn && locationSelect) {
		locationSelectBtn.addEventListener('click', () => {
			locationSelect.focus();
			locationSelect.click();
		});

		locationSelect.addEventListener('change', (e) => {
			handleLocationSelect(e.target.value);
		});
	}

	// Close dropdown when clicking outside
	document.addEventListener('mousedown', (event) => {
		if (servicesDropdownContainer && !servicesDropdownContainer.contains(event.target)) {
			closeServicesDropdown();
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
		}
	});

	// Cleanup on page unload
	window.addEventListener('beforeunload', () => {
		document.body.style.overflow = '';
		if (hoverTimeout) {
			clearTimeout(hoverTimeout);
		}
	});

	// Initialize - detect current page and set active menu item
	const currentPath = window.location.pathname;
	let detectedMenuItem = 'Home';

	if (currentPath.includes('/about')) {
		detectedMenuItem = 'About';
	} else if (currentPath.includes('/services')) {
		detectedMenuItem = 'Services';
	} else if (currentPath.includes('/projects')) {
		detectedMenuItem = 'Projects';
	} else if (currentPath.includes('/blog')) {
		detectedMenuItem = 'Blog';
	} else if (currentPath.includes('/contact')) {
		detectedMenuItem = 'Contact Us';
	}

	updateActiveMenuItem(detectedMenuItem);
});
