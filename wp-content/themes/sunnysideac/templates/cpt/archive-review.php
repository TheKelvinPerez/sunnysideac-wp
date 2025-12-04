<?php
/**
 * Review Archive Template
 *
 * Displays all approved customer reviews with filtering options.
 * Uses the helper functions we created in Phase 1.
 *
 * @package Sunnyside AC
 */

get_header();

// Page breadcrumbs
$breadcrumbs = array(
	array(
		'name' => 'Home',
		'url'  => home_url( '/' ),
	),
	array(
		'name' => 'Customer Reviews',
		'url'  => '',
	),
);

// Get approved reviews using our helper function
$approved_reviews    = sunnysideac_get_approved_reviews();
$average_rating      = sunnysideac_calculate_average_rating( $approved_reviews );
$rating_distribution = sunnysideac_get_rating_distribution( $approved_reviews );
$total_reviews       = count( $approved_reviews );

// Get available services and cities for filtering
$services = get_posts(
	[
		'post_type'      => 'service',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	]
);

$cities = get_posts(
	[
		'post_type'      => 'city',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	]
);
?>

<!-- Page Header with Breadcrumbs -->
<?php
get_template_part(
	'template-parts/page-header',
	null,
	array(
		'breadcrumbs' => $breadcrumbs,
		'title'       => 'Customer Reviews',
		'description' => 'Read what our customers throughout South Florida have to say about Sunnyside AC\'s professional HVAC services. Real reviews from real customers.',
		'show_ctas'   => true,
		'bg_color'    => 'white',
	)
);
?>

<main class="px-5 lg:px-0 max-w-7xl mx-auto">
	<div class="flex gap-10 flex-col py-12">
		<!-- Review Statistics Section -->
		<section class="bg-white rounded-[20px] p-6 md:p-8 lg:p-10">
			<?php
			get_template_part(
				'template-parts/title',
				null,
				array(
					'icon'  => sunnysideac_asset_url( 'assets/icons/review-star-icon-filled.svg' ),
					'title' => 'Customer Satisfaction Overview',
					'align' => 'center',
				)
			);
			?>

			<div class="grid md:grid-cols-3 gap-8 mt-8">
				<!-- Average Rating -->
				<div class="text-center">
					<div class="text-5xl font-bold text-orange-500 mb-2">
						<?php echo esc_html( $average_rating ); ?>
					</div>
					<div class="mb-2 text-center">
						<?php echo sunnysideac_get_star_rating_html( round( $average_rating ) ); ?>
					</div>
					<div class="text-gray-600">
						Average Rating
					</div>
					<div class="text-sm text-gray-500 mt-1">
						Based on <?php echo esc_html( $total_reviews ); ?> reviews
					</div>
				</div>

				<!-- Total Reviews -->
				<div class="text-center">
					<div class="text-5xl font-bold text-blue-500 mb-2">
						<?php echo esc_html( $total_reviews ); ?>
					</div>
					<div class="text-gray-600">
						Total Reviews
					</div>
					<div class="text-sm text-gray-500 mt-1">
						From verified customers
					</div>
				</div>

				<!-- Approval Rate -->
				<div class="text-center">
					<div class="text-5xl font-bold text-green-500 mb-2">
						100%
					</div>
					<div class="text-gray-600">
						Moderated
					</div>
					<div class="text-sm text-gray-500 mt-1">
						All reviews are verified
					</div>
				</div>
			</div>

			<!-- Rating Distribution -->
			<?php if ( $total_reviews > 0 ) : ?>
				<div class="mt-8 pt-8 border-t border-gray-200">
					<h3 class="text-lg font-semibold mb-4 text-center">Rating Distribution</h3>
					<div class="max-w-md mx-auto space-y-2">
						<?php foreach ( [ 5, 4, 3, 2, 1 ] as $stars ) : ?>
							<?php
							$count      = $rating_distribution[ $stars ] ?? 0;
							$percentage = $total_reviews > 0 ? round( ( $count / $total_reviews ) * 100 ) : 0;
							?>
							<div class="flex items-center gap-3">
								<div class="w-20 text-sm">
									<?php echo $stars; ?> stars
								</div>
								<div class="flex-1 bg-gray-200 rounded-full h-2">
									<div class="bg-gradient-to-r from-[#F79E37] to-[#E5462F] h-2 rounded-full transition-all duration-300"
										style="width: <?php echo esc_attr( $percentage ); ?>%"></div>
								</div>
								<div class="w-12 text-sm text-right">
									<?php echo esc_html( $count ); ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</section>

		<!-- Submit Review CTA -->
		<section class="bg-gradient-to-r from-[#F79E37] to-[#E5462F] rounded-[20px] p-8 text-center text-white">
			<h2 class="text-2xl md:text-3xl font-bold mb-4">
				Share Your Experience
			</h2>
			<p class="text-lg mb-6 opacity-90">
				Had a great experience with Sunnyside AC? We'd love to hear from you!
			</p>
			<a href="<?php echo esc_url( home_url( '/review/' ) ); ?>"
				class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-8 py-4 font-medium text-orange-500 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
				Submit Your Review
			</a>
		</section>

		<!-- Filter Options -->
		<?php if ( ! empty( $services ) || ! empty( $cities ) ) : ?>
			<section class="bg-white rounded-[20px] p-6 md:p-8">
				<h3 class="text-xl font-semibold mb-4">Filter Reviews</h3>
				<div class="grid md:grid-cols-2 gap-6">
					<?php if ( ! empty( $services ) ) : ?>
						<div>
							<label for="service-filter" class="block text-sm font-medium text-gray-700 mb-2">
								Filter by Service
							</label>
							<select id="service-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white">
								<option value="">All Services</option>
								<?php foreach ( $services as $service ) : ?>
									<option value="<?php echo esc_attr( $service->ID ); ?>">
										<?php echo esc_html( $service->post_title ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $cities ) ) : ?>
						<div>
							<label for="city-filter" class="block text-sm font-medium text-gray-700 mb-2">
								Filter by Location
							</label>
							<select id="city-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white">
								<option value="">All Cities</option>
								<?php foreach ( $cities as $city ) : ?>
									<option value="<?php echo esc_attr( $city->ID ); ?>">
										<?php echo esc_html( $city->post_title ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>

		<!-- Reviews Grid -->
		<section class="bg-white rounded-[20px] p-6 md:p-8 lg:p-10">
			<?php
			get_template_part(
				'template-parts/title',
				null,
				array(
					'title' => 'Customer Reviews',
					'align' => 'center',
				)
			);
			?>

			<?php if ( $approved_reviews ) : ?>
				<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8" id="reviews-container">
					<?php foreach ( $approved_reviews as $review ) : ?>
						<?php get_template_part( 'template-parts/review-card', null, [ 'review' => $review ] ); ?>
					<?php endforeach; ?>
				</div>

				<!-- Load More Button (for future pagination) -->
				<div class="text-center mt-8">
					<button id="load-more-reviews" class="hidden inline-flex items-center justify-center gap-2 rounded-full bg-gray-100 px-8 py-4 font-medium text-gray-700 transition-opacity hover:opacity-80 focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 focus:outline-none">
						Load More Reviews
					</button>
				</div>

			<?php else : ?>
				<div class="text-center py-12">
					<div class="text-6xl mb-4">⭐</div>
					<h3 class="text-xl font-semibold mb-2">No Reviews Yet</h3>
					<p class="text-gray-600 mb-6">
						Be the first to share your experience with Sunnyside AC!
					</p>
					<a href="<?php echo esc_url( home_url( '/review/' ) ); ?>"
						class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-[#F79E37] to-[#E5462F] px-8 py-4 font-medium text-white transition-opacity hover:opacity-90 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none">
						Submit First Review
					</a>
				</div>
			<?php endif; ?>
		</section>
	</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const serviceFilter = document.getElementById('service-filter');
	const cityFilter = document.getElementById('city-filter');
	const reviewsContainer = document.getElementById('reviews-container');
	const allReviewCards = reviewsContainer.querySelectorAll('.review-card');

	function filterReviews() {
		const selectedService = serviceFilter.value;
		const selectedCity = cityFilter.value;

		allReviewCards.forEach(card => {
			const cardService = card.dataset.serviceId;
			const cardCity = card.dataset.cityId;

			let showCard = true;

			if (selectedService && cardService !== selectedService) {
				showCard = false;
			}

			if (selectedCity && cardCity !== selectedCity) {
				showCard = false;
			}

			if (showCard) {
				card.style.display = 'block';
			} else {
				card.style.display = 'none';
			}
		});

		// Check if any reviews are visible
		const visibleReviews = Array.from(allReviewCards).filter(card => card.style.display !== 'none');

		if (visibleReviews.length === 0) {
			// Show "no reviews found" message
			if (!reviewsContainer.querySelector('.no-reviews-message')) {
				const noReviewsMsg = document.createElement('div');
				noReviewsMsg.className = 'col-span-full text-center py-8 no-reviews-message';
				noReviewsMsg.innerHTML = `
					<p class="text-gray-500">No reviews found matching your filters.</p>
					<button onclick="clearFilters()" class="mt-2 text-orange-500 hover:text-orange-600 underline">
						Clear filters
					</button>
				`;
				reviewsContainer.appendChild(noReviewsMsg);
			}
		} else {
			// Remove "no reviews found" message if it exists
			const noReviewsMsg = reviewsContainer.querySelector('.no-reviews-message');
			if (noReviewsMsg) {
				noReviewsMsg.remove();
			}
		}
	}

	function clearFilters() {
		serviceFilter.value = '';
		cityFilter.value = '';
		filterReviews();
	}

	if (serviceFilter) {
		serviceFilter.addEventListener('change', filterReviews);
	}

	if (cityFilter) {
		cityFilter.addEventListener('change', filterReviews);
	}

	// Make clearFilters available globally
	window.clearFilters = clearFilters;
});
</script>

<?php
get_footer();
