<?php

function plugin_settings_page()
{
    echo '<div class="container">';
    echo '<h1>WebP Converter Settings</h1>';
    echo '<h3>A Plugin by Bryan Vernon</h3>';
    echo '<form method="post" action="options.php">';
    settings_fields('plugin_settings');
    do_settings_sections('plugin_settings');
    submit_button('Update Settings');
    echo '</form>';
    echo '</div>';
}

function plugin_settings_init()
{
    add_settings_section(
        'plugin_section',
        'Featured Settings',
        'plugin_section_callback',
        'plugin_settings'
    );

    add_settings_field(
        'webp_enable_both',
        'Upload Original',
        'enable_both_callback',
        'plugin_settings',
        'plugin_section'
    );

    register_setting(
        'plugin_settings',
        'webp_enable_both'
    );
}

function plugin_section_callback()
{
    echo 'Configure the featured settings';
}

function enable_both_callback()
{
    $webp_enable_both = get_option('webp_enable_both');
    echo '<label><input type="checkbox" name="webp_enable_both" value="1" ' . checked(1, $webp_enable_both, false) . '> Enable the upload of Original along with the WebP Version</label>';
}

add_action('admin_menu', 'plugin_settings_add_page');
add_action('admin_init', 'plugin_settings_init');

function plugin_settings_add_page()
{
    add_options_page(
        'WebP Converter Settings',
        'WebP Converter Settings',
        'manage_options',
        'plugin-settings',
        'plugin_settings_page'
    );
}
