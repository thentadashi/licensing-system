<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RevisionSubmitted extends Notification
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
            ->subject('Student Submitted Revisions')
            ->view('emails.revision-submitted', [
                'user' => $notifiable,
                'application' => $this->application,
                'student' => $this->application->user,
                'url' => url(route('admin.applications.show', $this->application->id)),
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'application_id' => $this->application->id,
            'message' => 'Student '. $this->application->user->name . ' submitted revisions for ' . $this->application->application_type . ' application.',
        ];
    }
}
