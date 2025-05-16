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
                $product->image = $cloudinaryUrl;
            } else {
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
                    // Extract public ID from URL
                    $publicId = $this->extractCloudinaryPublicId($product->image);
                    if ($publicId) {
                        $this->cloudinary->deleteImage($publicId);
                    }
                } else {
                    // If it's a local file, delete it from local storage
                    Storage::disk('public')->delete($product->image);
                }
            }
            
            // Upload new image to Cloudinary for permanent storage
            $cloudinaryUrl = $this->cloudinary->uploadImage($request->file('image'), 'products');
            
            if ($cloudinaryUrl) {
                $product->image = $cloudinaryUrl;
            } else {
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
                // Extract public ID from URL
                $publicId = $this->extractCloudinaryPublicId($product->image);
                if ($publicId) {
                    $this->cloudinary->deleteImage($publicId);
                }
            } else {
                // If it's a local file, delete it from local storage
                Storage::disk('public')->delete($product->image);
            }
        }
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
    }
    
    /**
     * Extract the public ID from a Cloudinary URL
     *
     * @param string $url
     * @return string|null
     */
    private function extractCloudinaryPublicId(string $url): ?string
    {
        // Example URL: https://res.cloudinary.com/your-cloud-name/image/upload/v1589394838/products/abc123.jpg
        $pattern = '/cloudinary\.com\/.*\/(?:image|video)\/upload(?:\/[^\/]*)*\/(.+?)(?:\.[^\.]+)?$/i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1]; // This is the public ID including folder
        }
        return null;
    }
}
