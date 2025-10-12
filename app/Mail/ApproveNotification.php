<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApproveNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $bookingDetails;

    /**
     * Create a new message instance.
     *
     * @param array $bookingDetails
     * @param string $reason
     */
    public function __construct($bookingDetails)
    {
        $this->bookingDetails = $bookingDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Booking Approved Notification')
                    ->view('emails.approve_notification');
    }
}
