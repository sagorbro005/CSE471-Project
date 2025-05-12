<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        try {
            // Cache categories for better performance
            $categories = Category::orderBy('name')->get();
            $selectedCategory = $request->category;
            $search = $request->search;
            
            // Build query with eager loading for performance
            $query = Product::where('status', 'active')
                ->with('category');
            
            // Apply category filter
            if ($selectedCategory) {
                $query->whereHas('category', fn($q) => 
                    $q->where('slug', $selectedCategory)
                );
            }

            // Apply search filter if provided
            if ($search) {
                $searchTerm = '%' . $search . '%';
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm)
                      ->orWhere('description', 'like', $searchTerm)
                      ->orWhere('manufacturer', 'like', $searchTerm)
                      ->orWhereHas('category', fn($q) => 
                          $q->where('name', 'like', $searchTerm)
                      );
                });
            }
            
            // Get paginated products
            $products = $query->latest()->paginate(12)->withQueryString();
            
            return Inertia::render('Products/Index', [
                'products' => $products,
                'categories' => $categories,
                'filters' => [
                    'category' => $selectedCategory,
                    'search' => $search,
                ],
                'totalResults' => $products->total()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in ProductController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading products.');
        }
    }

    /**
     * Display the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Inertia\Response
     */
    public function show(Product $product)
    {
        try {
            // Check if product is active
            if ($product->status !== 'active') {
                abort(404);
            }

            // Eager load relationships in a single query
            $product->load(['category', 'reviews.user']);

            // Get related products efficiently
            $relatedProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('status', 'active')
                ->with('category')
                ->take(4)
                ->get();

            // Check if user can leave a review
            $userCanReview = false;
            $isLoggedIn = auth()->check();
            
            if ($isLoggedIn) {
                $userCanReview = Order::where('user_id', auth()->id())
                    ->where('status', 'Delivered')
                    ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
                    ->exists();
            }
            
            return Inertia::render('Products/Show', [
                'product' => $product,
                'relatedProducts' => $relatedProducts,
                'userCanReview' => $userCanReview,
                'isLoggedIn' => $isLoggedIn,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in ProductController@show: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Unable to display the product.');
        }
    }

    /**
     * Store a new product review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function review(Request $request, Product $product)
    {
        try {
            // Validate review input
            $validated = $request->validate([
                'review' => ['required', 'string', 'max:100']
            ]);

            // Create review with validated data
            $product->reviews()->create([
                'user_id' => auth()->id(),
                'review' => $validated['review']
            ]);

            return back()->with('success', 'Review submitted successfully!');
        } catch (\Exception $e) {
            \Log::error('Error in ProductController@review: ' . $e->getMessage());
            return back()->with('error', 'Unable to submit review. Please try again.');
        }
    }
}
