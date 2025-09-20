<?php

//Include all required files
require get_template_directory() . '/inc/index.php';

// Flush homepage cache when an order is created or completed to update order total
add_action('woocommerce_new_order', 'flush_homepage_cache');
add_action('woocommerce_order_status_completed', 'flush_homepage_cache');

function flush_homepage_cache() {
    if (function_exists('w3tc_flush_url')) {
        $url = home_url('/');
        w3tc_flush_url($url);
    } elseif (function_exists('w3tc_pgcache_flush')) {
        // Fallback: flush all page cache
        w3tc_pgcache_flush();
    }
}
