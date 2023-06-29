<?php

namespace imageConvert;

require_once plugin_dir_path(__FILE__) . '/settings.php';

function webp_converter_convert_to_webp($upload)
{
    $file_path = $upload['file'];
    $image = imagecreatefromstring(file_get_contents($file_path));
    $uploadsBoth = get_option('enable_both');

    if ($image !== false) {
        $webp_file_path = preg_replace('/\.(png|jpg|jpeg|tiff)$/', '.webp', $file_path);
        if (imagewebp($image, $webp_file_path)) {
            $upload['file'] = $webp_file_path;
            $upload['type'] = 'image/webp';
        } else {
            error_log('Failed to convert image to WebP: ' . $file_path);
        }
    } else {
        error_log('Failed to create image from file: ' . $file_path);
    }

    return $upload;

}
