<?php

/**
 * Responsible for enabling or disabling button depending on whether 
 * or not the user has selected a timeslot and should be able to proceed.
 */

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
