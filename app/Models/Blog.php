<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'category',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($blog) {
            $blog->slug = Str::slug($blog->title);
        });
    }

    public function getCategories()
    {
        return [
            'Health Tips',
            'Fitness',
            'Wellness',
            'Baby Care',
            'Diabetic Care',
            'Haircare',
            'Pregnancy'
        ];
    }
}
