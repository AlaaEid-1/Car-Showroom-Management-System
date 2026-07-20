<?php

namespace App\Notifications;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewInquiry extends Notification implements ShouldQueue
{
    use Queueable;

    protected $inquiry;

    public function __construct(Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $payload = $this->toArray($notifiable);
        return (new MailMessage)
            ->subject($payload['title'])
            ->line($payload['message'])
            ->action('View Inquiry', $payload['url']);
    }

    public function toArray($notifiable): array
    {
        $carTitle = $this->inquiry->car->title ?? 'Vehicle';
        return [
            'title' => 'New Inquiry',
            'message' => "New inquiry received for {$carTitle} from " . ($this->inquiry->buyer->name ?? 'Customer') . ".",
            'type' => 'new_inquiry',
            'url' => route('inquiries.show', $this->inquiry->id),
        ];
    }
}
