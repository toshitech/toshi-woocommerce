<?php

function toshi_get_integration_script_url() {
    if (get_option('woocommerce_toshi_settings')['sandbox'] ?? false) {
        return 'https://integration-sandbox-cdn.toshi.co/js/library.js';
    }
    return 'https://integration-cdn.toshi.co/js/library.js';
}

add_action('wp_enqueue_scripts', function() {
    wp_register_script('toshi_library', toshi_get_integration_script_url());
    wp_enqueue_script('toshi_library');

    wp_register_script('toshi_modal', plugins_url('js/toshi-modal.js', __FILE__), array('jquery'), true);
    wp_register_script('toshi_tooltip', plugins_url('js/toshi-tooltip.js', __FILE__), array('jquery'), true);

    // Required for cleaner handling and merging of DOM events.
    // See: js/toshi-modal.js
    wp_register_script('rxjs-lite', 'https://unpkg.com/rxjs/bundles/rxjs.umd.min.js');

    wp_register_style('toshi_button', plugins_url('toshi/lib/css/button.css'), __FILE__);
    
    // We only want these injected at checkout.
    if (is_checkout()) {
        wp_enqueue_script('rxjs-lite');
        wp_enqueue_script('toshi_modal');
        wp_enqueue_script('toshi_tooltip');
        wp_enqueue_style('toshi_button');
    }
    
    wp_register_style('toshi_tooltips', plugins_url('toshi/lib/css/toshi-tooltips.css'), __FILE__);
    wp_enqueue_style('toshi_tooltips');
});

add_action('admin_enqueue_scripts', function ($scripts) {
    wp_register_script('toshi_check_api_key', plugins_url('js/check-api-key.js', __FILE__), array('jquery'), true);
    
    wp_enqueue_script('toshi_check_api_key');
});
