<?php
namespace Properos\Commerce\ViewModel;

class ContactEmailVM
{
    public $title;
    public $from;
    public $name;
    public $phone;
    public $message;

    public function __construct($title, $from, $name, $phone, $message)
    {
        $this->title = $title;
        $this->from = $from;
        $this->name = $name;
        $this->phone = $phone;
        $this->message = $message;
    }
}