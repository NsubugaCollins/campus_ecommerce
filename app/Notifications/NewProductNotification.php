<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewProductNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Product $product;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
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
            ->subject("New Product Alert: {$this->product->name} - {$storeName}")
            ->greeting("Hello, {$notifiable->name}")
            ->line("We are excited to let you know that a new product has just been listed on {$storeName}!")
            ->line("Here are the details:")
            ->line("• Name: {$this->product->name}")
            ->line("• Category: {$this->product->category}")
            ->line("• Price: UGX " . number_format($this->product->price, 2));

        if (!empty($this->product->description)) {
            $mailMessage->line("• Description: {$this->product->description}");
        }

        return $mailMessage
            ->action('Shop Now', url('/'))
            ->line("Be the first to grab this deal before it runs out!")
            ->line("Thank you for being a valued member of {$storeName}!")
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
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
        ];
    }
}
