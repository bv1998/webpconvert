<?php

namespace imageConvert;

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
