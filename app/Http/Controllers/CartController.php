


namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    /**
     * Display the cart page with all cart items.
     */
    public function index()
    {
        // Get cart items for the authenticated user with their associated products
        $cartItems = Cart::where('user_id', auth()->id())
                        ->with('product')
                        ->get();

        // Calculate subtotal (sum of price * quantity for each item)
        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Return the Cart view with cart items and subtotal (with explicit structure)
        return Inertia::render('Cart/Index', [
            'cartItems' => $cartItems->map(function($item) {
                return [
                    'id' => $item->id, // Cart row ID
                    'product_id' => $item->product->id,
                    'product' => [
                        'id' => $item->product->id,
                        'slug' => $item->product->slug,
                        'name' => $item->product->name,
                        'description' => $item->product->description,
                        'price' => $item->product->price,
                        'image' => $item->product->image,
                    ],
                    'quantity' => $item->quantity,
                ];
            }),
            'subtotal' => $subtotal
        ]);
    }

    /**
     * Add a product to the cart.
     */
    public function addToCart(Request $request, $productId)
    {
        // Find the product by ID (not slug)
        $product = \App\Models\Product::findOrFail($productId);

        // Validate request data
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Check if the product already exists in the user's cart
        $cart = Cart::where('user_id', auth()->id())
                   ->where('product_id', $product->id)
                   ->first();

        if ($cart) {
            // If product exists in cart, increment quantity
            $cart->update([
                'quantity' => $cart->quantity + $request->quantity
            ]);
        } else {
            // If product doesn't exist in cart, create new entry
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        // Redirect back with success message
        return back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update the quantity of a cart item.
     */
    public function updateQuantity(Request $request, Cart $cart)
    {
        // Validate request data
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Security check: ensure the cart item belongs to the authenticated user
        if ($cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action');
        }

        // Update the cart item quantity
        $cart->update([
            'quantity' => $request->quantity
        ]);

        // Recalculate cart totals
        $cartItems = Cart::where('user_id', auth()->id())
                        ->with('product')
                        ->get();
                        
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Return a redirect with a flash success message for Inertia
        return back()->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove a specific item from the cart.
     */
    public function remove(Cart $cart)
    {
        // Security check: ensure the cart item belongs to the authenticated user
        if ($cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action');
        }

        // Delete the cart item
        $cart->delete();

        // Redirect back with success message
        return back()->with('success', 'Product removed from cart successfully!');
    }

    /**
     * Empty the entire cart.
     */
    public function empty()
    {
        // Delete all cart items for the authenticated user
        Cart::where('user_id', auth()->id())->delete();

        // Redirect back with success message
        return back()->with('success', 'Cart emptied successfully!');
    }

    /**
     * Proceed to checkout page.
     */
    public function checkout()
    {
        // Get cart items for the authenticated user with their associated products
        $cartItems = Cart::where('user_id', auth()->id())
                        ->with('product')
                        ->get();

        // Check if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty!');
        }

        // Calculate subtotal
        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Return the Checkout view with cart items and subtotal
        return Inertia::render('Cart/Checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal
        ]);
    }
}
