<?php

function toshi_get_integration_script_url() {
    if (get_option('woocommerce_toshi_settings')['sandbox']) {
        return 'https://integration-sandbox-cdn.toshi.co/js/library.js';
    }
    return 'https://integration-cdn.toshi.co/js/library.js';
}

function enqueue_toshi_scripts() {
    wp_register_script('toshi_library', toshi_get_integration_script_url());
    wp_enqueue_script('toshi_library');

    wp_register_script('toshi_modal', plugins_url('js/toshi-modal.js', __FILE__), array('jquery'), true);
    wp_enqueue_script('toshi_modal');
}

add_action('wp_enqueue_scripts', 'enqueue_toshi_scripts');

add_action('admin_enqueue_scripts', function ($scripts) {
    wp_register_script('toshi_check_api_key', plugins_url('js/check-api-key.js', __FILE__), array('jquery'), true);

    
    wp_enqueue_script('toshi_check_api_key');
});