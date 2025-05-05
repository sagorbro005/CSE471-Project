<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PrescriptionController extends Controller
{
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
                    $imagePath = $file->store('prescriptions', 'public');

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
                        Storage::disk('public')->delete($prescription->image_path);
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
                'error' => 'Failed to upload prescription. Please try again.'
            ]);
        }
    }

    public function getUserPrescriptions()
    {
        $prescriptions = auth()->user()->prescriptions()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($prescription) {
                return [
                    'id' => $prescription->id,
                    'image_url' => $prescription->image_url,
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