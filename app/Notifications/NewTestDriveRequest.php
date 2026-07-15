<?php

namespace App\Notifications;

use App\Models\TestDrive;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewTestDriveRequest extends Notification
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
            ->action('View Bookings', $payload['url']);
    }

    public function toArray($notifiable): array
    {
        $carTitle = $this->testDrive->car->title ?? 'Vehicle';
        $customerName = $this->testDrive->user->name ?? 'Customer';
        return [
            'title' => 'New Test Drive Request',
            'message' => "{$customerName} requested a test drive for {$carTitle}.",
            'type' => 'new_test_drive',
            'url' => route('dashboarddealer.test-drives.index'),
        ];
    }
}
