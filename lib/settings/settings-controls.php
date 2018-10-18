<?php

add_action('woocommerce_settings_tabs_shipping', function ($settings) {
    if (($_GET['section'] ?? '') !== 'toshi') {
        return;
    }

    ?>
    <button name="test_api" class="button-secondary" id="js-check-api-key" type="button" value="Test Credentials">Test Credentials</button>
    <?php
});
