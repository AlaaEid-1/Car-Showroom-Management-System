<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DealerRequest;

class NewDealerRequestNotification extends Notification
{
    use Queueable;

    public $dealerRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(DealerRequest $dealerRequest)
    {
        $this->dealerRequest = $dealerRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New Dealer Request',
            'message' => 'User ' . $this->dealerRequest->user->name . ' has requested to become a dealer.',
            'request_id' => $this->dealerRequest->id,
        ];
    }
}
