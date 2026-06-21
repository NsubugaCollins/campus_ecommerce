<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'phone_number',
        'provider',
        'amount',
        'plan',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
