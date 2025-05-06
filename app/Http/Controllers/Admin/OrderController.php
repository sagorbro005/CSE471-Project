<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    // List all product orders for admin with search, filter, pagination
    public function index(Request $request)
    {
        $ordersQuery = Order::with(['user', 'items' => function($q) {
            $q->orderBy('id');
        }])
        ->whereDoesntHave('prescriptions'); // Only orders without any prescription

        // Apply search filter to orders by customer name/email/phone
        if ($request->search) {
            $ordersQuery->whereHas('user', function($uq) use ($request) {
                $uq->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }
        // Filter by order status if needed (only allow 4 statuses)
        $validStatuses = ['Pending','Processing','Delivered','Cancelled'];
        if ($request->status && in_array($request->status, $validStatuses)) {
            $ordersQuery->where('status', $request->status);
        }
        $orders = $ordersQuery->orderByDesc('created_at')->paginate(10);

        // Format orders for frontend
        $orders->getCollection()->transform(function($order) {
            $createdAt = $order->created_at->copy()->timezone('Asia/Dhaka');
            return [
                'id' => $order->id,
                'user' => [
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone,
                ],
                'items' => $order->items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'image' => $item->image,
                    ];
                }),
                'date' => $createdAt->format('M d, Y'),
                'time' => $createdAt->format('h:i A'),
                'status' => $order->status,
            ];
        });

        return Inertia::render('admin/AdminOrdersIndex', [
            'orders' => $orders
        ]);
    }

    // Show details for a single product order
    public function show($id)
    {
        $order = Order::with(['user', 'items'])->whereDoesntHave('prescriptions')->findOrFail($id);
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
            'items' => $order->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'image' => $item->image,
                ];
            }),
            'date' => $createdAt->format('Y-m-d'),
            'time' => $createdAt->format('H:i:s'),
            'status' => $order->status,
            'subtotal' => $order->subtotal,
            'delivery_charge' => $order->delivery_charge,
            'total' => $order->total,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
        ];
        return Inertia::render('admin/AdminOrdersShow', [
            'order' => $data
        ]);
    }

    // PATCH: Update order status (admin)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Pending,Processing,Delivered,Cancelled',
        ]);
        $order = Order::whereDoesntHave('prescriptions')->findOrFail($id);
        $order->status = $request->status;
        $order->save();
        // Optionally, notify customer of status change here
        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Order status updated successfully.');
    }
}
