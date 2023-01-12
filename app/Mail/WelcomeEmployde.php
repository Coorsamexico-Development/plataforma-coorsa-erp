<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmployde extends Mailable
{
    use Queueable, SerializesModels;


    public $email = '';
    public $subjectText = '';
    public $actionUrl = '';
    public $actionText = 'INGRESAR';


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, String $email, String $actionUrl)
    {
        $this->subjectText = $subject;
        $this->email = $email;
        $this->actionUrl = $actionUrl;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->subjectText,
            to: $this->email
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mails.welcome.welcome-employe',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
