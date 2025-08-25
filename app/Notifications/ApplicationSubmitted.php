<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationSubmitted extends Notification
{
    use Queueable;

    public function via($notifiable) { return ['mail','database']; }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('We received your application')
            ->view('emails.application-submitted', [
                'user' => $notifiable,
                'url' => url(route('applications')),
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Application submitted',
            'message' => 'Your application was submitted successfully.'
        ];
    }
}
