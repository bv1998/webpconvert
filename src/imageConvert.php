<?php

namespace imageConvert;

function webp_converter_convert_to_webp($upload)
{
    
    $file_path = $upload['file'];
    // error_log($upload['type']);
    $image = imagecreatefromstring(file_get_contents($file_path));
    $photosBoth = get_option('webp_enable_both');
    if ($image !== false && in_array($upload['type'], CONVT_TYPES) ) {
        $webp_file_path = preg_replace('/\.(png|jpg|jpeg)$/', '.webp', $file_path);
        if (imagewebp($image, $webp_file_path)) {
            $upload['file'] = $webp_file_path;
            $upload['type'] = 'image/webp';

            if ($photosBoth === 1) {
                uploadOriginal($file_path);
            }

        } else {
            uploadOriginal($file_path);
        }
    } else {
            uploadOriginal($file_path);
    }

    return $upload;
}

function uploadOriginal($file_path){
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
error_log($original_attachment_id);
    $original_attachment_data = wp_generate_attachment_metadata($original_attachment_id, $original_file_path);
    wp_update_attachment_metadata($original_attachment_id, $original_attachment_data);
}