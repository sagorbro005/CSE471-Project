<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

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
            Log::debug('ImageHelper: Empty path, using default', ['default' => $default]);
            return asset($default);
        }

        // Check for Cloudinary URLs (critical for deployed version)
        if (strpos($path, 'cloudinary.com') !== false) {
            Log::debug('ImageHelper: Detected Cloudinary URL', ['path' => $path]);
            return $path; // Return Cloudinary URL as is
        }

        // Check if the path already starts with http or https
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            Log::debug('ImageHelper: Detected external URL', ['path' => $path]);
            return $path;
        }
        
        // Special handling for static images in /public/images directory
        if (str_starts_with($path, '/images/')) {
            Log::debug('ImageHelper: Detected public images path', ['path' => $path]);
            return asset($path);
        }
        
        // Check if the path starts with /storage
        if (str_starts_with($path, '/storage')) {
            Log::debug('ImageHelper: Detected storage path with leading slash', ['path' => $path]);
            return asset($path);
        }
        
        // Check if it might be a relative path to images directory
        if (str_starts_with($path, 'images/')) {
            Log::debug('ImageHelper: Detected relative images path', ['path' => $path]);
            return asset('/' . $path);
        }

        // Otherwise, assume it's a storage path
        Log::debug('ImageHelper: Assuming storage path', ['path' => $path, 'result' => 'storage/' . ltrim($path, '/')]);
        return asset('storage/' . ltrim($path, '/'));
    }
}
