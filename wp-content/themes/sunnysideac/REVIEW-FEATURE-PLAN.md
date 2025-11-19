# Customer Review Submission Feature - Complete Development Plan

## Table of Contents
1. [Overview & Requirements](#overview--requirements)
2. [Technical Architecture](#technical-architecture)
3. [Phase 1: CPT & Data Structure](#phase-1-cpt--data-structure)
4. [Phase 2: Frontend Form Structure](#phase-2-frontend-form-structure)
5. [Phase 3: Form Validation & AJAX](#phase-3-form-validation--ajax)
6. [Phase 4: Review Display System](#phase-4-review-display-system)
7. [Phase 5: Admin Management](#phase-5-admin-management)
8. [Phase 6: Integration & Testing](#phase-6-integration--testing)
9. [Security Checklist](#security-checklist)
10. [Future Enhancements](#future-enhancements)

---

## Overview & Requirements

### Feature Summary
Build a comprehensive customer review submission system that allows users to:
- Submit star ratings (1-5) with text reviews
- Associate reviews with specific cities and HVAC services
- Include reviewer name and email
- Store reviews in "pending" status for admin approval
- Display approved reviews on a dedicated reviews page

### User Requirements (Based on Q&A)
- **Display**: Dedicated reviews page (v1), future integration with existing components
- **Media**: Text-only for v1 (photos/videos saved for future version)
- **Notifications**: Simple admin dashboard management only (no email notifications)
- **Associations**: Reviews linked to both cities and services via dropdown menus

### Technical Requirements
- Follow existing codebase patterns and security measures
- Use existing CPTs (city, service) for relationships
- Implement comprehensive validation and sanitization
- Create incremental, testable development phases
- Maintain consistent styling and UX patterns

---

## Technical Architecture

### Existing Patterns to Follow
- **CPT Registration**: Extend `inc/core/post-types-taxonomies.php`
- **Template Parts**: Self-contained components with data at top
- **Form Handlers**: Follow `inc/forms/` pattern (like careers-handler.php)
- **Security**: Nonce verification, sanitization, validation like existing forms
- **Asset Loading**: Use existing Vite integration patterns
- **Styling**: Follow Tailwind CSS v4 patterns from `contact-us.php`

### New Components Required
```
inc/
├── forms/review-handler.php          # AJAX form processing
├── ajax.php                         # AJAX endpoint registration
└── admin/review-admin-columns.php   # Admin column management

template-parts/
├── review-form.php                  # Submission form component
└── review-display.php               # Review display component

page-templates/
└── page-submit-review.php           # Dedicated submission page

archive-review.php                   # Reviews archive template
single-review.php                    # Individual review template
```

---

## Phase 1: CPT & Data Structure

**Goal**: Create the backend data structure for reviews. Testable via WordPress admin.

### Step 1.1: Register Review CPT
**File**: `inc/core/post-types-taxonomies.php` (extend existing)

```php
// Add to existing CPT registration function
function sunnysideac_register_review_post_type() {
    register_post_type('review', [
        'labels' => [
            'name' => 'Customer Reviews',
            'singular_name' => 'Customer Review',
            'menu_name' => 'Reviews',
            'add_new' => 'Add New Review',
            'add_new_item' => 'Add New Review',
            'edit_item' => 'Edit Review',
            'new_item' => 'New Review',
            'view_item' => 'View Review',
            'search_items' => 'Search Reviews',
            'not_found' => 'No reviews found',
            'not_found_in_trash' => 'No reviews found in trash',
            'all_items' => 'All Reviews',
            'archives' => 'Review Archives',
        ],
        'public' => true,
        'has_archive' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'review'],
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 25,
        'menu_icon' => 'dashicons-star-filled',
        'supports' => ['title', 'editor', 'custom-fields'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'sunnysideac_register_review_post_type');
```

**Testing**:
- [ ] Verify CPT appears in WordPress admin menu
- [ ] Test creating a review manually via admin
- [ ] Check archive page functionality at `/review/`
- [ ] Verify rewrite rules work (`ddev wp rewrite flush`)

### Step 1.2: Create ACF Field Group for Reviews
**Action**: Manually create via WordPress admin UI

**Field Group Name**: "Review Details"
**Location**: Post Type == Review

**Fields**:
```
1. Reviewer Name
   - Type: Text
   - Required: Yes
   - Sanitization: Text
   - Field Name: reviewer_name

2. Reviewer Email
   - Type: Email
   - Required: Yes
   - Validation: Email format
   - Field Name: reviewer_email

3. Rating
   - Type: Number
   - Required: Yes
   - Min: 1, Max: 5
   - Step: 1
   - Field Name: rating

4. Service
   - Type: Post Object
   - Required: Yes
   - Filter by Post Type: Service
   - Return Format: Post Object
   - Field Name: service_relationship

5. City
   - Type: Post Object
   - Required: Yes
   - Filter by Post Type: City
   - Return Format: Post Object
   - Field Name: city_relationship

6. Review Status
   - Type: Select
   - Required: Yes
   - Choices:
       pending : Pending Approval
       approved : Approved
       rejected : Rejected
   - Default Value: pending
   - Field Name: review_status
```

**Testing**:
- [ ] Create field group and verify it appears on review edit screen
- [ ] Test creating a review with all fields populated
- [ ] Verify data saves correctly to postmeta
- [ ] Test field validation (required fields, email format, rating range)

### Step 1.3: Helper Functions for Review Data
**File**: `inc/helpers.php` (add to existing)

```php
/**
 * Get approved reviews for display
 */
function sunnysideac_get_approved_reviews($args = []) {
    $default_args = [
        'post_type' => 'review',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => 'review_status',
                'value' => 'approved',
                'compare' => '='
            ]
        ],
        'orderby' => 'date',
        'order' => 'DESC'
    ];

    $args = wp_parse_args($args, $default_args);
    return get_posts($args);
}

/**
 * Get pending reviews count for admin dashboard
 */
function sunnysideac_get_pending_reviews_count() {
    $args = [
        'post_type' => 'review',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => 'review_status',
                'value' => 'pending',
                'compare' => '='
            ]
        ]
    ];

    $reviews = get_posts($args);
    return count($reviews);
}

/**
 * Get star rating display HTML
 */
function sunnysideac_get_star_rating_html($rating) {
    if (empty($rating) || $rating < 1 || $rating > 5) {
        return '';
    }

    $star_icon = sunnysideac_asset_url('assets/images/star-icon.svg');
    $empty_star_icon = sunnysideac_asset_url('assets/images/star-empty-icon.svg');

    $html = '<div class="flex gap-1 review-stars" role="img" aria-label="' . $rating . ' out of 5 stars">';

    for ($i = 1; $i <= 5; $i++) {
        $icon_url = $i <= $rating ? $star_icon : $empty_star_icon;
        $html .= '<img src="' . esc_url($icon_url) . '" alt="" class="w-4 h-4" />';
    }

    $html .= '</div>';
    return $html;
}
```

**Testing**:
- [ ] Test `sunnysideac_get_approved_reviews()` returns correct reviews
- [ ] Verify `sunnysideac_get_pending_reviews_count()` works
- [ ] Test `sunnysideac_get_star_rating_html()` with different rating values
- [ ] Create sample reviews with different statuses to test functions

---

## Phase 2: Frontend Form Structure

**Goal**: Create the frontend submission form with proper styling and client-side validation (no backend submission yet).

### Step 2.1: Create Review Submission Page Template
**File**: `page-templates/page-submit-review.php`

```php
<?php
/**
 * Template Name: Submit Review
 *
 * @package Sunnyside AC
 */

get_header();
?>

<div class="container mx-auto px-4 py-16">
    <div class="max-w-2xl mx-auto">
        <header class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Share Your Experience</h1>
            <p class="text-xl text-gray-600">We value your feedback and would love to hear about your experience with our services.</p>
        </header>

        <?php get_template_part('template-parts/review-form'); ?>
    </div>
</div>

<?php
get_footer();
```

**Testing**:
- [ ] Create page in WordPress admin using "Submit Review" template
- [ ] Verify page displays correctly at chosen URL
- [ ] Test responsive layout on mobile/desktop

### Step 2.2: Create Review Form Component
**File**: `template-parts/review-form.php`

```php
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
    'star_icon' => sunnysideac_asset_url('assets/images/star-icon.svg'),
    'star_empty_icon' => sunnysideac_asset_url('assets/images/star-empty-icon.svg'),
];
?>

<section class="bg-white rounded-lg shadow-lg p-8">
    <form id="<?php echo esc_attr($config['form_id']); ?>" class="space-y-6">
        <!-- Security Fields -->
        <input type="hidden" name="action" value="submit_review">
        <input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>">

        <!-- Star Rating -->
        <div class="form-group">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Overall Rating <span class="text-red-500">*</span>
            </label>
            <div class="star-rating-container" data-rating="0">
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <button type="button" class="star-button p-2" data-rating="<?php echo $i; ?>">
                        <img src="<?php echo esc_url($assets['star_empty_icon']); ?>"
                             alt="Rate <?php echo $i; ?> stars"
                             class="w-8 h-8 transition-colors hover:text-yellow-400"
                             data-star-filled="<?php echo esc_url($assets['star_icon']); ?>"
                             data-star-empty="<?php echo esc_url($assets['star_empty_icon']); ?>">
                    </button>
                <?php endfor; ?>
            </div>
            <input type="hidden" name="rating" id="rating-value" value="0" required>
            <span class="text-sm text-gray-500 mt-1 block">Click to rate your experience</span>
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
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Tell us about your experience with Sunnyside AC..."
                maxlength="1000"></textarea>
            <div class="text-sm text-gray-500 mt-1">
                <span id="character-count">0</span> / 1000 characters
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
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select a service...</option>
                    <?php foreach ($services as $service) : ?>
                        <option value="<?php echo esc_attr($service->ID); ?>">
                            <?php echo esc_html($service->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="city-select" class="block text-sm font-medium text-gray-700 mb-2">
                    Service Location <span class="text-red-500">*</span>
                </label>
                <select
                    id="city-select"
                    name="city_id"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select a city...</option>
                    <?php foreach ($cities as $city) : ?>
                        <option value="<?php echo esc_attr($city->ID); ?>">
                            <?php echo esc_html($city->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Honeypot for Spam Protection -->
        <div class="hidden" aria-hidden="true">
            <label for="website">Website</label>
            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <!-- Submit Button -->
        <div class="form-group">
            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                <span class="submit-text"><?php echo esc_html($config['submit_text']); ?></span>
                <span class="loading-text hidden">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <?php echo esc_html($config['loading_text']); ?>
                </span>
            </button>
        </div>

        <!-- Form Messages -->
        <div id="form-messages" class="hidden">
            <div class="p-4 rounded-lg"></div>
        </div>
    </form>
</section>

<style>
.star-rating-container .star-button:hover img,
.star-rating-container .star-button.selected img {
    filter: brightness(0) saturate(100%) invert(72%) sepia(71%) saturate(2855%) hue-rotate(329deg) brightness(100%) contrast(108%);
}

.form-group.has-error input,
.form-group.has-error select,
.form-group.has-error textarea {
    border-color: #ef4444;
}

.form-group.has-error .error-message {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.success-message {
    background-color: #10b981;
    color: white;
}

.error-message {
    background-color: #ef4444;
    color: white;
}
</style>

<script>
// Form validation and interactivity will be added in Phase 3
</script>
```

**Testing**:
- [ ] Verify form displays correctly with all fields
- [ ] Test dropdown population with cities and services
- [ ] Check visual appearance and responsive design
- [ ] Verify honeypot field is hidden
- [ ] Test form accessibility (labels, ARIA attributes)

---

## Phase 3: Form Validation & AJAX

**Goal**: Add client-side validation and AJAX form submission functionality.

### Step 3.1: Add Client-Side JavaScript
**File**: Update `template-parts/review-form.php` (replace `<script>` section)

```javascript
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

        if (length > 900) {
            characterCount.classList.add('text-orange-500');
        } else {
            characterCount.classList.remove('text-orange-500');
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

    // Form submission
    form.addEventListener('submit', async function(e) {
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

        // Disable submit button and show loading
        const submitText = submitButton.querySelector('.submit-text');
        const loadingText = submitButton.querySelector('.loading-text');

        submitButton.disabled = true;
        submitText.classList.add('hidden');
        loadingText.classList.remove('hidden');

        // Prepare form data
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showMessage(result.data.message, 'success');
                form.reset();
                currentRating = 0;
                updateStarDisplay(0);
                characterCount.textContent = '0';

                // Scroll to top of form
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                showMessage(result.data.message, 'error');
            }
        } catch (error) {
            showMessage('An error occurred. Please try again.', 'error');
        } finally {
            // Re-enable submit button
            submitButton.disabled = false;
            submitText.classList.remove('hidden');
            loadingText.classList.add('hidden');
        }
    });

    function showMessage(message, type) {
        const messagesContainer = document.getElementById('form-messages');
        const messageDiv = messagesContainer.querySelector('div');

        messagesContainer.classList.remove('hidden');
        messageDiv.className = `p-4 rounded-lg ${type}-message';
        messageDiv.textContent = message;

        // Scroll to message
        messagesContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Hide message after 10 seconds
        setTimeout(() => {
            messagesContainer.classList.add('hidden');
        }, 10000);
    }
});
</script>
```

**Testing**:
- [ ] Test star rating interaction (click, hover, selection)
- [ ] Test character counter functionality
- [ ] Test real-time field validation (blur, input events)
- [ ] Test form submission with invalid data (show errors)
- [ ] Test form submission with valid data (AJAX call)
- [ ] Verify honeypot field works for spam protection
- [ ] Test loading states and button disable
- [ ] Test success/error message display

### Step 3.2: Create AJAX Handler
**File**: `inc/forms/review-handler.php`

```php
<?php
/**
 * Review Form AJAX Handler
 *
 * @package Sunnyside AC
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle review form submission
 */
function sunnysideac_handle_review_submission() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'review_submission_nonce')) {
        wp_send_json_error([
            'message' => 'Security verification failed. Please refresh the page and try again.'
        ]);
        wp_die();
    }

    // Check honeypot (anti-spam)
    if (!empty($_POST['website'])) {
        wp_send_json_error([
            'message' => 'Invalid submission.'
        ]);
        wp_die();
    }

    // Validate and sanitize input data
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $review_content = isset($_POST['review_content']) ? sanitize_textarea_field($_POST['review_content']) : '';
    $reviewer_name = isset($_POST['reviewer_name']) ? sanitize_text_field($_POST['reviewer_name']) : '';
    $reviewer_email = isset($_POST['reviewer_email']) ? sanitize_email($_POST['reviewer_email']) : '';
    $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
    $city_id = isset($_POST['city_id']) ? intval($_POST['city_id']) : 0;

    // Validation rules
    $errors = [];

    if ($rating < 1 || $rating > 5) {
        $errors[] = 'Please select a valid rating between 1 and 5 stars.';
    }

    if (empty($review_content)) {
        $errors[] = 'Review content is required.';
    } elseif (strlen($review_content) < 10) {
        $errors[] = 'Review must be at least 10 characters long.';
    } elseif (strlen($review_content) > 1000) {
        $errors[] = 'Review cannot exceed 1000 characters.';
    }

    if (empty($reviewer_name)) {
        $errors[] = 'Your name is required.';
    } elseif (strlen($reviewer_name) > 100) {
        $errors[] = 'Name cannot exceed 100 characters.';
    }

    if (empty($reviewer_email)) {
        $errors[] = 'Your email is required.';
    } elseif (!is_email($reviewer_email)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($service_id)) {
        $errors[] = 'Please select a service.';
    } elseif (get_post_type($service_id) !== 'service') {
        $errors[] = 'Invalid service selection.';
    }

    if (empty($city_id)) {
        $errors[] = 'Please select a city.';
    } elseif (get_post_type($city_id) !== 'city') {
        $errors[] = 'Invalid city selection.';
    }

    // If validation errors exist, send error response
    if (!empty($errors)) {
        wp_send_json_error([
            'message' => implode(' ', $errors)
        ]);
        wp_die();
    }

    // Check for duplicate submissions (same email + same service in last 24 hours)
    $duplicate_check = get_posts([
        'post_type' => 'review',
        'posts_per_page' => 1,
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'reviewer_email',
                'value' => $reviewer_email
            ],
            [
                'key' => 'service_relationship',
                'value' => $service_id
            ]
        ],
        'date_query' => [
            [
                'after' => '1 day ago'
            ]
        ]
    ]);

    if (!empty($duplicate_check)) {
        wp_send_json_error([
            'message' => 'You have already submitted a review for this service within the last 24 hours. Please wait before submitting another review.'
        ]);
        wp_die();
    }

    // Create review post
    $review_title = sprintf('Review by %s', $reviewer_name);

    $post_data = [
        'post_title' => $review_title,
        'post_content' => $review_content,
        'post_status' => 'pending',
        'post_type' => 'review',
        'meta_input' => [
            'reviewer_name' => $reviewer_name,
            'reviewer_email' => $reviewer_email,
            'rating' => $rating,
            'service_relationship' => $service_id,
            'city_relationship' => $city_id,
            'review_status' => 'pending',
            'submission_date' => current_time('Y-m-d H:i:s')
        ]
    ];

    $post_id = wp_insert_post($post_data, true);

    // Check if post creation was successful
    if (is_wp_error($post_id)) {
        error_log('Review submission error: ' . $post_id->get_error_message());
        wp_send_json_error([
            'message' => 'An error occurred while saving your review. Please try again.'
        ]);
        wp_die();
    }

    // Success response
    wp_send_json_success([
        'message' => 'Thank you for your review! It has been submitted successfully and will be visible once approved by our team.'
    ]);

    wp_die();
}
add_action('wp_ajax_submit_review', 'sunnysideac_handle_review_submission');
add_action('wp_ajax_nopriv_submit_review', 'sunnysideac_handle_review_submission');
```

**Testing**:
- [ ] Test AJAX handler with valid data (should create review post)
- [ ] Test with invalid nonce (should fail)
- [ ] Test with honeypot filled (should fail)
- [ ] Test all validation scenarios (missing fields, invalid data)
- [ ] Test duplicate submission prevention
- [ ] Verify post creation in WordPress admin with correct meta data
- [ ] Test error handling and logging

### Step 3.3: Register AJAX Handlers
**File**: `inc/ajax.php` (create if doesn't exist, or add to `functions.php`)

```php
<?php
/**
 * AJAX Handlers Registration
 *
 * @package Sunnyside AC
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include form handlers
require_once get_template_directory() . '/inc/forms/review-handler.php';
```

**Testing**:
- [ ] Verify AJAX endpoints are registered correctly
- [ ] Test AJAX actions `wp_ajax_submit_review` and `wp_ajax_nopriv_submit_review`
- [ ] Check that form handlers are loaded properly

---

## Phase 4: Review Display System

**Goal**: Create templates to display submitted reviews on the frontend.

### Step 4.1: Create Review Archive Template
**File**: `archive-review.php`

```php
<?php
/**
 * Review Archive Template
 *
 * @package Sunnyside AC
 */

get_header();

$reviews = sunnysideac_get_approved_reviews(['posts_per_page' => 12]);
$total_reviews = count($reviews);
?>

<div class="container mx-auto px-4 py-16">
    <header class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Customer Reviews</h1>
        <p class="text-xl text-gray-600">
            Read what our customers have to say about Sunnyside AC services throughout South Florida.
        </p>
        <?php if ($total_reviews > 0) : ?>
            <div class="mt-4">
                <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-semibold">
                    <?php echo esc_html($total_reviews); ?> Reviews
                </span>
            </div>
        <?php endif; ?>
    </header>

    <?php if ($total_reviews > 0) : ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($reviews as $review) : ?>
                <?php get_template_part('template-parts/review-display', null, ['review' => $review]); ?>
            <?php endforeach; ?>
        </div>

        <?php if ($total_reviews >= 12) : ?>
            <div class="text-center mt-12">
                <button class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    Load More Reviews
                </button>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <div class="text-center py-12">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">No Reviews Yet</h2>
                <p class="text-gray-600 mb-8">
                    Be the first to share your experience with Sunnyside AC!
                </p>
                <a href="<?php echo esc_url(home_url('/submit-review')); ?>"
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors inline-block">
                    Submit the First Review
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php get_footer();
```

**Testing**:
- [ ] Test archive page displays approved reviews correctly
- [ ] Verify "No Reviews Yet" state when no approved reviews exist
- [ ] Test responsive grid layout
- [ ] Check pagination/load more functionality (when implemented)

### Step 4.2: Create Single Review Template
**File**: `single-review.php`

```php
<?php
/**
 * Single Review Template
 *
 * @package Sunnyside AC
 */

get_header();

$review = get_post();
$reviewer_name = get_field('reviewer_name', $review->ID);
$rating = get_field('rating', $review->ID);
$service = get_field('service_relationship', $review->ID);
$city = get_field('city_relationship', $review->ID);
$submission_date = get_field('submission_date', $review->ID);
?>

<div class="container mx-auto px-4 py-16">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-blue-600">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="<?php echo esc_url(get_post_type_archive_link('review')); ?>" class="hover:text-blue-600">Reviews</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900">Review by <?php echo esc_html($reviewer_name); ?></li>
            </ol>
        </nav>

        <!-- Single Review Content -->
        <article class="bg-white rounded-lg shadow-lg p-8">
            <header class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    Review by <?php echo esc_html($reviewer_name); ?>
                </h1>

                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <?php if ($rating) : ?>
                            <?php echo sunnysideac_get_star_rating_html($rating); ?>
                        <?php endif; ?>

                        <?php if ($service) : ?>
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo esc_html($service->post_title); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($city) : ?>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                            📍 <?php echo esc_html($city->post_title); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <?php if ($submission_date) : ?>
                    <div class="text-sm text-gray-500">
                        Submitted on <?php echo esc_html(date('F j, Y', strtotime($submission_date))); ?>
                    </div>
                <?php endif; ?>
            </header>

            <div class="prose prose-lg max-w-none">
                <?php
                $content = apply_filters('the_content', $review->post_content);
                echo $content; // Already sanitized by WordPress
                ?>
            </div>
        </article>

        <!-- Related Actions -->
        <div class="mt-12 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Share Your Experience</h2>
            <p class="text-gray-600 mb-8">
                Have you used Sunnyside AC services? We'd love to hear about your experience!
            </p>
            <a href="<?php echo esc_url(home_url('/submit-review')); ?>"
               class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors inline-block">
                Submit Your Review
            </a>
        </div>
    </div>
</div>

<?php get_footer();
```

**Testing**:
- [ ] Test single review page displays all information correctly
- [ ] Verify breadcrumb navigation works
- [ ] Check structured data and SEO elements
- [ ] Test responsive layout

### Step 4.3: Create Review Display Component
**File**: `template-parts/review-display.php`

```php
<?php
/**
 * Review Display Component
 *
 * @var WP_Post $review The review post object (passed as argument)
 */

// Get review data from passed argument or global post
$review = $args['review'] ?? get_post();

// Get review meta fields
$reviewer_name = get_field('reviewer_name', $review->ID);
$rating = get_field('rating', $review->ID);
$service = get_field('service_relationship', $review->ID);
$city = get_field('city_relationship', $review->ID);
$submission_date = get_field('submission_date', $review->ID);

// Review excerpt for card display
$excerpt = wp_trim_words($review->post_content, 30, '...');

// Component configuration
$config = [
    'show_excerpt' => true,
    'show_full_link' => true,
    'max_excerpt_length' => 30
];
?>

<article class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
    <header class="mb-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-900">
                <?php echo esc_html($reviewer_name); ?>
            </h3>

            <?php if ($rating) : ?>
                <?php echo sunnysideac_get_star_rating_html($rating); ?>
            <?php endif; ?>
        </div>

        <div class="flex flex-wrap gap-2 mb-3">
            <?php if ($service) : ?>
                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">
                    <?php echo esc_html($service->post_title); ?>
                </span>
            <?php endif; ?>

            <?php if ($city) : ?>
                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                    📍 <?php echo esc_html($city->post_title); ?>
                </span>
            <?php endif; ?>
        </div>

        <?php if ($submission_date) : ?>
            <div class="text-xs text-gray-500">
                <?php echo esc_html(date('F j, Y', strtotime($submission_date))); ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="text-gray-700 mb-4">
        <?php if ($config['show_excerpt']) : ?>
            <p><?php echo esc_html($excerpt); ?></p>
        <?php else : ?>
            <div class="prose prose-sm max-w-none">
                <?php
                $content = apply_filters('the_content', $review->post_content);
                echo $content; // Already sanitized by WordPress
                ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($config['show_full_link'] && $config['show_excerpt']) : ?>
        <footer class="text-right">
            <a href="<?php echo esc_url(get_permalink($review->ID)); ?>"
               class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                Read Full Review →
            </a>
        </footer>
    <?php endif; ?>
</article>
```

**Testing**:
- [ ] Test component displays correctly with sample data
- [ ] Verify star rating display works
- [ ] Test service and city badges
- [ ] Check excerpt vs full content display
- [ ] Test responsive card layout

### Step 4.4: Add Navigation Menu Items
**Action**: Add via WordPress admin or programmatically

```php
// Add to functions.php if creating programmatically
function sunnysideac_add_review_menu_items() {
    // Get or create the primary menu
    $menu_name = 'primary';
    $menu_locations = get_nav_menu_locations();
    $menu_id = $menu_locations[$menu_name] ?? false;

    if ($menu_id) {
        // Add "Submit Review" menu item
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title' => 'Submit Review',
            'menu-item-url' => home_url('/submit-review'),
            'menu-item-status' => 'publish',
            'menu-item-type' => 'custom'
        ]);

        // Add "Reviews" menu item
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title' => 'Customer Reviews',
            'menu-item-url' => get_post_type_archive_link('review'),
            'menu-item-status' => 'publish',
            'menu-item-type' => 'custom'
        ]);
    }
}
add_action('init', 'sunnysideac_add_review_menu_items');
```

**Testing**:
- [ ] Verify menu items appear in navigation
- [ ] Test menu item links work correctly
- [ ] Check mobile menu display

---

## Phase 5: Admin Management

**Goal**: Create admin interface for managing submitted reviews.

### Step 5.1: Create Admin Column Management
**File**: `inc/admin/review-admin-columns.php`

```php
<?php
/**
 * Review Admin Columns Management
 *
 * @package Sunnyside AC
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom columns to review admin list
 */
function sunnysideac_review_admin_columns($columns) {
    $new_columns = [];

    // Add columns in desired order
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        if ($key === 'title') {
            $new_columns['rating'] = 'Rating';
            $new_columns['service'] = 'Service';
            $new_columns['city'] = 'City';
            $new_columns['status'] = 'Status';
            $new_columns['reviewer_email'] = 'Email';
        }
    }

    return $new_columns;
}
add_filter('manage_review_posts_columns', 'sunnysideac_review_admin_columns');

/**
 * Display content for custom columns
 */
function sunnysideac_review_admin_columns_content($column, $post_id) {
    switch ($column) {
        case 'rating':
            $rating = get_field('rating', $post_id);
            if ($rating) {
                echo sunnysideac_get_star_rating_html($rating) . ' (' . esc_html($rating) . '/5)';
            } else {
                echo '—';
            }
            break;

        case 'service':
            $service = get_field('service_relationship', $post_id);
            if ($service) {
                echo '<a href="' . esc_url(get_edit_post_link($service->ID)) . '">' . esc_html($service->post_title) . '</a>';
            } else {
                echo '—';
            }
            break;

        case 'city':
            $city = get_field('city_relationship', $post_id);
            if ($city) {
                echo '<a href="' . esc_url(get_edit_post_link($city->ID)) . '">' . esc_html($city->post_title) . '</a>';
            } else {
                echo '—';
            }
            break;

        case 'status':
            $status = get_field('review_status', $post_id);
            $status_colors = [
                'pending' => '#f59e0b',
                'approved' => '#10b981',
                'rejected' => '#ef4444'
            ];

            $color = $status_colors[$status] ?? '#6b7280';
            echo '<span style="background-color: ' . esc_attr($color) . '; color: white; padding: 2px 8px; border-radius: 3px; font-size: 12px; font-weight: bold;">';
            echo esc_html(ucfirst($status));
            echo '</span>';
            break;

        case 'reviewer_email':
            $email = get_field('reviewer_email', $post_id);
            if ($email) {
                echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_review_posts_custom_column', 'sunnysideac_review_admin_columns_content', 10, 2);

/**
 * Make custom columns sortable
 */
function sunnysideac_review_admin_columns_sortable($columns) {
    $columns['rating'] = 'rating';
    $columns['status'] = 'review_status';
    return $columns;
}
add_filter('manage_edit-review_sortable_columns', 'sunnysideac_review_admin_columns_sortable');

/**
 * Add filters for admin columns
 */
function sunnysideac_review_admin_filters() {
    global $typenow;

    if ($typenow === 'review') {
        // Status filter
        $current_status = isset($_GET['review_status']) ? sanitize_text_field($_GET['review_status']) : '';

        echo '<select name="review_status" id="review_status_filter">';
        echo '<option value="">All Statuses</option>';
        echo '<option value="pending"' . selected($current_status, 'pending', false) . '>Pending</option>';
        echo '<option value="approved"' . selected($current_status, 'approved', false) . '>Approved</option>';
        echo '<option value="rejected"' . selected($current_status, 'rejected', false) . '>Rejected</option>';
        echo '</select>';

        // Service filter
        $current_service = isset($_GET['service_filter']) ? intval($_GET['service_filter']) : 0;
        $services = get_posts(['post_type' => 'service', 'posts_per_page' => -1]);

        echo '<select name="service_filter" id="service_filter">';
        echo '<option value="">All Services</option>';
        foreach ($services as $service) {
            echo '<option value="' . esc_attr($service->ID) . '"' . selected($current_service, $service->ID, false) . '>';
            echo esc_html($service->post_title);
            echo '</option>';
        }
        echo '</select>';

        // City filter
        $current_city = isset($_GET['city_filter']) ? intval($_GET['city_filter']) : 0;
        $cities = get_posts(['post_type' => 'city', 'posts_per_page' => -1]);

        echo '<select name="city_filter" id="city_filter">';
        echo '<option value="">All Cities</option>';
        foreach ($cities as $city) {
            echo '<option value="' . esc_attr($city->ID) . '"' . selected($current_city, $city->ID, false) . '>';
            echo esc_html($city->post_title);
            echo '</option>';
        }
        echo '</select>';
    }
}
add_action('restrict_manage_posts', 'sunnysideac_review_admin_filters');

/**
 * Apply filters to admin query
 */
function sunnysideac_review_admin_filter_query($query) {
    global $pagenow;

    if ($pagenow === 'edit.php' && $query->query['post_type'] === 'review') {
        // Status filter
        if (isset($_GET['review_status']) && !empty($_GET['review_status'])) {
            $meta_query = $query->get('meta_query') ?: [];
            $meta_query[] = [
                'key' => 'review_status',
                'value' => sanitize_text_field($_GET['review_status']),
                'compare' => '='
            ];
            $query->set('meta_query', $meta_query);
        }

        // Service filter
        if (isset($_GET['service_filter']) && !empty($_GET['service_filter'])) {
            $meta_query = $query->get('meta_query') ?: [];
            $meta_query[] = [
                'key' => 'service_relationship',
                'value' => intval($_GET['service_filter']),
                'compare' => '='
            ];
            $query->set('meta_query', $meta_query);
        }

        // City filter
        if (isset($_GET['city_filter']) && !empty($_GET['city_filter'])) {
            $meta_query = $query->get('meta_query') ?: [];
            $meta_query[] = [
                'key' => 'city_relationship',
                'value' => intval($_GET['city_filter']),
                'compare' => '='
            ];
            $query->set('meta_query', $meta_query);
        }
    }
}
add_filter('pre_get_posts', 'sunnysideac_review_admin_filter_query');

/**
 * Add row actions for quick status changes
 */
function sunnysideac_review_row_actions($actions, $post) {
    if ($post->post_type === 'review') {
        $current_status = get_field('review_status', $post->ID);

        // Add quick approve/reject links
        if ($current_status === 'pending') {
            $actions['approve'] = '<a href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=approve_review&review_id=' . $post->ID), 'approve_review_' . $post->ID)) . '">Approve</a>';
            $actions['reject'] = '<a href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=reject_review&review_id=' . $post->ID), 'reject_review_' . $post->ID)) . '">Reject</a>';
        } elseif ($current_status === 'approved') {
            $actions['reject'] = '<a href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=reject_review&review_id=' . $post->ID), 'reject_review_' . $post->ID)) . '">Reject</a>';
        } elseif ($current_status === 'rejected') {
            $actions['approve'] = '<a href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=approve_review&review_id=' . $post->ID), 'approve_review_' . $post->ID)) . '">Approve</a>';
        }
    }

    return $actions;
}
add_filter('post_row_actions', 'sunnysideac_review_row_actions', 10, 2);

/**
 * Handle quick status change actions
 */
function sunnysideac_handle_quick_status_change() {
    $action = $_GET['action'] ?? '';
    $review_id = isset($_GET['review_id']) ? intval($_GET['review_id']) : 0;

    if (!$review_id || get_post_type($review_id) !== 'review') {
        wp_die('Invalid review ID');
    }

    $nonce_name = $action . '_review_' . $review_id;

    if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', $nonce_name)) {
        wp_die('Security check failed');
    }

    $new_status = '';

    switch ($action) {
        case 'approve_review':
            $new_status = 'approved';
            break;
        case 'reject_review':
            $new_status = 'rejected';
            break;
        default:
            wp_die('Invalid action');
    }

    update_field('review_status', $new_status, $review_id);

    // Redirect back to admin list
    wp_redirect(admin_url('edit.php?post_type=review'));
    exit;
}
add_action('admin_post_approve_review', 'sunnysideac_handle_quick_status_change');
add_action('admin_post_reject_review', 'sunnysideac_handle_quick_status_change');

/**
 * Add admin dashboard widget for pending reviews
 */
function sunnysideac_add_pending_reviews_dashboard_widget() {
    wp_add_dashboard_widget(
        'pending_reviews_widget',
        'Pending Reviews',
        'sunnysideac_pending_reviews_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'sunnysideac_add_pending_reviews_dashboard_widget');

/**
 * Dashboard widget content
 */
function sunnysideac_pending_reviews_dashboard_widget_content() {
    $pending_count = sunnysideac_get_pending_reviews_count();

    if ($pending_count > 0) {
        $pending_reviews = get_posts([
            'post_type' => 'review',
            'posts_per_page' => 5,
            'meta_query' => [
                [
                    'key' => 'review_status',
                    'value' => 'pending',
                    'compare' => '='
                ]
            ],
            'orderby' => 'date',
            'order' => 'DESC'
        ]);

        echo '<p><strong>' . $pending_count . ' pending review(s) need your attention.</strong></p>';

        if (!empty($pending_reviews)) {
            echo '<ul>';
            foreach ($pending_reviews as $review) {
                $reviewer_name = get_field('reviewer_name', $review->ID);
                $service = get_field('service_relationship', $review->ID);
                $service_name = $service ? $service->post_title : 'Unknown Service';

                echo '<li>';
                echo '<a href="' . esc_url(get_edit_post_link($review->ID)) . '">';
                echo esc_html($reviewer_name . ' - ' . $service_name);
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
        }

        echo '<p><a href="' . esc_url(admin_url('edit.php?post_type=review&review_status=pending')) . '" class="button">View All Pending Reviews</a></p>';
    } else {
        echo '<p>No pending reviews at this time.</p>';
    }
}
```

**Testing**:
- [ ] Verify custom columns display correctly in review admin
- [ ] Test column sorting functionality
- [ ] Test admin filters (status, service, city)
- [ ] Test quick approve/reject actions
- [ ] Verify dashboard widget shows pending reviews
- [ ] Test bulk actions (if implemented)

### Step 5.2: Include Admin Column File
**File**: Add to `functions.php` or create `inc/admin/admin.php`

```php
// Load admin-specific files
if (is_admin()) {
    require_once get_template_directory() . '/inc/admin/review-admin-columns.php';
}
```

**Testing**:
- [ ] Verify admin files load only in admin area
- [ ] Check that all admin functionality works correctly

---

## Phase 6: Integration & Testing

**Goal**: Complete integration testing and optimization.

### Step 6.1: Integration Testing Checklist

#### Frontend Form Testing
- [ ] Form loads correctly on submit-review page
- [ ] All dropdowns populate with correct cities and services
- [ ] Star rating interaction works (click, hover, selection)
- [ ] Client-side validation triggers appropriately
- [ ] Character counter works for review content
- [ ] Form submission via AJAX works correctly
- [ ] Success/error messages display properly
- [ ] Form resets after successful submission

#### Backend Processing Testing
- [ ] AJAX handler receives and processes data correctly
- [ ] All validation rules work (required fields, email format, rating range)
- [ ] Duplicate submission prevention works
- [ ] Review post created with correct data and meta fields
- [ ] Review status defaults to 'pending'
- [ ] Error handling and logging works

#### Admin Management Testing
- [ ] Review appears in WordPress admin with correct data
- [ ] Custom columns display correctly (rating, service, city, status, email)
- [ ] Admin filters work for status, service, and city
- [ ] Quick approve/reject actions work
- [ ] Bulk actions work (if implemented)
- [ ] Dashboard widget shows pending reviews
- [ ] Edit individual review works correctly

#### Frontend Display Testing
- [ ] Approved reviews display on archive page
- [ ] Review cards show correct information (excerpt, rating, badges)
- [ ] Single review pages display all information correctly
- [ ] Breadcrumb navigation works
- [ ] "No Reviews Yet" state displays correctly
- [ ] Responsive design works on all devices

#### Navigation Integration Testing
- [ ] Menu items appear in navigation
- [ ] Menu links work correctly
- [ ] Mobile navigation works
- [ ] Page titles and SEO elements are correct

### Step 6.2: Performance Testing
```php
// Add to functions.php for performance monitoring
function sunnysideac_review_performance_test() {
    if (current_user_can('administrator') && isset($_GET['test_review_performance'])) {
        $start_time = microtime(true);

        // Test review query performance
        $reviews = sunnysideac_get_approved_reviews(['posts_per_page' => 100]);

        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time) * 1000; // Convert to milliseconds

        echo '<div style="background: #fff; padding: 20px; margin: 20px; border: 1px solid #ccc;">';
        echo '<h3>Review Performance Test</h3>';
        echo '<p>Reviews fetched: ' . count($reviews) . '</p>';
        echo '<p>Query time: ' . round($execution_time, 2) . 'ms</p>';
        echo '</div>';
    }
}
add_action('wp_footer', 'sunnysideac_review_performance_test');
```

**Testing**:
- [ ] Test query performance with various numbers of reviews
- [ ] Check memory usage during review queries
- [ ] Verify page load times are acceptable
- [ ] Test caching effectiveness (if implemented)

### Step 6.3: Security Testing
- [ ] Test nonce verification prevents CSRF attacks
- [ ] Test input sanitization prevents XSS attacks
- [ ] Test SQL injection prevention
- [ ] Test spam protection (honeypot, rate limiting)
- [ ] Test file upload security (if implemented in future)
- [ ] Test user permission checks

### Step 6.4: Cross-Browser Testing
- [ ] Test in Chrome, Firefox, Safari, Edge
- [ ] Test on mobile devices (iOS, Android)
- [ ] Test on tablets
- [ ] Test with JavaScript disabled (graceful degradation)

### Step 6.5: Final Integration Checklist
- [ ] Flush rewrite rules: `ddev wp rewrite flush`
- [ ] Test all URLs work correctly
- [ ] Verify 404 errors are handled properly
- [ ] Check accessibility compliance
- [ ] Test with different user roles
- [ ] Verify database queries are optimized

---

## Security Checklist

### Input Validation & Sanitization
- [ ] All user inputs sanitized using appropriate WordPress functions
- [ ] Email validation using `is_email()`
- [ ] Numeric values validated with `intval()`
- [ ] Text content sanitized with `sanitize_text_field()` and `sanitize_textarea_field()`
- [ ] HTML content properly escaped on output

### Security Measures
- [ ] Nonce verification for all form submissions
- [ ] Honeypot field for spam protection
- [ ] Rate limiting for duplicate submissions
- [ ] User capability checks for admin functions
- [ ] CSRF protection via WordPress nonces

### Database Security
- [ ] All database queries use WordPress WP_Query or $wpdb methods
- [ ] Prepared statements used for custom queries
- [ ] Proper escaping of all database outputs

### File Security (Future Enhancement)
- [ ] File type validation for uploads
- [ ] File size limits enforced
- [ ] Proper filename sanitization
- [ ] Virus scanning (if required)

### Access Control
- [ ] Admin functions restricted to appropriate user capabilities
- [ ] Frontend forms accessible to all users
- [ ] Review management limited to administrators

---

## Future Enhancements (V2)

### Media Upload Support
- Photo upload functionality (5-10 photos per review)
- Video upload support (limited duration)
- Image compression and optimization
- Media gallery display on review pages
- CDN integration for media delivery

### Email Notifications
- Admin notification emails for new reviews
- Auto-responder emails to reviewers
- Email templates for approval/rejection notifications
- Daily/weekly review summary emails

### Enhanced Display Features
- Integration with existing customer-reviews.php component
- Advanced filtering and sorting for review archives
- Review search functionality
- Review helpfulness voting
- Review response/reply functionality

### SEO & Schema
- Structured data for reviews (Schema.org)
- Rich snippets for search results
- SEO-optimized review pages
- Social media sharing integration

### Advanced Features
- Review verification badges
- Multi-language support
- Review export functionality
- Analytics integration for review tracking
- AI-powered sentiment analysis

### Performance Optimizations
- Caching for review queries
- Lazy loading for review images
- Progressive loading for large review lists
- Database query optimization

---

## Development Commands

### Essential WordPress Commands
```bash
# Flush rewrite rules after CPT changes
ddev wp rewrite flush

# Clear caches
ddev wp cache flush

# Check post types
ddev wp post-type list

# Test review creation via CLI
ddev wp post create --post_type=review --post_title="Test Review" --post_content="This is a test review"
```

### Development Workflow
```bash
# Start development
ddev start
ddev vite

# Build for production
ddev build

# Debug PHP (if needed)
ddev xdebug on
# Remember to disable when done: ddev xdebug off
```

### Testing Commands
```bash
# Check review counts
ddev wp post list --post_type=review --field=ID

# Test form nonce generation
ddev wp eval "echo wp_create_nonce('review_submission_nonce');"

# Check custom fields
ddev wp eval "
\$review = get_post(1);
\$rating = get_field('rating', \$review->ID);
echo 'Review rating: ' . \$rating;
"
```

---

## Rollback Plan

If issues arise during deployment:

### Database Rollback
- Delete review posts created: `ddev wp post delete $(ddev wp post list --post_type=review --field=ID) --force`
- Remove custom field groups via WordPress admin
- Flush rewrite rules: `ddev wp rewrite flush`

### Code Rollback
- Remove created files via git reset
- Remove added functions from `functions.php`
- Remove menu items via WordPress admin or code
- Clear caches: `ddev wp cache flush`

### Testing Rollback
- Verify site functionality works correctly
- Check that no database errors exist
- Test that existing features still work
- Verify performance is not impacted

---

## Support & Maintenance

### Regular Maintenance Tasks
- Monitor review spam and adjust anti-spam measures
- Review and approve/reject pending reviews regularly
- Monitor performance of review queries
- Update documentation as features evolve

### Common Issues & Solutions
- **Form not submitting**: Check nonce verification, AJAX URL, JavaScript errors
- **Reviews not displaying**: Check post status, query parameters, cache issues
- **Admin columns not working**: Check ACF field names, meta query syntax
- **Performance issues**: Implement caching, optimize queries, consider pagination

### Monitoring
- Track review submission rates
- Monitor spam filter effectiveness
- Check page load times for review pages
- Monitor database query performance

---

**This comprehensive development plan provides a complete roadmap for implementing the customer review feature with incremental testing, security best practices, and future scalability considerations.**