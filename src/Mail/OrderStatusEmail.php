<?php

namespace Properos\Commerce\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Properos\Commerce\ViewModel\OrderCreatedVM;
use Properos\Base\Classes\Theme;

class OrderStatusEmail extends Mailable
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
        // dd($this->order_data->order->orderItems);
        // \Log::info($this->order_data->order->shipping_status);
        switch ($this->order_data->order->shipping_status) {
            case 'fulfilled':
                return $this->from($this->order_data->from)->view('themes.'.Theme::get().'.emails.order-fulfilled');
                break;

            case 'shipped':
                return $this->from($this->order_data->from)->view('themes.'.Theme::get().'.emails.order-shipped');
                break;
            case 'delivered':
                return $this->from($this->order_data->from)->view('themes.'.Theme::get().'.emails.order-delivered');
                break;
            case 'cancelled':
                return $this->from($this->order_data->from)->view('themes.'.Theme::get().'.emails.order-cancelled');
                break;

            default:
                return $this->from($this->order_data->from)->view('themes.'.Theme::get().'.emails.order-created');
                break;
        }

    }
}
