<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullname;
    public $email;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct($fullname, $email, $password)
    {
        $this->fullname = $fullname;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Password Reset')
                    ->view('emails.reset_password');
    }
}
