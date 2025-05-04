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
        
        // Get all orders for the current user
        $orders = Order::where('user_id', Auth::id())
            ->with(['items'])
            ->orderByDesc('created_at')
            ->get();
            
        // If no orders found, create a sample order for display purposes
        if ($orders->isEmpty() && config('app.env') === 'local') {
            // This is just for development to show how orders will look
            $sampleOrder = [
                'id' => 1,
                'status' => 'Processing',
                'formatted_date' => now()->format('d M Y, h:i A'),
                'total' => 2500.00,
                'items_count' => 3,
                'payment_icon' => 'fas fa-credit-card',
                'payment_text' => 'Card',
                'payment_status' => 'Paid',
                'progress' => 40,
            ];
            
            return Inertia::render('Orders/Index', [
                'orders' => [$sampleOrder]
            ]);
        }
        
        // Format orders for the frontend
        $formattedOrders = $orders->map(function($order) {
            return [
                'id' => $order->id,
                'status' => $order->status,
                'formatted_date' => $order->created_at->format('d M Y, h:i A'),
                'total' => $order->total,
                'items_count' => $order->items->count(),
                'payment_icon' => $this->getPaymentIcon($order),
                'payment_text' => $this->getPaymentText($order),
                'payment_status' => $order->payment_status,
                'progress' => $this->getOrderProgress($order->status),
            ];
        });
        
        // DEBUG: Log number of orders retrieved
        \Log::info('Orders retrieved for user', ['user_id' => Auth::id(), 'count' => $orders->count()]);
        
        return Inertia::render('Orders/Index', [
            'orders' => $formattedOrders
        ]);
    }

    // Show details for a single order
    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['items'])
            ->firstOrFail();
        return Inertia::render('Orders/Show', [
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'placed_at' => $order->created_at->format('d M Y, h:i A'),
                'payment_icon' => $this->getPaymentIcon($order),
                'payment_text' => $this->getPaymentText($order),
                'payment_status' => $order->payment_status,
                'subtotal' => $order->subtotal,
                'delivery_charge' => $order->delivery_charge,
                'total' => $order->total,
                'delivery_address' => $order->delivery_address,
                'contact_phone' => $order->contact_phone,
                'items' => $order->items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'image' => $item->image,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                    ];
                }),
            ]
        ]);
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
}
