<?php

function toshi_get_checkout_data() {
    /** @var WooCommerce $woocommerce */
    global $woocommerce;

    return array(
        'customer' => array(
            'name' => $woocommerce->customer->first_name,
            'surname' => $woocommerce->customer->last_name,
            'email' => $woocommerce->customer->email,
            'address_line_1' => $woocommerce->customer->shipping_address_1,
            'town' => $woocommerce->customer->shipping_city,
            'postcode' => $woocommerce->customer->get_shipping_postcode
        ),
       'basket' => toshi_get_cart_data()
    );
}
