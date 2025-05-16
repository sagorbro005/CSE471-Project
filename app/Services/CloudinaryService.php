<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CloudinaryService
{
    protected $cloudName;
    protected $apiKey;
    protected $apiSecret;

    public function __construct()
    {
        $this->cloudName = config('cloudinary.cloud_name');
        $this->apiKey = config('cloudinary.api_key');
        $this->apiSecret = config('cloudinary.api_secret');
        
        // Verify that Cloudinary credentials are properly loaded
        if (empty($this->cloudName) || empty($this->apiKey) || empty($this->apiSecret)) {
            Log::error('Cloudinary credentials missing', [
                'cloud_name' => empty($this->cloudName) ? 'missing' : 'present',
                'api_key' => empty($this->apiKey) ? 'missing' : 'present',
                'api_secret' => empty($this->apiSecret) ? 'missing' : 'present'
            ]);
        }
    }

    /**
     * Upload an image to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string|null URL of uploaded image or null on failure
     */
    public function uploadImage(UploadedFile $file, string $folder = 'products'): ?string
    {
        try {
            // Verify credentials are available
            if (empty($this->cloudName) || empty($this->apiKey) || empty($this->apiSecret)) {
                Log::error('Cannot upload to Cloudinary - credentials missing');
                return null;
            }
            
            // Generate a unique filename
            $publicId = $folder . '/' . Str::uuid();
            
            // Create signature
            $timestamp = time();
            $params = [
                'folder' => $folder,
                'public_id' => $publicId,
                'timestamp' => $timestamp,
            ];
            
            $signature = $this->generateSignature($params);
            
            // Prepare the form data
            $response = Http::attach(
                'file', 
                file_get_contents($file->getRealPath()), 
                $file->getClientOriginalName()
            )->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload", [
                'api_key' => $this->apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
                'folder' => $folder,
                'public_id' => $publicId,
            ]);
            
            $data = $response->json();
            
            if ($response->successful() && isset($data['secure_url'])) {
                Log::info('Successfully uploaded to Cloudinary', [
                    'url' => $data['secure_url'],
                    'public_id' => $data['public_id']
                ]);
                return $data['secure_url'];
            }
            
            Log::error('Cloudinary upload failed', [
                'error' => $data, 
                'status' => $response->status(),
                'response_body' => $response->body()
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Cloudinary upload exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
    
    /**
     * Delete an image from Cloudinary
     *
     * @param string $publicId The public ID of the image to delete
     * @return bool Whether the deletion was successful
     */
    public function deleteImage(string $publicId): bool
    {
        try {
            // Verify credentials are available
            if (empty($this->cloudName) || empty($this->apiKey) || empty($this->apiSecret)) {
                Log::error('Cannot delete from Cloudinary - credentials missing');
                return false;
            }
            
            $timestamp = time();
            $params = [
                'public_id' => $publicId,
                'timestamp' => $timestamp,
            ];
            
            $signature = $this->generateSignature($params);
            
            $response = Http::post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy", [
                'api_key' => $this->apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
                'public_id' => $publicId,
            ]);
            
            $data = $response->json();
            
            if ($response->successful() && isset($data['result']) && $data['result'] === 'ok') {
                Log::info('Successfully deleted image from Cloudinary', ['public_id' => $publicId]);
                return true;
            }
            
            Log::error('Cloudinary delete failed', [
                'error' => $data, 
                'public_id' => $publicId,
                'status' => $response->status(),
                'response_body' => $response->body()
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Cloudinary delete exception', [
                'error' => $e->getMessage(),
                'public_id' => $publicId,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
    
    /**
     * Extract the public ID from a Cloudinary URL
     *
     * @param string $url
     * @return string|null
     */
    public function extractPublicId(string $url): ?string
    {
        // Match public id from URL like: https://res.cloudinary.com/cloud-name/image/upload/v1234567890/folder/image_id.jpg
        $pattern = '/cloudinary\.com\/.*?\/(?:image|video)\/upload(?:\/[^\/]*)*\/(.+?)(?:\.[^\.]+)?$/i';
        
        if (preg_match($pattern, $url, $matches) && isset($matches[1])) {
            return $matches[1];
        }
        
        Log::warning('Failed to extract public ID from URL', ['url' => $url]);
        return null;
    }
    
    /**
     * Generate the signature for Cloudinary API
     *
     * @param array $params
     * @return string
     */
    private function generateSignature(array $params): string
    {
        // Sort params alphabetically
        ksort($params);
        
        // Build string to sign
        $stringToSign = '';
        foreach ($params as $key => $value) {
            $stringToSign .= $key . '=' . $value . '&';
        }
        
        // Remove trailing &
        $stringToSign = rtrim($stringToSign, '&');
        
        // Append API secret
        $stringToSign .= $this->apiSecret;
        
        // Generate signature
        return sha1($stringToSign);
    }
}
