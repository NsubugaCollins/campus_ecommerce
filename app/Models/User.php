<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'email',
        'phone',
        'password', 
        'role', 
        'login_count', 
        'last_login_at', 
        'points', 
        'referral_code', 
        'referred_by',
        'subscription_type',
        'subscription_expires_at'
    ];

    protected $hidden = [
        'password', 
        'remember_token'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = strtoupper(\Illuminate\Support\Str::random(8));
            }
        });
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'subscription_expires_at' => 'datetime',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Users who registered using this user's referral code.
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }



    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function subscriptionPayments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function isSubscribed()
    {
        if ($this->subscription_type === 'none') {
            return false;
        }
        if (!$this->subscription_expires_at) {
            return false;
        }
        return now()->lt($this->subscription_expires_at);
    }
}
