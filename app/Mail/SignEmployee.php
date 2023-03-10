<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SignEmployee extends Mailable
{
    use Queueable, SerializesModels;

    public $email = '';
    public $subjectText = '';
    public $msg = '';
    protected $pdf;
    /**
     * Create a new message instance.
     *
     * @param String $subject
     * @param String $email to send
     * @param String $msg
     * @param pdf
     *
     * @return void
     */
    public function __construct(String $subject, String $email, String $msg, $pdf)
    {
        $this->subjectText = $subject;
        $this->email = $email;
        $this->msg = $msg;
        $this->pdf = $pdf;
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
            to: $this->email,
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
            view: 'mails.sign-employee',

        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            Attachment::fromData(fn () => $this->pdf, 'Firma.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
