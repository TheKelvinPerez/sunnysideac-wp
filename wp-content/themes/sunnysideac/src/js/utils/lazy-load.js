/**
 * Lazy Loading Utility
 * Uses IntersectionObserver to load modules only when needed
 */

/**
 * Lazy load a module when element becomes visible in viewport
 * @param {string} selector - CSS selector for the element to observe
 * @param {Function} importFn - Function that returns dynamic import promise
 * @param {Object} options - IntersectionObserver options
 * @returns {void}
 */
export function lazyLoadModule(selector, importFn, options = {}) {
	const element = document.querySelector(selector);

	if (!element) {
		console.warn(`[LazyLoad] Element not found: ${selector}`);
		return;
	}

	const defaultOptions = {
		root: null,
		rootMargin: '50px', // Start loading 50px before element is visible
		threshold: 0.01     // Trigger when 1% of element is visible
	};

	const observerOptions = { ...defaultOptions, ...options };

	const observer = new IntersectionObserver((entries) => {
		entries.forEach(async (entry) => {
			if (entry.isIntersecting) {
				try {
					console.log(`[LazyLoad] Loading module for: ${selector}`);
					const module = await importFn();

					// Call init function if it exists
					if (module && typeof module.init === 'function') {
						module.init();
						console.log(`[LazyLoad] Module initialized: ${selector}`);
					} else if (module && typeof module.default === 'function') {
						// Support default export as init function
						module.default();
						console.log(`[LazyLoad] Module (default export) initialized: ${selector}`);
					}
				} catch (error) {
					console.error(`[LazyLoad] Failed to load module for ${selector}:`, error);
				} finally {
					// Disconnect observer after loading to prevent re-triggering
					observer.disconnect();
				}
			}
		});
	}, observerOptions);

	observer.observe(element);
}

/**
 * Lazy load a module when user focuses on an element
 * Useful for form validation that should only load when user interacts
 * @param {string} selector - CSS selector for the element
 * @param {Function} importFn - Function that returns dynamic import promise
 * @returns {void}
 */
export function lazyLoadOnFocus(selector, importFn) {
	const element = document.querySelector(selector);

	if (!element) {
		console.warn(`[LazyLoad] Element not found: ${selector}`);
		return;
	}

	let loaded = false;

	const loadModule = async () => {
		if (loaded) return;
		loaded = true;

		try {
			console.log(`[LazyLoad] Loading module on focus: ${selector}`);
			const module = await importFn();

			if (module && typeof module.init === 'function') {
				module.init();
			} else if (module && typeof module.default === 'function') {
				module.default();
			}
		} catch (error) {
			console.error(`[LazyLoad] Failed to load module for ${selector}:`, error);
		}
	};

	// Load on first focus or click
	element.addEventListener('focus', loadModule, { once: true, capture: true });
	element.addEventListener('click', loadModule, { once: true, capture: true });
}

/**
 * Lazy load a module when page matches a specific condition
 * Useful for page-specific modules (e.g., only on /careers page)
 * @param {Function} conditionFn - Function that returns true if module should load
 * @param {Function} importFn - Function that returns dynamic import promise
 * @returns {void}
 */
export function lazyLoadConditional(conditionFn, importFn) {
	if (!conditionFn()) {
		return;
	}

	// Load immediately if condition is met
	(async () => {
		try {
			console.log('[LazyLoad] Loading conditional module');
			const module = await importFn();

			if (module && typeof module.init === 'function') {
				module.init();
			} else if (module && typeof module.default === 'function') {
				module.default();
			}
		} catch (error) {
			console.error('[LazyLoad] Failed to load conditional module:', error);
		}
	})();
}
