<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getCount()
    {
        if (auth()->check()) {
            return self::where('user_id', auth()->id())->sum('quantity');
        }

        $guestCartId = request()->cookie('guest_cart_id');
        if ($guestCartId) {
            return self::where('session_id', $guestCartId)->sum('quantity');
        }

        return 0;
    }
}
