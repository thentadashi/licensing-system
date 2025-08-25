<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Application $application, public string $old, public string $new) {}

    public function via($notifiable) { return ['mail','database']; }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Application status updated: {$this->new}")
            ->view('emails.application-status-changed', [
                'user' => $notifiable,
                'application' => $this->application,
                'old' => $this->old,
                'new' => $this->new,
                'url' => url(route('applications.show', $this->application->id)),
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Application status updated',
            'application_id' => $this->application->id,
            'from' => $this->old,
            'to' => $this->new,
            'admin_notes' => $this->application->admin_notes,
        ];
    }
}
