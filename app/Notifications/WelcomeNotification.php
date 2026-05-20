<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
            ->subject("Welcome to {$storeName}!")
            ->greeting("Hello, {$notifiable->name}")
            ->line("Welcome to {$storeName} – your campus sharing economy platform! We are thrilled to have you join our community.")
            ->line("With {$storeName}, you can buy and sell items directly within the campus community, negotiate trade-ins, and earn rewards.")
            ->line("Here is a quick overview of what you can do:")
            ->line("• Browse and shop premium deals directly on the platform.")
            ->line("• Sell your own products or negotiate trade-ins.")
            ->line("• Refer friends using your unique referral code ({$notifiable->referral_code}) to earn reward points!")
            ->action('Start Shopping Now', url('/'))
            ->line("If you have any questions, feel free to contact us.")
            ->line("Thank you for being part of {$storeName}!")
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
            //
        ];
    }
}
