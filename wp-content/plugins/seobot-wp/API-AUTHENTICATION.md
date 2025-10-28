# SEObot API Authentication Guide

This document explains the authentication mechanism available in the SEObot API and provides code examples.

## Overview

The SEObot API supports Basic Authentication where your API key is used as the username:

- **Basic Authentication** - Provide your API key as the username and any value as the password
- **X-SEObot-Auth Header** - Alternative header for environments where Authorization header is stripped

All API endpoints require authentication. Unauthenticated requests will receive a 401 Unauthorized response.

## Configuring Your API Key

Before using the API, ensure you have configured an API key in your WordPress dashboard:

1. Go to **Settings > SEObot API**
2. Set your API key or generate a new one
3. (Optional) Configure a default author for API-created content

## Authentication Method: Basic Auth

This method uses HTTP Basic Authentication with your API key as the username and any value (or empty) as the password.

**Example with cURL:**

```bash
curl -X GET "https://your-site.com/wp-json/seobot/v1/post" --user "your-api-key:"
```

**Example with JavaScript:**

```javascript
const headers = new Headers();
headers.set('Authorization', 'Basic ' + btoa('your-api-key:'));

const response = await fetch('https://your-site.com/wp-json/seobot/v1/post', {
  headers: headers
});
const data = await response.json();
console.log(data);
```

**Example with PHP:**

```php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://your-site.com/wp-json/seobot/v1/post");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "your-api-key:");

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
print_r($data);
```

## Alternative Authentication Header

Some server configurations (e.g., certain Apache setups, CDNs, or proxy servers) may strip the `Authorization` header. In such cases, you can use the `X-SEObot-Auth` header as a fallback.

**Using X-SEObot-Auth header with cURL:**

```bash
curl -X GET "https://your-site.com/wp-json/seobot/v1/post" \
  -H "X-SEObot-Auth: Basic $(echo -n 'your-api-key:' | base64)"
```

**Using X-SEObot-Auth header with JavaScript:**

```javascript
const headers = new Headers();
headers.set('X-SEObot-Auth', 'Basic ' + btoa('your-api-key:'));

const response = await fetch('https://your-site.com/wp-json/seobot/v1/post', {
  headers: headers
});
const data = await response.json();
console.log(data);
```

**Using both headers for maximum compatibility:**

```javascript
const encodedAuth = 'Basic ' + btoa('your-api-key:');
const headers = new Headers();
headers.set('Authorization', encodedAuth);
headers.set('X-SEObot-Auth', encodedAuth); // Fallback header

const response = await fetch('https://your-site.com/wp-json/seobot/v1/post', {
  headers: headers
});
```

## Security Best Practices

1. **Always use HTTPS** in production environments to prevent API key interception.
2. **Regularly rotate your API keys**, especially if you suspect they may have been compromised.
3. **Use a strong, random API key** - we recommend at least 32 characters.
4. **Implement IP whitelisting** at the server level if your API is only accessed from specific locations.

## Troubleshooting Authentication Issues

If you encounter authentication problems:

1. **Verify your API key** is correctly configured in the WordPress dashboard.
2. **Check for typos** in your API key.
3. **Confirm the request URL** includes the correct endpoint path.
4. **Examine server logs** for more detailed error information.

## WordPress Core API vs. SEObot API

The SEObot API offers a simplified authentication mechanism compared to WordPress core:

- **WordPress Core REST API** requires cookie authentication, application passwords, or OAuth.
- **SEObot API** uses a simple API key approach via Basic Auth for easier integration.

## Need Help?

If you continue to experience authentication issues, please:

1. Enable debugging in your WordPress installation.
2. Check the PHP error logs for more detailed information.
3. Contact our support team at support@seobot-api.com. 