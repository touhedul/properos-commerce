<?php

namespace Properos\Commerce\Controllers\Integrations\ECommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  Properos\Commerce\Classes\Integrations\Common\ECommerceClass;

class ECommerceController extends Controller
{
	private $ecommerce;

	public function __construct()
	{
		$data = config('amazon.store.1dash');
		$this->ecommerce = new ECommerceClass('amazon', $data);
	}

	//Testing Calls
	function getAmazonProducts()
	{
		$options['IdType'] = 'ASIN';
		$options['IdList'] = ['B01M6C9DU8'];
		return $this->ecommerce->getProductList($options);
	}

	/**
	 * Sync an item with it's counterpart in a marketplace by marketplace identifier
	 *
	 * @param  \Illuminate\Http\Request
	 * @return \Illuminate\Http\Response
	 */
	public function getOrdersItems($id)
	{
		return $this->ecommerce->getOrderItems($id);
	}

	/**
	 * Sync an item with it's counterpart in a marketplace by marketplace identifier
	 *
	 * @param  \Illuminate\Http\Request
	 * @return \Illuminate\Http\Response
	 */
	public function getOrders()
	{
		$options['status'] = "Canceled";
		return json_encode($this->ecommerce->getOrders($options));
	}
	
	/**
	 * Sync an item with it's counterpart in a marketplace by marketplace identifier
	 *
	 * @param  \Illuminate\Http\Request
	 * @return \Illuminate\Http\Response
	 */
	public function updateProductQtyFeed()
	{
		$options = [];
		return json_encode($this->ecommerce->updateProductQtyFeed($options));
	}

	/**
	 * Sync an item with it's counterpart in a marketplace by marketplace identifier
	 *
	 * @param  \Illuminate\Http\Request
	 * @return \Illuminate\Http\Response
	 */
	public function updateProductQtyFeedResult($id)
	{
		$options ['id'] = $id;
		return json_encode($this->ecommerce->updateProductQtyFeedResult($options));
	}
}