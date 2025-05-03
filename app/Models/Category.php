<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Ensure name is always a string.
     */
    public function getNameAttribute($value)
    {
        return (string) $value;
    }

    /**
     * Get the route key name for route model binding.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
