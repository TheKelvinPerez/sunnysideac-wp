<?php
/**
 * Review Submission Form Component
 */

// Security nonce
$nonce = wp_create_nonce('review_submission_nonce');

// Get cities and services for dropdowns
$cities = get_posts([
    'post_type' => 'city',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC'
]);

$services = get_posts([
    'post_type' => 'service',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC'
]);

// Form configuration
$config = [
    'form_id' => 'review-submission-form',
    'action_url' => admin_url('admin-ajax.php'),
    'submit_text' => 'Submit Review',
    'loading_text' => 'Submitting...'
];

// Asset URLs
$assets = [
    'star_icon' => sunnysideac_asset_url('assets/icons/review-star-icon-filled.svg'),
    'star_empty_icon' => sunnysideac_asset_url('assets/icons/review-star-icon-empty.svg'),
];
?>

<section class="bg-white rounded-lg shadow-lg p-8">
    <form id="<?php echo esc_attr($config['form_id']); ?>" class="space-y-6" novalidate>
        <!-- Security Fields -->
        <input type="hidden" name="action" value="submit_review">
        <input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>">

        <!-- Star Rating -->
        <div class="form-group">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Overall Rating <span class="text-red-500">*</span>
            </label>
            <div class="star-rating-container flex gap-2" data-rating="0">
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <button type="button" class="star-button p-2 transition-colors hover:scale-110" data-rating="<?php echo $i; ?>">
                        <img src="<?php echo esc_url($assets['star_empty_icon']); ?>"
                             alt="Rate <?php echo $i; ?> stars"
                             class="w-8 h-8 transition-all"
                             data-star-filled="<?php echo esc_url($assets['star_icon']); ?>"
                             data-star-empty="<?php echo esc_url($assets['star_empty_icon']); ?>">
                    </button>
                <?php endfor; ?>
            </div>
            <input type="hidden" name="rating" id="rating-value" value="0" required>
            <span class="text-sm text-gray-500 mt-1 block">Click to rate your experience (1-5 stars)</span>
        </div>

        <!-- Review Content -->
        <div class="form-group">
            <label for="review-content" class="block text-sm font-medium text-gray-700 mb-2">
                Your Review <span class="text-red-500">*</span>
            </label>
            <textarea
                id="review-content"
                name="review_content"
                rows="6"
                required
                minlength="10"
                maxlength="1000"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                placeholder="Tell us about your experience with Sunnyside AC... What service did you receive? How was our technician's work? Would you recommend us to others?"></textarea>
            <div class="flex justify-between text-sm text-gray-500 mt-1">
                <span id="character-count">0</span>
                <span>1000 characters maximum</span>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="grid md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="reviewer-name" class="block text-sm font-medium text-gray-700 mb-2">
                    Your Name <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="reviewer-name"
                    name="reviewer_name"
                    required
                    maxlength="100"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="John Smith">
            </div>

            <div class="form-group">
                <label for="reviewer-email" class="block text-sm font-medium text-gray-700 mb-2">
                    Your Email <span class="text-red-500">*</span>
                </label>
                <input
                    type="email"
                    id="reviewer-email"
                    name="reviewer_email"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="john@example.com">
            </div>
        </div>

        <!-- Service and City Selection -->
        <div class="grid md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="service-select" class="block text-sm font-medium text-gray-700 mb-2">
                    Service Used <span class="text-red-500">*</span>
                </label>
                <select
                    id="service-select"
                    name="service_id"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                    <option value="">Select a service...</option>
                    <?php foreach ($services as $service) : ?>
                        <option value="<?php echo esc_attr($service->ID); ?>">
                            <?php echo esc_html($service->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($services)) : ?>
                    <p class="text-sm text-red-500 mt-1">No services available. Please add services in WordPress admin.</p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="city-select" class="block text-sm font-medium text-gray-700 mb-2">
                    Service Location <span class="text-red-500">*</span>
                </label>
                <select
                    id="city-select"
                    name="city_id"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                    <option value="">Select a city...</option>
                    <?php foreach ($cities as $city) : ?>
                        <option value="<?php echo esc_attr($city->ID); ?>">
                            <?php echo esc_html($city->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($cities)) : ?>
                    <p class="text-sm text-red-500 mt-1">No cities available. Please add cities in WordPress admin.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Honeypot for Spam Protection -->
        <div class="hidden" aria-hidden="true" style="position: absolute; left: -5000px;">
            <label for="website">Website</label>
            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <!-- Submit Button -->
        <div class="form-group pt-4">
            <button
                type="submit"
                class="w-full rounded-full bg-gradient-to-r from-[#F79E37] to-[#E5462F] px-8 py-4 text-base font-medium text-white transition-opacity duration-200 hover:opacity-90 focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                <span class="submit-text"><?php echo esc_html($config['submit_text']); ?></span>
                <span class="loading-text hidden flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <?php echo esc_html($config['loading_text']); ?>
                </span>
            </button>
        </div>

        <!-- Form Messages -->
        <div id="form-messages" class="hidden">
            <div class="p-4 rounded-lg flex items-start">
                <div class="message-icon mr-3"></div>
                <div class="message-text"></div>
            </div>
        </div>

        <!-- Privacy Notice -->
        <div class="text-sm text-gray-500 mt-6 p-4 bg-gray-50 rounded-lg">
            <p class="mb-2"><strong>Privacy Notice:</strong></p>
            <ul class="list-disc list-inside space-y-1 text-xs">
                <li>Your name will be displayed publicly with your review</li>
                <li>Your email address will never be shared publicly</li>
                <li>Reviews are moderated and may take 24-48 hours to appear</li>
                <li>By submitting, you agree to our review guidelines</li>
            </ul>
        </div>
    </form>
</section>

<style>
/* Star Rating Styles */
.star-rating-container .star-button:hover img,
.star-rating-container .star-button.selected img {
    transform: scale(1.1);
    filter: brightness(1.1);
}

/* Form Validation Styles */
.form-group.has-error input,
.form-group.has-error select,
.form-group.has-error textarea {
    border-color: #ef4444;
    background-color: #fef2f2;
}

.form-group.has-error .error-message {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
}

.form-group.has-error .error-message::before {
    content: "⚠️";
    margin-right: 0.25rem;
}

/* Success/Error Message Styles */
.form-messages .success-message {
    background-color: #10b981;
    color: white;
}

.form-messages .error-message {
    background-color: #ef4444;
    color: white;
}

.form-messages .info-message {
    background-color: #3b82f6;
    color: white;
}

/* Focus Styles */
input:focus, select:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Character Counter Styles */
#character-count.warning {
    color: #f59e0b;
}

#character-count.error {
    color: #ef4444;
}

/* Responsive Grid Adjustments */
@media (max-width: 768px) {
    .grid.md\\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
}

/* Loading State */
button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('review-submission-form');
    const ratingButtons = document.querySelectorAll('.star-button');
    const ratingValue = document.getElementById('rating-value');
    const reviewContent = document.getElementById('review-content');
    const characterCount = document.getElementById('character-count');
    const submitButton = form.querySelector('button[type="submit"]');

    // Star rating functionality
    let currentRating = 0;

    ratingButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const rating = parseInt(this.dataset.rating);
            setRating(rating);
        });

        button.addEventListener('mouseenter', function() {
            const hoverRating = parseInt(this.dataset.rating);
            updateStarDisplay(hoverRating);
        });
    });

    document.querySelector('.star-rating-container').addEventListener('mouseleave', function() {
        updateStarDisplay(currentRating);
    });

    function setRating(rating) {
        currentRating = rating;
        ratingValue.value = rating;
        updateStarDisplay(rating);
        validateField(ratingValue);
    }

    function updateStarDisplay(rating) {
        ratingButtons.forEach((button, index) => {
            const star = button.querySelector('img');
            if (index < rating) {
                star.src = star.dataset.starFilled;
                button.classList.add('selected');
            } else {
                star.src = star.dataset.starEmpty;
                button.classList.remove('selected');
            }
        });
    }

    // Character counter
    reviewContent.addEventListener('input', function() {
        const length = this.value.length;
        characterCount.textContent = length;

        // Update character count color based on length
        characterCount.classList.remove('warning', 'error');
        if (length > 900) {
            characterCount.classList.add('error');
        } else if (length > 700) {
            characterCount.classList.add('warning');
        }

        validateField(this);
    });

    // Real-time validation
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            validateField(this);
        });

        field.addEventListener('input', function() {
            if (this.classList.contains('has-error')) {
                validateField(this);
            }
        });
    });

    function validateField(field) {
        const formGroup = field.closest('.form-group');
        let isValid = true;
        let errorMessage = '';

        // Remove existing error
        const existingError = formGroup.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        formGroup.classList.remove('has-error');

        // Required field validation
        if (field.hasAttribute('required') && !field.value.trim()) {
            isValid = false;
            errorMessage = 'This field is required.';
        }

        // Email validation
        if (field.type === 'email' && field.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address.';
            }
        }

        // Rating validation
        if (field.id === 'rating-value' && field.value === '0') {
            isValid = false;
            errorMessage = 'Please select a rating.';
        }

        // Review content validation
        if (field.id === 'review-content' && field.value) {
            if (field.value.length < 10) {
                isValid = false;
                errorMessage = 'Review must be at least 10 characters long.';
            }
        }

        // Show error if invalid
        if (!isValid) {
            formGroup.classList.add('has-error');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.textContent = errorMessage;
            formGroup.appendChild(errorDiv);
        }

        return isValid;
    }

    // Form submission (prepare for AJAX in Phase 3)
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate all fields
        const requiredFields = form.querySelectorAll('[required]');
        let isFormValid = true;

        requiredFields.forEach(field => {
            if (!validateField(field)) {
                isFormValid = false;
            }
        });

        // Check honeypot
        const honeypot = document.getElementById('website');
        if (honeypot.value) {
            // Spam bot detected - don't submit
            return false;
        }

        if (!isFormValid) {
            // Find first error field and scroll to it
            const firstError = form.querySelector('.form-group.has-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }

        // Collect form data
        const formData = new FormData(form);
        const submissionData = {
            action: formData.get('action'),
            nonce: formData.get('nonce'),
            rating: formData.get('rating'),
            review_content: formData.get('review_content'),
            reviewer_name: formData.get('reviewer_name'),
            reviewer_email: formData.get('reviewer_email'),
            service_id: formData.get('service_id'),
            city_id: formData.get('city_id'),
            website: formData.get('website') // honeypot field
        };

        // Console log for debugging
        console.log('🚀 Review Form Submission Data:', submissionData);

        // Show loading state
        const submitText = submitButton.querySelector('.submit-text');
        const loadingText = submitButton.querySelector('.loading-text');

        submitButton.disabled = true;
        submitText.classList.add('hidden');
        loadingText.classList.remove('hidden');

        // AJAX submission (Phase 3 implementation)
        submitReviewViaAJAX(submissionData);
    });

    function submitReviewViaAJAX(data) {
        console.log('📡 Sending AJAX request with data:', data);

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)
        })
        .then(response => {
            console.log('📥 AJAX response received:', response);
            return response.json();
        })
        .then(result => {
            console.log('✅ AJAX result:', result);

            if (result.success) {
                showMessage(result.data.message, 'success');
                // Reset form after successful submission
                setTimeout(() => {
                    document.getElementById('review-submission-form').reset();
                    currentRating = 0;
                    updateStarDisplay(0);
                    characterCount.textContent = '0';
                }, 2000);
            } else {
                showMessage(result.data.message || 'An error occurred. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('❌ AJAX error:', error);
            showMessage('Connection error. Please try again.', 'error');
        })
        .finally(() => {
            // Reset loading state
            const submitButton = document.querySelector('button[type="submit"]');
            const submitText = submitButton.querySelector('.submit-text');
            const loadingText = submitButton.querySelector('.loading-text');

            submitButton.disabled = false;
            submitText.classList.remove('hidden');
            loadingText.classList.add('hidden');
        });
    }

    function showMessage(message, type) {
        const messagesContainer = document.getElementById('form-messages');
        const messageDiv = messagesContainer.querySelector('div');
        const messageIcon = messageDiv.querySelector('.message-icon');
        const messageText = messageDiv.querySelector('.message-text');

        messagesContainer.classList.remove('hidden');
        messageDiv.className = `p-4 rounded-lg flex items-start ${type}-message`;

        // Set icon based on message type
        const icons = {
            success: '✅',
            error: '❌',
            info: 'ℹ️'
        };
        messageIcon.textContent = icons[type] || icons.info;

        messageText.textContent = message;

        // Scroll to message
        messagesContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Hide message after 10 seconds
        setTimeout(() => {
            messagesContainer.classList.add('hidden');
        }, 10000);
    }
});
</script>