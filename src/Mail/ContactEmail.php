<?php

namespace Properos\Commerce\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Properos\Commerce\ViewModel\ContactEmailVM;
use Properos\Base\Classes\Theme;

class ContactEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email_data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ContactEmailVM $email_data)
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
        return $this->from($this->email_data->from)->view('themes.'.Theme::get().'.emails.contact');
    }
}
