<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeclineNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $bookingDetails;
    public $reason;

    /**
     * Create a new message instance.
     *
     * @param array $bookingDetails
     * @param string $reason
     */
    public function __construct($bookingDetails, $reason)
    {
        $this->bookingDetails = $bookingDetails;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Booking Declined Notification')
                    ->view('emails.decline_notification');
    }
}
