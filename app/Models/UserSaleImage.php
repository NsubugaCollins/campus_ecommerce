<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSaleImage extends Model
{
    protected $fillable = [
        'user_sale_id',
        'image_url',
    ];

    public function userSale()
    {
        return $this->belongsTo(UserSale::class);
    }
}
