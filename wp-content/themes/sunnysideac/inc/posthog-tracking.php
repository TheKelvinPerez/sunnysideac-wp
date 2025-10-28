<?php
/**
 * PostHog Analytics & Event Tracking (Performance Optimized)
 *
 * This file contains all PostHog tracking functions for:
 * - Session recording (deferred, non-blocking)
 * - Event tracking (conversions, engagement, navigation)
 * - Person properties for segmentation
 * - Feature flags support
 *
 * PERFORMANCE FEATURES:
 * - Async/deferred loading
 * - requestIdleCallback for non-critical init
 * - Manual event tracking (no autocapture overhead)
 * - Batched event sending
 * - Prepared for multiple analytics providers (FB Pixel, GA, etc.)
 */

/**
 * Get PostHog distinct ID for current user
 * Uses WordPress user ID if logged in, otherwise generates session-based ID
 */
function sunnysideac_get_posthog_distinct_id() {
	if ( is_user_logged_in() ) {
		return 'wp_user_' . get_current_user_id();
	}

	// Use PHP session or cookie-based ID for anonymous users
	if ( ! isset( $_COOKIE['posthog_distinct_id'] ) ) {
		$distinct_id = 'anon_' . bin2hex( random_bytes( 16 ) );
		setcookie( 'posthog_distinct_id', $distinct_id, time() + ( 86400 * 365 ), '/' ); // 1 year
		return $distinct_id;
	}

	return $_COOKIE['posthog_distinct_id'];
}

/**
 * Capture server-side PostHog event
 *
 * @param string $event_name Event name (e.g., 'form submitted', 'page viewed')
 * @param array  $properties Event properties
 * @param bool   $send_feature_flags Whether to include feature flags (default: false)
 */
function sunnysideac_capture_posthog_event( $event_name, $properties = array(), $send_feature_flags = false ) {
	if ( ! class_exists( 'PostHog\PostHog' ) ) {
		return;
	}

	try {
		$distinct_id = sunnysideac_get_posthog_distinct_id();

		// Add standard properties
		$properties = array_merge(
			array(
				'$current_url' => $_SERVER['REQUEST_URI'] ?? '',
				'$host'        => $_SERVER['HTTP_HOST'] ?? '',
				'user_agent'   => $_SERVER['HTTP_USER_AGENT'] ?? '',
				'referrer'     => $_SERVER['HTTP_REFERER'] ?? '',
				'environment'  => $_ENV['APP_ENV'] ?? 'production',
			),
			$properties
		);

		PostHog\PostHog::capture(
			array(
				'distinctId'         => $distinct_id,
				'event'              => $event_name,
				'properties'         => $properties,
				'send_feature_flags' => $send_feature_flags,
			)
		);
	} catch ( Exception $e ) {
		error_log( 'PostHog capture error: ' . $e->getMessage() );
	}
}

/**
 * Set person properties for segmentation
 *
 * @param array $properties Person properties to set
 */
function sunnysideac_set_person_properties( $properties = array() ) {
	if ( ! class_exists( 'PostHog\PostHog' ) ) {
		return;
	}

	try {
		$distinct_id = sunnysideac_get_posthog_distinct_id();

		PostHog\PostHog::capture(
			array(
				'distinctId' => $distinct_id,
				'event'      => '$identify',
				'properties' => array(
					'$set' => $properties,
				),
			)
		);
	} catch ( Exception $e ) {
		error_log( 'PostHog identify error: ' . $e->getMessage() );
	}
}

/**
 * Track page view server-side
 * Useful for tracking service-city pages and other dynamic pages
 */
function sunnysideac_track_page_view() {
	global $post;

	$properties = array(
		'page_type'  => 'unknown',
		'page_title' => get_the_title(),
	);

	// Identify page type
	if ( is_front_page() ) {
		$properties['page_type'] = 'homepage';
	} elseif ( is_singular( 'service' ) ) {
		$properties['page_type']    = 'service';
		$properties['service_name'] = get_the_title();

		// Check if it's a service-city page
		$city_slug = get_query_var( 'city_slug' );
		if ( $city_slug ) {
			$properties['page_type'] = 'service_city';
			$properties['city_slug'] = $city_slug;
			$city_post               = get_page_by_path( $city_slug, OBJECT, 'city' );
			if ( $city_post ) {
				$properties['city_name'] = $city_post->post_title;
			}
		}
	} elseif ( is_singular( 'city' ) ) {
		$properties['page_type'] = 'city';
		$properties['city_name'] = get_the_title();
	} elseif ( is_singular( 'post' ) ) {
		$properties['page_type']     = 'blog_post';
		$properties['post_category'] = wp_get_post_categories( get_the_ID(), array( 'fields' => 'names' ) );
	} elseif ( is_page() ) {
		$properties['page_type']     = 'page';
		$properties['page_template'] = get_page_template_slug();
	}

	// Set person properties for segmentation
	$person_props = array(
		'last_page_viewed' => $properties['page_title'],
		'last_page_type'   => $properties['page_type'],
	);

	// Add geographic data if available (for local SEO insights)
	if ( isset( $_SERVER['HTTP_CF_IPCOUNTRY'] ) ) {
		$person_props['country'] = $_SERVER['HTTP_CF_IPCOUNTRY'];
	}

	sunnysideac_set_person_properties( $person_props );

	// Track the page view
	sunnysideac_capture_posthog_event( '$pageview', $properties );
}

/**
 * Track form submissions server-side
 * Called from AJAX handlers
 */
function sunnysideac_track_form_submission( $form_type, $form_data = array() ) {
	$properties = array(
		'form_type' => $form_type,
	);

	// Add relevant form data (sanitized - no PII in events)
	if ( $form_type === 'contact' ) {
		$properties['service_type'] = $form_data['serviceType'] ?? '';
		$properties['category']     = $form_data['selectCategory'] ?? '';
	} elseif ( $form_type === 'careers' ) {
		$properties['position']         = $form_data['position'] ?? '';
		$properties['years_experience'] = $form_data['experience'] ?? 0;
	} elseif ( $form_type === 'warranty' ) {
		$properties['equipment_type'] = $form_data['equipment_type'] ?? '';
		$properties['urgent']         = ( $form_data['urgent_service'] ?? '' ) === 'yes';
	}

	sunnysideac_capture_posthog_event( 'form submitted', $properties );

	// Update person properties to mark as lead
	sunnysideac_set_person_properties(
		array(
			'is_lead'            => true,
			'last_form_submit'   => $form_type,
			'total_form_submits' => 1, // PostHog will increment this
		)
	);
}

/**
 * Output PostHog JavaScript SDK with performance optimizations
 * Uses latest PostHog snippet with:
 * - Async/deferred loading
 * - requestIdleCallback for non-critical init
 * - Manual tracking (no autocapture overhead)
 * - Batched events
 */
function sunnysideac_output_posthog_js() {
	if ( empty( $_ENV['POSTHOG_API_KEY'] ) ) {
		return;
	}

	$api_key     = esc_js( $_ENV['POSTHOG_API_KEY'] );
	$host        = esc_js( $_ENV['POSTHOG_HOST'] ?? 'https://us.i.posthog.com' );
	$distinct_id = esc_js( sunnysideac_get_posthog_distinct_id() );
	?>
	<!-- PostHog Analytics (Performance Optimized) -->
	<script>
		// PostHog Loader - Using proxied CDN for better caching
		!function(t,e){var o,n,p,r;e.__SV||(window.posthog=e,e._i=[],e.init=function(i,s,a){function g(t,e){var o=e.split(".");2==o.length&&(t=t[o[0]],e=o[1]),t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}}(p=t.createElement("script")).type="text/javascript",p.crossOrigin="anonymous",p.async=!0,p.src=(s.asset_host || window.location.origin + '/cdn/posthog')+"/static/array.js",(r=t.getElementsByTagName("script")[0]).parentNode.insertBefore(p,r);var u=e;for(void 0!==a?u=e[a]=[]:a="posthog",u.people=u.people||[],u.toString=function(t){var e="posthog";return"posthog"!==a&&(e+="."+a),t||(e+=" (stub)"),e},u.people.toString=function(){return u.toString(1)+".people (stub)"},o="init capture register register_once register_for_session unregister unregister_for_session getFeatureFlag getFeatureFlagPayload isFeatureEnabled reloadFeatureFlags updateEarlyAccessFeatureEnrollment getEarlyAccessFeatures on onFeatureFlags onSessionId getSurveys getActiveMatchingSurveys renderSurvey canRenderSurvey getNextSurveyStep identify setPersonProperties group resetGroups setPersonPropertiesForFlags resetPersonPropertiesForFlags setGroupPropertiesForFlags resetGroupPropertiesForFlags reset get_distinct_id getGroups get_session_id get_session_replay_url alias set_config startSessionRecording stopSessionRecording sessionRecordingStarted captureException loadToolbar get_property getSessionProperty createPersonProfile opt_in_capturing opt_out_capturing has_opted_in_capturing has_opted_out_capturing clear_opt_in_out_capturing debug".split(" "),n=0;n<o.length;n++)g(u,o[n]);e._i.push([i,s,a])},e.__SV=1)}(document,window.posthog||[]);

		// Initialize PostHog with performance settings
		posthog.init('<?php echo $api_key; ?>', {
			api_host: '<?php echo $host; ?>',
			person_profiles: 'identified_only', // Only create profiles for identified users

			// PERFORMANCE: Disable auto pageview (we track manually)
			capture_pageview: false,
			capture_pageleave: true,

			// PERFORMANCE: Disable autocapture (manual tracking is faster)
			autocapture: false,

			// Session recording with privacy
			session_recording: {
				maskAllInputs: true,
				maskTextSelector: '*',
				recordCrossOriginIframes: false,
			},

			// PERFORMANCE: Enable performance tracking
			capture_performance: true,

			// Use proxied asset URLs for better caching
			api_host: '<?php echo $host; ?>',
			ui_host: '<?php echo $host; ?>',
			asset_host: window.location.origin + '/cdn/posthog',

			// Loaded callback - runs after library loads
			loaded: function(posthog) {
				// Use requestIdleCallback to defer non-critical initialization
				// This ensures PostHog doesn't block main thread
				if ('requestIdleCallback' in window) {
					requestIdleCallback(function() {
						initPostHogTracking(posthog);
					}, { timeout: 2000 });
				} else {
					// Fallback for browsers without requestIdleCallback
					setTimeout(function() {
						initPostHogTracking(posthog);
					}, 1);
				}
			}
		});

		/**
		 * Initialize PostHog tracking (runs in idle time)
		 * This function sets up all event listeners without blocking
		 */
		function initPostHogTracking(posthog) {
			// Identify user with server-provided ID
			posthog.identify('<?php echo $distinct_id; ?>');

			// Capture initial pageview
			posthog.capture('$pageview', {
				'$current_url': window.location.href,
				'$pathname': window.location.pathname,
				'$referrer': document.referrer,
				'page_title': document.title,
				'screen_width': window.screen.width,
				'screen_height': window.screen.height,
				'viewport_width': window.innerWidth,
				'viewport_height': window.innerHeight,
			});

			// Store globally for other analytics scripts
			window._posthog_loaded = true;

			// Trigger custom event for other scripts
			if ('CustomEvent' in window) {
				window.dispatchEvent(new CustomEvent('posthog_loaded'));
			}
		}
	</script>
	<?php
}
add_action( 'wp_head', 'sunnysideac_output_posthog_js', 999 );

/**
 * Track page views on template load (server-side)
 */
function sunnysideac_track_template_pageview() {
	if ( ! is_admin() && ! wp_doing_ajax() ) {
		sunnysideac_track_page_view();
	}
}
add_action( 'template_redirect', 'sunnysideac_track_template_pageview' );

/**
 * Add PostHog event tracking to contact form submission
 */
add_action( 'wp_ajax_nopriv_web3forms_submit', 'sunnysideac_track_contact_form', 1 );
add_action( 'wp_ajax_web3forms_submit', 'sunnysideac_track_contact_form', 1 );
function sunnysideac_track_contact_form() {
	// This runs before form submission
	sunnysideac_track_form_submission( 'contact', $_POST );
}

/**
 * Track careers form submission
 */
add_action( 'wp_ajax_nopriv_sunnysideac_handle_careers_form', 'sunnysideac_track_careers_form_submit', 1 );
add_action( 'wp_ajax_sunnysideac_handle_careers_form', 'sunnysideac_track_careers_form_submit', 1 );
function sunnysideac_track_careers_form_submit() {
	sunnysideac_track_form_submission( 'careers', $_POST );
}

/**
 * Track warranty claim submission
 */
add_action( 'wp_ajax_nopriv_sunnysideac_handle_warranty_claim_form', 'sunnysideac_track_warranty_form_submit', 1 );
add_action( 'wp_ajax_sunnysideac_handle_warranty_claim_form', 'sunnysideac_track_warranty_form_submit', 1 );
function sunnysideac_track_warranty_form_submit() {
	sunnysideac_track_form_submission( 'warranty', $_POST );
}

/**
 * Output JavaScript for client-side event tracking
 * Runs in idle time to avoid blocking main thread
 * All event listeners use passive mode when possible
 */
function sunnysideac_output_posthog_tracking_js() {
	if ( empty( $_ENV['POSTHOG_API_KEY'] ) ) {
		return;
	}
	?>
	<script>
	/**
	 * PostHog Client-Side Event Tracking (Performance Optimized)
	 * - All listeners run in idle time
	 * - Uses event delegation to minimize listeners
	 * - Throttled scroll tracking
	 * - Non-blocking event capture
	 */
	(function() {
		'use strict';

		// Wait for PostHog to load and initialize
		function waitForPostHog(callback) {
			if (window._posthog_loaded && typeof posthog !== 'undefined') {
				callback();
			} else {
				// Listen for posthog_loaded event or poll
				if ('CustomEvent' in window) {
					window.addEventListener('posthog_loaded', callback, { once: true });
				}
				setTimeout(function() { waitForPostHog(callback); }, 100);
			}
		}

		// Initialize tracking when PostHog is ready (in idle time)
		if ('requestIdleCallback' in window) {
			requestIdleCallback(function() {
				waitForPostHog(setupTracking);
			});
		} else {
			setTimeout(function() {
				waitForPostHog(setupTracking);
			}, 1000);
		}

		function setupTracking() {
			// Event delegation for clicks (single listener on document)
			document.addEventListener('click', handleClick, { passive: true });

			// Form field focus tracking (delegated, once per session per field)
			document.addEventListener('focus', handleFocus, { capture: true, passive: true });

			// Throttled scroll tracking
			setupScrollTracking();

			// Time on page tracking
			setupTimeTracking();
		}

		/**
		 * Handle all click events (event delegation)
		 */
		function handleClick(e) {
			const target = e.target.closest('a, button');
			if (!target) return;

			const href = target.getAttribute('href') || '';

			// Phone call clicks
			if (href.startsWith('tel:')) {
				posthog.capture('phone call clicked', {
					'phone_number': href.replace('tel:', ''),
					'button_location': getElementLocation(target),
					'button_text': target.textContent.trim().substring(0, 50),
				});
			}

			// Email clicks
			else if (href.startsWith('mailto:')) {
				posthog.capture('email clicked', {
					'email': href.replace('mailto:', ''),
					'button_location': getElementLocation(target),
				});
			}

			// CTA button clicks
			else if (target.id && (target.id.includes('call-btn') || target.id === 'submit-btn')) {
				posthog.capture('cta button clicked', {
					'button_id': target.id,
					'button_type': href.startsWith('tel:') ? 'call' : 'submit',
				});
			}

			// Navigation clicks
			else if (target.closest('nav')) {
				const menuText = target.textContent.trim();
				if (menuText && menuText.length < 100) {
					posthog.capture('navigation clicked', {
						'menu_item': menuText,
						'menu_type': target.closest('#mobile-menu') ? 'mobile' : 'desktop',
					});
				}
			}

			// Service area selection
			else if (target.id === 'location-select' && target.value) {
				posthog.capture('service area selected', {
					'location': target.value,
				});
			}

			// FAQ interactions
			else if (target.hasAttribute('aria-expanded')) {
				const isExpanding = target.getAttribute('aria-expanded') === 'false';
				posthog.capture('faq interaction', {
					'action': isExpanding ? 'expanded' : 'collapsed',
					'question': target.textContent.trim().substring(0, 100),
				});
			}

			// Social media clicks
			else if (href.match(/facebook|instagram|twitter|youtube|linkedin/i)) {
				let platform = 'unknown';
				if (href.includes('facebook')) platform = 'facebook';
				else if (href.includes('instagram')) platform = 'instagram';
				else if (href.includes('twitter')) platform = 'twitter';
				else if (href.includes('youtube')) platform = 'youtube';
				else if (href.includes('linkedin')) platform = 'linkedin';

				posthog.capture('social media clicked', {
					'platform': platform,
					'location': getElementLocation(target),
				});
			}
		}

		/**
		 * Handle form field focus (track engagement)
		 */
		const focusedFields = new Set();
		function handleFocus(e) {
			if (e.target.matches('input, select, textarea')) {
				const fieldKey = e.target.name + '_' + e.target.type;
				if (!focusedFields.has(fieldKey)) {
					focusedFields.add(fieldKey);

					const form = e.target.closest('form');
					posthog.capture('form field focused', {
						'form_id': form ? form.id : 'unknown',
						'field_name': e.target.name,
						'field_type': e.target.type || e.target.tagName.toLowerCase(),
					});
				}
			}
		}

		/**
		 * Throttled scroll depth tracking
		 */
		function setupScrollTracking() {
			let maxScroll = 0;
			const scrollMilestones = [25, 50, 75, 90, 100];
			const scrolledMilestones = new Set();
			let scrollTimeout;

			window.addEventListener('scroll', function() {
				// Throttle to max once per second
				if (scrollTimeout) return;

				scrollTimeout = setTimeout(function() {
					scrollTimeout = null;

					const scrollPercent = Math.round(
						(window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100
					);

					if (scrollPercent > maxScroll) {
						maxScroll = scrollPercent;

						scrollMilestones.forEach(function(milestone) {
							if (scrollPercent >= milestone && !scrolledMilestones.has(milestone)) {
								scrolledMilestones.add(milestone);
								posthog.capture('scroll depth', {
									'depth_percentage': milestone,
									'page_url': window.location.pathname,
								});
							}
						});
					}
				}, 1000);
			}, { passive: true });
		}

		/**
		 * Time on page tracking
		 */
		function setupTimeTracking() {
			const startTime = Date.now();

			window.addEventListener('beforeunload', function() {
				const timeOnPage = Math.round((Date.now() - startTime) / 1000);

				// Only track if user stayed > 5 seconds
				if (timeOnPage > 5) {
					posthog.capture('time on page', {
						'duration_seconds': timeOnPage,
						'page_url': window.location.pathname,
					});
				}
			});
		}

		/**
		 * Helper: Get element location
		 */
		function getElementLocation(element) {
			if (element.closest('header')) return 'header';
			if (element.closest('footer')) return 'footer';
			if (element.closest('nav')) return 'navigation';
			if (element.closest('[id*="hero"]')) return 'hero';
			if (element.closest('[id*="contact"]')) return 'contact_section';
			return 'content';
		}
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'sunnysideac_output_posthog_tracking_js', 99 );

/**
 * Analytics Manager - Centralized system for multiple analytics providers
 * Prepares for FB Pixel, Google Analytics, etc.
 * All scripts load deferred and non-blocking
 */
function sunnysideac_analytics_manager() {
	?>
	<script>
	/**
	 * Analytics Manager
	 * Coordinates multiple analytics providers without blocking
	 * Usage: window.AnalyticsManager.trackEvent('conversion', {...})
	 */
	window.AnalyticsManager = {
		providers: {},
		queue: [],

		/**
		 * Register an analytics provider
		 */
		register: function(name, provider) {
			this.providers[name] = provider;
			// Process queued events
			this.queue.forEach(function(item) {
				this.trackEvent(item.event, item.data);
			}.bind(this));
			this.queue = [];
		},

		/**
		 * Track event across all providers
		 */
		trackEvent: function(eventName, data) {
			// If providers aren't loaded yet, queue the event
			if (Object.keys(this.providers).length === 0) {
				this.queue.push({ event: eventName, data: data });
				return;
			}

			// Send to all registered providers
			for (var name in this.providers) {
				if (this.providers[name] && typeof this.providers[name].track === 'function') {
					try {
						this.providers[name].track(eventName, data);
					} catch (e) {
						console.warn('Analytics provider error:', name, e);
					}
				}
			}
		}
	};

	// Register PostHog when loaded
	if (window._posthog_loaded && typeof posthog !== 'undefined') {
		window.AnalyticsManager.register('posthog', {
			track: function(event, data) {
				posthog.capture(event, data);
			}
		});
	} else {
		window.addEventListener('posthog_loaded', function() {
			window.AnalyticsManager.register('posthog', {
				track: function(event, data) {
					posthog.capture(event, data);
				}
			});
		});
	}

	// Ready for future providers:
	// - Facebook Pixel
	// - Google Analytics 4
	// - LinkedIn Insight Tag
	// - etc.
	</script>
	<?php
}
add_action( 'wp_footer', 'sunnysideac_analytics_manager', 1 );
