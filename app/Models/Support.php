<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'status'
    ];
    
    // Define which attributes should be treated as dates
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
