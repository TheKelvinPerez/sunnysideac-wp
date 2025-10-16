<?php
/**
 * Customer Reviews Section Template Part
 * Displays customer testimonials with interactive carousel functionality
 */

// Icons data
$icons = [
    'star' => sunnysideac_asset_url('assets/icons/star-icon.svg'),
    'testimonial_star' => sunnysideac_asset_url('assets/images/home-page/testimonial-star.svg'),
    'arrow_right' => sunnysideac_asset_url('assets/images/home-page/customer-review-arrow.svg'),
];

// Images data
$images = [
    'customer_review_card' => sunnysideac_asset_url('assets/images/home-page/Customer-Review-Card-Image.png'),
    'review_photos' => [
        sunnysideac_asset_url('assets/images/images/hero/review_photo_1.png'),
        sunnysideac_asset_url('assets/images/images/hero/review_photo_2.png'),
        sunnysideac_asset_url('assets/images/images/hero/review_photo_3.png'),
    ],
];

// Reviews data
$reviews_data = [
    [
        'id' => 1,
        'name' => 'Alexa Rodriguez',
        'location' => 'Boca Raton Resident',
        'rating' => 5,
        'review' => 'Clients know they can count on Sunny Side 24/7 AC not just for reliable cooling solutions, but for the warmth and integrity of a family that treats every customer like their own. From the first handshake to the final inspection, every project is completed with passion, precision, and a promise of lasting comfort.',
        'avatar' => $images['review_photos'][0],
    ],
    [
        'id' => 2,
        'name' => 'Sarah Johnson',
        'location' => 'Miami Resident',
        'rating' => 5,
        'review' => 'Outstanding service from start to finish! The team was professional, punctual, and incredibly knowledgeable. They explained everything clearly and left my home cleaner than when they arrived. My new AC system is working perfectly!',
        'avatar' => $images['review_photos'][1],
    ],
    [
        'id' => 3,
        'name' => 'Michael Torres',
        'location' => 'Fort Lauderdale Resident',
        'rating' => 5,
        'review' => 'Emergency AC repair on a Sunday and they came out immediately! Fair pricing, excellent work, and genuine care for customer satisfaction. This is what real family business service looks like.',
        'avatar' => $images['review_photos'][2],
    ],
];

// Description text
$description_text = 'Our greatest reward is the trust and satisfaction of our customers. From emergency repairs to full system installations, homeowners count on us for reliable service, expert craftsmanship, and a personal touch that makes all the difference.';
?>

<section class="w-full rounded-[20px] bg-white p-6 md:p-12" id="customer-reviews">
    <div class="mx-auto max-w-7xl">
        <!-- Mobile Layout: Stacked vertically -->
        <div class="block lg:hidden">
            <!-- Header Section -->
            <header class="mb-8 text-left">
                <?php
                get_template_part(
                    'template-parts/title',
                    null,
                    [
                        'icon' => $icons['testimonial_star'],
                        'title' => 'Testimonials',
                        'id' => 'testimonials-heading',
                        'align' => 'left',
                    ]
                );
                ?>
                <?php
                get_template_part(
                    'template-parts/subheading',
                    null,
                    [
                        'text' => 'Customer Reviews',
                        'class' => 'mb-4 text-gray-600 md:text-4xl md:leading-tight',
                    ]
                );
                ?>
                <p class="max-w-4xl text-base leading-tight font-light text-black">
                    <?php echo esc_html($description_text); ?>
                </p>
            </header>

            <!-- Image Section -->
            <div class="mb-8">
                <div class="relative">
                    <!-- Main Image -->
                    <img
                        src="<?php echo esc_url($images['customer_review_card']); ?>"
                        alt="Customer service representative helping a client"
                        class="mx-auto h-auto w-full max-w-sm rounded-2xl object-cover"
                        loading="lazy"
                        decoding="async"
                    />

                    <!-- Stats Card -->
                    <div class="absolute -right-4 bottom-3 md:-right-2">
                        <div class="rounded-2xl bg-gradient-to-l from-[#D87400] to-[#F8AA50] p-4 text-white">
                            <div class="text-2xl font-medium">100+</div>
                            <div class="text-sm font-thin">Customer Reviews</div>
                        </div>
                    </div>

                    <!-- Circular Badge -->
                    <div class="absolute -top-5 -right-5">
                        <div class="relative h-20 w-20">
                            <div class="absolute inset-0 rounded-full bg-white shadow-lg"></div>
                            <div class="absolute inset-0 z-10 flex items-center justify-center">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white">
                                    <img src="<?php echo esc_url($icons['arrow_right']); ?>" alt="" class="h-4 w-4" loading="lazy" decoding="async" />
                                </div>
                            </div>
                            <div class="absolute inset-0 z-5">
                                <svg
                                    class="h-full w-full rotating-text p-1.5"
                                    viewBox="0 0 128 128"
                                >
                                    <defs>
                                        <path
                                            id="circle-path-mobile"
                                            d="M 64,64 m-50,0 a 50,50 0 1,1 100,0 a 50,50 0 1,1 -100,0"
                                        />
                                    </defs>
                                    <text
                                        class="fill-black text-[18px] font-normal md:text-[10px]"
                                        style="text-shadow: 0 0 3px white"
                                    >
                                        <textPath href="#circle-path-mobile" startOffset="0%">
                                            The Best at keeping you refreshed!
                                        </textPath>
                                    </text>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Card -->
            <article class="flex h-[95vw] flex-col gap-4 rounded-2xl bg-gray-50 p-4">
                <div class="flex gap-1 review-stars" aria-label="5 out of 5 stars">
                    <?php for ($i = 0; $i < 5; $i++) : ?>
                        <img
                            src="<?php echo esc_url($icons['star']); ?>"
                            alt=""
                            class="h-4 w-4 opacity-100"
                            loading="lazy"
                            decoding="async"
                        />
                    <?php endfor; ?>
                </div>

                <blockquote class="min-h-0 flex-1 overflow-y-auto text-base leading-tight text-gray-900 review-content">
                    "<?php echo esc_html($reviews_data[0]['review']); ?>"
                </blockquote>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <img
                            src="<?php echo esc_url($reviews_data[0]['avatar']); ?>"
                            alt="<?php echo esc_attr($reviews_data[0]['name']); ?> profile picture"
                            class="h-12 w-12 rounded-full object-cover review-avatar"
                            loading="lazy"
                            decoding="async"
                        />
                        <div>
                            <h2 class="text-sm font-semibold text-gray-900 review-name">
                                <?php echo esc_html($reviews_data[0]['name']); ?>
                            </h2>
                            <p class="text-sm font-light text-black review-location">
                                <?php echo esc_html($reviews_data[0]['location']); ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-300 transition-colors duration-200 hover:bg-gray-400 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none prev-review-btn"
                            aria-label="Previous review"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 19l-7-7 7-7"
                                />
                            </svg>
                        </button>
                        <button
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-300 transition-colors duration-200 hover:bg-gray-400 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none next-review-btn"
                            aria-label="Next review"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </article>
        </div>

        <!-- Desktop Layout: 2-column grid -->
        <div class="hidden lg:grid lg:grid-cols-2 lg:items-start lg:gap-12">
            <!-- Left Column: Content -->
            <div class="flex h-full flex-col justify-between space-y-8">
                <!-- Header Section -->
                <header class="text-left">
                    <?php
                    get_template_part(
                        'template-parts/title',
                        null,
                        [
                            'icon' => $icons['testimonial_star'],
                            'title' => 'Testimonials',
                            'id' => 'testimonials-heading-desktop',
                            'align' => 'left',
                        ]
                    );
                    ?>
                    <?php
                    get_template_part(
                        'template-parts/subheading',
                        null,
                        [
                            'text' => 'Customer Reviews',
                            'class' => 'mb-4 text-5xl text-gray-600 md:text-4xl md:leading-tight',
                        ]
                    );
                    ?>
                    <p class="text-lg leading-tight font-light text-black">
                        <?php echo esc_html($description_text); ?>
                    </p>
                </header>

                <!-- Review Card -->
                <article class="flex h-72 flex-col gap-4 rounded-2xl bg-gray-50 p-6">
                    <div class="flex gap-1 review-stars-desktop" aria-label="5 out of 5 stars">
                        <?php for ($i = 0; $i < 5; $i++) : ?>
                            <img
                                src="<?php echo esc_url($icons['star']); ?>"
                                alt=""
                                class="h-4 w-4 opacity-100"
                                loading="lazy"
                                decoding="async"
                            />
                        <?php endfor; ?>
                    </div>

                    <blockquote class="min-h-0 flex-1 overflow-y-auto text-lg leading-tight text-gray-900 review-content-desktop">
                        "<?php echo esc_html($reviews_data[0]['review']); ?>"
                    </blockquote>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <img
                                src="<?php echo esc_url($reviews_data[0]['avatar']); ?>"
                                alt="<?php echo esc_attr($reviews_data[0]['name']); ?> profile picture"
                                class="h-12 w-12 rounded-full object-cover review-avatar-desktop"
                                loading="lazy"
                                decoding="async"
                            />
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 review-name-desktop">
                                    <?php echo esc_html($reviews_data[0]['name']); ?>
                                </h2>
                                <p class="text-base font-light text-black review-location-desktop">
                                    <?php echo esc_html($reviews_data[0]['location']); ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button
                                type="button"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-300 transition-colors duration-200 hover:bg-gray-400 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none prev-review-btn-desktop"
                                aria-label="Previous review"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 19l-7-7 7-7"
                                    />
                                </svg>
                            </button>
                            <button
                                type="button"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-300 transition-colors duration-200 hover:bg-gray-400 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none next-review-btn-desktop"
                                aria-label="Next review"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Right Column: Full-width Image -->
            <div class="relative">
                <div class="relative">
                    <!-- Main Image - Full width -->
                    <img
                        src="<?php echo esc_url($images['customer_review_card']); ?>"
                        alt="Customer service representative helping a client"
                        class="h-auto w-full rounded-2xl object-cover"
                        loading="lazy"
                        decoding="async"
                    />

                    <!-- Stats Card -->
                    <div class="absolute -right-6 bottom-8">
                        <div class="rounded-2xl bg-gradient-to-l from-[#D87400] to-[#F8AA50] p-6 text-white">
                            <div class="mb-2 text-3xl font-medium">100+</div>
                            <div class="text-lg font-thin">Customer Reviews</div>
                        </div>
                    </div>

                    <!-- Circular Badge -->
                    <div class="absolute top-4 -right-6">
                        <div class="relative h-32 w-32">
                            <div class="absolute inset-0 rounded-full bg-white shadow-lg"></div>
                            <div class="absolute inset-0 z-10 flex items-center justify-center">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white">
                                    <img src="<?php echo esc_url($icons['arrow_right']); ?>" alt="" class="h-8 w-8" loading="lazy" decoding="async" />
                                </div>
                            </div>
                            <div class="absolute inset-0 z-5">
                                <svg
                                    class="h-full w-full rotating-text p-2"
                                    viewBox="0 0 128 128"
                                >
                                    <defs>
                                        <path
                                            id="circle-path-desktop"
                                            d="M 64,64 m-50,0 a 50,50 0 1,1 100,0 a 50,50 0 1,1 -100,0"
                                        />
                                    </defs>
                                    <text
                                        class="fill-black text-lg font-normal"
                                        style="text-shadow: 0 0 3px white"
                                    >
                                        <textPath href="#circle-path-desktop" startOffset="0%">
                                            The Best at keeping you refreshed!
                                        </textPath>
                                    </text>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .rotating-text {
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store reviews data in JavaScript
    const reviewsData = <?php echo json_encode($reviews_data); ?>;

    let currentReviewIndex = 0;

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

    // Add keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'ArrowLeft') {
            handlePreviousReview();
        } else if (event.key === 'ArrowRight') {
            handleNextReview();
        }
    });

    // Optional: Auto-play carousel
    let autoPlayInterval;

    function startAutoPlay() {
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
    const section = document.getElementById('customer-reviews');
    if (section) {
        section.addEventListener('mouseenter', stopAutoPlay);
        section.addEventListener('mouseleave', startAutoPlay);
    }
});
</script>