<?php

namespace App\Notifications;

use App\Models\UserSale;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferMadeNotification extends Notification
{
    use Queueable;

    public UserSale $userSale;

    /**
     * Create a new notification instance.
     */
    public function __construct(UserSale $userSale)
    {
        $this->userSale = $userSale;
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

        $mailMessage = (new MailMessage)
            ->subject("New Trade-in Offer Received - {$this->userSale->product_name} - {$storeName}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Good news! We have reviewed your trade-in / sale request for '{$this->userSale->product_name}'.")
            ->line("We are pleased to make you the following offer:")
            ->line("• Product: {$this->userSale->product_name}")
            ->line("• Expected Price: UGX " . number_format($this->userSale->expected_price, 2))
            ->line("• Our Offered Price: UGX " . number_format($this->userSale->offered_price, 2));

        if (!empty($this->userSale->admin_notes)) {
            $mailMessage->line("• Admin Notes: {$this->userSale->admin_notes}");
        }

        return $mailMessage
            ->action('Review & Respond to Offer', url('/user-sales/' . $this->userSale->id))
            ->line("You can accept or reject this offer directly from your account page.")
            ->line("Thank you for using {$storeName}!")
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
            'user_sale_id' => $this->userSale->id,
            'product_name' => $this->userSale->product_name,
            'offered_price' => $this->userSale->offered_price,
        ];
    }
}
