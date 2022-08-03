<?php

namespace Properos\Commerce\Classes;

use Properos\Base\Exceptions\ApiException;
use Properos\Base\Classes\Base;
use Properos\Commerce\Models\Collection;

class CCollection extends Base
{

	function __construct()
	{
		parent::__construct(Collection::class, 'Collection');     
	}

}