<?php

namespace originalPhoto;

function passOriginal($photo)
{
    $file_path = $photo['file'];

    $image = imagecreatefromstring(file_get_contents($file_path));
    $photosBoth = get_option('enable_both');

    if ($image !== false) {
        // Handle the original photo using wp_handle_upload()
        $original_upload = array(
            'file' => $file_path,
            'url' => $photo['url'],
            'type' => $photo['type'],
        );
        wp_handle_upload($original_upload, array('test_form' => false));
    } else {
        // Failed to create image from file
        error_log('Failed to create image from file: ' . $file_path);
    }

    return $photo;
}

function original_metadata($metadata, $attachment_id)
{
    $file_path = get_attached_file($attachment_id);

    // Upload the original image
    $original_file_path = $file_path;
    $original_file_name = basename($original_file_path);
    $original_upload = array(
        'file' => $original_file_path,
        'url' => wp_get_attachment_url($attachment_id),
        'type' => get_post_mime_type($attachment_id),
    );
    wp_handle_upload($original_upload, array('test_form' => false));

    return $metadata;
}
