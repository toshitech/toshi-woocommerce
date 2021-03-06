<?php

function toshi_api_url($path = null) {
    $url = get_option('woocommerce_toshi_settings')['api_url'];

    if ($path !== null) {
        return $url . '/' . $path;
    }

    return $url;
}

function toshi_api_key() {
    return get_option('woocommerce_toshi_settings')['api_key'];
}

function toshi_choose_slot_button_class() {
    return get_option('woocommerce_toshi_settings')['choose_slot_button_class'];
}
