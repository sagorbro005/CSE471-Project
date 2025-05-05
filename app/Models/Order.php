<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'total', 'status', 'payment_method', 'payment_status', 'delivery_address', 'contact_phone', 'subtotal', 'delivery_charge', 'coupon_code', 'discount',
        'card_type', 'card_number', 'expiry', 'cvv', 'mobile_payment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
