<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'description',
        'category',
        'image',
        'price',
    ];

    protected $appends = ['image_url'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return '';
        }
        if (\Illuminate\Support\Str::startsWith($this->image, ['http://', 'https://'])) {
            return route('image.proxy', ['url' => $this->image]);
        }
        return asset('storage/' . $this->image);
    }
}

