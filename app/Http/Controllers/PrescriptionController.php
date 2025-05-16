<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Log;

class PrescriptionController extends Controller
{
    protected $cloudinary;
    
    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }
    
    /**
     * Extract the public ID from a Cloudinary URL and delete the image
     *
     * @param string $url
     * @return void
     */
    private function deleteCloudinaryImage(string $url): void
    {
        // Use the improved extractPublicId method from CloudinaryService
        $publicId = $this->cloudinary->extractPublicId($url);
        if ($publicId) {
            $this->cloudinary->deleteImage($publicId);
        } else {
            Log::error('Failed to extract public ID from URL', ['url' => $url]);
        }
    }
    public function index()
    {
        return Inertia::render('UploadPrescription');
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'prescriptions' => 'required|array|min:1|max:5',
                'prescriptions.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'notes' => 'nullable|string|max:500'
            ]);

            $prescriptionFiles = $request->file('prescriptions');
            $prescriptions = [];

            // Create a single order for all uploaded prescriptions
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                // Add other required order fields if necessary (set default or null)
            ]);

            foreach ($prescriptionFiles as $file) {
                try {
                    // Upload to Cloudinary for permanent storage
                    $cloudinaryUrl = $this->cloudinary->uploadImage($file, 'prescriptions');
                    
                    // Set image path based on the upload result
                    if ($cloudinaryUrl) {
                        Log::info('Successfully uploaded prescription to Cloudinary', ['file' => $file->getClientOriginalName()]);
                        $imagePath = $cloudinaryUrl;
                    } else {
                        Log::warning('Failed to upload to Cloudinary, falling back to local storage', ['file' => $file->getClientOriginalName()]);
                        $imagePath = $file->store('prescriptions', 'public');
                    }

                    // Create prescription record linked to the order
                    $prescriptions[] = Prescription::create([
                        'user_id' => auth()->id(),
                        'order_id' => $order->id,
                        'image_path' => $imagePath,
                        'notes' => $request->notes,
                        'status' => 'pending'
                    ]);
                } catch (\Exception $e) {
                    // If there's an error, delete any files that were uploaded
                    foreach ($prescriptions as $prescription) {
                        if (strpos($prescription->image_path, 'cloudinary.com') !== false) {
                            // Delete from Cloudinary
                            $this->deleteCloudinaryImage($prescription->image_path);
                        } else {
                            // Delete from local storage
                            Storage::disk('public')->delete($prescription->image_path);
                        }
                        $prescription->delete();
                    }
                    $order->delete();
                    throw $e;
                }
            }

            return back()->with([
                'success' => 'Prescription uploaded successfully! One Medimart representative will call you shortly for confirming this order.'
            ]);

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Failed to upload prescription. Please try again. Details: ' . $e->getMessage()
            ]);
        }
    }

    public function getUserPrescriptions()
    {
        $prescriptions = auth()->user()->prescriptions()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($prescription) {
                $imageUrl = $prescription->image_path;
                
                // Handle both Cloudinary and local storage URLs
                if ($imageUrl && !strpos($imageUrl, 'cloudinary.com')) {
                    $imageUrl = asset('storage/' . $imageUrl);
                }
                
                return [
                    'id' => $prescription->id,
                    'image_url' => $imageUrl,
                    'status' => $prescription->status,
                    'status_label' => $prescription->status_label,
                    'notes' => $prescription->notes,
                    'created_at' => $prescription->created_at->format('Y-m-d H:i:s'),
                    'is_pending' => $prescription->isPending(),
                    'is_approved' => $prescription->isApproved(),
                    'is_rejected' => $prescription->isRejected(),
                ];
            });

        return Inertia::render('UploadPrescription', [
            'prescriptions' => $prescriptions
        ]);
    }

    public function updateStatus(Request $request, Prescription $prescription)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $prescription->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Prescription status updated successfully.');
    }
}