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
    protected $uploadPreset;

    public function __construct()
    {
        $this->cloudName = config('cloudinary.cloud_name');
        $this->apiKey = config('cloudinary.api_key');
        $this->apiSecret = config('cloudinary.api_secret');
        $this->uploadPreset = env('CLOUDINARY_UPLOAD_PRESET', 'ml_default');
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
            // Generate a unique filename
            $uniqueFileName = $folder . '/' . Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            
            // Create basic authentication header
            $timestamp = time();
            $signature = $this->generateSignature($timestamp, $folder);
            
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
                'public_id' => Str::uuid()->toString(),
            ]);
            
            $data = $response->json();
            
            if ($response->successful() && isset($data['secure_url'])) {
                return $data['secure_url'];
            }
            
            Log::error('Cloudinary upload failed', ['error' => $data]);
            return null;
        } catch (\Exception $e) {
            Log::error('Cloudinary upload exception', ['error' => $e->getMessage()]);
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
            $timestamp = time();
            $signature = $this->generateSignature($timestamp, $publicId);
            
            $response = Http::post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy", [
                'api_key' => $this->apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
                'public_id' => $publicId,
            ]);
            
            $data = $response->json();
            
            if ($response->successful() && isset($data['result']) && $data['result'] === 'ok') {
                return true;
            }
            
            Log::error('Cloudinary delete failed', ['error' => $data]);
            return false;
        } catch (\Exception $e) {
            Log::error('Cloudinary delete exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Generate the signature for Cloudinary API
     *
     * @param int $timestamp
     * @param string $folder
     * @return string
     */
    private function generateSignature(int $timestamp, string $params): string
    {
        $signatureData = "folder={$params}&timestamp={$timestamp}{$this->apiSecret}";
        return sha1($signatureData);
    }
}
