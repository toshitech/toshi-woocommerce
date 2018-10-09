<?php
/**
 * Plugin Name: TOSHI
 * Plugin URI: https://www.toshi.co
 * Description: Adds TOSHI shipping option to WooCommerce.
 * Version: 1.0.0
 * Author: TOSHI
 * Author URI: https://www.toshi.co
 */

require(plugin_dir_path(__FILE__) . './lib/shipping-method.function.php');
require(plugin_dir_path(__FILE__) . './lib/script-management.php');
require(plugin_dir_path(__FILE__) . './lib/modal-template.php');
require(plugin_dir_path(__FILE__) . './lib/checkout-data.php');
require(plugin_dir_path(__FILE__) . './lib/select-slot.php');
require(plugin_dir_path(__FILE__) . './lib/settings/settings-controls.php');

if (!defined('WPINC')) {
    die;
}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    setup_toshi_shipping_method();
}
