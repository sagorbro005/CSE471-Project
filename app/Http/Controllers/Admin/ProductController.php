<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $cloudinary;
    
    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }
    /**
     * Display a listing of the products for admin management.
     */
    public function index()
    {
        $products = Product::with('category')
            ->orderByDesc('created_at')
            ->paginate(12);

        return Inertia::render('admin/Products/Index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return Inertia::render('admin/Products/Create', [
            'categories' => $categories,
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : (object)[]
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'manufacturer' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $product = new Product($validated);
        $product->slug = Str::slug($validated['name']) . '-' . uniqid();

        if ($request->hasFile('image')) {
            // Upload image to Cloudinary for permanent storage
            $cloudinaryUrl = $this->cloudinary->uploadImage($request->file('image'), 'products');
            
            if ($cloudinaryUrl) {
                Log::info('Successfully uploaded product image to Cloudinary', ['product' => $validated['name']]);
                $product->image = $cloudinaryUrl;
            } else {
                Log::warning('Failed to upload product image to Cloudinary, falling back to local storage', ['product' => $validated['name']]);
                // Fallback to local storage if Cloudinary upload fails
                $product->image = $request->file('image')->store('products', 'public');
            }
        }

        $product->save();

        return redirect()->route('admin.products')->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return Inertia::render('admin/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : (object)[]
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'manufacturer' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $product->fill($validated);
        $product->slug = Str::slug($validated['name']) . '-' . $product->id;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                // If it's a Cloudinary URL, extract the public ID and delete it
                if (strpos($product->image, 'cloudinary.com') !== false) {
                    // Use improved extractPublicId method from CloudinaryService
                    $publicId = $this->cloudinary->extractPublicId($product->image);
                    if ($publicId) {
                        Log::info('Deleting old product image from Cloudinary', ['product_id' => $product->id, 'public_id' => $publicId]);
                        $this->cloudinary->deleteImage($publicId);
                    } else {
                        Log::warning('Failed to extract public ID from product image URL', ['product_id' => $product->id, 'image' => $product->image]);
                    }
                } else {
                    // If it's a local file, delete it from local storage
                    Log::info('Deleting old product image from local storage', ['product_id' => $product->id, 'image' => $product->image]);
                    Storage::disk('public')->delete($product->image);
                }
            }
            
            // Upload new image to Cloudinary for permanent storage
            $cloudinaryUrl = $this->cloudinary->uploadImage($request->file('image'), 'products');
            
            if ($cloudinaryUrl) {
                Log::info('Successfully uploaded updated product image to Cloudinary', ['product_id' => $product->id]);
                $product->image = $cloudinaryUrl;
            } else {
                Log::warning('Failed to upload updated product image to Cloudinary, falling back to local storage', ['product_id' => $product->id]);
                // Fallback to local storage if Cloudinary upload fails
                $product->image = $request->file('image')->store('products', 'public');
            }
        }

        $product->save();

        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            // If it's a Cloudinary URL, extract the public ID and delete it
            if (strpos($product->image, 'cloudinary.com') !== false) {
                // Use the CloudinaryService to extract the public ID
                $publicId = $this->cloudinary->extractPublicId($product->image);
                if ($publicId) {
                    Log::info('Deleting product image from Cloudinary during product deletion', ['product_id' => $product->id]);
                    $this->cloudinary->deleteImage($publicId);
                }
            } else {
                // Delete from local storage
                Log::info('Deleting product image from local storage during product deletion', ['product_id' => $product->id]);
                Storage::disk('public')->delete($product->image);
            }
        }
        
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
    }
}
