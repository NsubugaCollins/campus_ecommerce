<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Order $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
            ->subject("Order Confirmation - Order #{$this->order->id} - {$storeName}")
            ->greeting("Hello, {$notifiable->name}")
            ->line("Thank you for your order! Your order #{$this->order->id} has been placed successfully.")
            ->line("Here are your order details:")
            ->line("• Total Amount: UGX " . number_format($this->order->total_amount, 2))
            ->line("• Shipping Address: {$this->order->shipping_address}")
            ->line("• Payment Method: " . strtoupper($this->order->payment_method))
            ->action('View Your Orders', url('/orders'))
            ->line("We are processing your order and will contact you shortly for delivery.")
            ->line("Thank you for shopping at {$storeName}!")
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
            'order_id' => $this->order->id,
            'total_amount' => $this->order->total_amount,
        ];
    }
}
