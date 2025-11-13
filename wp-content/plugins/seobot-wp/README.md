# SEObot API Plugin for WordPress

A custom WordPress plugin that provides API endpoints for managing blog posts, categories, and media, even when the standard WordPress REST API is disabled or restricted.

## Features

- Custom API endpoints similar to the WordPress REST API
- Works even when the standard WordPress REST API is disabled or blocked
- Simple authentication using Basic Auth with API key as username
- Default author and category selection for new posts
- SEO metadata integration for popular plugins like Yoast SEO, RankMath, and All in One SEO
- Optional support for unfiltered HTML content (including script tags) with security controls

## API Endpoints

The plugin provides the following API endpoints, all under the `/seobot/v1/` namespace:

1. `GET /seobot/v1/categories` - List all categories with their IDs, names, and slugs
2. `GET /seobot/v1/{post_type}?status={status}&slug={slug}` - Get posts by post type, status, and slug
3. `GET /seobot/v1/{post_type}?status={status}&per_page=100&_fields=id,slug,status,title` - List posts with specific fields
4. `DELETE /seobot/v1/{post_type}/{post_id}` - Delete a post
5. `PUT /seobot/v1/{post_type}/{post_id}` - Update a post
6. `POST /seobot/v1/{post_type}` - Create a new post
7. `POST /seobot/v1/media` - Upload media files (dedicated endpoint)
8. `GET /seobot/v1/media` - Get media items (alias for /seobot/v1/attachment)

## Authentication

The SEObot API uses Basic Authentication with your API key as the username:

```
Authorization: Basic base64(your-api-key:)
```

See the [API-AUTHENTICATION.md](API-AUTHENTICATION.md) file for detailed examples and best practices.

## Installation

1. Upload the `seobot` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to `Settings > SEObot` to configure the plugin and get your API key

## Configuration

In the plugin settings, you can:

1. View and regenerate your API key
2. See the full API base URL
3. Select a default category for new posts
4. Select a default author for new posts

### Unfiltered HTML Support

The SEObot API allows unfiltered HTML content including script tags by default. This means you can include any HTML content in your posts via the API without WordPress stripping it.

**Example with script tags:**
```bash
curl -X POST "https://your-site.com/wp-json/seobot/v1/posts" \
  --user "your-api-key:" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Post with Script",
    "content": "<p>Some content</p><script>console.log(\"Hello World\");</script>",
    "status": "publish"
  }'
```

⚠️ **Security Note**: This API allows unfiltered HTML including script tags. Ensure your API requests are from trusted sources only.

## API Examples

### Get Categories
```bash
curl -X GET "https://your-site.com/wp-json/seobot/v1/categories" --user "your-api-key:"
```

### Get Posts
```bash
curl -X GET "https://your-site.com/wp-json/seobot/v1/posts?status=publish&per_page=10" --user "your-api-key:"
```

### Create a Post
```bash
curl -X POST "https://your-site.com/wp-json/seobot/v1/posts" \
  --user "your-api-key:" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "My New Post",
    "content": "This is the content of my new post.",
    "status": "publish",
    "categories": [1, 2],
    "seo_data": {
      "title": "SEO Title",
      "description": "SEO Description",
      "focus_keyword": "keyword"
    }
  }'
```

### Upload Media
```bash
curl -X POST "https://your-site.com/wp-json/seobot/v1/media" \
  --user "your-api-key:" \
  -F "file=@path/to/image.jpg" \
  -F "title=My Image Title"
```

## Testing

The plugin includes test scripts to verify functionality:

1. `comprehensive-test.sh` - Tests all endpoints with Basic Auth authentication
2. `test-media.sh` - Specifically tests media upload functionality
3. `test-wp-endpoints.sh` - Compares SEObot API with WordPress core REST API
4. `test-script-tags.php` - Tests script tag preservation functionality

## Support and Contributions

For support or contributions, please contact us or open an issue on GitHub.