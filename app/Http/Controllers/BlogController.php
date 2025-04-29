<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
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
        
        $relatedBlogs = Blog::where('category', $blog->category)
            ->where('id', '!=', $blog->id)
            ->where('status', true)
            ->limit(3)
            ->get();
            
        return Inertia::render('Blogs/Show', [
            'blog' => $blog,
            'relatedBlogs' => $relatedBlogs
        ]);
    }
}
