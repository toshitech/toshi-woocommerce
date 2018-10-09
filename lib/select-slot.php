<?php

add_action('woocommerce_review_order_before_submit', function() {
    global /** @var WooCommerce $woocommerce */ $woocommerce;

    $shipping_methods = $woocommerce->session->get('chosen_shipping_methods');

    if (count($shipping_methods) > 0 && $shipping_methods[0] === 'toshi') {
        ?>
        <button class="button alt" onclick="window.wp_toshi_plugin.showModal(event)" name="woocommerce_checkout_toshi_select_slot" id="select_slot_button" value="Choose a time slot" data-value="Choose a time slot">Choose a time slot</button>
        <?php
    }
});