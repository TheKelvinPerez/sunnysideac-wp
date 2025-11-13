/**
 * Careers Form Module
 * Handles validation and submission for the careers application form
 * Lazy-loaded only on /careers page
 */

/**
 * Initialize careers form validation and submission
 */
export function init() {
	const careersForm = document.getElementById('careers-form');
	if (!careersForm) {
		console.warn('[CareersForm] Form not found');
		return;
	}

	console.log('[CareersForm] Initializing...');

	const positionSelect = document.getElementById('position');
	const otherPositionDiv = document.getElementById('other_position_div');
	const submitBtn = document.getElementById('careers-submit');
	const submitText = document.getElementById('submit-text');
	const submitLoading = document.getElementById('submit-loading');
	const successMessage = document.getElementById('application-success');

	// Get AJAX URL from data attribute (set by PHP)
	const ajaxUrl = careersForm.dataset.ajaxUrl || '/wp-admin/admin-ajax.php';

	// Show/hide "Other position" field
	if (positionSelect && otherPositionDiv) {
		positionSelect.addEventListener('change', function() {
			const otherPositionField = document.getElementById('other_position');
			if (this.value === 'Other') {
				otherPositionDiv.classList.remove('hidden');
				if (otherPositionField) {
					otherPositionField.required = true;
				}
			} else {
				otherPositionDiv.classList.add('hidden');
				if (otherPositionField) {
					otherPositionField.required = false;
					otherPositionField.value = '';
				}
			}
		});
	}

	// Form validation
	function validateForm() {
		let isValid = true;
		const requiredFields = careersForm.querySelectorAll('[required]');

		requiredFields.forEach(field => {
			if (!field.value.trim()) {
				isValid = false;
				field.classList.add('border-red-300');
			} else {
				field.classList.remove('border-red-300');
			}
		});

		// Email validation
		const email = document.getElementById('email');
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		if (email && email.value && !emailRegex.test(email.value)) {
			isValid = false;
			email.classList.add('border-red-300');
		}

		// Phone validation
		const phone = document.getElementById('phone');
		const phoneRegex = /^[\d\s\-\(\)\+]+$/;
		if (phone && phone.value && !phoneRegex.test(phone.value)) {
			isValid = false;
			phone.classList.add('border-red-300');
		}

		// Resume file validation
		const resume = document.getElementById('resume');
		if (resume && resume.files && resume.files[0]) {
			const file = resume.files[0];
			const maxSize = 5 * 1024 * 1024; // 5MB
			const allowedTypes = [
				'application/pdf',
				'application/msword',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
			];

			if (file.size > maxSize) {
				isValid = false;
				alert('Resume file must be smaller than 5MB');
				resume.classList.add('border-red-300');
			}

			if (!allowedTypes.includes(file.type)) {
				isValid = false;
				alert('Resume must be a PDF, DOC, or DOCX file');
				resume.classList.add('border-red-300');
			}
		}

		return isValid;
	}

	// Handle form submission
	careersForm.addEventListener('submit', async function(e) {
		e.preventDefault();

		if (!validateForm()) {
			alert('Please fill in all required fields correctly.');
			return;
		}

		// Show loading state
		if (submitBtn) submitBtn.disabled = true;
		if (submitText) submitText.classList.add('hidden');
		if (submitLoading) submitLoading.classList.remove('hidden');

		const formData = new FormData(careersForm);

		try {
			const response = await fetch(ajaxUrl, {
				method: 'POST',
				body: formData
			});

			const result = await response.json();

			if (result.success) {
				// Show success message
				if (successMessage) {
					successMessage.classList.remove('hidden');
					successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });

					// Hide success message after 10 seconds
					setTimeout(() => {
						successMessage.classList.add('hidden');
					}, 10000);
				}

				careersForm.reset();
				console.log('[CareersForm] Application submitted successfully');
			} else {
				alert('There was an error submitting your application. Please try again or call us directly.');
			}
		} catch (error) {
			console.error('[CareersForm] Submission error:', error);
			alert('There was an error submitting your application. Please try again or call us directly.');
		} finally {
			// Reset button state
			if (submitBtn) submitBtn.disabled = false;
			if (submitText) submitText.classList.remove('hidden');
			if (submitLoading) submitLoading.classList.add('hidden');
		}
	});

	// Real-time validation
	const requiredFields = careersForm.querySelectorAll('[required]');
	requiredFields.forEach(field => {
		field.addEventListener('blur', function() {
			if (this.hasAttribute('required') && !this.value.trim()) {
				this.classList.add('border-red-300');
			} else {
				this.classList.remove('border-red-300');
			}
		});
	});

	console.log('[CareersForm] Initialized successfully');
}
