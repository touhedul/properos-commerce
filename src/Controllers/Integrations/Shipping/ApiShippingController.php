<?php

namespace Properos\Commerce\Controllers\Integrations\Shipping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Properos\Commerce\Classes\Integrations\Shipping\Common\IShippingUPS;

class ApiShippingController extends Controller
{
	private $shippingUPS;

	public function __construct(IShippingUPS $shippingUPS)
	{
		$this->shippingUPS = $shippingUPS;
	}

	public function getShippingfInfo(Request $request)
	{
		$data = $request->all();
		$res = $this->shippingUPS->createLabel($data);
		// $res = $this->shippingUPS->getLabel($data);
		// $res = $this->shippingUPS->voidLabel($data);
		// $res = $this->shippingUPS->getShippingRates($data);

		return response()->json(['status' => 1, 'data' => [$res]]);
	}

}