<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
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
            $categories = Category::orderBy('name')->get();
            $selectedCategory = $request->category;
            $search = $request->search;
            
            $query = Product::where('status', 'active')
                           ->with('category'); // Eager load categories
            
            if ($selectedCategory) {
                $query->whereHas('category', function($q) use ($selectedCategory) {
                    $q->where('slug', $selectedCategory);
                });
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('manufacturer', 'like', "%{$search}%")
                      ->orWhereHas('category', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            }
            
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
            if ($product->status !== 'active') {
                abort(404);
            }

            // Eager load the category and reviews with their users
            $product->load(['category', 'reviews.user']);

            $relatedProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('status', 'active')
                ->with('category') // Eager load categories for related products
                ->take(4)
                ->get();

            $userCanReview = false;
            if (auth()->check()) {
                $userId = auth()->id();
                $userCanReview = \App\Models\Order::where('user_id', $userId)
                    ->where('status', 'Delivered')
                    ->whereHas('items', function ($q) use ($product) {
                        $q->where('product_id', $product->id);
                    })
                    ->exists();
            }
            return Inertia::render('Products/Show', [
                'product' => $product,
                'relatedProducts' => $relatedProducts,
                'userCanReview' => $userCanReview,
                'isLoggedIn' => auth()->check(),
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
            $request->validate([
                'review' => ['required', 'string', 'max:100']
            ]);

            $product->reviews()->create([
                'user_id' => auth()->id(),
                'review' => $request->review
            ]);

            return back()->with('success', 'Review submitted successfully!');
        } catch (\Exception $e) {
            \Log::error('Error in ProductController@review: ' . $e->getMessage());
            return back()->with('error', 'Unable to submit review. Please try again.');
        }
    }
}
