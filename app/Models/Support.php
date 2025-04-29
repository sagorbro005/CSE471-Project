<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message'
    ];
}
