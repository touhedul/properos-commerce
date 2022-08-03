<?php

namespace Properos\Commerce\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Properos\Commerce\ViewModel\SubscriberEmailVM;
use Properos\Base\Classes\Theme;

class SubcriberEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email_data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SubscriberEmailVM $email_data)
    {
        $this->email_data = $email_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS','support@properos.com'))->subject('Newsletter subscription')->view('themes.'.Theme::get().'.emails.subscribers');
    }
}
