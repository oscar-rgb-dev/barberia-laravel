<?php

namespace App\Helpers;

class ImageHelper
{
    public static function getImageSrc($imagePath)
    {
        if (!$imagePath) {
            return asset('images/default-product.jpg');
        }
        
        // Si es ruta de /tmp
        if (str_starts_with($imagePath, '/tmp/')) {
            try {
                if (file_exists($imagePath)) {
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $mimeType = mime_content_type($imagePath) ?: 'image/jpeg';
                    return "data:$mimeType;base64,$imageData";
                }
            } catch (\Exception $e) {
                // Si hay error, usar imagen por defecto
            }
            return asset('images/default-product.jpg');
        }
        
        // Si ya es Base64
        if (str_starts_with($imagePath, 'data:image')) {
            return $imagePath;
        }
        
        // Si es ruta normal
        return asset($imagePath);
    }
}