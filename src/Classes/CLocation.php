<?php

namespace Properos\Commerce\Classes;

use Illuminate\Http\Request;
use Properos\Commerce\Models\StoreLocation;
use Properos\Base\Classes\Base;

class CLocation extends Base
{
	function __construct()
	{
		parent::__construct(StoreLocation::class, 'StoreLocation');     
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