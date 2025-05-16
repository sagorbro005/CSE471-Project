<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    protected $cloudinary;
    
    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }
    public function index(Request $request)
    {
        $query = Blog::where('status', true);
        
        if ($request->category) {
            $query->where('category', $request->category);
        }
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('content', 'like', "%{$request->search}%")
                  ->orWhere('category', 'like', "%{$request->search}%");
            });
        }
        
        $blogs = $query->latest()->paginate(12)->withQueryString();
        
        return Inertia::render('Blogs/Index', [
            'blogs' => $blogs,
            'categories' => (new Blog)->getCategories(),
            'filters' => $request->only(['search', 'category'])
        ]);
    }

    public function show(Blog $blog)
    {
        if (!$blog->status) {
            abort(404);
        }
        
        // Format the blog data to ensure image path is correctly handled
        $blogData = [
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'content' => $blog->content,
            'category' => $blog->category,
            'status' => $blog->status,
            'created_at' => $blog->created_at,
            'updated_at' => $blog->updated_at,
            'image' => $blog->image ? (strpos($blog->image, 'cloudinary.com') !== false ? $blog->image : asset('storage/' . $blog->image)) : null
        ];
        
        $relatedBlogs = Blog::where('category', $blog->category)
            ->where('id', '!=', $blog->id)
            ->where('status', true)
            ->limit(3)
            ->get()
            ->map(function($relatedBlog) {
                return [
                    'id' => $relatedBlog->id,
                    'title' => $relatedBlog->title,
                    'slug' => $relatedBlog->slug,
                    'content' => $relatedBlog->content,
                    'category' => $relatedBlog->category,
                    'status' => $relatedBlog->status,
                    'created_at' => $relatedBlog->created_at,
                    'updated_at' => $relatedBlog->updated_at,
                    'image' => $relatedBlog->image ? (strpos($relatedBlog->image, 'cloudinary.com') !== false ? $relatedBlog->image : asset('storage/' . $relatedBlog->image)) : null
                ];
            });
            
        return Inertia::render('Blogs/Show', [
            'blog' => $blogData,
            'relatedBlogs' => $relatedBlogs
        ]);
    }

    // Admin: Show all blogs (including drafts, etc.)
    public function adminIndex()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        $categories = (new Blog)->getCategories();
        return Inertia::render('admin/AdminBlogs', [
            'blogs' => $blogs,
            'categories' => $categories,
            'flash' => session()->has('success') ? ['success' => session('success')] : []
        ]);
    }

    // Admin: Store new blog (with image upload)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('image')) {
            // Upload image to Cloudinary for permanent storage
            $cloudinaryUrl = $this->cloudinary->uploadImage($request->file('image'), 'blogs');
            
            if ($cloudinaryUrl) {
                $validated['image'] = $cloudinaryUrl;
            } else {
                // Fallback to local storage if Cloudinary upload fails
                $validated['image'] = $request->file('image')->store('blogs', 'public');
            }
        }
        Blog::create($validated);
        return redirect()->route('admin.blogs')->with('success', 'Blog created successfully!');
    }

    // Admin: Update blog
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('image')) {
            // Upload image to Cloudinary for permanent storage
            $cloudinaryUrl = $this->cloudinary->uploadImage($request->file('image'), 'blogs');
            
            if ($cloudinaryUrl) {
                $validated['image'] = $cloudinaryUrl;
            } else {
                // Fallback to local storage if Cloudinary upload fails
                $validated['image'] = $request->file('image')->store('blogs', 'public');
            }
        }
        $blog->update($validated);
        return redirect()->route('admin.blogs')->with('success', 'Blog updated successfully!');
    }

    // Admin: Delete blog
    public function destroy(Blog $blog)
    {
        // Delete image if it exists
        if ($blog->image) {
            // If it's a Cloudinary URL, extract the public ID and delete it
            if (strpos($blog->image, 'cloudinary.com') !== false) {
                $this->deleteCloudinaryImage($blog->image);
            } else {
                // If it's a local file, delete it from local storage
                Storage::disk('public')->delete($blog->image);
            }
        }
        
        $blog->delete();
        return redirect()->route('admin.blogs')->with('success', 'Blog deleted successfully!');
    }
    
    /**
     * Extract the public ID from a Cloudinary URL and delete the image
     *
     * @param string $url
     * @return void
     */
    private function deleteCloudinaryImage(string $url): void
    {
        // Use the improved extractPublicId method from CloudinaryService
        $publicId = $this->cloudinary->extractPublicId($url);
        if ($publicId) {
            $this->cloudinary->deleteImage($publicId);
        } else {
            Log::error('Failed to extract public ID from URL', ['url' => $url]);
        }
    }
}
