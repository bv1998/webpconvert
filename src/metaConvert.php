<?php

namespace metaConvert;

function webp_converter_convert_attachments_to_webp($metadata, $attachment_id)
{
    $file_path = get_attached_file($attachment_id);
    $file_type = get_post_mime_type($atachment_id);
    $image = imagecreatefromstring(file_get_contents($file_path));
    $photosBoth = get_option('webp_enable_both');
    if ($image !== false && in_array($file_type, CONVT_TYPES)) {
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
