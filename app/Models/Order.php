<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'shipping_address',
        'payment_method',
        'payment_status',
        'paypal_order_id',
    ];


    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => \App\Events\OrderCreated::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function sendConfirmationEmail()
    {
        try {
            // Since there is no Mailable defined yet, we'll just log it or send a raw mail if configured
            // For now, let's just log to prevent the 500 error if mail fails
            \Log::info("Order confirmation email would be sent for Order #" . $this->id);
            
            // If you want to actually try sending a raw email:
            // \Illuminate\Support\Facades\Mail::raw("Your order #{$this->id} has been placed successfully.", function($message) {
            //     $message->to($this->user->email)->subject("Order Confirmation");
            // });
        } catch (\Exception $e) {
            \Log::error("Failed to send order confirmation email: " . $e->getMessage());
        }
    }
}
