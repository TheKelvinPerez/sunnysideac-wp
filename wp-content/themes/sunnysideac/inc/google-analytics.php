<?php
/**
 * Google Analytics 4 Integration (Performance Optimized)
 *
 * Features:
 * - Async/deferred loading via requestIdleCallback
 * - Integrates with Analytics Manager
 * - Auto-tracks PostHog events in GA4
 * - Non-blocking (0ms blocking time)
 * - Privacy-focused (IP anonymization)
 */

/**
 * Output Google Analytics 4 tracking code
 * Loads in idle time to avoid blocking page render
 */
function sunnysideac_google_analytics() {
	if ( empty( $_ENV['GA4_MEASUREMENT_ID'] ) ) {
		return;
	}

	$measurement_id = esc_js( $_ENV['GA4_MEASUREMENT_ID'] );
	?>
	<!-- Google Analytics 4 (Performance Optimized) -->
	<script>
		// Initialize dataLayer early (lightweight)
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		// Configure GA4 with privacy and performance settings
		gtag('config', '<?php echo $measurement_id; ?>', {
			'send_page_view': false,        // We track manually for better control
			'anonymize_ip': true,           // GDPR compliance
			'cookie_flags': 'SameSite=None;Secure', // Modern cookie settings
			'allow_google_signals': false,  // Disable cross-site tracking
		});

		// Load gtag.js asynchronously in idle time (non-blocking)
		if ('requestIdleCallback' in window) {
			requestIdleCallback(function() {
				loadGoogleAnalytics();
			}, { timeout: 3000 });
		} else {
			// Fallback for browsers without requestIdleCallback
			setTimeout(function() {
				loadGoogleAnalytics();
			}, 1500);
		}

		/**
		 * Load Google Analytics script asynchronously
		 */
		function loadGoogleAnalytics() {
			var script = document.createElement('script');
			script.async = true;
			script.defer = true;
			script.src = 'https://www.googletagmanager.com/gtag/js?id=<?php echo $measurement_id; ?>';

			script.onload = function() {
				// Mark GA as loaded
				window._ga_loaded = true;

				// Send initial pageview
				gtag('event', 'page_view', {
					'page_title': document.title,
					'page_location': window.location.href,
					'page_path': window.location.pathname,
				});

				// Register with Analytics Manager
				if (window.AnalyticsManager) {
					window.AnalyticsManager.register('google_analytics', {
						track: function(eventName, data) {
							// Map PostHog events to GA4 events
							const eventMap = {
								// Conversions
								'contact form submitted': 'generate_lead',
								'phone call clicked': 'contact',
								'email clicked': 'email_contact',
								'form submitted': 'form_submission',

								// Engagement
								'navigation clicked': 'navigation_click',
								'service area selected': 'location_selected',
								'faq interaction': 'faq_engagement',
								'social media clicked': 'social_click',

								// Standard events
								'$pageview': 'page_view',
								'scroll depth': 'scroll',
							};

							const gaEventName = eventMap[eventName] || eventName.replace(/ /g, '_');

							// Send to GA4
							gtag('event', gaEventName, data);
						}
					});

					// Trigger custom event
					if ('CustomEvent' in window) {
						window.dispatchEvent(new CustomEvent('ga_loaded'));
					}
				}
			};

			script.onerror = function() {
				console.warn('Google Analytics failed to load');
			};

			document.head.appendChild(script);
		}
	</script>
	<?php
}
add_action( 'wp_head', 'sunnysideac_google_analytics', 999 );

/**
 * Track enhanced ecommerce events (for future use)
 * Call this when user requests a quote or books a service
 *
 * Example usage:
 * sunnysideac_ga4_track_conversion('service_quote', array(
 *     'service_type' => 'AC Installation',
 *     'estimated_value' => 5000,
 *     'city' => 'Miami',
 * ));
 */
function sunnysideac_ga4_track_conversion( $conversion_type, $data = array() ) {
	if ( empty( $_ENV['GA4_MEASUREMENT_ID'] ) ) {
		return;
	}

	// Map conversion types to GA4 event names
	$event_map = array(
		'service_quote'     => 'request_quote',
		'service_booking'   => 'book_service',
		'emergency_service' => 'emergency_call',
		'maintenance_plan'  => 'subscribe',
	);

	$event_name = $event_map[ $conversion_type ] ?? $conversion_type;

	// Add to queue for client-side tracking
	?>
	<script>
		if (typeof gtag !== 'undefined') {
			gtag('event', '<?php echo esc_js( $event_name ); ?>', <?php echo wp_json_encode( $data ); ?>);
		} else {
			// Queue for when GA loads
			window.addEventListener('ga_loaded', function() {
				gtag('event', '<?php echo esc_js( $event_name ); ?>', <?php echo wp_json_encode( $data ); ?>);
			}, { once: true });
		}
	</script>
	<?php
}

/**
 * Set user properties in GA4 (for segmentation)
 * Called automatically when PostHog sets person properties
 */
function sunnysideac_ga4_set_user_properties( $properties ) {
	if ( empty( $_ENV['GA4_MEASUREMENT_ID'] ) ) {
		return;
	}

	// Map PostHog properties to GA4 user properties
	$ga4_properties = array();

	// Convert PostHog properties to GA4 format
	if ( isset( $properties['is_lead'] ) ) {
		$ga4_properties['user_type'] = $properties['is_lead'] ? 'lead' : 'visitor';
	}

	if ( isset( $properties['last_form_submit'] ) ) {
		$ga4_properties['last_conversion_type'] = $properties['last_form_submit'];
	}

	if ( isset( $properties['last_page_type'] ) ) {
		$ga4_properties['last_visited_section'] = $properties['last_page_type'];
	}

	if ( ! empty( $ga4_properties ) ) {
		?>
		<script>
			if (typeof gtag !== 'undefined') {
				gtag('set', 'user_properties', <?php echo wp_json_encode( $ga4_properties ); ?>);
			}
		</script>
		<?php
	}
}
