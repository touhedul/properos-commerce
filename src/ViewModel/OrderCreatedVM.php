<?php
namespace Properos\Commerce\ViewModel;
use Properos\Commerce\Models\Order;

class OrderCreatedVM
{
    public $from;
    public $order;
    public $name;
    public $email;
    public $message;
    public $system;
    public $subject;

    public function __construct($from, $order, $name, $email, $subject = false, $system = false)
    {
        $this->from = $from;
        $this->order = $order;
        $this->name = $name;
        $this->email = $email;
        $this->system = $system;
        $this->subject = $subject;
    }
}