<?php
/**
 * Additional controls not created via the WooCommerce 
 * shipping option constructor (found in ../shipping-method.function.php)
 * 
 * This particular control is used to test the credentials supplied in the form.
 * 
 * Related JS: js/check-api-key.js
 */
add_action('woocommerce_settings_tabs_shipping', function ($settings) {
    if (($_GET['section'] ?? '') !== 'toshi') {
        return;
    }

    ?>
    <button name="test_api" class="button-secondary" id="js-check-api-key" type="button" value="Test Credentials">Test Credentials</button>
    <?php
});
