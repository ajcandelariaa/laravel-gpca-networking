<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttendeeMeetingCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details, $isReceiver;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $isReceiver = true)
    {
        $this->isReceiver = $isReceiver;
        $this->details = $details;
    }


    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        if ($this->isReceiver) {
            $subject = "Meeting Cancelled by " . $this->details['requesterName'] . " - " . $this->details['eventName'];
        } else {
            $subject = "You Cancelled a Meeting with " . $this->details['receiverName'] . " - " . $this->details['eventName'];
        }

        return new Envelope(
            subject: $subject ?? 'Meeting Cancelled',
        );
    }


    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        if ($this->isReceiver) {
            return new Content(
                markdown: 'emails.meeting.cancelled.receiver-mail',
            );
        } else {
            return new Content(
                markdown: 'emails.meeting.cancelled.requester-mail',
            );
        }
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
