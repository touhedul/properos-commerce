<?php

namespace Properos\Commerce\Classes;

use Properos\Base\Exceptions\ApiException;
use Properos\Base\Classes\Base;
use Properos\Commerce\Models\PackageType;

class CPackage extends Base
{

	function __construct()
	{
		parent::__construct(PackageType::class, 'PackageType');  
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