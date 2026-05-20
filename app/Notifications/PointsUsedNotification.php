<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PointsUsedNotification extends Notification
{
    use Queueable;

    public int $pointsUsed;
    public float $discount;
    public int $remainingPoints;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $pointsUsed, float $discount, int $remainingPoints)
    {
        $this->pointsUsed = $pointsUsed;
        $this->discount = $discount;
        $this->remainingPoints = $remainingPoints;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $storeName = \App\Models\Setting::get('store_name', 'Cycle');
        $storeEmail = \App\Models\Setting::get('store_email', 'support@cycle.com');
        $storePhone = \App\Models\Setting::get('store_phone', '+256 700 000000');
        $storeAddress = \App\Models\Setting::get('store_address', 'Main Campus Plaza, Block A');

        return (new MailMessage)
            ->subject("Reward Points Redeemed - {$storeName}")
            ->greeting("Hello, {$notifiable->name}")
            ->line("You have successfully redeemed reward points for a discount on your purchase!")
            ->line("Here is your point transaction summary:")
            ->line("• Points Redeemed: {$this->pointsUsed} points")
            ->line("• Discount Received: UGX " . number_format($this->discount, 2))
            ->line("• Remaining Balance: {$this->remainingPoints} points")
            ->action('Continue Shopping', url('/'))
            ->line("Keep shopping and earning points to enjoy more discounts on future purchases!")
            ->line("Thank you for being a part of {$storeName}!")
            ->line("")
            ->line("Warm regards,")
            ->line("The {$storeName} Team")
            ->line("Website: " . url('/'))
            ->line("Contact: {$storePhone}")
            ->line("Email: {$storeEmail}")
            ->line("Address: {$storeAddress}");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'points_used' => $this->pointsUsed,
            'discount' => $this->discount,
            'remaining_points' => $this->remainingPoints,
        ];
    }
}
