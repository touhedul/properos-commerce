<?php

namespace Properos\Commerce\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Properos\Commerce\ViewModel\OrderCreatedVM;
use Properos\Base\Classes\Theme;

class OrderCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $order_data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(OrderCreatedVM $order_data)
    {
       $this->order_data = $order_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->order_data->from, env('APP_NAME', 'PROPEROS'))->subject('New order received')->view('themes.'.Theme::get().'.emails.order-created');
    }
}
