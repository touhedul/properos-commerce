<?php

namespace Properos\Commerce\Classes;

use Properos\Base\Classes\Base;
use Properos\Commerce\Models\Payment;

class CPayments extends Base
{

    function __construct()
    {
        parent::__construct(Payment::class, 'Payment');
    }

    public function init_fillable()
    {
        foreach ($this->model->getFillable() as $key) {
            $this->fillable[$key] = null;
        }
    }
}
