<?php

namespace Properos\Commerce\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Properos\Commerce\ViewModel\OrderCreatedVM;
use Properos\Base\Classes\Theme;

class OrderInvoiceEmail extends Mailable
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
        $subject = 'Properos Invoice';
        if(isset($this->order_data->subject)){
            $subject = $this->order_data->subject;
        }
        return $this->from($this->order_data->from)->subject($subject)->view('themes.'.Theme::get().'.emails.order-invoice');

    }
}
