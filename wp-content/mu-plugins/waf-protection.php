<?php
/**
 * WordPress Application Firewall (WAF)
 *
 * @package SunnysideAC
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Block suspicious requests that match common attack patterns
 */
function waf_block_suspicious_requests() {
    $blocked = false;
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $query_string = $_SERVER['QUERY_STRING'] ?? '';

    // Block suspicious patterns
    $suspicious_patterns = [
        '/\.\.\//',                    // Directory traversal
        '/<script[^>]*>/i',            // XSS attempts
        '/union.*select/i',           // SQL injection
        '/exec.*\(/i',                // Code execution
    ];

    foreach ($suspicious_patterns as $pattern) {
        if (preg_match($pattern, $uri . $query_string)) {
            $blocked = true;
            break;
        }
    }

    // Block suspicious user agents
    $blocked_agents = ['sqlmap', 'nikto', 'nmap', 'wscan'];
    foreach ($blocked_agents as $agent) {
        if (stripos($user_agent, $agent) !== false) {
            $blocked = true;
            break;
        }
    }

    if ($blocked) {
        error_log('WAF BLOCK: Suspicious request from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        header('HTTP/1.1 403 Forbidden');
        exit;
    }
}
add_action('init', 'waf_block_suspicious_requests', 1);

/**
 * Rate limit login attempts
 */
function waf_rate_limit_login() {
    if (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $attempts = get_transient('waf_login_attempts_' . $ip) ?: [];

        // Clean old attempts (older than 1 hour)
        $current_time = time();
        $attempts = array_filter($attempts, function($timestamp) use ($current_time) {
            return ($current_time - $timestamp) < 3600;
        });

        // Block if rate limit exceeded (more than 30 attempts per hour)
        if (count($attempts) > 30) {
            header('HTTP/1.1 429 Too Many Requests');
            exit;
        }

        $attempts[] = $current_time;
        set_transient('waf_login_attempts_' . $ip, $attempts, 3600);
    }
}
add_action('login_init', 'waf_rate_limit_login');

/**
 * Block suspicious HTTP methods (but allow REST API methods)
 */
function waf_block_suspicious_methods() {
    $allowed_methods = ['GET', 'POST', 'HEAD', 'OPTIONS', 'PUT', 'PATCH', 'DELETE'];
    $method = $_SERVER['REQUEST_METHOD'] ?? '';

    if (!in_array($method, $allowed_methods)) {
        header('HTTP/1.1 405 Method Not Allowed');
        exit;
    }
}
add_action('init', 'waf_block_suspicious_methods', 1);
