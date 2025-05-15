<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Get the full URL for an image
     *
     * @param string|null $path
     * @param string $default
     * @return string
     */
    public static function url($path = null, $default = '/images/default.png')
    {
        if (empty($path)) {
            return asset($default);
        }

        // Check if the path already starts with http or https
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Check if the path starts with /storage
        if (str_starts_with($path, '/storage')) {
            return asset($path);
        }

        // Otherwise, assume it's a storage path
        return asset('storage/' . ltrim($path, '/'));
    }
}
