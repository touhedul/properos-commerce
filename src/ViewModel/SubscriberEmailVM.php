<?php
namespace Properos\Commerce\ViewModel;

class SubscriberEmailVM
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}