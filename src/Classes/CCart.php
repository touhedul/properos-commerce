<?php

namespace Properos\Commerce\Classes;

use Illuminate\Http\Request;
use Properos\Commerce\Models\Item;
use Properos\Commerce\Models\CartItem;
use Properos\Users\Models\User;
use Properos\Users\Models\UserAddress;
use Illuminate\Support\Facades\Session;
use Properos\Base\Exceptions\ApiException;

//use App\Classes\Integrations\Shipping\Common\IShippingUPS;
use Properos\Commerce\Classes\Integrations\Shipping\UPS\ShippingUPS;
use Illuminate\Support\Facades\Response;
use Properos\Commerce\Models\Order;
use Properos\Base\Classes\Helper;
use Illuminate\Support\Str;
use Properos\Commerce\Models\StoreLocation;
use Properos\Commerce\Models\Tax;

class CCart
{
	private $item;
	private $cartItem;
	private $shipping_ups;

	function __construct()
	{
		$this->item = new Item();
		$this->cartItem = new cartItem();
	}

	public function set($item_id, $qty, $data = null)
	{
		if ($item_id > 0) {
			$item = $this->item->find($item_id);
			
			if(isset($item->variants) && count($item->variants)>0){
				if($data != null){
					$count = 0;
					foreach($item->variants as $variant){
						foreach($data as $key => $v){
							$str_variants = explode(',',$variant[$key]);
							if(isset($variant[$key])){
								foreach($str_variants as $str){
									if($str == $v){
										$count++;
									}
								}
							}
	
						}
						if($count == count($data)){
							continue;
						}else{
							$count = 0;
						}
					}
					if($count == 0){
						throw new ApiException('Variant is not available', '003',null);
					}
				}else{
					$count = 0;
					foreach($item->variants as $variant){
						foreach($variant as $key => $v){
							if($count == 0){
								$data[$key] = explode(",",$v)[0];
							}
						}
						$count ++;
					}
				}
			}
			
			if ($item) {
				if ($item->total_qty > 0 && $item->active > 0) {
					if (\Auth::check()) {
						if ($qty > 0 && $qty <= $item->total_qty) {
						//LOGGED USER
							$cartItems = $this->cartItem->where('user_id', \Auth::user()->id)->where('item_id', $item->id)->get();
							$item_exist = false;
							foreach($cartItems as $cartItem){
								if($cartItem->options == null || count(array_diff($cartItem->options, $data)) == 0){
									if (((int)$qty + $cartItem->qty) <= $item->total_qty) {
										$cartItem->qty += $qty;
										$cartItem->save();
										$item_exist = true;
									} else {
										throw new ApiException('Product quantity must be less or equal than ' . $item->total_qty, "003");
									}
								}
							}
							if(!$item_exist){
								$cartItem = new CartItem();
								$cartItem->item_id = $item_id;
								$cartItem->qty = $qty;
								$cartItem->user_id = \Auth::user()->id;
								$cartItem->options = $data;
								$cartItem->save();
							}
							// if ($cartItem && count(array_diff($cartItem->options, $data)) == 0) {
							// 	if (((int)$qty + $cartItem->qty) <= $item->total_qty) {
							// 		$cartItem->qty += $qty;
							// 		$cartItem->save();
							// 	} else {
							// 		throw new ApiException('Product quantity must be less or equal than ' . $item->total_qty, "003");
							// 	}
							// } else {
							// 	$cartItem = new CartItem();
							// 	$cartItem->item_id = $item_id;
							// 	$cartItem->qty = $qty;
							// 	$cartItem->user_id = \Auth::user()->id;
							// 	$cartItem->options = $data;
							// 	$cartItem->save();
							// }

							if(!$this->cartItem->where('user_id', \Auth::user()->id)->first()){
								Order::where([['user_id', \Auth::user()->id],['status','cart']])->delete();
							}

							$check_order = Order::where([['user_id', \Auth::user()->id],['status','cart']])->first();
							if(!$check_order){
								$order = new Order();

								$order->user_id = \Auth::user()->id;
								$order->customer_name = \Auth::user()->firstname . ' ' . \Auth::user()->lastname;
								$order->customer_email = \Auth::user()->email;

								$order->shipping_status = 'pending';
								$order->status = 'cart';
								$order->shipping_urgency = 'normal';
								$order->order_number = 'unknown'; 
								$order->origin = 'store';
								$address = UserAddress::where([['user_id', \Auth::user()->id],['default',1]])->first();
								if($address){
									$store = StoreLocation::whereRaw('LOWER(country) like ?', [$address->country])
															->whereRaw('LOWER(state) like ?', [$address->state])
															->get();
									// $store = StoreLocation::where([['country',$address->country],['state', $address->state],['city',$address->city]])
									// 				->union(StoreLocation::where([['country',$address->country],['state', $address->state]]))
									// 				->union(StoreLocation::where([['country',$address->country]]))
									// 				->get();
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
									$order->shipping_address1 = $address->address1;
									$order->shipping_address2 = $address->address2;
									$order->shipping_city = $address->city;
									$order->shipping_state = $address->state;
									$order->shipping_zip = $address->zip;
									$order->shipping_country = $address->country;
								}

								$order->save();
								
								$order->order_number = Helper::base2base(1000 * $order->id, 10, 36) . strtoupper(Str::random(4));
								$order->token = Helper::gen_invoice_token($order->id);
								$order->save();

								$cartItem->order_id = $order->id;
								$cartItem->save();
							}else{
								$cartItem->order_id = $check_order->id;
								$cartItem->save();
							}
						} else {
							throw new ApiException('Product quantity must be less or equal than stock availability ' . $item->total_qty, "003");
						}

					} else {
						//GUEST USER
						$sessionCart = Session::has('cart') ? Session::get('cart') : null;
						if ($sessionCart) {
							$current_items = $sessionCart['items'];
							$item_exist = false;
							foreach($current_items as $key => $it){
								if($it['item']->id == $item->id && ($it['options'] == null || count(array_diff($it['options'], $data)) == 0)){
									$item_exist = true;
									$current_items[$key]['qty'] += $qty;
									$current_items[$key]['options'] = $data;
								}
							}
							if(!$item_exist){
								$current_items[] = [
									'qty' => $qty,
									'item' => $item,
									'options' => $data
								];
							}
							// if (isset($current_items[$item->id])) {
							// 	$current_items[$item->id]['qty'] += $qty;
							// 	$current_items[$item->id]['options'] = $data;
							// } else {
								
							// }
							Session::put('cart.items', $current_items);
						} else {
							$current_items = [];
							$current_items[$item->id] = [
								'qty' => $qty,
								'item' => $item,
								'options' => $data
							];
							Session::put('cart.items', $current_items);
						}

						$order = Session::has('order_id') ? Session::get('order_id') : null;

						if(!$order){
							$order = new Order();

							$order->shipping_status = 'pending';
							$order->status = 'cart';
							$order->shipping_urgency = 'normal';
							$order->order_number = 'unknown';
							$order->origin = 'store';
							$order->save();
							
							$order->order_number = Helper::base2base(1000 * $order->id, 10, 36) . strtoupper(Str::random(4));
							$order->token = Helper::gen_invoice_token($order->id);
							$order->save();

							Session::put('order_id', $order->id);
						}
					}
				} else {
					//OUT OF STOCK
				}
			} else {
				//ITEM NOT FOUND
			}
		}
		return $this->get();
	}

	public function getCount()
	{
		$result = array("status" => 1, "data" => ["count" => 0]);;
		if (\Auth::check()) {
			$cart_items = \DB::select("SELECT qty FROM cart_items WHERE user_id='" . \Auth::user()->id . "'");
			foreach ($cart_items as $cart_item) {
				$result["data"]["count"] += $cart_item->qty;
			}
		} else {
			$cart = session()->get('cart', []);
			if (!is_array($cart)) {
				$cart = [];
				session()->put('cart', []);
			}
			foreach ($cart as $cart_item) {
				$result["data"]["count"] += $cart_item["qty"];
			}
		}
		return $result;
	}

	public function get()
	{
		$result = array("status" => 1, "data" => ["count" => 0, "cart" => []]);
		if (\Auth::check()) {
			$cart = [];
			$cart_items = \DB::select("SELECT items.id, items.price, items.msrp, items.`name`, items.`description`, items.`taxable`, items.`tax_percent`, items.`discount_percent`, items.`total_qty`,
										items.`package_type`, items.`weight`,items.`width`,items.`height`,items.`length`, items.`sku`, cart_items.id as 'cart_id',cart_items.options,
										MAX(cart_items.qty) as 'qty', MIN(files.id), ANY_VALUE(files.`thumb_path`) as 'thumb_path'
										FROM items
										INNER JOIN cart_items ON cart_items.item_id = items.id
										LEFT JOIN files ON files.owner_id = items.id AND files.owner_type = 'item' AND files.type = 'image' AND files.deleted_at IS NULL 
										WHERE user_id='" . \Auth::user()->id . "' AND order_id <> 0 GROUP BY cart_items.id");
			$options =[];
			foreach ($cart_items as $cart_item) {
				
				$result["data"]["cart"][] = [
					"id" => $cart_item->id, "price" => $cart_item->price, "msrp" => $cart_item->msrp, "name" => $cart_item->name,
					"description" => $cart_item->description, "discount_percent" => $cart_item->discount_percent, "total_qty" => $cart_item->total_qty,
					"qty" => $cart_item->qty, "thumb_path" => $cart_item->thumb_path,
					"package_type" => $cart_item->package_type, "weight" => $cart_item->weight, "width" => $cart_item->width, "height" => $cart_item->height,
					"length" => $cart_item->length, 
					"taxable" => $cart_item->taxable, "tax_percent" => $cart_item->tax_percent, "sku" => $cart_item->sku, 
					"options" => json_decode($cart_item->options, true)
				]; 
				// if(isset($cart_item->options) && $cart_item->options != null){
				// 	$options[] = json_decode($cart_item->options, true);
				// }
				// $result["data"]["cart"][$cart_item->id]['options'] =$options;
				$result["data"]["count"] += $cart_item->qty;
			}
			$order_id = CartItem::where([['user_id',\Auth::user()->id],['order_id','<>',0]])->first();
			if($order_id){
				$result["data"]["order_id"] = $order_id->order_id;
			}else{
				$result["data"]["order_id"] =0;
			}
			
		} else {
			$cart = session()->get('cart.items', []);
			if (!is_array($cart)) {
				$cart = [];
				session()->put('cart.items', []);
			}
			$order_id = session()->get('order_id', 0);
			if (!$order_id > 0) {
				$order_id = 0;
				session()->put('order_id', []);
			}
			$items = [];
			foreach($cart as $c){
				$items[] = $c['item']->id;
			}
			// $items = array_keys($cart);
			
			if (count($items) > 0) {

				$cart_items = \DB::select("SELECT items.id, items.price, items.msrp, items.`name`, items.`taxable`, items.`tax_percent`, items.`description`,items.`discount_percent`, items.`total_qty`,
				  items.`package_type`, items.`weight`,items.`width`,items.`height`,items.`length`, items.`sku`,
				  MIN(files.id), ANY_VALUE(files.`thumb_path`) as 'thumb_path'
				FROM items
				LEFT JOIN files ON files.owner_id = items.id AND files.owner_type = 'item' AND files.type = 'image' AND files.deleted_at IS NULL 
				WHERE items.id IN (" . implode(",", $items) . ") GROUP BY items.id"); 

				/*$cart_items = \DB::select("SELECT items.id, items.price, items.msrp, items.`name`, items.`description`,items.`discount_percent`, items.`total_qty` , files.`thumb_path`
										   FROM items, files   WHERE items.id IN (" . implode(",", $items) . ") AND files.owner_id = items.id"); */
										  
				foreach ($cart_items as $cart_item) {
					foreach($cart as $v){
						
						if($cart_item->id == $v['item']->id){
							$result["data"]["cart"][] = [
								"id" => $cart_item->id, "price" => $cart_item->price, "msrp" => $cart_item->msrp, "name" => $cart_item->name,
								"description" => $cart_item->description, "discount_percent" => $cart_item->discount_percent, "total_qty" => $cart_item->total_qty,
								"qty" => $v["qty"], "thumb_path" => $cart_item->thumb_path,
								"package_type" => $cart_item->package_type, "weight" => $cart_item->weight, "width" => $cart_item->width, "height" => $cart_item->height,
								"length" => $cart_item->length, 
								"taxable" => $cart_item->taxable, "tax_percent" => $cart_item->tax_percent, "sku" => $cart_item->sku,
								"options" => isset($v['options']) ?  $v['options'] : null
							]; 
							$result["data"]["count"] += $v["qty"];
						}
					}
					/* $result["data"]["count"] += $cart[$cart_item->id]["qty"]; */
				}
				$result["data"]["order_id"] = $order_id;
			}
		}
		
		return $result;
	}
/* 
	public function set($item, $qty)
	{
		$cart = session()->get('cart.items',[]);
		$cart[$item]=array("qty"=>$qty);
		session()->put('cart', $cart);
		if (\Auth::check()) {
			\App\Models\CartItem::updateOrCreate(["user_id"=>\Auth::user()->id,"item_id"=>$item],["qty"=>$qty]);
		}
		return $this->get();
	} */

	public function changeQty($item, $qty)
	{
		if (\Auth::check()) {
			$cartItem = $this->cartItem->where('user_id', \Auth::user()->id)->where('item_id', $item)->with('item')->first();
			if ($cartItem) {
				if ((int)$qty <= $cartItem->item->total_qty) {
					$cartItem->qty = $qty;
					$cartItem->save();
				} else {
					return Response::json(array(
						'status' => '0',
						'code' => 003,
						'message' => 'Product quantity must be less or equal than ' . $cartItem->item->total_qty,
					), 500);    
				}
			}
			/* \App\Models\CartItem::updateOrCreate(["user_id" => \Auth::user()->id, "item_id" => $item], ["qty" => $cart[$item]["qty"]]); */
		} else {
			$cart = session()->get('cart', []);
			if (count($cart['items']) > 0) {
				foreach($cart['items'] as $key => $v){
					if($v['item']->id == $item){
						$cart['items'][$key]["qty"] = $qty;
					}
				}
				// if (isset($cart['items'][$item]))
				// 	$cart['items'][$item]["qty"] = $qty;
				session()->pull('cart', []);
				session()->put('cart', $cart);
			}
		}
		return $this->get();
	}

	public function decrease($item, $qty)
	{
		if (\Auth::check()) {
			$cartItem = $this->cartItem->where('item_id', $item)->first();
			if ($cartItem) {
				$cartItem->qty -= $qty;
				$cartItem->save();
			}
			/* \App\Models\CartItem::updateOrCreate(["user_id" => \Auth::user()->id, "item_id" => $item], ["qty" => $cart[$item]["qty"]]); */
		} else {
			$cart = session()->get('cart', []);
			if ((isset($cart[$item])) && ($cart[$item]["qty"] > 1)) {
				$cart[$item]["qty"]--;
				session()->put('cart', $cart);

			}
		}
		return $this->get();
	}

	public function remove($item)
	{
		if (\Auth::check()) {
			$cartItem = $this->cartItem->where('item_id', $item)->first();
			if(isset($cartItem->order_id))
				$order_id = $cartItem->order_id;
			else
				$order_id  = 0 ;
			if ($cartItem->delete()) {
				$cItem = $this->cartItem->where('user_id', \Auth::user()->id)->first();
				
				if(!$cItem){
					Order::where('id',$order_id)->forceDelete();
				}
				return $this->get();
			} else {
				return 'Error removing item from cart';
			}
			
		} else {
			$cart = session()->get('cart', []);
			if (count($cart['items']) > 0) {
				$current_items = $cart['items'];
				foreach($current_items as $key => $v){
					if($v['item']->id == $item){
						unset($current_items[$key]);
						session()->put('cart.items', $current_items);
					}
				}
				// if (isset($current_items[$item])) {
				// 	unset($current_items[$item]);
				// 	session()->put('cart.items', $current_items);
				// }
				if(count($cart['items']) == 1){
					$orderId = session()->get('order_id',0);
					Order::where('id',$orderId)->delete();
					session()->put('order_id', 0);
				}
			}
		}
		return $this->get(); 

	/* 	$cart = session()->get('cart', []);
		if (isset($cart[$item])) unset($cart[$item]);
		session()->put('cart', $cart);
		if (\Auth::check()) {
			\App\Models\CartItem::where("user_id", \Auth::user()->id)->where("item_id", $item)->delete();
		}
		return $this->get(); */
	}

	public function removeAll()
	{
		$cart = [];
		session()->put('cart', $cart);
		if (\Auth::check()) {
			$order_id = CartItem::where("user_id", \Auth::user()->id)->first();
			if($order_id){
				$orderId = $order_id->order_id;
				CartItem::where("user_id", \Auth::user()->id)->delete();
			}
		}else{
			$orderId = session()->get('order_id',0);
			session()->put('order_id', 0);
		}
		if(isset($orderId)){
			Order::where([['id', $orderId],['status','cart']])->forceDelete();
		}
		return $this->get();
	}

	public function merge()
	{
		if (\Auth::check()) {
			$cart = session()->get('cart', []);
			$cart_items = \DB::select("SELECT cart_items.* FROM cart_items WHERE user_id='" . \Auth::user()->id . "'");
			foreach ($cart_items as $cart_item) {
				if (!isset($cart[$cart_item->id])) {
					$cart[$cart_item->id] = ["qty" => $cart_item->qty];
				}
			}
			session()->put('cart', $cart);
			return $this->save();
		} else
			return $this->get();
	}

	public function save()
	{
		if (\Auth::check()) {
			$cart = session()->get('cart', []);
			foreach ($cart as $key => $val) {
				CartItem::updateOrCreate(["user_id" => \Auth::user()->id, "item_id" => $key], ["qty" => $val["qty"]]);
			}
		}
		return $this->get();
	}

	public function getAvailableShippingMethods($payload_info)
	{
		$data = [];
		if (isset($payload_info['carrier'])) {
			switch ($payload_info['carrier']) {
				case 'ups':
					if (\Auth::check()) {
						$user_address = UserAddress::where('user_id', \Auth::user()->id)->get();
						if (count($user_address) > 0) {
							foreach ($user_address as $key => $address) {
								if ($address->default == 1) {
									$default_address = $address;
								}
							}
							if (isset($default_address) && !array_key_exists('address', $payload_info)) {
								$data['customer_address'] = $default_address->address1 . ' ' . $default_address->address2;
								$data['customer_city'] = $default_address->city;
								$data['customer_state'] = $default_address->state;
								$data['customer_zip'] = $default_address->zip;
								$data['customer_country'] = $default_address->country;
							} else {
								$data['customer_address'] = $payload_info['address']['address'];
								$data['customer_city'] = $payload_info['address']['city'];
								$data['customer_state'] = $payload_info['address']['state'];
								$data['customer_zip'] = $payload_info['address']['zip'];
								$data['customer_country'] = $payload_info['address']['country'];
							}
						} else if (isset($payload_info['address'])) {
							$data['customer_address'] = $payload_info['address']['address'];
							$data['customer_city'] = $payload_info['address']['city'];
							$data['customer_state'] = $payload_info['address']['state'];
							$data['customer_zip'] = $payload_info['address']['zip'];
							$data['customer_country'] = $payload_info['address']['country'];
						}
					} else {
						if (isset($payload_info['address'])) {
							$data['customer_address'] = $payload_info['address']['address'];
							$data['customer_city'] = $payload_info['address']['city'];
							$data['customer_state'] = $payload_info['address']['state'];
							$data['customer_zip'] = $payload_info['address']['zip'];
							$data['customer_country'] = $payload_info['address']['country'];
						}
					}
					if (count($data) > 0) {
						if (config('shipping.ups.shipper_address')) {
							$cart_items = $this->get()['data']['cart'];
							if (count($cart_items) > 0) {
								$length = reset($cart_items)['length'];
								$width = 0.0;
								$height = reset($cart_items)['height'];
								$weight = 0.0;

								foreach ($cart_items as $key => $cart_item) {
									$width += $cart_item['width'] * $cart_item['qty'];
									$weight += $cart_item['weight'] * $cart_item['qty'];
									if ($cart_item['length'] > $length) {
										$length = $cart_item['length'];
									}
									if ($cart_item['height'] > $height) {
										$height = $cart_item['height'];
									}
								}
								$data['package_length'] = (string)$length;
								$data['package_width'] = (string)$width;
								$data['package_height'] = (string)$height;
								$data['package_weight'] = (string)$weight;

								$data['shipper_address'] = config('shipping.ups.shipper_address.address');
								$data['shipper_city'] = config('shipping.ups.shipper_address.city');
								$data['shipper_zip'] = config('shipping.ups.shipper_address.zip');
								$data['shipper_state'] = config('shipping.ups.shipper_address.state');
								$data['shipper_country'] = config('shipping.ups.shipper_address.country');
							}

							return (new ShippingUPS())->getShippingRates($data);
						}
					}
					break;

				default:
					# code...
					break;
			}
		}
	}
}