<?php

namespace Properos\Commerce\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Properos\Base\Classes\Base;
use Properos\Commerce\Models\Subscriber;


class CSubscribers extends Base
{
	function __construct()
	{
		parent::__construct(Subscriber::class, 'Subscriber');     
	}

	public function init_fillable()
    {
        foreach ($this->model->getFillable() as $key) {
            $this->fillable[$key] = null;
        }
    }
}