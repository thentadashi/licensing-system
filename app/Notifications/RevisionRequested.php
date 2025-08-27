<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RevisionRequested extends Notification
{
    use Queueable;

    public $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Revision Required for Your Application')
            ->view('emails.revision-requested', [
                'user' => $notifiable,
                'application' => $this->application,
                'notes' => $this->application->revision_notes,
                'url' => url(route('applications.show', $this->application->id)),
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'application_id' => $this->application->id,
            'message' => 'Admin requested revisions for your application.',
        ];
    }
}
