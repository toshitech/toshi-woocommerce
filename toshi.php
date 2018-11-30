<?php
/**
 * Plugin Name: TOSHI
 * Plugin URI: https://www.toshi.co
 * Description: Adds TOSHI shipping option to WooCommerce.
 * Version: 3.1.2
 * Author: TOSHI
 * Author URI: https://www.toshi.co
 */

require(plugin_dir_path(__FILE__) . './lib/shipping-method.function.php');
require(plugin_dir_path(__FILE__) . './lib/toshi-shipping-option-selected.function.php');
require(plugin_dir_path(__FILE__) . './lib/settings/settings-helpers.php');
require(plugin_dir_path(__FILE__) . './lib/script-management.php');
require(plugin_dir_path(__FILE__) . './lib/checkout-data.php');
require(plugin_dir_path(__FILE__) . './lib/select-slot.php');
require(plugin_dir_path(__FILE__) . './lib/settings/settings-controls.php');
require(plugin_dir_path(__FILE__) . './lib/checkout/cart-data-helper.php');
require(plugin_dir_path(__FILE__) . './lib/checkout/modal-data.php');
require(plugin_dir_path(__FILE__) . './lib/order-confirmation.php');
require(plugin_dir_path(__FILE__) . './lib/checkout/quote-reference.php');
require(plugin_dir_path(__FILE__) . './lib/checkout/submit-button-disabling.php');


if (!defined('WPINC')) {
    die;
}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    setup_toshi_shipping_method();
}
