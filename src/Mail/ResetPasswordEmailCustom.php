<?php

namespace Properos\Users\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordEmailCustom extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $token)
    {
       $this->user = $user;
       $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /* dd($this->user); */
        return $this->from(env('MAIL_FROM_ADDRESS'))->view('themes.'.Theme::get().'.emails.password-reset');
    }
}
