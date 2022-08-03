<?php

namespace Properos\Commerce\Classes;

use Illuminate\Http\Request;
use Properos\Base\Classes\Base;
use Properos\Commerce\Models\Tax;

class CTax extends Base
{
	function __construct()
	{
		parent::__construct(Tax::class, 'Tax');     
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

	public function update(array $data)
	{
	}

	public function destroy($id)
	{
	}
}