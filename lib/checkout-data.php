<?php

function toshi_get_checkout_data() {
    /** @var WooCommerce $woocommerce */
    global $woocommerce;
    return array(
        'customer' => array(
            'name' => $woocommerce->customer->get_first_name(),
            'surname' => $woocommerce->customer->get_last_name(),
            'email' => $woocommerce->customer->get_email(),
            'address_line_1' => $woocommerce->customer->get_shipping_address_1(),
            'town' => $woocommerce->customer->get_shipping_city(),
            'postcode' => $woocommerce->customer->get_shipping_postcode()
        ),
        'items' => $woocommerce->cart->get_cart_contents()
    );
}

function do_something($postData) {
    echo "hi";
}