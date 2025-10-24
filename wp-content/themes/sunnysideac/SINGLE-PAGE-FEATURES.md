# Single Page Features & Patterns Reference

This document outlines all the features, patterns, and best practices used in our high-quality single pages (services, cities, brands, and special pages like financing).

## Table of Contents
- [Document Structure](#document-structure)
- [SEO Features](#seo-features)
- [Schema.org Structured Data](#schemaorg-structured-data)
- [Semantic HTML & Microdata](#semantic-html--microdata)
- [Visual Design Patterns](#visual-design-patterns)
- [Component Patterns](#component-patterns)
- [Typography & Spacing](#typography--spacing)
- [Accessibility Features](#accessibility-features)
- [Code Examples](#code-examples)

---

## Document Structure

### Complete HTML Document
All single pages use complete HTML document structure (not relying solely on header.php):

```php
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- SEO Meta Tags -->
	<!-- ... -->

	<?php wp_head(); ?>

	<!-- JSON-LD Structured Data -->
	<!-- ... -->
</head>

<body <?php body_class(); ?>>

<?php get_header(); ?>

<main class="min-h-screen bg-gray-50" role="main" itemscope itemtype="https://schema.org/[TYPE]">
	<!-- Page Content -->
</main>

<?php get_footer(); ?>

</body>
</html>
```

### Container Structure
```php
<main class="min-h-screen bg-gray-50" role="main" itemscope itemtype="https://schema.org/[TYPE]">
	<div class="lg:px-0 max-w-7xl mx-auto">
		<section class="flex gap-10 flex-col">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<!-- Page sections here -->
			</article>
		</section>
	</div>
</main>
```

---

## SEO Features

### 1. Meta Tags in `<head>`
```php
// SEO Variables
$page_title    = 'Page Title - Sunnyside AC';
$meta_desc     = 'Compelling meta description under 160 characters';
$canonical_url = get_permalink(); // or home_url('/page-slug/')
$og_image      = has_post_thumbnail() ? get_the_post_thumbnail_url($post_id, 'large') : sunnysideac_asset_url('assets/images/default-og.jpg');
```

```html
<!-- SEO Meta Tags -->
<title><?php echo esc_html( $page_title ); ?></title>
<meta name="description" content="<?php echo esc_attr( $meta_desc ); ?>">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<link rel="canonical" href="<?php echo esc_url( $canonical_url ); ?>">

<!-- Open Graph Meta Tags -->
<meta property="og:locale" content="en_US">
<meta property="og:type" content="article">
<meta property="og:title" content="<?php echo esc_attr( $page_title ); ?>">
<meta property="og:description" content="<?php echo esc_attr( $meta_desc ); ?>">
<meta property="og:url" content="<?php echo esc_url( $canonical_url ); ?>">
<meta property="og:site_name" content="Sunnyside AC">
<meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo esc_attr( $page_title ); ?>">
<meta name="twitter:description" content="<?php echo esc_attr( $meta_desc ); ?>">
<meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>">
```

---

## Schema.org Structured Data

### JSON-LD Graph Structure
All pages use a `@graph` array to include multiple schema types:

```php
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@graph": [
		{
			"@type": "BreadcrumbList",
			"itemListElement": [...]
		},
		{
			"@type": "LocalBusiness",
			"name": "Sunnyside AC",
			"telephone": "<?php echo esc_js( SUNNYSIDE_PHONE_DISPLAY ); ?>",
			"address": {
				"@type": "PostalAddress",
				"streetAddress": "6609 Emerald Lake Dr",
				"addressLocality": "Miramar",
				"addressRegion": "FL",
				"postalCode": "33023",
				"addressCountry": "US"
			},
			"url": "<?php echo esc_url( home_url( '/' ) ); ?>",
			"priceRange": "$$",
			"openingHours": "Mo-Su 00:00-23:59",
			"areaServed": "Florida"
		},
		{
			"@type": "[PageSpecificSchema]",
			// ... page-specific schema
		},
		{
			"@type": "FAQPage",
			"mainEntity": [...]
		}
	]
}
</script>
```

### Common Schema Types Used

#### 1. BreadcrumbList (All Pages)
```json
{
	"@type": "BreadcrumbList",
	"itemListElement": [
		{
			"@type": "ListItem",
			"position": 1,
			"name": "Home",
			"item": "https://example.com/"
		},
		{
			"@type": "ListItem",
			"position": 2,
			"name": "Category",
			"item": "https://example.com/category/"
		},
		{
			"@type": "ListItem",
			"position": 3,
			"name": "Current Page",
			"item": "https://example.com/category/page/"
		}
	]
}
```

#### 2. Service (Service Pages)
```json
{
	"@type": "Service",
	"serviceType": "<?php echo esc_js( $service_title ); ?>",
	"provider": {
		"@type": "LocalBusiness",
		"name": "Sunnyside AC"
	}
}
```

#### 3. HowTo (Process Sections)
```php
{
	"@type": "HowTo",
	"name": "Our <?php echo esc_js( $service_title ); ?> Process",
	"step": [
		<?php foreach ( $service_process as $index => $step ) : ?>
		{
			"@type": "HowToStep",
			"position": <?php echo $index + 1; ?>,
			"name": "<?php echo esc_js( $step['title'] ); ?>",
			"text": "<?php echo esc_js( $step['description'] ); ?>"
		}<?php echo $index < count( $service_process ) - 1 ? ',' : ''; ?>
		<?php endforeach; ?>
	]
}
```

#### 4. FAQPage (FAQ Sections)
```php
{
	"@type": "FAQPage",
	"mainEntity": [
		<?php
		$faq_count = count( $faqs );
		foreach ( $faqs as $index => $faq ) :
		?>
		{
			"@type": "Question",
			"name": "<?php echo esc_js( $faq['question'] ); ?>",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "<?php echo esc_js( wp_strip_all_tags( $faq['answer'] ) ); ?>"
			}
		}<?php echo $index < $faq_count - 1 ? ',' : ''; ?>
		<?php endforeach; ?>
	]
}
```

#### 5. FinancialProduct (Financing Pages)
```json
{
	"@type": "FinancialProduct",
	"name": "HVAC Financing",
	"description": "Flexible financing options for HVAC installations",
	"provider": {
		"@type": "LocalBusiness",
		"name": "Sunnyside AC"
	},
	"featureList": [
		"Flexible Payment Plans",
		"Quick Approval Process"
	]
}
```

---

## Semantic HTML & Microdata

### Breadcrumb Navigation with Microdata
```php
<nav aria-label="Breadcrumb" class="mb-6 flex justify-center" itemscope itemtype="https://schema.org/BreadcrumbList">
	<ol class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
		<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
			<a itemprop="item" href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-orange-500">
				<span itemprop="name">Home</span>
			</a>
			<meta itemprop="position" content="1">
		</li>
		<li class="text-gray-400">/</li>
		<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
			<a itemprop="item" href="<?php echo esc_url( home_url( '/category/' ) ); ?>" class="hover:text-orange-500">
				<span itemprop="name">Category</span>
			</a>
			<meta itemprop="position" content="2">
		</li>
		<li class="text-gray-400">/</li>
		<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
			<span itemprop="name" class="font-semibold text-orange-500">Current Page</span>
			<meta itemprop="position" content="3">
		</li>
	</ol>
</nav>
```

### Semantic Section Structure
```php
<section class="section-name bg-white rounded-[20px] p-6 md:p-10 mb-6"
         aria-labelledby="section-heading"
         itemscope
         itemtype="https://schema.org/[TYPE]">
	<header class="text-center mb-8">
		<h2 id="section-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4" itemprop="name">
			Section Title
		</h2>
		<p class="text-lg text-gray-600">
			Section subtitle
		</p>
	</header>

	<!-- Section content -->
</section>
```

### Image with Microdata
```php
<?php if ( has_post_thumbnail() ) : ?>
	<figure class="mt-8" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
		<?php
		the_post_thumbnail(
			'large',
			array(
				'class'    => 'w-full h-auto rounded-2xl shadow-lg',
				'itemprop' => 'url',
				'alt'      => esc_attr( $title . ' services in South Florida' ),
			)
		);
		?>
		<meta itemprop="width" content="1200">
		<meta itemprop="height" content="630">
	</figure>
<?php endif; ?>
```

---

## Visual Design Patterns

### 1. Gradient Text Effect
```html
<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">
	<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
		Your Title Here
	</span>
</h1>
```

### 2. Number Badges
**Small Badge (Benefits/Features)**
```html
<div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-orange-200 to-orange-300 mb-4">
	<span class="text-xl font-bold text-orange-500">
		<?php echo $index + 1; ?>
	</span>
</div>
```

**Large Badge (Process Steps)**
```html
<div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-orange-200 to-orange-300">
	<span class="text-3xl font-bold text-orange-500">
		<?php echo $index + 1; ?>
	</span>
</div>
```

### 3. Hover Cards
```html
<article class="group bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:scale-105 hover:bg-orange-50 hover:shadow-lg">
	<!-- Card content -->
</article>
```

### 4. Icon Circles
```html
<!-- Icon Circle with SVG -->
<div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-orange-200 to-orange-300">
	<svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
		<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="[SVG_PATH]" />
	</svg>
</div>
```

### 5. CTA Button Styles

**Primary (Orange Gradient)**
```html
<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
	class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none">
	<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
		Get a Free Quote
	</span>
</a>
```

**Secondary (Blue Gradient)**
```html
<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
	class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
	aria-label="Call to schedule service - <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
	<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
		Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
	</span>
</a>
```

**White (for colored backgrounds)**
```html
<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
	class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-white px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
	<span class="text-lg font-bold text-orange-500">
		Request a Quote
	</span>
</a>
```

### 6. Background Patterns

**White Card**
```html
<section class="bg-white rounded-[20px] p-6 md:p-10 mb-6">
```

**Gray Background Card**
```html
<section class="bg-gray-50 rounded-[20px] p-6 md:p-10 mb-6">
```

**Gradient Background Card**
```html
<section class="bg-gradient-to-br from-blue-50 to-orange-50 rounded-[20px] p-6 md:p-10 mb-6">
```

**Gradient CTA Section**
```html
<section class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 text-center mb-6">
```

---

## Component Patterns

### 1. Benefits/Features Grid
```php
<section class="benefits bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="benefits-heading">
	<header class="text-center mb-8">
		<h2 id="benefits-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
			Why Choose Our Service
		</h2>
		<p class="text-lg text-gray-600">
			Service You Can Trust, Comfort You Deserve
		</p>
	</header>

	<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
		<?php foreach ( $benefits as $index => $benefit ) : ?>
			<article class="group bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:scale-105 hover:bg-orange-50 hover:shadow-lg">
				<!-- Number Badge -->
				<div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-orange-200 to-orange-300 mb-4">
					<span class="text-xl font-bold text-orange-500">
						<?php echo $index + 1; ?>
					</span>
				</div>

				<!-- Benefit Text -->
				<h3 class="text-lg font-semibold text-gray-900 mb-2">
					<?php echo esc_html( $benefit['title'] ); ?>
				</h3>
				<p class="text-gray-600">
					<?php echo esc_html( $benefit['description'] ); ?>
				</p>
			</article>
		<?php endforeach; ?>
	</div>
</section>
```

### 2. Process Steps (HowTo)
```php
<section class="process bg-white rounded-[20px] p-6 md:p-10 mb-6"
         aria-labelledby="process-heading"
         itemscope
         itemtype="https://schema.org/HowTo">
	<header class="text-center mb-12">
		<h2 id="process-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4" itemprop="name">
			Our Process
		</h2>
		<p class="text-lg text-gray-600">
			Your Comfort, Our Process
		</p>
	</header>

	<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
		<?php foreach ( $process_steps as $index => $step ) : ?>
			<article class="group" itemprop="step" itemscope itemtype="https://schema.org/HowToStep">
				<meta itemprop="position" content="<?php echo $index + 1; ?>">

				<div class="bg-gray-50 rounded-2xl p-8 text-center transition-all duration-300 hover:scale-105 hover:bg-orange-50 hover:shadow-lg h-full flex flex-col items-center">
					<!-- Step Number in Circle -->
					<div class="mb-6">
						<div class="relative inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-orange-200 to-orange-300">
							<span class="text-3xl font-bold text-orange-500">
								<?php echo $index + 1; ?>
							</span>
						</div>
					</div>

					<!-- Step Content -->
					<h3 class="text-xl font-bold text-gray-900 mb-3" itemprop="name">
						<?php echo esc_html( $step['title'] ); ?>
					</h3>

					<p class="text-base text-gray-600 leading-relaxed" itemprop="text">
						<?php echo esc_html( $step['description'] ); ?>
					</p>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>
```

### 3. FAQ Component Integration
```php
<?php
// Transform FAQ data to match component format
$formatted_faqs = array_map(
	function ( $faq ) {
		return array(
			'question' => $faq['question'],
			'answer'   => wp_strip_all_tags( $faq['answer'] ),
		);
	},
	$faqs
);

get_template_part(
	'template-parts/faq-component',
	null,
	array(
		'faq_data'     => $formatted_faqs,
		'title'        => 'Frequently Asked Questions',
		'mobile_title' => 'FAQ',
		'subheading'   => 'Got Questions? We\'ve Got Answers!',
		'description'  => 'Find answers to common questions about our services.',
		'show_schema'  => false, // Set to false if schema already added in <head>
		'section_id'   => 'page-faq-section',
	)
);
?>
```

### 4. Service/City Grid Links
```php
<section class="cities-served bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="cities-heading">
	<header class="text-center mb-8">
		<h2 id="cities-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
			<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
				Cities We Serve
			</span>
		</h2>
		<p class="text-lg text-gray-600">
			Expert services across South Florida
		</p>
	</header>

	<nav aria-label="Service areas">
		<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
			<?php foreach ( SUNNYSIDE_SERVICE_AREAS as $city ) : ?>
				<a href="<?php echo esc_url( home_url( sprintf( '/%s/%s/', sanitize_title( $city ), $service_slug ) ) ); ?>"
					class="group block bg-gray-50 rounded-2xl p-4 text-center transition-all duration-300 hover:scale-105 hover:bg-orange-50 hover:shadow-lg">
					<h3 class="font-semibold text-gray-900 group-hover:text-orange-500">
						<?php echo esc_html( $city ); ?>
					</h3>
					<p class="text-sm text-gray-600 mt-1">
						Service Available
					</p>
				</a>
			<?php endforeach; ?>
		</div>
	</nav>
</section>
```

### 5. CTA Section
```php
<section class="cta-section bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 text-center" aria-labelledby="cta-heading">
	<h2 id="cta-heading" class="text-3xl md:text-4xl font-bold text-white mb-4">
		Ready to Get Started?
	</h2>
	<p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
		Contact us today for expert service
	</p>

	<div class="flex flex-col sm:flex-row justify-center gap-4">
		<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
			class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-white px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
			<span class="text-lg font-bold text-orange-500">
				Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
			</span>
		</a>

		<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
			class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
			<span class="text-lg font-bold text-white">
				Request a Quote
			</span>
		</a>
	</div>
</section>
```

---

## Typography & Spacing

### Heading Sizes
```css
/* Main Page Title (H1) */
text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight

/* Section Titles (H2) */
text-3xl md:text-4xl font-bold

/* Subsection Titles (H3) */
text-xl font-bold

/* Card Titles */
text-lg font-semibold
```

### Paragraph Text
```css
/* Lead/Intro Text */
text-lg md:text-xl text-gray-600

/* Body Text */
text-lg text-gray-700

/* Small/Supporting Text */
text-base text-gray-600

/* Fine Print */
text-sm text-gray-600
```

### Spacing Patterns
```css
/* Section Margin Bottom */
mb-6

/* Header Margin Bottom */
mb-8 (for section headers)
mb-12 (for major section headers)

/* Content Padding */
p-6 md:p-10 (white cards)
p-8 md:p-12 (CTA sections)

/* Grid Gaps */
gap-4 (tight grids - cities/services)
gap-6 (standard grids - benefits/features)
gap-8 (loose grids - process steps)
gap-10 (section spacing)
```

---

## Accessibility Features

### ARIA Labels
```html
<!-- Section Labels -->
<section aria-labelledby="section-heading">
	<h2 id="section-heading">Section Title</h2>
</section>

<!-- Navigation Labels -->
<nav aria-label="Breadcrumb">
<nav aria-label="Service areas">

<!-- Link Labels for Phone Numbers -->
<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
	aria-label="Call to schedule service - <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
```

### Focus States
All interactive elements include focus rings:
```css
focus:ring-2 focus:ring-[COLOR] focus:ring-offset-2 focus:outline-none
```

### Semantic HTML
- Use `<main>` for main content
- Use `<article>` for self-contained content
- Use `<section>` for thematic grouping
- Use `<header>` for introductory content
- Use `<nav>` for navigation
- Use `<aside>` for tangentially related content

---

## Code Examples

### Complete Single Service Page Structure
```php
<?php
/**
 * Template Name: Single Service
 * Template for displaying individual service posts
 * URL: /services/{service-slug}/
 */

get_header();

if ( have_posts() ) :
	the_post();

	$service_id    = get_the_ID();
	$service_title = get_the_title();

	// Get ACF fields
	$service_description = get_field( 'service_description', $service_id );
	$service_benefits    = get_field( 'service_benefits', $service_id );
	$service_process     = get_field( 'service_process', $service_id );
	$service_faqs        = get_field( 'service_faqs', $service_id );

	// SEO Variables
	$page_title    = $service_title . ' - Sunnyside AC';
	$meta_desc     = $service_description ? wp_trim_words( $service_description, 20 ) : 'Expert ' . strtolower( $service_title ) . ' services in South Florida.';
	$canonical_url = get_permalink();
	$og_image      = has_post_thumbnail() ? get_the_post_thumbnail_url( $service_id, 'large' ) : get_template_directory_uri() . '/assets/images/default-og.jpg';
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- SEO Meta Tags -->
	<title><?php echo esc_html( $page_title ); ?></title>
	<meta name="description" content="<?php echo esc_attr( $meta_desc ); ?>">
	<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
	<link rel="canonical" href="<?php echo esc_url( $canonical_url ); ?>">

	<!-- Open Graph Meta Tags -->
	<meta property="og:locale" content="en_US">
	<meta property="og:type" content="article">
	<meta property="og:title" content="<?php echo esc_attr( $page_title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( $meta_desc ); ?>">
	<meta property="og:url" content="<?php echo esc_url( $canonical_url ); ?>">
	<meta property="og:site_name" content="Sunnyside AC">
	<meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">

	<!-- Twitter Card Meta Tags -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo esc_attr( $page_title ); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr( $meta_desc ); ?>">
	<meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>">

	<?php wp_head(); ?>

	<!-- JSON-LD Structured Data -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@graph": [
			{
				"@type": "BreadcrumbList",
				"itemListElement": [
					{
						"@type": "ListItem",
						"position": 1,
						"name": "Home",
						"item": "<?php echo esc_url( home_url( '/' ) ); ?>"
					},
					{
						"@type": "ListItem",
						"position": 2,
						"name": "Services",
						"item": "<?php echo esc_url( home_url( '/services/' ) ); ?>"
					},
					{
						"@type": "ListItem",
						"position": 3,
						"name": "<?php echo esc_js( $service_title ); ?>",
						"item": "<?php echo esc_url( $canonical_url ); ?>"
					}
				]
			},
			{
				"@type": "LocalBusiness",
				"name": "Sunnyside AC",
				"telephone": "<?php echo esc_js( SUNNYSIDE_PHONE_DISPLAY ); ?>",
				"address": {
					"@type": "PostalAddress",
					"streetAddress": "6609 Emerald Lake Dr",
					"addressLocality": "Miramar",
					"addressRegion": "FL",
					"postalCode": "33023",
					"addressCountry": "US"
				},
				"url": "<?php echo esc_url( home_url( '/' ) ); ?>",
				"priceRange": "$$",
				"openingHours": "Mo-Su 00:00-23:59",
				"areaServed": "Florida"
			},
			{
				"@type": "Service",
				"serviceType": "<?php echo esc_js( $service_title ); ?>",
				"provider": {
					"@type": "LocalBusiness",
					"name": "Sunnyside AC"
				}
			}
			<?php if ( $service_process ) : ?>
			,
			{
				"@type": "HowTo",
				"name": "Our <?php echo esc_js( $service_title ); ?> Process",
				"step": [
					<?php foreach ( $service_process as $index => $step ) : ?>
					{
						"@type": "HowToStep",
						"position": <?php echo $index + 1; ?>,
						"name": "<?php echo esc_js( $step['title'] ); ?>",
						"text": "<?php echo esc_js( $step['description'] ); ?>"
					}<?php echo $index < count( $service_process ) - 1 ? ',' : ''; ?>
					<?php endforeach; ?>
				]
			}
			<?php endif; ?>
			<?php if ( $service_faqs ) : ?>
			,
			{
				"@type": "FAQPage",
				"mainEntity": [
					<?php foreach ( $service_faqs as $index => $faq ) : ?>
					{
						"@type": "Question",
						"name": "<?php echo esc_js( $faq['question'] ); ?>",
						"acceptedAnswer": {
							"@type": "Answer",
							"text": "<?php echo esc_js( wp_strip_all_tags( $faq['answer'] ) ); ?>"
						}
					}<?php echo $index < count( $service_faqs ) - 1 ? ',' : ''; ?>
					<?php endforeach; ?>
				]
			}
			<?php endif; ?>
		]
	}
	</script>
</head>

<body <?php body_class(); ?>>

<?php get_header(); ?>

<main class="min-h-screen bg-gray-50" role="main" itemscope itemtype="https://schema.org/Service">

	<!-- Container matching front-page style -->
	<div class="lg:px-0 max-w-7xl mx-auto">
		<section class="flex gap-10 flex-col">

			<!-- Hero Section with Service Title & CTA -->
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'service-page' ); ?>>

				<!-- Page Header - Breadcrumbs & Title -->
				<header class="entry-header bg-white rounded-[20px] p-6 md:p-10 mb-6">
					<!-- Breadcrumbs -->
					<nav aria-label="Breadcrumb" class="mb-6 flex justify-center" itemscope itemtype="https://schema.org/BreadcrumbList">
						<ol class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
							<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
								<a itemprop="item" href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-orange-500">
									<span itemprop="name">Home</span>
								</a>
								<meta itemprop="position" content="1">
							</li>
							<li class="text-gray-400">/</li>
							<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
								<a itemprop="item" href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="hover:text-orange-500">
									<span itemprop="name">Services</span>
								</a>
								<meta itemprop="position" content="2">
							</li>
							<li class="text-gray-400">/</li>
							<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
								<span itemprop="name" class="font-semibold text-orange-500"><?php echo esc_html( $service_title ); ?></span>
								<meta itemprop="position" content="3">
							</li>
						</ol>
					</nav>

					<!-- Main Title with Gradient -->
					<div class="text-center mb-8">
						<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4" itemprop="name">
							<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
								<?php echo esc_html( $service_title ); ?>
							</span>
						</h1>

						<p class="text-lg md:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
							Expert <?php echo esc_html( strtolower( $service_title ) ); ?> services throughout South Florida
						</p>
					</div>

					<!-- CTA Buttons -->
					<div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
						<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
							class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none"
							aria-label="Call to schedule service - <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
							<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
								Schedule Service Now
							</span>
						</a>

						<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
							class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none">
							<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
								Get a Free Quote
							</span>
						</a>
					</div>
				</header>

				<!-- Additional sections here: Description, Benefits, Process, FAQs, Cities, CTA -->

			</article>

		</section>
	</div>

</main>

<?php get_footer(); ?>

</body>
</html>

<?php else : ?>

	<!-- 404 if no post found -->
	<div class="lg:px-0 max-w-7xl mx-auto">
		<section class="flex gap-10 flex-col">
			<div class="bg-white rounded-[20px] p-10 text-center">
				<h1 class="text-4xl font-bold text-gray-900 mb-4">Service Not Found</h1>
				<p class="text-lg text-gray-600 mb-8">The service you're looking for doesn't exist or has been removed.</p>
				<a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-8 py-4 text-white font-medium hover:opacity-90">
					View All Services
				</a>
			</div>
		</section>
	</div>

<?php endif; ?>
```

---

## Best Practices Checklist

When creating a new single page, ensure:

- [ ] Complete HTML document structure (DOCTYPE, html, head, body)
- [ ] All SEO meta tags in `<head>` section
- [ ] Comprehensive JSON-LD structured data in `@graph` format
- [ ] Proper breadcrumb navigation with Schema.org microdata
- [ ] Gradient text effect on main title
- [ ] Number badges with gradient backgrounds
- [ ] Hover effects on all interactive cards
- [ ] Semantic HTML5 tags (main, article, section, header, nav)
- [ ] ARIA labels for accessibility
- [ ] Focus states on all interactive elements
- [ ] Responsive design with mobile-first approach
- [ ] Consistent spacing (mb-6 for sections)
- [ ] FAQ component integration (if applicable)
- [ ] CTA buttons with proper gradients and hover states
- [ ] Proper use of constants (SUNNYSIDE_PHONE_DISPLAY, etc.)
- [ ] 404 fallback for post not found state

---

## Color Palette Reference

### Gradients
```css
/* Primary Orange Gradient */
from-[#fb9939] to-[#e5462f]

/* Blue Gradient */
from-[#7fcbf2] to-[#594bf7]

/* Orange Badge Gradient */
from-orange-200 to-orange-300

/* Background Gradients */
from-blue-50 to-orange-50
from-orange-50 to-orange-100
```

### Solid Colors
```css
/* Text Colors */
text-gray-900  /* Headings */
text-gray-700  /* Body text */
text-gray-600  /* Supporting text */
text-orange-500 /* Accent text/icons */

/* Background Colors */
bg-white       /* Cards */
bg-gray-50     /* Alternating sections */
bg-orange-50   /* Hover states */
```

---

## File Locations

- Single Service Template: `single-service.php`
- Single City Template: `single-city.php`
- Single Brand Template: `single-brand.php`
- Financing Page: `page-financing.php`
- FAQ Component: `template-parts/faq-component.php`
- Page Header Component: `template-parts/page-header.php`

---

## Notes

- Always use `esc_html()`, `esc_attr()`, `esc_url()`, and `esc_js()` for proper escaping
- Use `wp_strip_all_tags()` for FAQ answers in JSON-LD
- Test all pages in Google's Rich Results Test
- Validate HTML with W3C validator
- Check accessibility with WAVE or axe DevTools
- Test responsive design on mobile, tablet, and desktop
- Ensure all images have proper alt text
- Keep Schema.org structured data up to date

---

**Last Updated:** January 2025
**Maintained By:** Sunnyside AC Development Team
