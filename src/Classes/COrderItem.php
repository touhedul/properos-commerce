<?php

namespace Properos\Commerce\Classes;

use Illuminate\Http\Request;
use Properos\Commerce\Models\Order;
use Properos\Commerce\Models\OrderItem;
use Properos\Base\Classes\Base;

class COrderItem extends Base
{
	function __construct()
	{
		parent::__construct(OrderItem::class, 'OrderItem');
		$this->order = new Order();
		$this->orderItem = new OrderItem();
	}

	public function store(array $data)
	{
		if (count($data) > 0) {
			if (array_key_exists('order_id', $data)) {
				$order = $this->order->with('orderItems')->find($data['order_id']);
				if ($order) {
					$order_item = new OrderItem();
					$order_item->name = $data['name'];
					$order_item->description = $data['description'];
					$order_item->price = $data['price'];
					$order_item->item_id = $data['item_id'];
					$order_item->qty = $data['qty'];
					$order_item->sku = $data['sku'];

					$order->total_amount = $order->total_amount + ($data['price'] * $data['qty']);
					$order->orderItems()->save($order_item);
					$order->save();
				}
			}
		}
		return $order_item;
	}
}