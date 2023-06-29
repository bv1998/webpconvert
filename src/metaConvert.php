<?php

namespace metaConvert;

require_once plugin_dir_path(__FILE__) . '/settings.php';

function webp_converter_convert_attachments_to_webp($metadata, $attachment_id)
{
    $file_path = get_attached_file($attachment_id);
    $image = imagecreatefromstring(file_get_contents($file_path));
    $uploadsBoth = get_option('enable_both');
    if ($image !== false) {
        $webp_file_path = preg_replace('/\.(png|jpg|jpeg)$/', '.webp', $file_path);
        if (imagewebp($image, $webp_file_path)) {
            $metadata['file'] = basename($webp_file_path);
            $metadata['sizes'] = array();
        } else {
            error_log('Failed to convert image to WebP: ' . $file_path);
        }
    } else {
        error_log('Failed to create image from file: ' . $file_path);
    }

    return $metadata;
}
