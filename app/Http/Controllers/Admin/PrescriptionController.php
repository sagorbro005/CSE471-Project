<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PrescriptionController extends Controller
{
    // List all prescriptions with search, filter, pagination
    public function index(Request $request)
    {
        $query = Prescription::with('user', 'order')
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('user', function ($uq) use ($request) {
                    $uq->where('name', 'like', '%'.$request->search.'%')
                       ->orWhere('email', 'like', '%'.$request->search.'%')
                       ->orWhere('phone', 'like', '%'.$request->search.'%');
                });
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            });

        // Only get orders that have at least one prescription (i.e., orders placed via Upload Prescription)
        $ordersQuery = \App\Models\Order::with(['user', 'prescriptions' => function($q) {
            $q->orderBy('created_at');
        }])
        ->whereHas('prescriptions') // Only orders with at least one prescription
        ;
        // Apply search filter to orders by customer name/email/phone
        if ($request->search) {
            $ordersQuery->whereHas('user', function($uq) use ($request) {
                $uq->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }
        // Filter by prescription status if needed
        if ($request->status) {
            $ordersQuery->whereHas('prescriptions', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }
        $orders = $ordersQuery->orderByDesc('created_at')->paginate(10);

        $orders = $orders->through(function($order) {
            $createdAt = $order->created_at->copy()->timezone('Asia/Dhaka');
            return [
                'id' => $order->id,
                'user' => [
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone,
                ],
                'prescriptions' => $order->prescriptions->map(function($prescription) {
                    return [
                        'id' => $prescription->id,
                        'image_url' => $prescription->image_url, // Use the accessor from the model
                        'image_path' => $prescription->image_path, // Keep the original path for debugging
                        'notes' => $prescription->notes,
                        'status' => $prescription->status,
                    ];
                }),
                'date' => $createdAt->format('M d, Y'),
                'time' => $createdAt->format('h:i A'),
                'status' => $order->prescriptions->max('status'), // show the highest status among prescriptions
            ];
        });

        return Inertia::render('admin/AdminPrescriptionsIndex', [
            'orders' => $orders
        ]);
    }

    // Show a single order (details page)
    public function show($id)
    {
        $order = \App\Models\Order::with(['user', 'prescriptions'])->findOrFail($id);
        // Convert date/time to Bangladesh time
        $createdAt = $order->created_at->copy()->timezone('Asia/Dhaka');
        $data = [
            'id' => $order->id,
            'user' => [
                'name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone,
                'date_of_birth' => $order->user->date_of_birth,
                'address' => $order->user->address,
                'gender' => $order->user->gender,
                'age' => $order->user->date_of_birth ? \Carbon\Carbon::parse($order->user->date_of_birth)->age : null,
            ],
            'images' => $order->prescriptions->map(function($prescription) {
                return [
                    'url' => $prescription->image_url, // Use the accessor from the model
                    'path' => $prescription->image_path // Keep the original path for debugging
                ];
            }),
            // Collect all unique notes (non-empty) from all prescriptions in the order
            'notes' => $order->prescriptions->pluck('notes')->filter(function($note){ return !empty($note); })->unique()->values(),
            'date' => $createdAt->format('M d, Y'),
            'time' => $createdAt->format('h:i A'),
            'status' => $order->prescriptions->max('status'),
        ];
        return Inertia::render('admin/AdminPrescriptionsShow', [
            'prescription' => $data
        ]);
    }

    // Update order status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);
        $order = \App\Models\Order::with('prescriptions')->findOrFail($id);
        // Update status for all prescriptions in this order
        foreach ($order->prescriptions as $prescription) {
            $prescription->status = $request->status;
            $prescription->save();
        }
        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
