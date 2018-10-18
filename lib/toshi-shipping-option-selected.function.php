<?php

function toshi_shipping_option_selected() {
  global /** @var WooCommerce $woocommerce */ $woocommerce;

  $shipping_methods = $woocommerce->session->get('chosen_shipping_methods');

  return count($shipping_methods) > 0 && $shipping_methods[0] === 'toshi';
}
