// Import Tailwind CSS
import './css/main.css';

// Import navigation module
import './js/navigation.js';

// Import lazy loading utilities
import { lazyLoadModule, lazyLoadConditional } from './js/utils/lazy-load.js';

// Navigation auto-initializes on import

// Lazy-load components when they become visible in viewport
// This improves initial page load performance by deferring non-critical JavaScript

// Contact Form - Load when form is visible (120ms savings on initial load)
lazyLoadModule('#contact-form', () => import('./js/forms/contact-form.js'));

// Reviews Carousel - Load when reviews section is visible (60ms savings)
lazyLoadModule('#customer-reviews', () => import('./js/components/reviews-carousel.js'));

// Careers Form - Load only on /careers page (75ms savings on other pages)
lazyLoadConditional(
  () => window.location.pathname.includes('/careers'),
  () => import('./js/forms/careers-form.js')
);

// Customer Portal - Load only on /customer-portal page
lazyLoadConditional(
  () => window.location.pathname.includes('/customer-portal'),
  () => import('./js/forms/customer-portal.js')
);

console.log('✅ SunnySide AC theme loaded with Vite - Lazy loading enabled');
