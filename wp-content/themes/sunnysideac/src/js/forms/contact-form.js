/**
 * Contact Form Module
 * Handles validation and submission for the contact form
 * Lazy-loaded when contact form is in viewport
 */

/**
 * Initialize contact form validation and submission
 */
export function init() {
	const form = document.getElementById('contact-form');
	if (!form) {
		console.warn('[ContactForm] Form not found');
		return;
	}

	console.log('[ContactForm] Initializing...');

	const submitBtn = document.getElementById('submit-btn');
	const formStatus = document.getElementById('form-status');
	const successMessage = document.getElementById('success-message');
	const errorMessage = document.getElementById('error-message');

	// Form validation functions
	function showError(fieldId, message) {
		const field = document.getElementById(fieldId);
		const errorElement = document.getElementById(`${fieldId}-error`);

		if (field) {
			field.classList.add('ring-2', 'ring-red-400');
			field.setAttribute('aria-invalid', 'true');
		}
		if (errorElement) {
			errorElement.textContent = message;
			errorElement.classList.remove('hidden');
		}
	}

	function clearError(fieldId) {
		const field = document.getElementById(fieldId);
		const errorElement = document.getElementById(`${fieldId}-error`);

		if (field) {
			field.classList.remove('ring-2', 'ring-red-400');
			field.removeAttribute('aria-invalid');
		}
		if (errorElement) {
			errorElement.classList.add('hidden');
		}
	}

	function validateField(field) {
		const fieldId = field.id;
		const value = field.value.trim();

		clearError(fieldId);

		if (field.hasAttribute('required') && !value) {
			showError(fieldId, `${field.name} is required`);
			return false;
		}

		if (fieldId === 'fullName' && value.length < 2) {
			showError(fieldId, 'Full name must be at least 2 characters');
			return false;
		}

		if (fieldId === 'phoneNumber' && !/^[0-9+()\-\s]{7,}$/.test(value)) {
			showError(fieldId, 'Enter a valid phone number');
			return false;
		}

		if (fieldId === 'emailAddress' && !/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/.test(value)) {
			showError(fieldId, 'Enter a valid email address');
			return false;
		}

		return true;
	}

	function validateForm() {
		const fields = form.querySelectorAll('input[required], select[required]');
		let isValid = true;

		fields.forEach(field => {
			if (!validateField(field)) {
				isValid = false;
			}
		});

		return isValid;
	}

	// Update select styling based on value
	function updateSelectStyling(select) {
		if (select.value === '') {
			select.classList.add('text-gray-400');
			select.classList.remove('text-black');
		} else {
			select.classList.remove('text-gray-400');
			select.classList.add('text-black');
		}
	}

	// Add event listeners for real-time validation
	const inputs = form.querySelectorAll('input, select');
	inputs.forEach(input => {
		input.addEventListener('blur', () => validateField(input));
		input.addEventListener('input', () => clearError(input.id));

		if (input.tagName === 'SELECT') {
			input.addEventListener('change', () => {
				updateSelectStyling(input);
				validateField(input);
			});
			// Initialize styling
			updateSelectStyling(input);
		}
	});

	// Form submission
	form.addEventListener('submit', async function(e) {
		e.preventDefault();

		if (!validateForm()) {
			return;
		}

		// Show loading state
		const originalText = submitBtn.textContent;
		submitBtn.textContent = 'Sending...';
		submitBtn.disabled = true;

		try {
			// Get form data
			const formData = new FormData(form);
			const data = Object.fromEntries(formData.entries());

			// Create dynamic subject
			const firstName = (data.fullName || '').trim().split(/\s+/)[0] || 'Customer';
			const serviceLabel = data.serviceType || 'Service Inquiry';
			const structureLabel = data.selectCategory || 'General';
			const dynamicSubject = `SSAC - ${firstName} • ${serviceLabel} • ${structureLabel}`;

			// Update the dynamic subject
			const subjectInput = form.querySelector('input[name="subject"]');
			if (subjectInput) {
				subjectInput.value = dynamicSubject;
			}

			// Convert FormData to JSON following Web3Forms documentation
			const object = Object.fromEntries(formData);
			const json = JSON.stringify(object);

			// Submit to Web3Forms
			const response = await fetch('https://api.web3forms.com/submit', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'Accept': 'application/json'
				},
				body: json
			});

			const result = await response.json();

			if (response.status === 200) {
				// Show success message
				if (formStatus) formStatus.classList.remove('hidden');
				if (successMessage) successMessage.classList.remove('hidden');
				if (errorMessage) errorMessage.classList.add('hidden');

				// Reset form
				form.reset();

				// Reset select styling
				const selects = form.querySelectorAll('select');
				selects.forEach(select => updateSelectStyling(select));

				// Scroll to success message
				if (formStatus) {
					formStatus.scrollIntoView({ behavior: 'smooth', block: 'center' });
				}

				console.log('[ContactForm] Form submitted successfully');
			} else {
				throw new Error(result.message || 'Submission failed');
			}
		} catch (error) {
			console.error('[ContactForm] Submission error:', error);

			// Show error message
			if (formStatus) formStatus.classList.remove('hidden');
			if (errorMessage) errorMessage.classList.remove('hidden');
			if (successMessage) successMessage.classList.add('hidden');

			// Scroll to error message
			if (formStatus) {
				formStatus.scrollIntoView({ behavior: 'smooth', block: 'center' });
			}
		} finally {
			// Restore button state
			submitBtn.textContent = originalText;
			submitBtn.disabled = false;
		}
	});

	console.log('[ContactForm] Initialized successfully');
}
