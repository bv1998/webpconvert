<?php

namespace imageConvert;

function webp_converter_convert_to_webp($upload)
{
    $file_path = $upload['file'];
    $image = imagecreatefromstring(file_get_contents($file_path));
    $photosBoth = get_option('webp_enable_both');
    error_log(print_r($photosBoth, true));

    if ($image !== false) {
        $webp_file_path = preg_replace('/\.(png|jpg|jpeg)$/', '.webp', $file_path);
        if (imagewebp($image, $webp_file_path)) {
            $upload['file'] = $webp_file_path;
            $upload['type'] = 'image/webp';

            if ($photosBoth !== '') {
                $original_file_path = $file_path;
                $original_file_name = basename($original_file_path);
                $original_upload = array(
                    'guid' => $upload['url'],
                    'post_mime_type' => $upload['type'],
                    'post_title' => $original_file_name,
                    'post_content' => '',
                    'post_status' => 'inherit',
                );
                $original_attachment_id = wp_insert_attachment($original_upload, $original_file_path);

                $original_attachment_data = wp_generate_attachment_metadata($original_attachment_id, $original_file_path);
                wp_update_attachment_metadata($original_attachment_id, $original_attachment_data);
            }

        } else {
            error_log('Failed to convert image to WebP: ' . $file_path);
        }
    } else {
        error_log('Failed to create image from file: ' . $file_path);
    }

    return $upload;
}
