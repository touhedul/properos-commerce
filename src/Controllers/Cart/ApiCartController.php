<?php

namespace Properos\Commerce\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Properos\Commerce\Classes\CCart;
use Properos\Base\Classes\Api;
use Properos\Base\Exceptions\ApiException;
use Properos\Commerce\Models\Discount;
use Properos\Commerce\Classes\CDiscount;
use Properos\Commerce\Models\Order;
use Properos\Commerce\Classes\COrder;
use Properos\Commerce\Models\Item;
use Properos\Commerce\Models\StoreLocation;
use Properos\Commerce\Models\Tax;


class ApiCartController extends Controller
{
	private $cart;
	private $cOrder;
	private $order;
	
	function __construct(CCart $cart,COrder $cOrder, Order $order)
	{
		$this->cart = $cart;
		$this->cOrder = $cOrder;
		$this->order = $order;
	}

	public function get()
	{
		return json_encode($this->cart->get());
	}

	public function getCount()
	{
		return $this->cart->getCount();
	}

	public function set($item_id, $qty, Request $request)
	{
		$data = $request->all();
		return json_encode($this->cart->set($item_id, $qty, $data));
	}

	public function setItem($item_id, $qty)
	{
		return json_encode($this->cart->set($item_id, $qty));
	}

	//NEW
	public function addItem($item_id, $qty)
	{
		$cartItem = $this->cart->addItem($item_id, $qty);
		if ($cartItem) {
			return Api::success('Item added successfully', $cartItem);
		}
		return Api::error('Error adding item to cart', []);
	}

	public function changeQty($item_id, $qty = null)
	{
		if (!$qty) {
			$qty = 1;
		}

		$result = $this->cart->changeQty($item_id, $qty);
		
		if(isset($result['data']['order_id']) && $result['data']['order_id'] > 0){
			$order = Order::where('id',$result['data']['order_id'])->first();
			
			$cal = $this->cOrder->calculateSubtotalTax($result['data']['cart'], $order);
			if($order){
				$order->total_amount = $cal['amount'] + $cal['tax'] + $order->shipping_cost_estimated - $order->discount_amount;
				$order->total_tax_amount = $cal['tax'];
				$order->subtotal = $cal['amount'];
				$order->tax_base = $cal['tax_base'];
				$order->save();
				if($order->discount_code != '' && $order->discount_amount > 0){
					$discount = Discount::where('code',$order->discount_code)->first();
					$cDiscount = new CDiscount();
					try{
						$items = Item::whereHas('item_cart', function($query) use($order){
							$query->where('order_id', $order->id);
						})->with('item_cart')->get()->toArray();
						
						foreach($items as $key=>$item){
							$items[$key]['item_id'] = $item['id'];
							if($item['item_cart'] && count($item['item_cart'])>0){
								foreach($item['item_cart'] as $cart){
									if($cart['order_id'] == $order->id){
										$items[$key]['qty'] = $item['item_cart'][0]['qty'];
									}
								}
							}
						}
						$a_order = $order->toArray();
						$a_order['order_items'] = $items;
						$discount_info = $cDiscount->checkDiscount($discount->toArray(), $a_order);

						$discount_amount = 0.00;
						$discount_taxable = 0.00;
						$subtotal = 0;
						if(isset($a_order['order_items'])){
							foreach($a_order['order_items'] as $item){
								if($item['discount_percent'] > 0){ 
									$subtotal += ($item['price'] - ($item['price']*$item['discount_percent']/100)) * $item['qty'];
								}else{
									$subtotal += $item['price'] * $item['qty'];
								}
							}
						}
						switch($discount_info['discount_type']){
							case 'percentage':
								if(isset($discount_info['discount_items'])){
									$discount_taxable = 0;
									foreach($discount_info['discount_items'] as $item){
										if($item[2] == 0){
											$discount_amount += $discount_amount + (($item[1] * $discount_info['discount_value']/100)*$item[0]);
										}else{
											$discount_taxable += $discount_taxable + (($item[1] * $discount_info['discount_value']/100)*$item[0]);
										}
									}
									$discount_amount += $discount_taxable;
								}else{
									$discount_amount +=  $discount_info['discount_notaxable'] * $discount_info['discount_value']/100;
									$discount_taxable = $discount_info['discount_taxable'] * $discount_info['discount_value']/100;
									$discount_amount +=  $discount_taxable;
								}
								break;
							case 'fixed_amount':
								$discount_percent =  $discount_info['discount_value'] *100 / ($discount_info['discount_notaxable'] +  $discount_info['discount_taxable']);
								$discount_amount +=  $discount_info['discount_notaxable'] * $discount_percent/100;
								$discount_taxable = $discount_info['discount_taxable'] * $discount_percent/100;
								$discount_amount +=  $discount_taxable;
								if(isset($discount_info['discount_items'])){
									$amount = 0;
									foreach($discount_info['discount_items'] as $item){
										$amount += $item[1]*$item[0];
									}
									if($amount < $discount_info['discount_value']){
										$discount_amount = 0;
										$discount_percent =  $amount *100 / ($discount_info['discount_notaxable'] +  $discount_info['discount_taxable']);
										$discount_amount +=  $discount_info['discount_notaxable'] * $discount_percent/100;
										$discount_taxable = $discount_info['discount_taxable'] * $discount_percent/100;
										$discount_amount +=  $discount_taxable;
										// $discount_amount = $amount;
									}
								}
								// $discount_amount =  $result['discount_value'];
								break;
							case 'free_shipping':
								$discount_amount = $order['shipping_cost_estimated'];
								break;
							case 'buy_x_get_y':
								// $discount_amount +=  $discount_info['discount_notaxable'] * $discount_info['discount_value']/100;
								// $discount_taxable = $discount_info['discount_taxable'] * $discount_info['discount_value']/100;
								// $discount_amount +=  $discount_taxable;
								$discount_taxable = 0;
								if(isset($discount_info['discount_items'])){
									foreach($discount_info['discount_items'] as $item){
										if($item[2] == 0){
											$discount_amount += $discount_amount + (($item[1] * $discount_info['discount_value']/100)*$item[0]);
										}else{
											$discount_taxable += $discount_taxable + (($item[1] * $discount_info['discount_value']/100)*$item[0]);
										}
									}
									$discount_amount += $discount_taxable;
								}
								break;
						}
						$order->tax_base = $discount_info['discount_taxable'] - $discount_taxable;
						$order->total_tax_amount = ($order->tax > 0 && $discount_info['discount_taxable'] > 0) ? ($discount_info['discount_taxable'] - $discount_taxable)* $order->tax/100 : 0.00;
						$order->subtotal = $subtotal;
						$order->discount_code = $discount->code;
						$order->discount_amount = $discount_amount;
						if($discount_amount >= 0){
							$order->total_amount = $order->total_amount - $discount_amount;
						}else{
							$order->total_amount = $order->total_amount + $discount_amount;
						}
						$order->total_amount += $order->total_tax_amount;
						$order->shipping_city = isset($order['shipping_city']) ? $order['shipping_city'] : '';
						$order->shipping_state = isset($order['shipping_state']) ? $order['shipping_state'] : '';
						$order->shipping_country = isset($order['shipping_country']) ? $order['shipping_country'] : '';
						$order->save();
						// switch($discount_info['discount_type']){
						// 	case 'percentage':
						// 		$discount_amount =  $order->total_amount * $discount_info['discount_value']*1 /100;
						// 		break;
						// 	case 'fixed_amount':
						// 		$discount_amount =  $discount_info['discount_value'];
						// 		break;
						// 	case 'free_shipping':
						// 		$discount_amount = $order->shipping_cost_estimated;
						// 		break;
						// 	case 'buy_x_get_y':
						// 		if($discount_info['discount_value']*1 <100 && $discount_info['discount_items']){
						// 			foreach($discount_info['discount_items'] as $item){
						// 				$discount_amount = $discount_amount + (($item[1] * $discount_info['discount_value']/100)*$item[0]);
						// 			}
						// 		}else{
						// 			foreach($discount_info['discount_items'] as $item){
						// 				$discount_amount = $discount_amount*1 + ($item[1]* $discount_info['discount_value']/100) * $item[0];
						// 			}
						// 		}
						// 		break;
						// }
						// $order->discount_code = $discount->code;
						// $order->discount_amount = $discount_amount;
						// $order->total_amount = $order->total_amount - $discount_amount;
						// $order->save();
					}catch (ApiException $e){
						$order->discount_code = null;
						$order->discount_amount = 0.00;
						$order->save();
						$result['data']['order'] = $order;
						return Api::error('021', $e->message(), $result['data']);
					}
				}
				
				$result['data']['order'] = $order;
			}
		}
		return json_encode($result);
	}
	public function increase($item_id)
	{
		return json_encode($this->cart->increase($item_id, $qty));
	}

	public function decrease($item_id, $qty = 1)
	{
		return json_encode($this->cart->decrease($item_id, $qty));
	}

	public function remove($item_id)
	{
		return json_encode($this->cart->remove($item_id));
	}

	public function shippingRates(Request $request, $carrier)
	{
		$order_id = $request->input('order_id');
		$order = Order::where('id', $order_id)->first();
		if(!$order){
			$order = new Order();
			$order->shipping_status = 'pending';
			$order->status = 'cart';
			$order->shipping_urgency = 'normal';
			$order->order_number = 'unknown';
			$order->origin = 'store';
		}
		$store = StoreLocation::whereRaw('LOWER(country) like ?', [$request->input('shipping_country')])
								->whereRaw('LOWER(state) like ?', [$request->input('shipping_state')])
								->get();
								
		if($store && isset($store[0])){
			$order->tax = $store[0]->tax;
		}else{
			$rest = Tax::where([['country', null],['state',null],['city', null]])->first();
			if($rest){
				$order->tax = $rest->tax;
			}else{
				$order->tax = 0;
			}
		}
		$order->shipping_address1 = $request->input('shipping_address') . ' ' . $request->input('shipping_address_cont');
		$order->shipping_city = $request->input('shipping_city');
		$order->shipping_state = $request->input('shipping_state');
		$order->shipping_zip = $request->input('shipping_zip');
		$order->shipping_country = $request->input('shipping_country');

		$order->total_amount = $order->total_amount - $order->total_tax_amount;
		$order->total_tax_amount = $order->tax_base * $order->tax /100;
		$order->total_amount = $order->total_amount + $order->total_tax_amount;

		$order->save();
		switch ($carrier) {
			case 'ups':
				$payload_info['carrier'] = 'ups';
				$payload_info['address']['address'] = $request->input('shipping_address') . ' ' . $request->input('shipping_address_cont');
				$payload_info['address']['city'] = $request->input('shipping_city');
				$payload_info['address']['zip'] = $request->input('shipping_zip');
				$payload_info['address']['state'] = $request->input('shipping_state');
				$payload_info['address']['country'] = $request->input('shipping_country');
				$available_methods = $this->cart->getAvailableShippingMethods($payload_info);
				if (isset($available_methods['methods'])) {
					$available_shipping_methods = $available_methods['methods'];
					return Api::success('Available shipping methods', ['available_shipping_methods' => $available_shipping_methods, 'order' => $order]);
				} else if (isset($available_methods['error_code'])) {
					throw new ApiException($available_methods['error_msg'], $available_methods['error_code']);
				}else if(count($available_methods) == 0){
					throw new ApiException('No shipping information available at this time.', '002', $order);
				}
				break;
			default:
				# code...
				break;
		}
	}
}