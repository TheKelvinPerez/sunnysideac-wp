<?php
/**
 * Static Homepage Generator using WP-CLI
 * This script uses WP-CLI to generate a static index.html from WordPress
 */

// Get the WordPress directory
$wp_dir = dirname(__FILE__) . '/..';

// Change to WordPress root
chdir($wp_dir);

// Use WP-CLI shell to execute WordPress and capture output
$command = 'wp shell --allow-root --basic <<EOF
ob_start();
define(\'WP_USE_THEMES\', true);
wp();
include_once(ABSPATH . WPINC . \'/template-loader.php\');
\$html = ob_get_contents();
ob_end_clean();
file_put_contents(\'' . $wp_dir . '/index.html.tmp\', \$html);
echo "Generated static homepage with " . strlen(\$html) . " bytes\n";
EOF';

// Execute the command
$output = shell_exec($command);

echo $output;