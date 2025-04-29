<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'image_path',
        'status',
        'notes'
    ];

    protected $appends = ['image_url'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get the full URL for the prescription image
    public function getImageUrlAttribute()
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }

    // Get the status label
    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    // Check if prescription is pending
    public function isPending()
    {
        return $this->status === 'pending';
    }

    // Check if prescription is approved
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    // Check if prescription is rejected
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}