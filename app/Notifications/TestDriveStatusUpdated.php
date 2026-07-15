<?php

namespace App\Notifications;

use App\Models\TestDrive;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TestDriveStatusUpdated extends Notification
{
    use Queueable;

    protected $testDrive;

    public function __construct(TestDrive $testDrive)
    {
        $this->testDrive = $testDrive;
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
            ->action('View My Workspace', $payload['url']);
    }

    public function toArray($notifiable): array
    {
        $carTitle = $this->testDrive->car->title ?? 'Vehicle';
        $status = ucfirst($this->testDrive->status);
        return [
            'title' => "Test Drive Request {$status}",
            'message' => "Your test drive request for {$carTitle} has been {$this->testDrive->status}.",
            'type' => 'test_drive_status',
            'url' => route('inquiries.index') . '#testdrives',
        ];
    }
}
