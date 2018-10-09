<?php

function toshi_settings_init()
{
    register_setting('toshi', 'api_key');

    add_settings_section('toshi_section_developers', __('Developer Section', 'toshi'), function () {
        echo '<p>Developers</p>';
    }, 'toshi');

    add_settings_field(
        'wporg_settings_field',
        'WPOrg Setting',
        'wporg_settings_field_cb',
        'reading',
        'toshi_section_developers'
    );
}

function toshi_options_page()
{
    add_submenu_page('options-general.php', 'TOSHI Settings', 'TOSHI Settings', 'manage_options', 'toshi', 'toshi_options_page_html');
}

function toshi_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options-general.php?page=toshi" method="post">
            <?php
            // output security fields for the registered setting "toshi_options"
            settings_fields('toshi_options');
            // output setting sections and their fields
            // (sections are registered for "toshi", each field is registered to a specific section)
            do_settings_sections('toshi');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'toshi_options_page');