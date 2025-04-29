<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['question', 'answer', 'section', 'order'];

    public static function getSections()
    {
        return [
            'delivery' => 'Delivery',
            'payments' => 'Payments',
            'referrals' => 'Referrals',
            'promotions' => 'Promotions',
            'return_refund' => 'Return & Refund'
        ];
    }
}
