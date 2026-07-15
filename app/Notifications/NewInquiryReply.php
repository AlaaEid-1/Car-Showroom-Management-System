<?php

namespace App\Notifications;

use App\Models\Inquiry;
use App\Models\InquiryMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewInquiryReply extends Notification
{
    use Queueable;

    protected $inquiry;
    protected $reply;

    public function __construct(Inquiry $inquiry, InquiryMessage $reply)
    {
        $this->inquiry = $inquiry;
        $this->reply = $reply;
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
            ->action('View Conversation', $payload['url']);
    }

    public function toArray($notifiable): array
    {
        $senderName = $this->reply->sender->name ?? 'A user';
        $carTitle = $this->inquiry->car->title ?? 'Vehicle';
        return [
            'title' => 'New Message Reply',
            'message' => "{$senderName} sent a new message regarding {$carTitle}: \"" . \Illuminate\Support\Str::limit($this->reply->message, 50) . "\"",
            'type' => 'inquiry_reply',
            'url' => route('inquiries.show', $this->inquiry->id),
        ];
    }
}
