#!/usr/bin/env node

/**
 * Critical CSS Extraction Script
 *
 * This script extracts critical CSS for above-the-fold content
 * to improve rendering performance by inlining critical styles
 * and deferring non-critical CSS loading.
 */

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Configuration
const config = {
  distDir: path.join(__dirname, '../dist'),
  cssFile: 'main.css', // Main CSS file to process
  criticalThreshold: 1024 * 50, // 50KB max for critical CSS
  excludeSelectors: [
    // Selectors that are not critical for above-the-fold content
    '.footer-',
    '.modal-',
    '.dropdown-',
    '.sidebar-',
    '.offcanvas-',
    '.carousel-',
    '.gallery-',
    '.blog-',
    '.comments-',
    '.form-',
    '.contact-',
    '.careers-',
    '.customer-portal-',
    '[data-lazy]',
    '.lazy-'
  ]
};

/**
 * Extract critical CSS from full stylesheet
 * @param {string} css - Full CSS content
 * @returns {string} Critical CSS
 */
function extractCriticalCSS(css) {
  const lines = css.split('\n');
  const criticalLines = [];
  let inMediaQuery = false;
  let braceCount = 0;
  let currentRule = '';
  let skipRule = false;

  for (const line of lines) {
    const trimmedLine = line.trim();

    // Skip comments
    if (trimmedLine.startsWith('/*') || trimmedLine.startsWith('//')) {
      continue;
    }

    // Track media queries (always include critical media queries)
    if (trimmedLine.startsWith('@media') &&
        (trimmedLine.includes('max-width') || trimmedLine.includes('min-width'))) {
      inMediaQuery = true;
      criticalLines.push(line);
      continue;
    }

    if (inMediaQuery) {
      criticalLines.push(line);
      if (trimmedLine === '}') {
        inMediaQuery = false;
      }
      continue;
    }

    // Track braces for nested rules
    braceCount += (trimmedLine.match(/\{/g) || []).length;
    braceCount -= (trimmedLine.match(/\}/g) || []).length;

    // Build current rule
    currentRule += line + '\n';

    // Check if selector should be excluded
    if (trimmedLine.includes('{') && braceCount === 1) {
      const selector = trimmedLine.split('{')[0].trim();
      skipRule = config.excludeSelectors.some(exclude => selector.includes(exclude));

      // Include essential selectors
      const essentialSelectors = [
        'html', 'body', ':root',
        '.hero-', '.header-', '.nav-', '.logo-',
        'h1', 'h2', 'h3', '.title-',
        '.btn-', 'button', '.button-',
        '.loading-', '.skeleton-',
        '.bg-', '.text-', '.font-',
        '.container', '.wrapper', '.section-',
        '.grid', '.flex', '.block',
        '.hidden', '.visible',
        'img', 'picture', '.image-'
      ];

      const isEssential = essentialSelectors.some(essential => selector.includes(essential));

      if (!skipRule || isEssential) {
        criticalLines.push(line);
      }
    } else if (!skipRule && braceCount > 0) {
      criticalLines.push(line);
    }

    // Reset when rule is complete
    if (braceCount === 0 && currentRule.includes('}')) {
      currentRule = '';
      skipRule = false;
    }
  }

  return criticalLines.join('\n');
}

/**
 * Optimize critical CSS
 * @param {string} css - Critical CSS to optimize
 * @returns {string} Optimized critical CSS
 */
function optimizeCriticalCSS(css) {
  return css
    // Remove extra whitespace
    .replace(/\s+/g, ' ')
    // Remove unnecessary semicolons
    .replace(/;}/g, '}')
    // Remove empty rules
    .replace(/[^{}]+\{\}/g, '')
    // Optimize zeros
    .replace(/0px/g, '0')
    .replace(/0em/g, '0')
    .replace(/0rem/g, '0')
    // Trim
    .trim();
}

/**
 * Generate preloading CSS for non-critical styles
 * @param {string} cssPath - Path to CSS file
 * @returns {string} Preloading HTML
 */
function generatePreloadHTML(cssPath) {
  return `<link rel="preload" href="${cssPath}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="${cssPath}"></noscript>`;
}

/**
 * Main function
 */
async function main() {
  try {
    console.log('🚀 Extracting critical CSS...');

    // Ensure dist directory exists
    if (!fs.existsSync(config.distDir)) {
      console.log('❌ Dist directory not found. Run `npm run build` first.');
      process.exit(1);
    }

    // Find main CSS file (check both root and assets/css directories)
    let cssFiles = [];

    try {
      cssFiles = fs.readdirSync(config.distDir)
        .filter(file => file.includes('.css') && !file.includes('.map'))
        .filter(file => file.includes('main') || file === config.cssFile);
    } catch (err) {
      // Try assets/css directory
      try {
        cssFiles = fs.readdirSync(path.join(config.distDir, 'assets', 'css'))
          .filter(file => file.includes('.css') && !file.includes('.map'));
      } catch (err2) {
        // Try assets directory
        try {
          const allFiles = fs.readdirSync(path.join(config.distDir, 'assets'))
            .filter(file => file.includes('.css') && !file.includes('.map'));
          cssFiles = allFiles;
        } catch (err3) {
          cssFiles = [];
        }
      }
    }

    if (cssFiles.length === 0) {
      console.log('❌ No CSS files found in dist directory.');
      process.exit(1);
    }

      // Determine the full path to the CSS file
    let cssFilePath;
    if (fs.existsSync(path.join(config.distDir, cssFiles[0]))) {
      cssFilePath = path.join(config.distDir, cssFiles[0]);
    } else if (fs.existsSync(path.join(config.distDir, 'assets', cssFiles[0]))) {
      cssFilePath = path.join(config.distDir, 'assets', cssFiles[0]);
    } else {
      cssFilePath = path.join(config.distDir, 'assets', 'css', cssFiles[0]);
    }
    console.log(`📄 Processing CSS file: ${cssFiles[0]}`);

    // Read CSS file
    const fullCSS = fs.readFileSync(cssFilePath, 'utf8');
    console.log(`📏 Original CSS size: ${(fullCSS.length / 1024).toFixed(2)}KB`);

    // Extract critical CSS
    const criticalCSS = extractCriticalCSS(fullCSS);
    console.log(`📏 Critical CSS size: ${(criticalCSS.length / 1024).toFixed(2)}KB`);

    // Optimize critical CSS
    const optimizedCSS = optimizeCriticalCSS(criticalCSS);
    console.log(`📏 Optimized CSS size: ${(optimizedCSS.length / 1024).toFixed(2)}KB`);

    // Check if critical CSS is too large
    if (optimizedCSS.length > config.criticalThreshold) {
      console.log(`⚠️  Critical CSS is ${(optimizedCSS.length / 1024).toFixed(2)}KB, consider reducing exclusions`);
    }

    // Write critical CSS file
    const criticalPath = path.join(config.distDir, 'critical.css');
    fs.writeFileSync(criticalPath, optimizedCSS);

    // Generate preload HTML
    const preloadHTML = generatePreloadHTML(`/wp-content/themes/sunnysideac/dist/${cssFiles[0]}`);
    const preloadPath = path.join(config.distDir, 'preload.html');
    fs.writeFileSync(preloadPath, preloadHTML);

    // Generate stats
    const stats = {
      original: fullCSS.length,
      critical: optimizedCSS.length,
      nonCritical: fullCSS.length - optimizedCSS.length,
      savings: ((fullCSS.length - optimizedCSS.length) / fullCSS.length * 100).toFixed(1),
      criticalFile: 'critical.css',
      preloadFile: 'preload.html'
    };

    const statsPath = path.join(config.distDir, 'critical-stats.json');
    fs.writeFileSync(statsPath, JSON.stringify(stats, null, 2));

    console.log('✅ Critical CSS extraction complete!');
    console.log(`📊 Stats:`);
    console.log(`   - Original: ${(stats.original / 1024).toFixed(2)}KB`);
    console.log(`   - Critical: ${(stats.critical / 1024).toFixed(2)}KB`);
    console.log(`   - Non-critical: ${(stats.nonCritical / 1024).toFixed(2)}KB`);
    console.log(`   - Savings: ${stats.savings}% deferred`);
    console.log(`📁 Files created:`);
    console.log(`   - critical.css (for inlining)`);
    console.log(`   - preload.html (for async loading)`);
    console.log(`   - critical-stats.json (for reference)`);

  } catch (error) {
    console.error('❌ Error extracting critical CSS:', error.message);
    process.exit(1);
  }
}

// Run if called directly
if (import.meta.url === `file://${process.argv[1]}`) {
  main();
}

export { extractCriticalCSS, optimizeCriticalCSS, generatePreloadHTML };