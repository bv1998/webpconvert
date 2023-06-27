<?php
/**
 * Plugin Name: WebP Converter
 * Description: Converts media to WebP format upon upload.
 * Version: 1.1.0
 * Author: Bryan Vernon
 */

require_once __DIR__ . '/src/imageConvert.php';

require_once __DIR__ . '/src/metaConvert.php';

require_once __DIR__ . '/src/uploadOriginal.php';

require_once plugin_dir_path(__FILE__) . '/src/settings.php';

register_activation_hook(__FILE__, 'webp_converter_activate');

$uploadOrigin = get_option('upload_both');

function webp_converter_activate()
{
    // Activation code goes here
}

register_deactivation_hook(__FILE__, 'webp_converter_deactivate');

function webp_converter_deactivate()
{
    // Deactivation code goes here
}
// if($uploadOrigin === true){
add_filter('wp_handle_upload', 'originalPhoto\\passOriginal');
add_filter('wp_generate_attachment_metadata', 'originalPhoto\\original_metadata', 10, 2);
// }
add_filter('wp_handle_upload', 'imageConvert\\webp_converter_convert_to_webp');
add_filter('wp_generate_attachment_metadata', 'metaConvert\\webp_converter_convert_attachments_to_webp', 10, 2);
