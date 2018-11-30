<?php
/**
 * Adds "Choose time slot" buttons to before submit and after shipping rate.
 */

add_action('woocommerce_review_order_before_submit', function() {
    if (toshi_shipping_option_selected()) {
        ?>
        <button class="button alt" style="background-color: black;" onclick="window.wp_toshi_plugin.showModal(event)" name="woocommerce_checkout_toshi_select_slot" id="select_slot_button" value="Choose a time slot" data-value="Choose a time slot">Choose a time slot</button>
        <?php
    }
});

add_action('woocommerce_after_shipping_rate', function (WC_Shipping_Rate $shipping) {
    if ($shipping->id === 'toshi' && is_checkout() && toshi_shipping_option_selected()) {
        ?>
        <br />
        <a href="/" id="js-toshi-select-delivery-button"
           onclick="window.wp_toshi_plugin.showModal(event); document.getElementById('shipping_method_0_toshi').checked = true;">Choose time slot</a>
        <?php
    }
});
