<?php

namespace Properos\Commerce\Classes;

use Properos\Base\Classes\Base;
use Properos\Commerce\Models\Option;

class COption extends Base
{

	function __construct()
	{
		parent::__construct(Option::class, 'Option');  
	}

	public function init_fillable()
    {
        foreach ($this->model->getFillable() as $key) {
            $this->fillable[$key] = null;
        }
    }

	public function store(array $data)
	{

	}

	public function update(array $data, $id)
	{

	}

}