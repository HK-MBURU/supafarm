<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormSubmission extends Notification implements ShouldQueue
{
    use Queueable;

    public $contact;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Contact Form Submission: ' . $this->contact->subject)
            ->greeting('Hello!')
            ->line('You have received a new contact form submission from your website.')
            ->line('Name: ' . $this->contact->name)
            ->line('Email: ' . $this->contact->email)
            ->line('Subject: ' . $this->contact->subject)
            ->line('Message:')
            ->line($this->contact->message)
            ->action('View in Admin Panel', url('/admin/contacts'))
            ->line('Thank you for using Supa Farm Supplies!');
    }
}