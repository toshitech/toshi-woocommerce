<?php

add_action('woocommerce_review_order_before_submit', function() {
    if (toshi_shipping_option_selected()) {
        ?>
        <button class="button alt" style="background-color: black;" onclick="window.wp_toshi_plugin.showModal(event)" name="woocommerce_checkout_toshi_select_slot" id="select_slot_button" value="Choose a time slot" data-value="Choose a time slot">Choose a time slot</button>
        <?php
    }
});

add_action('woocommerce_review_order_after_submit', function($thing) {
    // If TOSHI isn't selected, do nothing.
    if (! toshi_shipping_option_selected()) {
        return; 
    }

    ?>
    <script type="text/javascript">
        jQuery(function ($) {
            window.wp_toshi_plugin_streams.canProceed$.subscribe((created) => {
                var placeOrderButton = jQuery('input[name=woocommerce_checkout_place_order]');
                var blockingEvent = null;
                var clickPlaceOrderEventName = 'click.disablePlaceOrderProgression';

                if (! created) {
                    placeOrderButton.attr('disabled', 'disabled');
                    placeOrderButton.attr('title', 'Please select a delivery slot before continuing');
                    placeOrderButton.attr('data-toshi-tooltip', true);
                    
                    var event = placeOrderButton.bind(clickPlaceOrderEventName, function (e) {
                        e.preventDefault();
                    });

                    // Reload TOSHI tooltips
                    $('*[data-toshi-tooltip=true]').toshiTooltip();

                    return;
                }

                // Shadow order has been created. Remove restrictions on button.
                placeOrderButton.removeAttr('disabled');
                placeOrderButton.removeAttr('title');
                placeOrderButton.unbind(clickPlaceOrderEventName);
            });
        });
    </script>
    <?php
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

add_filter('woocommerce_order_button_html', function ($place_order_button) {
    // if (toshi_shipping_option_selected()) {
    //     return preg_replace(
    //         "/name=\"woocommerce_checkout_place_order\"/", 
    //         "name=\"woocommerce_checkout_place_order\" data-toshi-tooltip=\"true\" title=\"Please select a delivery slot before continuing\"",
    //         $place_order_button
    //     );
    // }

    return $place_order_button;
});
