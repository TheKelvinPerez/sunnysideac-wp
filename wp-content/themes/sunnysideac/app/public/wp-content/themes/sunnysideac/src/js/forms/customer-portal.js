/**
 * Customer Portal Module
 * Handles functionality for the customer portal login/dashboard
 * Lazy-loaded only on /customer-portal page
 */

/**
 * Initialize customer portal functionality
 */
export function init() {
	// Check if we're on the customer portal page
	if (!window.location.pathname.includes('/customer-portal')) {
		console.warn('[CustomerPortal] Not on customer portal page');
		return;
	}

	console.log('[CustomerPortal] Initializing...');

	// Handle login form
	initLoginForm();

	// Handle dashboard functionality
	initDashboard();

	// Handle portal navigation
	initPortalNavigation();

	console.log('[CustomerPortal] Initialized successfully');
}

/**
 * Initialize login form functionality
 */
function initLoginForm() {
	const loginForm = document.getElementById('customer-login-form');
	if (!loginForm) return;

	console.log('[CustomerPortal] Initializing login form...');

	const emailInput = document.getElementById('customer-email');
	const passwordInput = document.getElementById('customer-password');
	const loginBtn = document.getElementById('login-btn');
	const loginError = document.getElementById('login-error');
	const loginSuccess = document.getElementById('login-success');

	// Form validation
	function validateLoginForm() {
		let isValid = true;

		// Validate email
		if (!emailInput.value.trim() || !/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/.test(emailInput.value)) {
			showFieldError(emailInput, 'Please enter a valid email address');
			isValid = false;
		} else {
			clearFieldError(emailInput);
		}

		// Validate password
		if (!passwordInput.value || passwordInput.value.length < 6) {
			showFieldError(passwordInput, 'Password must be at least 6 characters');
			isValid = false;
		} else {
			clearFieldError(passwordInput);
		}

		return isValid;
	}

	// Show field error
	function showFieldError(field, message) {
		field.classList.add('ring-2', 'ring-red-400');
		field.setAttribute('aria-invalid', 'true');

		let errorElement = field.parentNode.querySelector('.error-message');
		if (!errorElement) {
			errorElement = document.createElement('span');
			errorElement.className = 'error-message text-red-500 text-sm mt-1 block';
			field.parentNode.appendChild(errorElement);
		}
		errorElement.textContent = message;
	}

	// Clear field error
	function clearFieldError(field) {
		field.classList.remove('ring-2', 'ring-red-400');
		field.removeAttribute('aria-invalid');

		const errorElement = field.parentNode.querySelector('.error-message');
		if (errorElement) {
			errorElement.remove();
		}
	}

	// Real-time validation
	emailInput.addEventListener('blur', () => {
		if (emailInput.value) {
			validateLoginForm();
		}
	});

	passwordInput.addEventListener('blur', () => {
		if (passwordInput.value) {
			validateLoginForm();
		}
	});

	// Clear errors on input
	[emailInput, passwordInput].forEach(input => {
		input.addEventListener('input', () => clearFieldError(input));
	});

	// Handle form submission
	loginForm.addEventListener('submit', async function(e) {
		e.preventDefault();

		if (!validateLoginForm()) {
			return;
		}

		// Show loading state
		const originalText = loginBtn.textContent;
		loginBtn.textContent = 'Logging in...';
		loginBtn.disabled = true;
		loginError.classList.add('hidden');
		loginSuccess.classList.add('hidden');

		try {
			// Simulate API call (replace with actual authentication)
			await new Promise(resolve => setTimeout(resolve, 1500));

			// For demo purposes, accept any valid email + 6+ char password
			if (emailInput.value && passwordInput.value.length >= 6) {
				loginSuccess.classList.remove('hidden');
				loginSuccess.textContent = 'Login successful! Redirecting...';

				// Simulate redirect to dashboard
				setTimeout(() => {
					console.log('[CustomerPortal] Redirecting to dashboard...');
					// In real implementation, this would redirect to actual dashboard
					showDashboard();
				}, 1500);
			} else {
				throw new Error('Invalid credentials');
			}

		} catch (error) {
			console.error('[CustomerPortal] Login error:', error);
			loginError.classList.remove('hidden');
			loginError.textContent = error.message || 'Login failed. Please try again.';
		} finally {
			loginBtn.textContent = originalText;
			loginBtn.disabled = false;
		}
	});
}

/**
 * Initialize dashboard functionality
 */
function initDashboard() {
	const dashboard = document.getElementById('customer-dashboard');
	if (!dashboard) return;

	console.log('[CustomerPortal] Initializing dashboard...');

	// Initialize tabs
	initDashboardTabs();

	// Initialize service request form
	initServiceRequestForm();

	// Initialize appointment booking
	initAppointmentBooking();
}

/**
 * Initialize dashboard tabs
 */
function initDashboardTabs() {
	const tabButtons = document.querySelectorAll('[data-tab]');
	const tabContents = document.querySelectorAll('[data-tab-content]');

	if (!tabButtons.length || !tabContents.length) return;

	tabButtons.forEach(button => {
		button.addEventListener('click', () => {
			const targetTab = button.dataset.tab;

			// Update button states
			tabButtons.forEach(btn => {
				btn.classList.remove('bg-blue-600', 'text-white');
				btn.classList.add('bg-gray-200', 'text-gray-700');
			});
			button.classList.remove('bg-gray-200', 'text-gray-700');
			button.classList.add('bg-blue-600', 'text-white');

			// Show/hide tab content
			tabContents.forEach(content => {
				if (content.dataset.tabContent === targetTab) {
					content.classList.remove('hidden');
				} else {
					content.classList.add('hidden');
				}
			});
		});
	});
}

/**
 * Initialize service request form
 */
function initServiceRequestForm() {
	const serviceForm = document.getElementById('service-request-form');
	if (!serviceForm) return;

	const submitBtn = serviceForm.querySelector('button[type="submit"]');
	const formStatus = document.getElementById('service-form-status');

	serviceForm.addEventListener('submit', async function(e) {
		e.preventDefault();

		const originalText = submitBtn.textContent;
		submitBtn.textContent = 'Submitting...';
		submitBtn.disabled = true;
		formStatus.classList.add('hidden');

		try {
			// Simulate API call
			await new Promise(resolve => setTimeout(resolve, 2000));

			formStatus.classList.remove('hidden');
			formStatus.classList.add('bg-green-100', 'text-green-700');
			formStatus.textContent = 'Service request submitted successfully!';

			serviceForm.reset();

		} catch (error) {
			console.error('[CustomerPortal] Service request error:', error);
			formStatus.classList.remove('hidden');
			formStatus.classList.add('bg-red-100', 'text-red-700');
			formStatus.textContent = 'Failed to submit service request. Please try again.';
		} finally {
			submitBtn.textContent = originalText;
			submitBtn.disabled = false;
		}
	});
}

/**
 * Initialize appointment booking
 */
function initAppointmentBooking() {
	const calendar = document.getElementById('appointment-calendar');
	if (!calendar) return;

	// Simple calendar implementation
	const today = new Date();
	const currentMonth = today.getMonth();
	const currentYear = today.getFullYear();

	// Generate calendar days (simplified)
	for (let day = 1; day <= 30; day++) {
		const dayElement = document.createElement('button');
		dayElement.textContent = day;
		dayElement.className = 'p-2 hover:bg-blue-100 rounded';
		dayElement.addEventListener('click', () => selectDate(day, currentMonth, currentYear));
		calendar.appendChild(dayElement);
	}

	function selectDate(day, month, year) {
		// Remove previous selection
		calendar.querySelectorAll('button').forEach(btn => {
			btn.classList.remove('bg-blue-600', 'text-white');
		});

		// Add selection to clicked day
		event.target.classList.add('bg-blue-600', 'text-white');

		console.log(`[CustomerPortal] Selected date: ${month + 1}/${day}/${year}`);
	}
}

/**
 * Initialize portal navigation
 */
function initPortalNavigation() {
	const logoutBtn = document.getElementById('portal-logout');
	if (logoutBtn) {
		logoutBtn.addEventListener('click', () => {
			console.log('[CustomerPortal] Logging out...');
			showLoginForm();
		});
	}

	// Mobile menu toggle
	const mobileMenuToggle = document.getElementById('portal-mobile-menu-toggle');
	const mobileMenu = document.getElementById('portal-mobile-menu');

	if (mobileMenuToggle && mobileMenu) {
		mobileMenuToggle.addEventListener('click', () => {
			mobileMenu.classList.toggle('hidden');
		});
	}
}

/**
 * Show login form
 */
function showLoginForm() {
	const loginSection = document.getElementById('customer-login');
	const dashboardSection = document.getElementById('customer-dashboard');

	if (loginSection) loginSection.classList.remove('hidden');
	if (dashboardSection) dashboardSection.classList.add('hidden');
}

/**
 * Show dashboard
 */
function showDashboard() {
	const loginSection = document.getElementById('customer-login');
	const dashboardSection = document.getElementById('customer-dashboard');

	if (loginSection) loginSection.classList.add('hidden');
	if (dashboardSection) dashboardSection.classList.remove('hidden');
}

/**
 * Handle forgot password
 */
export function handleForgotPassword() {
	const email = prompt('Enter your email address:');
	if (email && /[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/.test(email)) {
		console.log('[CustomerPortal] Password reset requested for:', email);
		alert('Password reset link has been sent to your email.');
	} else if (email) {
		alert('Please enter a valid email address.');
	}
}