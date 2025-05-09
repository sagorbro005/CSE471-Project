<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrderController extends Controller
{
    // Show all orders for the logged-in user
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view your orders.');
        }
        
        // Get all orders for the current user, EXCLUDING prescription orders
        $orders = Order::where('user_id', Auth::id())
            ->whereDoesntHave('prescriptions') // Exclude orders with any prescription
            ->with(['items'])
            ->orderByDesc('created_at')
            ->get();
            
        // Log orders for debugging
        \Log::info('Retrieved orders count: ' . $orders->count(), ['user_id' => Auth::id()]);
            
        // If no orders found, create a sample order for display purposes
        if ($orders->isEmpty() && config('app.env') === 'local') {
            // This is just for development to show how orders will look
            $sampleOrder = [
                'id' => 1,
                'status' => 'Processing',
                'formatted_date' => now()->format('d M Y, h:i A'),
                'total' => 2500.00,
                'subtotal' => 2000.00,
                'delivery_charge' => 500.00,
                'items_count' => 3,
                'payment_icon' => 'fas fa-credit-card',
                'payment_text' => 'Card',
                'payment_status' => 'Paid',
                'progress' => 40,
                'products' => [
                    [
                        'id' => 1,
                        'name' => 'Product 1',
                        'quantity' => 2,
                        'price' => 1000.00,
                        'image' => 'product1.jpg',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Product 2',
                        'quantity' => 1,
                        'price' => 500.00,
                        'image' => 'product2.jpg',
                    ],
                ],
                'placed_at' => now()->format('F j, Y h:i A'),
            ];
            
            return Inertia::render('Orders/Index', [
                'orders' => [$sampleOrder]
            ]);
        }
        
        // Format orders for frontend (include status, subtotal, delivery_charge, total, payment_method, payment_status)
        $orders = $orders->map(function($order) {
            return [
                'id' => $order->id,
                'status' => $order->status,
                'subtotal' => $order->subtotal,
                'delivery_charge' => $order->delivery_charge,
                'total' => $order->total,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'placed_at' => $order->created_at->timezone('Asia/Dhaka')->format('Y-m-d H:i:s'),
                'items' => $order->items->map(function($item) {
    return [
        'id' => $item->id,
        'name' => $item->name,
        'quantity' => $item->quantity,
        'price' => $item->price,
        'image' => $item->image,
        'slug' => optional($item->product)->slug, // Include product slug for navigation
    ];
}),
            ];
        });
        
        // DEBUG: Log number of orders retrieved
        \Log::info('Orders retrieved for user', ['user_id' => Auth::id(), 'count' => $orders->count()]);
        
        return Inertia::render('Orders/Index', [
            'orders' => $orders
        ]);
    }

    // Show details for a single order
    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['items', 'user'])
            ->firstOrFail();
        // Format order for frontend
        $orderData = [
            'id' => $order->id,
            'status' => $order->status,
            'placed_at' => $order->created_at->format('F j, Y h:i A'),
            'total' => $order->total,
            'subtotal' => $order->subtotal,
            'delivery_charge' => $order->delivery_charge,
            'discount' => $order->discount ?? 0,
            'coupon_code' => $order->coupon_code ?? null,
            'payment_icon' => $this->getPaymentIcon($order),
            'payment_text' => $this->getPaymentText($order),
            'payment_status' => $order->payment_status,
            'status_class' => $this->getOrderStatusClass($order->status),
            'delivery_address' => $order->delivery_address ?? ($order->user->address ?? null),
            'contact_phone' => $order->contact_phone ?? ($order->user->phone ?? null),
            'contact_email' => $order->user->email ?? null,
            'customer_name' => $order->user->name ?? null,
            'items' => $order->items->map(function($item) {
    return [
        'id' => $item->id,
        'name' => $item->name,
        'quantity' => $item->quantity,
        'price' => $item->price,
        'image' => $item->image,
        'slug' => optional($item->product)->slug, // Include product slug for navigation
    ];
}),
        ];
        return Inertia::render('Orders/Show', [
            'order' => $orderData
        ]);
    }

    // Download invoice as PDF or HTML
    public function downloadInvoice($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['items', 'user'])
            ->firstOrFail();
        // For PDF generation, use dompdf/barryvdh
        // If not installed, fallback to HTML
        if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoice', compact('order'));
            return $pdf->download('invoice_'.$order->id.'.pdf');
        } else {
            // fallback: return HTML view for download
            return response()->view('invoice', compact('order'))
                ->header('Content-Disposition', 'attachment; filename=invoice_'.$order->id.'.html');
        }
    }

    // Place a new order (Checkout process)
    public function process(Request $request)
    {
        $validated = $request->validate([
            'payment_type' => 'required|in:card,mobile,cod',
            'district' => 'required|string',
            'cart' => 'required|array|min:1',
            'subtotal' => 'required|numeric',
            'delivery_charge' => 'required|numeric',
            'total' => 'required|numeric',
            // Card fields
            'card_type' => 'nullable|string',
            'card_number' => 'nullable|string',
            'expiry' => 'nullable|string',
            'cvv' => 'nullable|string',
            // Mobile fields
            'mobile_payment' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Ensure user is authenticated
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Please login to place an order.');
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'Pending',
                'payment_method' => $validated['payment_type'],
                'payment_status' => 'Pending',
                'subtotal' => $validated['subtotal'],
                'delivery_charge' => $validated['delivery_charge'],
                'total' => $validated['total'],
                'delivery_address' => $validated['district'],
                'contact_phone' => $validated['phone'] ?? null,
                // Store payment/card/mobile details as needed
                'card_type' => $validated['card_type'] ?? null,
                'card_number' => $validated['card_number'] ?? null,
                'expiry' => $validated['expiry'] ?? null,
                'cvv' => $validated['cvv'] ?? null,
                'mobile_payment' => $validated['mobile_payment'] ?? null,
            ]);
            // DEBUG: Log order creation
            \Log::info('Order created', ['order_id' => $order->id, 'user_id' => $order->user_id]);

            foreach ($validated['cart'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']['id'],
                    'name' => $item['product']['name'],
                    'image' => $item['product']['image'] ?? null,
                    'price' => $item['product']['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            // Optionally, clear user's cart here (if cart model exists)
            \App\Models\Cart::where('user_id', Auth::id())->delete();

            DB::commit();
            
            // Redirect with flash data for order confirmation
            session()->flash('success', 'Order placed successfully!');
            session()->flash('order_id', $order->id);
            
            return redirect()->route('orders.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order placement failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    // Helpers for payment icon/text and progress
    private function getPaymentIcon($order)
    {
        if ($order->payment_method === 'card') {
            return 'fas fa-credit-card';
        }
        if ($order->payment_method === 'mobile') {
            return 'fas fa-mobile-alt';
        }
        return 'fas fa-money-bill';
    }
    private function getPaymentText($order)
    {
        if ($order->payment_method === 'card') {
            return 'Card';
        }
        if ($order->payment_method === 'mobile') {
            return 'Mobile';
        }
        return 'Cash';
    }
    private function getOrderProgress($status)
    {
        switch ($status) {
            case 'Pending': return 10;
            case 'Processing': return 40;
            case 'Shipped': return 70;
            case 'Delivered': return 100;
            default: return 0;
        }
    }
    private function getOrderStatusClass($status)
    {
        switch ($status) {
            case 'Pending': return 'badge-secondary';
            case 'Processing': return 'badge-info';
            case 'Shipped': return 'badge-primary';
            case 'Delivered': return 'badge-success';
            default: return 'badge-dark';
        }
    }
}
