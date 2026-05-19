<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Notifications\WelcomeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        try {
            $event->user->notify(new WelcomeNotification());
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send welcome email to user {$event->user->id}: " . $e->getMessage());
        }
    }
}
