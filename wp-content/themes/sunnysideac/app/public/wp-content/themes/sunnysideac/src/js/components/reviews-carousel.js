/**
 * Reviews Carousel Module
 * Handles navigation and auto-play for customer reviews carousel
 * Lazy-loaded when reviews section is in viewport
 */

/**
 * Initialize reviews carousel
 */
export function init() {
	const section = document.getElementById('customer-reviews');
	if (!section) {
		console.warn('[ReviewsCarousel] Section not found');
		return;
	}

	console.log('[ReviewsCarousel] Initializing...');

	// Get reviews data from data attribute
	const reviewsData = JSON.parse(section.dataset.reviews || '[]');

	if (!reviewsData.length) {
		console.warn('[ReviewsCarousel] No reviews data found');
		return;
	}

	let currentReviewIndex = 0;
	let autoPlayInterval = null;

	// Function to update review content
	function updateReview(index) {
		const review = reviewsData[index];

		// Update mobile elements
		const mobileContent = document.querySelector('.review-content');
		const mobileName = document.querySelector('.review-name');
		const mobileLocation = document.querySelector('.review-location');
		const mobileAvatar = document.querySelector('.review-avatar');

		if (mobileContent) mobileContent.textContent = `"${review.review}"`;
		if (mobileName) mobileName.textContent = review.name;
		if (mobileLocation) mobileLocation.textContent = review.location;
		if (mobileAvatar) {
			mobileAvatar.src = review.avatar;
			mobileAvatar.alt = `${review.name} profile picture`;
		}

		// Update desktop elements
		const desktopContent = document.querySelector('.review-content-desktop');
		const desktopName = document.querySelector('.review-name-desktop');
		const desktopLocation = document.querySelector('.review-location-desktop');
		const desktopAvatar = document.querySelector('.review-avatar-desktop');

		if (desktopContent) desktopContent.textContent = `"${review.review}"`;
		if (desktopName) desktopName.textContent = review.name;
		if (desktopLocation) desktopLocation.textContent = review.location;
		if (desktopAvatar) {
			desktopAvatar.src = review.avatar;
			desktopAvatar.alt = `${review.name} profile picture`;
		}
	}

	// Navigation functions
	function handlePreviousReview() {
		currentReviewIndex = currentReviewIndex === 0 ? reviewsData.length - 1 : currentReviewIndex - 1;
		updateReview(currentReviewIndex);
	}

	function handleNextReview() {
		currentReviewIndex = currentReviewIndex === reviewsData.length - 1 ? 0 : currentReviewIndex + 1;
		updateReview(currentReviewIndex);
	}

	// Add event listeners for mobile buttons
	const prevBtnMobile = document.querySelector('.prev-review-btn');
	const nextBtnMobile = document.querySelector('.next-review-btn');

	if (prevBtnMobile) {
		prevBtnMobile.addEventListener('click', handlePreviousReview);
	}
	if (nextBtnMobile) {
		nextBtnMobile.addEventListener('click', handleNextReview);
	}

	// Add event listeners for desktop buttons
	const prevBtnDesktop = document.querySelector('.prev-review-btn-desktop');
	const nextBtnDesktop = document.querySelector('.next-review-btn-desktop');

	if (prevBtnDesktop) {
		prevBtnDesktop.addEventListener('click', handlePreviousReview);
	}
	if (nextBtnDesktop) {
		nextBtnDesktop.addEventListener('click', handleNextReview);
	}

	// Add keyboard navigation (only when carousel is visible/focused)
	section.addEventListener('keydown', function(event) {
		if (event.key === 'ArrowLeft') {
			handlePreviousReview();
		} else if (event.key === 'ArrowRight') {
			handleNextReview();
		}
	});

	// Auto-play functions
	function startAutoPlay() {
		stopAutoPlay(); // Clear any existing interval
		autoPlayInterval = setInterval(handleNextReview, 8000); // Change every 8 seconds
	}

	function stopAutoPlay() {
		if (autoPlayInterval) {
			clearInterval(autoPlayInterval);
			autoPlayInterval = null;
		}
	}

	// Start auto-play
	startAutoPlay();

	// Pause auto-play on hover
	section.addEventListener('mouseenter', stopAutoPlay);
	section.addEventListener('mouseleave', startAutoPlay);

	// Cleanup on page unload
	window.addEventListener('beforeunload', stopAutoPlay);

	console.log(`[ReviewsCarousel] Initialized with ${reviewsData.length} reviews`);
}
