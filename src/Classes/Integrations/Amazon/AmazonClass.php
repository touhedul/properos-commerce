<?php

namespace Properos\Commerce\Classes\Integrations\Amazon;

use Illuminate\Http\File;
use Properos\AmazonMws\AmazonParticipationList;
use Properos\AmazonMws\AmazonOrderList;
use Properos\AmazonMws\AmazonProductsList;
use Properos\Commerce\Classes\Integrations\Common\ECommerceInterface;
use Carbon\Carbon;
use Properos\Base\Classes\Helper;

class AmazonClass implements ECommerceInterface
{
	protected $seller_id;
	protected $marketplace_id;
	protected $aws_key_id;
	protected $secret_key;
	protected $mws_auth_token;
	protected $limit;
	protected $url;
	protected $next_token;
	protected $disable_ssl;

	public function __construct($data)
	{
		if (count($data) > 0) {
			$this->setSettings($data);
		}
	}

	public function getLimit()
	{
		return $this->limit;
	}

	public function setLimit($limit)
	{
		$this->limit = $limit;
		return $this;
	}

	public function setSettings($data)
	{

		$this->seller_id = \helper::getValue($data, 'seller_id', '');
		$this->marketplace_id = \helper::getValue($data, 'marketplace_id', '');
		$this->aws_key_id = \helper::getValue($data, 'aws_key_id', '');
		$this->secret_key = \helper::getValue($data, 'secret_key', '');
		$this->mws_auth_token = \helper::getValue($data, 'mws_auth_token', '');
		$this->url = 'https://mws.amazonservices.com/';
		$this->disable_ssl = \helper::getValue($data, 'disable_ssl', false);
	}

	public function connectionCheck()
	{
		$amz = new AmazonParticipationList($this->getSettings()); //store name matches the array key in the config file\
		$amz->fetchParticipationList();
		if (count($amz->getMarketplaceList()) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getSettings()
	{
		return [
			'seller_id' => $this->seller_id,
			'marketplace_id' => $this->marketplace_id,
			'aws_key_id' => $this->aws_key_id,
			'secret_key' => $this->secret_key,
			'mws_auth_token' => $this->mws_auth_token,
			'url' => $this->url,
			'disable_ssl' => $this->disable_ssl
		];
	}

	public function getCustomer($id, $options)
	{
		if (isset($options['fields'])) {
			unset($options['fields']);
		}
		$res_customer = json_decode(\api::call($this->url . '/customers/' . $id, $options, 'GET', $this->headers(), $this->connectionOptions()));
		if ($res_customer == null || (isset($res_customer->status) && $res_customer->status == 0)) {
			return [];
		}
		$customer = new \stdClass();
		$customer->id = $res_customer->id;
		$customer->name = $res_customer->first_name . ' ' . $res_customer->last_name;
		$customer->first_name = $res_customer->first_name;
		$customer->last_name = $res_customer->last_name;
		$customer->phone = $res_customer->phone;
		$customer->email = $res_customer->email;
		$customer->orders = $res_customer->orders_count;
		$customer->ecommerce = 'amazon';
		$customer->address = '';
		if (isset($res_customer->shipping)) {
			$customer->address = $this->address($res_customer->shipping);
		}

		return $customer;
	}

	public function getCustomers($options)
	{
		if (isset($options['fields'])) {
			unset($options['fields']);
		}
		if (!isset($options['page'])) {
			$options['page'] = '1';
		}
		if (!isset($options['role'])) {
			$options['role'] = 'customer';
		}
		if (!isset($options['per_page'])) {
			$options['per_page'] = $this->limit;
		}
		$res = json_decode(\api::call($this->url . '/customers', $options, 'GET', $this->headers(), $this->connectionOptions()));
		if ($res == null || (isset($res->status) && $res->status == 0)) {
			return [];
		}
		$customer = new \stdClass();
		$customers = [];
		foreach ($res as $res_customer) {
			$customer->id = $res_customer->id;
			$customer->name = $res_customer->first_name . ' ' . $res_customer->last_name;
			$customer->first_name = $res_customer->first_name;
			$customer->last_name = $res_customer->last_name;
			$customer->phone = $res_customer->phone;
			$customer->email = $res_customer->email;
			$customer->orders = $res_customer->orders_count;
			$customer->ecommerce = 'amazon';
			$customer->address = '';
			if (isset($res_customer->shipping)) {
				$customer->address = $this->address($res_customer->shipping);
			}
			$customers[] = $customer;
		}
		return $customers;
	}

	public function getCountCustomers($options)
	{
        //TODO
		return 0;
	}

	public function getOrders($options = [])
	{
		$amz = new AmazonOrderList($this->getSettings()); //store name matches the array key in the config file

		$amz->setUseToken(true);

		if (isset($options['status'])) {
			$amz->setOrderStatusFilter($options['status']); //no shipped or pending orders
		} else {
			$amz->setOrderStatusFilter(["Pending", "Unshipped", "PartiallyShipped", "Canceled", "Unfulfillable", "Shipped"]);
		}
		if (!isset($options['per_page'])) {
			$amz->setMaxResultsPerPage($this->limit);
		} else {
			$amz->setMaxResultsPerPage($options['per_page']);
		}

		if (isset($options['modified'])) {
			$date = Carbon::parse($options['modified']);
			$amz->setLimits('Modified', $date->toIso8601String(), $date->addDay()->toIso8601String()); //accepts either specific timestamps or relative times
		} else {
			$amz->setLimits('Created', "-365 days"); //accepts either specific timestamps or relative times
		}

		$amz->setUseToken(); //tells the object to automatically use tokens right away
		$amz->fetchOrders(); //this is what actually sends the request
		$orders = [];

		foreach ($amz->getList() as $res_order) {
			if (is_array($res_order) || is_object($res_order)) {
				$orders[] = $this->buildOrder($res_order->getData());
			}
		}
		/* dd($orders[0]->ordered); */
		return $orders;
	}

	public function getOrder($id, $options = [])
	{
		$amz = new \Properos\AmazonMws\AmazonOrder($this->getSettings()); //store name matches the array key in the config file
		$amz->setOrderId($id);
		$amz->fetchOrder(); //this is what actually sends the request
		return $this->buildOrder($amz->getData());
	}

	public function getOrderItems($order_id)
	{
		$amz = new \Properos\AmazonMws\AmazonOrderItemList($this->getSettings()); //store name matches the array key in the config file
		$amz->setOrderId($order_id);
		$amz->fetchItems(); //this is what actually sends the request
		return $amz->getItems();
	}

	public function getCountOrders($options = [])
	{
		$res = json_decode(\api::call($this->url . '/orders', $options, 'GET', $this->headers(), $this->connectionOptions()));
		if ($res == null || (isset($res->status) && $res->status == 0)) {
			return ['count' => 0];
		}
		return $res;
	}

	public function getShipments($options)
	{
	//TODO

	}

	public function getProducts($options)
	{
		$amz = new \Properos\AmazonMws\AmazonProductSearch($this->getSettings());
		$amz->setQuery('test');
		$amz->searchProducts();
		return $amz->getProduct();
	}

	public function getProductList($options)
	{
		$amz = new \Properos\AmazonMws\AmazonProductList($this->getSettings());
		$amz->setIdType($options['IdType']);
		$amz->setProductIds($options['IdList']);
		$amz->fetchProductList();
		return $amz->getProduct();
	}

	public function getInventory($options)
	{
		$obj = new \Properos\AmazonMws\AmazonInventoryList($this->getSettings());
		$obj->setUseToken(); //tells the object to automatically use tokens right away
		$amz->setUseToken(true);
        //$obj->setStartTime("- 24 hours");
		$obj->fetchInventoryList(); //this is what actually sends the request
		return $obj->getSupply();
	}

	public function address($r_address)
	{
		$address = rtrim(\helper::getValue($r_address, 'AddressLine1', '') . ' ' . \helper::getValue($r_address, 'AddressLine2', ''), '') . ', ';
		$address = rtrim($address . \helper::getValue($r_address, 'City', ''), ', ') . ', ';
		$address = rtrim($address . \helper::getValue($r_address, 'StateOrRegion', ''), ', ') . ' ';
		$address = rtrim($address . \helper::getValue($r_address, 'PostalCode', ''), ' ') . ', ';
		$address = rtrim($address . \helper::getValue($r_address, 'County', ''), ', ');

		return $address;
	}

	private function buildOrder($data, $options = [])
	{
		if ($data == false) {
			return [];
		}

		$order = new \stdClass();
		$order->number = \helper::getValue($data, 'AmazonOrderId', '');
		$order->tracking_number = '';
		$order->billing_address = '';
		if (isset($data->billing)) {
			$order->billing_address = $this->address($data->billing);
		}
		$order->shipping_address = '';
		if (isset($data['ShippingAddress'])) {
			$order->shipping_address = $this->address($data['ShippingAddress']);
		}
		$order->ordered = '';
		if (isset($data['PurchaseDate'])) {
			$order->ordered = Carbon::createFromTimestampUTC(strtotime($data['PurchaseDate']));
		}
		$order->total = '0';
		if (isset($data['OrderTotal'])) {
			$order->total = \helper::getValue($data, 'OrderTotal.Amount', '');
		}
		$order->orders = '0';
		$order->ecommerce = 'amazon';
		$order->name = \helper::getValue($data, 'BuyerName', '');
		$fullname = \helper::splitFullname($order->name);
		$order->first_name = $fullname['first_name'];
		$order->last_name = $fullname['last_name'];
		$order->phone = \helper::getValue($data, 'ShippingAddress.Phone', '');
		$order->email = \helper::getValue($data, 'BuyerEmail', '');

		$orderItems = $this->getOrderItems($order->number);
		if (count($orderItems) > 0) {
			$order->items = $orderItems;
		} else {
			$order->items = [];
		}

		$order->status = $data['OrderStatus'];
		return $order;
	}

	public function updateProductQtyFeed($options)
	{
		$amz = new \Properos\AmazonMws\AmazonFeed($this->getSettings()); //store name matches the array key in the config file
		$amz->setFeedType('_POST_INVENTORY_AVAILABILITY_DATA_');
		$data = [
			'sku' => $options['sku'],
			'qty' => $options['qty']
		];
		$amz->setFeedContent(Helper::create_xml_feed($data));
		$amz->submitFeed();
		return $amz->getResponse();
	}

	public function updateProductQtyFeedResult($options)
	{
		$amz = new \Properos\AmazonMws\AmazonFeedResult($this->getSettings()); //store name matches the array key in the config file
		$amz->setFeedId($options['id']);
		$amz->fetchFeedResult();
		return $amz->saveFeed(__DIR__.'\FeedsResults\\'.$options['id'] .'.xml');
	}
}