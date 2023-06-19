<?php
/**
 * Plugin Name: WebP Converter
 * Description: Converts media to WebP format upon upload.
 * Version: 1.0.0
 * Author: Bryan Vernon
 */

register_activation_hook(__FILE__, 'webp_converter_activate');

function webp_converter_activate()
{
    // Activation code goes here
}

register_deactivation_hook(__FILE__, 'webp_converter_deactivate');

function webp_converter_deactivate()
{
    // Deactivation code goes here
}

add_filter('wp_handle_upload', 'webp_converter_convert_to_webp');
add_filter('wp_generate_attachment_metadata', 'webp_converter_convert_attachments_to_webp', 10, 2);

function webp_converter_convert_to_webp($upload)
{
    $file_path = $upload['file'];
    $image = imagecreatefromstring(file_get_contents($file_path));

    if ($image !== false) {
        $webp_file_path = preg_replace('/\.(png|jpg|jpeg)$/', '.webp', $file_path);
        if (imagewebp($image, $webp_file_path)) {
            $upload['file'] = $webp_file_path;
            $upload['type'] = 'image/webp';

            // Upload the original image alongside WebP
            $original_file_path = $file_path;
            $original_file_name = basename($original_file_path);
            $original_upload = array(
                'file' => $original_file_path,
                'url' => $upload['url'],
                'type' => $upload['type'],
            );
            wp_handle_upload($original_upload, array('test_form' => false));

        } else {
            // Conversion to WebP failed
            error_log('Failed to convert image to WebP: ' . $file_path);
        }
    } else {
        // Failed to create image from file
        error_log('Failed to create image from file: ' . $file_path);
    }

    return $upload;
}

function webp_converter_convert_attachments_to_webp($metadata, $attachment_id)
{
    $file_path = get_attached_file($attachment_id);
    $image = imagecreatefromstring(file_get_contents($file_path));

    if ($image !== false) {
        $webp_file_path = preg_replace('/\.(png|jpg|jpeg)$/', '.webp', $file_path);
        if (imagewebp($image, $webp_file_path)) {
            $metadata['file'] = basename($webp_file_path);
            $metadata['sizes'] = array();

            // Upload the original image alongside WebP
            $original_file_path = $file_path;
            $original_file_name = basename($original_file_path);
            $original_upload = array(
                'file' => $original_file_path,
                'url' => wp_get_attachment_url($attachment_id),
                'type' => get_post_mime_type($attachment_id),
            );
            wp_handle_upload($original_upload, array('test_form' => false));

        } else {
            // Conversion to WebP failed
            error_log('Failed to convert image to WebP: ' . $file_path);
        }
    } else {
        // Failed to create image from file
        error_log('Failed to create image from file: ' . $file_path);
    }

    return $metadata;
}
