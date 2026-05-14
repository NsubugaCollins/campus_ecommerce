<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSale extends Model
{
    protected $fillable = [
        'user_id',
        'product_name',
        'category',
        'condition',
        'description',
        'expected_price',
        'offered_price',
        'status',
        'admin_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(UserSaleImage::class);
    }
}
