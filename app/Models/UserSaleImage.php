<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSaleImage extends Model
{
    protected $fillable = [
        'user_sale_id',
        'image_url',
    ];

    public function getImageUrlAttribute($value)
    {
        if (empty($value)) {
            return '';
        }
        if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://'])) {
            return route('image.proxy', ['url' => $value]);
        }
        return asset('storage/' . $value);
    }

    public function userSale()
    {
        return $this->belongsTo(UserSale::class);
    }
}
