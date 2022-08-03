<?php

namespace Properos\Commerce\Classes;

use Illuminate\Http\Request;
use Properos\Commerce\Models\Order;
use Properos\Commerce\Models\OrderItem;
use Properos\Commerce\Models\Item;
use Properos\Commerce\Models\PaymentMethod;
use Properos\Commerce\Models\ShippingMethod;
use Illuminate\Support\Facades\Auth;
use Properos\Commerce\Classes\Payment\CPayment;
use Illuminate\Support\Facades\DB;
use Properos\Commerce\Classes\CAccount;
use Properos\Commerce\Classes\CCart;
use Properos\Commerce\Models\CustomerProfile;
use Properos\Commerce\Models\CustomerPaymentProfile;
use Illuminate\Support\Facades\Mail;
use Properos\Commerce\Mail\OrderStatusEmail;
use Properos\Commerce\Mail\OrderInvoiceEmail;
use Properos\Commerce\Mail\OrderQuoteEmail;
use Properos\Commerce\ViewModel\OrderCreatedVM;
use Properos\Commerce\Events\ProductQtyChanged;
use Properos\Base\Exceptions\ApiException;
use Properos\Commerce\Classes\PaymentProcessors\PaypalClass;
use Properos\Base\Classes\Base;
use Properos\Base\Classes\Helper;
//use App\Classes\Integrations\Shipping\Common\IShippingUPS;
use Properos\Commerce\Classes\Integrations\Shipping\UPS\ShippingUPS;
use Properos\Commerce\Classes\CFile;
use Properos\Commerce\Models\File;
use Illuminate\Support\Str;
use Properos\Users\Models\User;
use Properos\Commerce\Models\Setting;
use Properos\Commerce\Models\Discount;
use Properos\Commerce\Jobs\calculateAffiliateGain;
use Properos\Commerce\Models\Subscription;

class COrder extends Base
{
	private $order;
	private $item;
	private $paymentMethod;
	private $shippingMethod;
	private $cAccount;
	private $cItem;
	private $cCart;
	private $shipping_ups;

	function __construct(
		CAccount $cAccount,
		PaymentMethod $paymentMethod,
		ShippingMethod $shippingMethod,
		Item $item,
		Order $order,
		CItem $cItem,
		CCart $cCart
	) {
		parent::__construct(Order::class, 'Order');
		$this->order = $order;
		$this->item = $item;
		$this->paymentMethod = $paymentMethod;
		$this->shippingMethod = $shippingMethod;
		$this->cAccount = $cAccount;
		$this->cItem = $cItem;
		$this->cCart = $cCart;
	}

	public function store(array $data)
	{
		if (count($data) > 0) {
			if(isset($data['id']) && $data['id'] > 0){
				$order = Order::where('id', $data['id'])->first();
				if(!$order){
					throw new ApiException("Sorry, order can't be created", "005");
				}
			}else{
				$order = new Order();
			}
			$order->user_id = $data['user_id'];
			$order->creator_id = isset($data['creator_id']) ? $data['creator_id'] : \Auth::user()->id;
			if ($data['user_id'] > 0) {
				$user = User::where('id', $data['user_id'])->first();
				if($user){
					$order->customer_name = $user->firstname.' '.$user->lastname;
					$order->customer_email = $user->email;
					$order->customer_phone = $user->phone;
					$order->customer_company = $user->company;
				}
			}
			$order->total_amount = $data['total_amount'];

			if (array_key_exists('tax', $data)) {
				$order->tax = $data['tax'];
			}else{
				$order->tax = 0.00;
			}
			if (array_key_exists('address_id', $data)) {
				$order->address_id = $data['address_id'];
			}
			if (array_key_exists('subscription_id', $data)) {
				$order->subscription_id = $data['subscription_id'];
			}

			if (array_key_exists('shipping_address_1', $data)) {
				$order->shipping_address1 = $data['shipping_address_1'];
			}
			if (array_key_exists('shipping_address_2', $data)) {
				$order->shipping_address2 = $data['shipping_address_2'];
			}
			if (array_key_exists('shipping_city', $data)) {
				$order->shipping_city = $data['shipping_city'];
			}
			if (array_key_exists('shipping_zip', $data)) {
				$order->shipping_zip = $data['shipping_zip'];
			}
			if (array_key_exists('shipping_state', $data)) {
				$order->shipping_state = $data['shipping_state'];
			}
			if (array_key_exists('shipping_country', $data)) {
				$order->shipping_country = $data['shipping_country'];
			}
			if (isset($data['status'])) {
				$order->status = $data['status'];
				if ($data['status'] == 'paid') {
					$order->paid_amount = $order->total_amount;
				}
			} else {
				$order->status = 'pending';
			}
			if (isset($data['shipping_status'])) {
				$order->shipping_status = $data['shipping_status'];
			} else {
				$order->shipping_status = 'pending';
			}

			$order->shipping_urgency = 'normal';

			if (array_key_exists('shipping_method', $data)) {
				$order->shipping_method = $data['shipping_method'];
			}
			if (array_key_exists('shipping_method_code', $data)) {
				$order->shipping_method_code = $data['shipping_method_code'];
			}
			if (array_key_exists('shipping_cost', $data)) {
				$order->shipping_cost = $data['shipping_cost'];
			} else {
				$order->shipping_cost = 0.00;
			}
			if (array_key_exists('shipping_cost_estimated', $data)) {
				$order->shipping_cost_estimated = $data['shipping_cost_estimated'];
			} else {
				$order->shipping_cost_estimated = 0.00;
			}
			if (array_key_exists('shipping_tracking', $data)) {
				$order->shipping_tracking = $data['shipping_tracking'];
			}
			if (array_key_exists('origin', $data)) {
				$order->origin = $data['origin'];
			} else {
				$order->origin = 'store';
			}
			if (array_key_exists('notes', $data)) {
				$order->notes = $data['notes'];
			}
			if (array_key_exists('partial', $data)) {
				$order->partial = $data['partial'];
				if ($order->partial == 1) {
					if (array_key_exists('min_payment', $data)) {
						$order->min_payment = $data['min_payment'];
					}
				} else {
					$order->min_payment = null;
				}

			}

			if (array_key_exists('customer_company', $data) && $data['customer_company'] != '') {
				$order->customer_company = $data['customer_company'];
			}

			if (array_key_exists('total_tax_amount', $data)) {
				$order->total_tax_amount = $data['total_tax_amount'];
			}
			$order->order_number = 'unknown';
			try {
				DB::transaction(function () use ($order, $data) {
					$order->save();
					if (array_key_exists('order_items', $data)) {
						if (count($data['order_items'] > 0)) {
							$order_items = array_map(function ($element) {
								return (object)$element;
							}, $data['order_items']);
							foreach ($order_items as $key => $order_item) {
								$new_order_item = new OrderItem();
								$new_order_item->order_id = $order->id;
								$new_order_item->item_id = $order_item->item_id;
								$new_order_item->description = $order_item->description;
								$new_order_item->name = $order_item->name;
								$new_order_item->qty = $order_item->qty;
								$new_order_item->sku = isset($order_item->sku) ? $order_item->sku : null ;
								$new_order_item->price = $order_item->price;
								$new_order_item->discount_percent = isset($order_item->discount_percent) ? $order_item->discount_percent : 0.00;
								$new_order_item->taxes = (isset($order_item->taxable)) ? $order_item->taxable : ((isset($order_item->tax)) ? $order_item->tax : 0.00);
								$new_order_item->save();
							}
						}
					}
					$this->decreaseItemQtyFromOrder($order, true);
					$invoicing = Setting::where('key', 'invoicing')->first();
					if ($invoicing) {
						$setting = json_decode($invoicing->data, true);
						if ($setting['order_number_type'] == 'consecutive') {
							$order->order_number = $setting['prefix_order'] . ($order->id) . $setting['suffix_order'];
						} else {
							$order->order_number = Helper::base2base(1000 * $order->id, 10, 36) . strtoupper(Str::random(4));
						}
					} else {
						$order->order_number = Helper::base2base(1000 * $order->id, 10, 36) . strtoupper(Str::random(4));
					}
					$order->token = Helper::gen_invoice_token($order->id);
					
					$total_amount_result = $this->getTotalAmountWithOutTax($order->orderItems);
					$tax_base = $this->getTaxBaseAmount($order->orderItems);
					
					$order->total_amount = $total_amount_result;
					$order->total_tax_amount = $tax_base*$order->tax/100;
					$order->tax_base = $tax_base;
					$discount_amount = 0.00;
					$order->subtotal = $total_amount_result;
					if (array_key_exists('discount_code', $data) && $data['discount_code'] != '') {
						$discount = Discount::where('code', $data['discount_code'])->first();
						
						if($discount){
							$cDiscount = new CDiscount();
							
							$check = $cDiscount->checkDiscount($discount->toArray(), $order->toArray(), false);
							
							if($check != false){
								$order->total_amount = $total_amount_result;
								$discount_amount = 0.00;
								$discount_taxable = 0.00;
								switch($check['discount_type']){
									case 'percentage':
										if(isset($check['discount_items'])){
											$discount_taxable = 0;
											foreach($check['discount_items'] as $item){
												if($item[2] == 0){
													$discount_amount = $discount_amount + (($item[1] * $check['discount_value']/100)*$item[0]);
												}else{
													$discount_taxable = $discount_taxable + (($item[1] * $check['discount_value']/100)*$item[0]);
												}
											}
											$discount_amount += $discount_taxable;
										}else{
											$discount_amount +=  $check['discount_notaxable'] * $check['discount_value']/100;
											$discount_taxable = $check['discount_taxable'] * $check['discount_value']/100;
											$discount_amount +=  $discount_taxable;
										}
										break;
									case 'fixed_amount':
										$discount_percent =  $check['discount_value'] *100 / ($check['discount_notaxable'] +  $check['discount_taxable']);
										$discount_amount +=  $check['discount_notaxable'] * $discount_percent/100;
										$discount_taxable = $check['discount_taxable'] * $discount_percent/100;
										$discount_amount +=  $discount_taxable;
										if(isset($check['discount_items'])){
											$amount = 0;
											foreach($check['discount_items'] as $item){
												$amount += $item[1]*$item[0];
											}
											if($amount < $check['discount_value']){
												$discount_amount = 0;
												$discount_percent =  $amount *100 / ($check['discount_notaxable'] +  $check['discount_taxable']);
												$discount_amount +=  $check['discount_notaxable'] * $discount_percent/100;
												$discount_taxable = $check['discount_taxable'] * $discount_percent/100;
												$discount_amount +=  $discount_taxable;
												// $discount_amount = $amount;
											}
										}
										break;
									case 'free_shipping':
										$discount_amount = $order['shipping_cost_estimated'];
										break;
									case 'buy_x_get_y':
										// $discount_amount +=  $check['discount_notaxable'] * $check['discount_value']/100;
										// $discount_taxable = $check['discount_taxable'] * $check['discount_value']/100;
										// $discount_amount +=  $discount_taxable;
										$discount_taxable = 0;
										if(isset($check['discount_items'])){
											foreach($check['discount_items'] as $item){
												if($item[2] == 0){
													$discount_amount = $discount_amount + (($item[1] * $check['discount_value']/100)*$item[0]);
												}else{
													$discount_taxable = $discount_taxable + (($item[1] * $check['discount_value']/100)*$item[0]);
												}
											}
											$discount_amount += $discount_taxable;
										}
										break;
								}
								$order->tax_base = $check['discount_taxable'] - $discount_taxable;
								$order->total_tax_amount = ($order->tax > 0 && $check['discount_taxable'] > 0) ? ($check['discount_taxable'] - $discount_taxable)* $order->tax/100 : 0.00;
								$order->discount_code = $discount->code;
								$order->discount_amount = number_format($discount_amount,2);
								
								$order->total_amount += $order->total_tax_amount;
								$order->discount_code = $data['discount_code'];
								$discount->usage_count = $discount->usage_count + 1;
								$discount->save();
							}
							if($discount_amount >= 0){
								$order->total_amount = $order->total_amount - $discount_amount + $order->shipping_cost_estimated;
							}else{
								$order->total_amount = $order->total_amount + $discount_amount + $order->shipping_cost_estimated;
							}
							$paymentInfo['amount'] = $order->total_amount;
						}
					}else{
						$order->discount_code = null;
						$order->discount_amount = 0.00;
						$order->total_amount += $order->total_tax_amount + $order->shipping_cost_estimated;
					}
					$order->save();
				});
				return $order;
				// return $this->order->with('orderItems')->get();
			} catch (\Exception $e) {
				DB::rollback();
				throw $e;
			}
		}
	}

	public function update(array $data, $id)
	{
		$order = $this->order->with('orderItems')->find($id);
		if ($order) {
			/* $current_shipping_status = $order->shipping_status;
			$current_shipping_method = $order->shipping_method;
			$current_shipping_tracking = $order->shipping_tracking; */
			if (count($data) > 0) {
				$order->user_id = $data['user_id'];
				if ($data['user_id'] > 0) {
					$user = User::where('id', $data['user_id'])->first();
					if ($user) {
						$order->customer_name = $user->firstname.' '.$user->lastname;;
						$order->customer_email = $user->email;
						$order->customer_phone = $user->phone;
					}
				}
				if (isset($data['order_number'])) {
					$order->order_number = $data['order_number'];
				}
				$order->total_amount = $data['total_amount'];
				if (array_key_exists('shipping_address_1', $data)) {
					$order->shipping_address1 = $data['shipping_address_1'];
				}else{
					$order->shipping_address1 = null;
				}
				if (array_key_exists('address_id', $data)) {
					$order->address_id = $data['address_id'];
				}else{
					$order->address_id = 0;
				}
				if (array_key_exists('shipping_address_2', $data)) {
					$order->shipping_address2 = $data['shipping_address_2'];
				}else{
					$order->shipping_address2 = null;
				}
				if (array_key_exists('shipping_city', $data)) {
					$order->shipping_city = $data['shipping_city'];
				}else{
					$order->shipping_city = null;
				}
				if (array_key_exists('shipping_zip', $data)) {
					$order->shipping_zip = $data['shipping_zip'];
				}else{
					$order->shipping_zip = null;
				}
				if (array_key_exists('shipping_state', $data)) {
					$order->shipping_state = $data['shipping_state'];
				}else{
					$order->shipping_state = null;
				}
				if (array_key_exists('shipping_country', $data)) {
					$order->shipping_country = $data['shipping_country'];
				}else{
					$order->shipping_country = null;
				}
				if (array_key_exists('shipping_method', $data)) {
					$order->shipping_method = $data['shipping_method'];
				}
				if (array_key_exists('shipping_method_code', $data)) {
					$order->shipping_method_code = $data['shipping_method_code'];
				}
				if (array_key_exists('shipping_cost', $data)) {
					$order->shipping_cost = $data['shipping_cost'];
				} else {
					$order->shipping_cost = 0.00;
				}
				if (array_key_exists('shipping_cost_estimated', $data)) {
					if($order->status == 'pending'){
						$order->shipping_cost_estimated = $data['shipping_cost_estimated'];
					}
				} else {
					$order->shipping_cost_estimated = 0.00;
				}
				if (array_key_exists('shipping_tracking', $data)) {
					$order->shipping_tracking = $data['shipping_tracking'];
				}
				if (array_key_exists('origin', $data)) {
					$order->origin = $data['origin'];
				} else {
					$order->origin = 'store';
				}
				if (array_key_exists('shipping_status', $data)) {
					/* if ($current_shipping_status != $data['shipping_status']) { */
					$order->shipping_status = $data['shipping_status'];
					/* } */
				}
				if (array_key_exists('notes', $data)) {
					$order->notes = $data['notes'];
				}
				if (array_key_exists('partial', $data)) {
					$order->partial = $data['partial'];
					if ($order->partial == 1) {
						if (array_key_exists('min_payment', $data)) {
							$order->min_payment = $data['min_payment'];
						}
					} else {
						$order->min_payment = null;
					}

				}
				
				if (array_key_exists('total_tax_amount', $data)) {
					$order->total_tax_amount = $data['total_tax_amount'];
				}

				if (array_key_exists('tax', $data)) {
					$order->tax = $data['tax'];
				}

				if (array_key_exists('shipping_urgency', $data)) {
					$order->shipping_urgency = $data['shipping_urgency'];
				}else{
					$order->shipping_urgency = 'normal';
				}
				$order->save();
				if (array_key_exists('order_items', $data)) {
					if (count($data['order_items'] > 0)) {
						$order_items = array_map(function ($element) {
							return (object)$element;
						}, $data['order_items']);
						
						foreach ($order_items as $key => $order_item) {
							$item = $this->item->find($order_item->item_id);
							if ($item) {
								if (is_numeric($order_item->id) && $order_item->id > 0 && count($order->orderItems) > 0) {
									foreach ($order->orderItems as $key => $current_order_item) {
										if ($current_order_item->id == $order_item->id) {
											$current_qty = $current_order_item->qty;
											$current_order_item->order_id = $order_item->order_id;
											$current_order_item->item_id = $order_item->item_id;
											$current_order_item->description = $order_item->description;
											$current_order_item->name = $order_item->name;
											$current_order_item->qty = $order_item->qty;
											$current_order_item->sku = $order_item->sku;
											$current_order_item->price = $order_item->price;
											$current_order_item->taxes = $order_item->taxes;
											$current_order_item->discount_percent = $order_item->discount_percent;
											if ($current_order_item->save()) {
												$diference = 0;
												if ($current_order_item->qty > $current_qty) {
													$diference = $current_order_item->qty - $current_qty;
													$this->cItem->decreaseItemQty($item, $diference, true);
												} else if ($current_order_item->qty < $current_qty) {
													$diference = $current_qty - $current_order_item->qty;
													$this->cItem->increaseItemQty($item, $diference, true);
												}
											}
										}
									}
								} else {
									$new_order_item = new OrderItem();
									$new_order_item->order_id = $order->id;
									$new_order_item->item_id = $order_item->item_id;
									$new_order_item->description = $order_item->description;
									$new_order_item->name = $order_item->name;
									$new_order_item->qty = $order_item->qty;
									$new_order_item->sku = $order_item->sku;
									$new_order_item->price = $order_item->price;
									$new_order_item->taxes = $item->taxable;
									$new_order_item->discount_percent = $order_item->discount_percent;
									$order->total_amount = $order->total_amount + ($new_order_item->price * $new_order_item->qty);
									if ($new_order_item->save()) {
										$this->cItem->decreaseItemQty($item, $order_item->qty, true);
									}
								}
							}
						}
						$order = $this->order->with('orderItems')->find($id);
					}
				}
				$total_amount_result = $this->getTotalAmountWithOutTax($order->orderItems);
				$tax_base = $this->getTaxBaseAmount($order->orderItems);
				
				$order->total_amount = $total_amount_result;
				$order->total_tax_amount = $tax_base*$order->tax/100;
				$order->tax_base = $tax_base;
				$discount_amount = 0.00;
				$order->subtotal = $total_amount_result;
				
				if (array_key_exists('discount_code', $data) && $data['discount_code'] != '') {
					$discount = Discount::where('code', $data['discount_code'])->first();

					if($discount){
						$cDiscount = new CDiscount();
						$check = $cDiscount->checkDiscount($discount->toArray(), $order->toArray(), false);
						
						if($check != false){
							$order->total_amount = $total_amount_result;
							$discount_amount = 0.00;
							$discount_taxable = 0.00;
							
							switch($check['discount_type']){
								case 'percentage':
									$discount_amount +=  $check['discount_notaxable'] * $check['discount_value']/100;
									$discount_taxable = $check['discount_taxable'] * $check['discount_value']/100;
									$discount_amount +=  $discount_taxable;
									break;
								case 'fixed_amount':
									$discount_percent =  $check['discount_value'] *100 / ($check['discount_notaxable'] +  $check['discount_taxable']);
									$discount_amount +=  $check['discount_notaxable'] * $discount_percent/100;
									$discount_taxable = $check['discount_taxable'] * $discount_percent/100;
									$discount_amount +=  $discount_taxable;
									break;
								case 'free_shipping':
									$discount_amount = $order['shipping_cost_estimated'];
									break;
								case 'buy_x_get_y':
									// $discount_amount +=  $check['discount_notaxable'] * $check['discount_value']/100;
									// $discount_taxable = $check['discount_taxable'] * $check['discount_value']/100;
									// $discount_amount +=  $discount_taxable;
									$discount_taxable = 0;
									if(isset($check['discount_items'])){
										foreach($check['discount_items'] as $item){
											if($item[2] == 0){
												$discount_amount = $discount_amount + (($item[1] * $check['discount_value']/100)*$item[0]);
											}else{
												$discount_taxable = $discount_taxable + (($item[1] * $check['discount_value']/100)*$item[0]);
											}
										}
										$discount_amount += $discount_taxable;
									}
									break;
							}
							$order->tax_base = $check['discount_taxable'] - $discount_taxable;
							$order->total_tax_amount = ($order->tax > 0 && $check['discount_taxable'] > 0) ? ($check['discount_taxable'] - $discount_taxable)* $order->tax/100 : 0.00;
							$order->discount_code = $discount->code;
							$order->discount_amount = number_format($discount_amount,2);
							
							$order->total_amount += $order->total_tax_amount;
							$order->discount_code = $data['discount_code'];
							$discount->usage_count = $discount->usage_count + 1;
							$discount->save();
						}
						
						if($discount_amount >= 0){
							$order->total_amount = $order->total_amount - $discount_amount;
						}else{
							$order->total_amount = $order->total_amount + $discount_amount;
						}
						$paymentInfo['amount'] = $order->total_amount;
					}
				}else{
					$order->discount_code = null;
					$order->discount_amount = 0.00;
					$order->total_amount += $order->total_tax_amount;
				}
				
				$order->total_amount = $order->total_amount + $order->shipping_cost_estimated;
				if ($data['status'] == 'refunded') {
					$order->paid_amount = 0.00;
				}
				if ($data['status'] == 'paid') {
					$order->paid_amount = $order->total_amount;
				}
				$order->status = $data['status'];
				$order->shipping_status = $data['shipping_status'];
				$order->save();
				
				/* if ($current_shipping_status != $order->shipping_status ||
					$current_shipping_method != $order->shipping_method ||
					$current_shipping_tracking != $order->shipping_tracking) { */
				if (isset($data['notify']) && $data['notify'] == true && ($order->shipping_status == 'fulfilled' || $order->shipping_status == 'shipped'
					|| $order->shipping_status == 'fullfiling' || $order->shipping_status == 'cancelled' || $order->shipping_status == 'delivered'
					|| $order->shipping_status == 'returned')) {
					$this->sendOrderStatusEmail($order->id);
				}
				/* } */
			}
		}
		$order_data = $this->order->with(['orderItems.item.files' => function ($q) {
			return $q->where('type', '=', 'image');
		}])->find($order->id);

		return $order_data;
	}

	public function placeOrder(array $data)
	{
		if (count($data) > 0) {
			if(isset($data['id'])){
				$order = Order::where('id', $data['id'])->first();
				if(!$order){
					throw new ApiException("Sorry, order can't be created", "005");
				}
			}else{
				$order = new Order();
			}
			$order->created_at = date('Y-m-d H:i:s');
			if (Auth::check()) {
				$order->user_id = isset($data['user_id']) ? $data['user_id'] : Auth::user()->id;
				$order->creator_id = isset($data['creator_id']) ? $data['creator_id'] : \Auth::user()->id;
				$order->customer_name = isset($data['customer_name']) ? $data['customer_name'] : Auth::user()->firstname . ' ' . Auth::user()->lastname;
				$order->customer_email =  isset($data['customer_email']) ? $data['customer_email'] : Auth::user()->email;
				$order->customer_phone = isset($data['customer_phone']) ? $data['customer_phone'] :Auth::user()->phone;
			} else {
				if (array_key_exists('customer_name', $data)) {
					$order->customer_name = $data['customer_name'];
				}
				if (array_key_exists('customer_email', $data)) {
					$order->customer_email = $data['customer_email'];
				}
				$order->user_id = isset($data['user_id']) ? $data['user_id'] : 0;
				$order->creator_id = isset($data['creator_id']) ? $data['creator_id'] : 0;
			}
			if (array_key_exists('shipping_address_1', $data)) {
				$order->shipping_address1 = $data['shipping_address_1'];
			}
			if (array_key_exists('subscription_id', $data)) {
				$order->subscription_id = $data['subscription_id'];
			}
			if (array_key_exists('address_id', $data)) {
				$order->address_id = $data['address_id'];
			}
			if (array_key_exists('shipping_address_2', $data)) {
				$order->shipping_address2 = $data['shipping_address_2'];
			}
			if (array_key_exists('origin', $data)) {
				$order->origin = $data['origin'];
			} else {
				$order->origin = 'store';
			}
			if (array_key_exists('shipping_city', $data)) {
				$order->shipping_city = $data['shipping_city'];
			}
			if (array_key_exists('shipping_zip', $data)) {
				$order->shipping_zip = $data['shipping_zip'];
			}
			if (array_key_exists('shipping_state', $data)) {
				$order->shipping_state = $data['shipping_state'];
			}
			if (array_key_exists('shipping_country', $data)) {
				$order->shipping_country = $data['shipping_country'];
			}
			if (array_key_exists('payment_method_id', $data)) {
				$order->payment_method_id = $data['payment_method_id'];
				$paymentMethod = $this->paymentMethod->find($order->payment_method_id);
			} else {
				$card_method = $this->paymentMethod->where('name', 'credit-card')->first();
				if ($card_method) {
					$order->payment_method_id = $card_method->id;
					$paymentMethod = $this->paymentMethod->find($order->payment_method_id);
				}
			}
			if (array_key_exists('shipping_tracking', $data)) {
				$order->shipping_tracking = $data['shipping_tracking'];
			}
			//For now just assuming UPS
			if (array_key_exists('carrier', $data)) {
				if ($data['carrier'] == 'ups') {
					if (config('shipping.ups.api_integration') == true) {
						$order->shipping_method = $this->shippingMethod->where('name', 'ups')->first()->id;
						if (array_key_exists('selected_shipping_method_code', $data)) {
							$order->shipping_method_code = $data['selected_shipping_method_code'];
							$payload_info['carrier'] = $data['carrier'];
							$payload_info['address']['address'] = $data['shipping_address_1'] . ' ' . $data['shipping_address_2'];
							$payload_info['address']['city'] = $data['shipping_city'];
							$payload_info['address']['zip'] = $data['shipping_zip'];
							$payload_info['address']['state'] = $data['shipping_state'];
							$payload_info['address']['country'] = $data['shipping_country'];
							$ups_methods = $this->cCart->getAvailableShippingMethods($payload_info);
							
							if (isset($ups_methods['methods'])) {
								if (count($ups_methods['methods']) > 0) {
									foreach ($ups_methods['methods'] as $key => $method) {
										if ($method->code == $data['selected_shipping_method_code']) {
											if ($method->code == '03') {
												$order->shipping_cost_estimated = 0.00;
											} else {
												$order->shipping_cost_estimated = $method->cost;
											}
										}
									}
								}
							}
						} else {
							$order->shipping_method_code = '03';
							$order->shipping_cost_estimated = 0.00;
						}
					}

				}
			}
			
			
			$order->order_number = 'unknown';
			$order->save();
			
			try {
				DB::transaction(function () use ($order, $data) {
					if (array_key_exists('order_items', $data)) {
						if (count($data['order_items']) > 0) {
							$order_items = array_map(function ($element) {
								return (object)$element;
							}, $data['order_items']);
							
							foreach ($order_items as $key => $order_item) {
								$item = $this->item->find($order_item->id);
								if ($item && $order_item->qty > 0) {
									if(isset($order_item->options) && count($order_item->options) > 0){
										$orderItem = OrderItem::where([['order_id',$order->id],['item_id',$item->id],['options',$order_item->options]])->first();
									}else{
										$orderItem = OrderItem::where([['order_id',$order->id],['item_id',$item->id]])->first();
									}
									if($item->type != 'plan'){
										if ($item->total_qty >= $order_item->qty) {
											if($orderItem){
												$orderItem->description = Helper::shorten_string($order_item->description, 20);
												$orderItem->name = $order_item->name;
												$orderItem->qty = $order_item->qty;
												$orderItem->sku = $item->sku;
												$orderItem->price = $order_item->price;
												$orderItem->taxes = $item->taxable;
												$orderItem->discount_percent = $item->discount_percent;
												$orderItem->options = $order_item->options;
												$orderItem->save();
											}else{
												$tax = (isset($order_item->tax_percent)) ? $order_item->tax_percent/100 : 0;
												$new_order_item = new OrderItem();
												$new_order_item->order_id = $order->id;
												$new_order_item->item_id = $item->id;
												$new_order_item->description = (isset($order_item->description)) ? Helper::shorten_string($order_item->description, 20) : Helper::shorten_string($item->description, 20);
												$new_order_item->name = $order_item->name;
												$new_order_item->qty = $order_item->qty;
												$new_order_item->sku = $item->sku;
												$new_order_item->price = $order_item->price;
												$new_order_item->taxes = $item->taxable;
												$new_order_item->discount_percent = $item->discount_percent;
												$new_order_item->options = isset($order_item->options) ? $order_item->options : null;
												$new_order_item->save();
											}
										} else {
											throw new \Exception('The availability of product ' . $item->name . ' is less than your order qty');
										}
									}else{
										$new_order_item = new OrderItem();
										$new_order_item->order_id = $order->id;
										$new_order_item->item_id = $item->id;
										$new_order_item->description = isset($order_item->description) ? $order_item->description : 'Plan';
										$new_order_item->name = $order_item->name;
										$new_order_item->qty = $order_item->qty;
										$new_order_item->sku = $item->sku;
										$new_order_item->price = $order_item->price;
										$new_order_item->taxes = $item->taxable;
										$new_order_item->discount_percent = $item->discount_percent;
										$new_order_item->options = isset($order_item->options) ? $order_item->options : null;
										$new_order_item->save();
									}
									
								}else{
									if(isset($order_item->type) && $order_item->type == 'custom_plan'){
										$new_order_item = new OrderItem();
										$new_order_item->order_id = $order->id;
										$new_order_item->item_id = 0;
										$new_order_item->description = isset($order_item->description) ? $order_item->description : 'Plan';
										$new_order_item->name = $order_item->name;
										$new_order_item->qty = $order_item->qty;
										$new_order_item->sku = null;
										$new_order_item->price = $order_item->price;
										$new_order_item->taxes = 0;
										$new_order_item->discount_percent = 0;
										$new_order_item->options = isset($order_item->options) ? $order_item->options : null;
										$new_order_item->save();
									}
								}
							}
						}
					}
					$invoicing = Setting::where('key', 'invoicing')->first();
					if ($invoicing) {
						$setting = json_decode($invoicing->data, true);
						if ($setting['order_number_type'] == 'consecutive') {
							$order->order_number = $setting['prefix_order'] . ($order->id) . $setting['suffix_order'];
						} else {
							$order->order_number = Helper::base2base(1000 * $order->id, 10, 36) . strtoupper(Str::random(4));
						}
					} else {
						$order->order_number = Helper::base2base(1000 * $order->id, 10, 36) . strtoupper(Str::random(4));
					}
					$order->token = Helper::gen_invoice_token($order->id);
					$order->shipping_status = 'pending';
					$order->shipping_urgency = 'normal';
					$order->save();
				}, 2);
			} catch (\Exception $error) {
				DB::rollback();
				$result = $order;
				throw new ApiException($error->getMessage(), "003", $order);
			}
			if (array_key_exists('save_address', $data)) {
				if ($data['save_address'] == true) {
					$address_data['address1'] = $order->shipping_address1;
					if ($order->shipping_address2) {
						$address_data['address2'] = $order->shipping_address2;
					}
					$address_data['city'] = $order->shipping_city;
					$address_data['zip'] = $order->shipping_zip;
					$address_data['state'] = $order->shipping_state;
					$address_data['country'] = $order->shipping_country;
					$address_data['default'] = true;
					$this->cAccount->addAddress($address_data);
				}
			}
			//Payment process
			$result = [];
			
			if (array_key_exists('payment_type', $data)) {
				$total_amount_result = $this->getTotalAmountWithOutTax($order->orderItems);
				$tax_base = $this->getTaxBaseAmount($order->orderItems);
				
				$paymentInfo['amount'] = $total_amount_result + $tax_base*$order->tax/100 + $order->shipping_cost_estimated;
				$order->total_amount = $paymentInfo['amount'];
				$order->total_tax_amount = $tax_base*$order->tax/100;
				$order->tax_base = $tax_base;
				$discount_amount = 0.00;
				$order->subtotal = $total_amount_result;
				if (array_key_exists('total_amount', $data)) {
					if (array_key_exists('discount_code', $data) && $data['discount_code'] != '') {
						$discount = Discount::where('code', $data['discount_code'])->first();

						if($discount){
							$cDiscount = new CDiscount();
							
							$check = $cDiscount->checkDiscount($discount->toArray(), $order->toArray(), false);
							
							if($check != false){
								$order->total_amount = $total_amount_result + $order->shipping_cost_estimated;
								$discount_amount = 0.00;
								$discount_taxable = 0.00;
								
								switch($check['discount_type']){
									case 'percentage':
										if(isset($check['discount_items'])){
											$discount_taxable = 0;
											foreach($check['discount_items'] as $item){
												if($item[2] == 0){
													$discount_amount = $discount_amount + (($item[1] * $check['discount_value']/100)*$item[0]);
												}else{
													$discount_taxable = $discount_taxable + (($item[1] * $check['discount_value']/100)*$item[0]);
												}
											}
											$discount_amount += $discount_taxable;
										}else{
											$discount_amount +=  $check['discount_notaxable'] * $check['discount_value']/100;
											$discount_taxable = $check['discount_taxable'] * $check['discount_value']/100;
											$discount_amount +=  $discount_taxable;
										}
										break;
									case 'fixed_amount':
										$discount_percent =  $check['discount_value'] *100 / ($check['discount_notaxable'] +  $check['discount_taxable']);
										$discount_amount +=  $check['discount_notaxable'] * $discount_percent/100;
										$discount_taxable = $check['discount_taxable'] * $discount_percent/100;
										$discount_amount +=  $discount_taxable;
										if(isset($check['discount_items'])){
											$amount = 0;
											foreach($check['discount_items'] as $item){
												$amount += $item[1]*$item[0];
											}
											if($amount < $check['discount_value']){
												$discount_amount = 0;
												$discount_percent =  $amount *100 / ($check['discount_notaxable'] +  $check['discount_taxable']);
												$discount_amount +=  $check['discount_notaxable'] * $discount_percent/100;
												$discount_taxable = $check['discount_taxable'] * $discount_percent/100;
												$discount_amount +=  $discount_taxable;
											}
										}
										break;
									case 'free_shipping':
										$discount_amount = $order['shipping_cost_estimated'];
										break;
									case 'buy_x_get_y':
										$discount_taxable = 0;
										if(isset($check['discount_items'])){
											foreach($check['discount_items'] as $item){
												if($item[2] == 0){
													$discount_amount = $discount_amount + (($item[1] * $check['discount_value']/100)*$item[0]);
												}else{
													$discount_taxable = $discount_taxable + (($item[1] * $check['discount_value']/100)*$item[0]);
												}
											}
											$discount_amount += $discount_taxable;
										}
										// $discount_amount +=  $check['discount_notaxable'] * $check['discount_value']/100;
										// $discount_taxable = $check['discount_taxable'] * $check['discount_value']/100;
										// $discount_amount +=  $discount_taxable;
										break;
								}
								$order->tax_base = $check['discount_taxable'] - $discount_taxable;
								$order->total_tax_amount = ($order->tax > 0 && $check['discount_taxable'] > 0) ? ($check['discount_taxable'] - $discount_taxable)* $order->tax/100 : 0.00;
								$order->discount_code = $discount->code;
								$order->discount_amount = number_format($discount_amount,2);;
								
								$order->total_amount += $order->total_tax_amount;
								$order->discount_code = $data['discount_code'];
								$discount->usage_count = $discount->usage_count + 1;
								$discount->save();
							}
							if($discount_amount >= 0){
								$order->total_amount = $order->total_amount - $discount_amount;
							}else{
								$order->total_amount = $order->total_amount + $discount_amount;
							}
							$paymentInfo['amount'] = $order->total_amount;
						}
					}else{
						$order->discount_code = null;
					}

					$order->save();
				}
				
				if($order->total_amount > 0){
					if ($data['payment_type'] != 'paypal') {
						if (isset($data['customer_name'])){
							$paymentInfo['shipping_firstname'] = $data['customer_name'];
							$paymentInfo['customer_email'] = $data['customer_email'];
						} else if(\Auth::check()){
							$paymentInfo['shipping_firstname'] = \Auth::user()->firstname;
							$paymentInfo['shipping_lastname'] = \Auth::user()->lastname;
							$paymentInfo['customer_email'] = \Auth::user()->email;
							$data['user'] = \Auth::user();
						}
						// if (isset($data['customer_name'])) $paymentInfo['shipping_firstname'] = $data['customer_name'];
						if (isset($data['shipping_address_1'])) $paymentInfo['shipping_address'] = $data['shipping_address_1'];
						if (isset($data['shipping_city'])) $paymentInfo['shipping_city'] = $data['shipping_city'];
						if (isset($data['shipping_state'])) $paymentInfo['shipping_state'] = $data['shipping_state'];
						if (isset($data['shipping_zip'])) $paymentInfo['shipping_zip'] = $data['shipping_zip'];
						if (isset($data['shipping_country'])) $paymentInfo['shipping_country'] = $data['shipping_country'];

						if ($data['payment_type'] == 'credit-card') {
							if (isset($data['card_holder'])) {
								$name = explode(' ', $data['card_holder']);
								$paymentInfo['billing_firstname'] = ((count($name) == 1 || count($name)==2) ? $name[0] : ((count($name) > 2) ? $name[0] .' '.$name[1] : ''));
								$paymentInfo['billing_lastname'] = ((count($name) == 2) ? $name[1] : ((count($name) > 2) ? $name[2] : ''));
							}
	
							// if (isset($data['card_holder'])) $paymentInfo['billing_firstname'] = $data['card_holder'];
							if (isset($data['billing_address'])) $paymentInfo['billing_address'] = $data['billing_address'];
							if (isset($data['billing_city'])) $paymentInfo['billing_city'] = $data['billing_city'];
							if (isset($data['billing_state'])) $paymentInfo['billing_state'] = $data['billing_state'];
							if (isset($data['billing_zip'])) $paymentInfo['billing_zip'] = $data['billing_zip'];
							if (isset($data['billing_country'])) $paymentInfo['billing_country'] = $data['billing_country'];
	
							if (isset($paymentMethod)) {
								$paymentInfo['type'] = 'card';
								if (array_key_exists('token', $data)) {
									$paymentInfo['token'] = $data['token'];
								}
								if (array_key_exists('description', $data)) {
									$paymentInfo['description'] = $data['description'];
								}
							}
						} else if ($data['payment_type'] == 'profile') {
							if (array_key_exists('user', $data)) {
								$paymentInfo['type'] = 'profile';
								$user = $data['user'];
								
								if (array_key_exists('selected_customer_method_id', $data) && $data['selected_customer_method_id'] > 0) {
									$customer_payment_profile = CustomerPaymentProfile::find($data['selected_customer_method_id']);
									if ($customer_payment_profile) {
										$data['last_4'] = substr($customer_payment_profile->last_numbers, strlen($customer_payment_profile->last_numbers) - 4);
										if ($customer_payment_profile->customerProfile->user_id == $user->id) {
											$paymentInfo['profile_id'] = $customer_payment_profile->customerProfile->customer_profile_id;
											$paymentInfo['payment_profile_id'] = $customer_payment_profile->payment_profile_id;
										} else {
											throw new ApiException('Unauthenticated user.', "001");
										}
									} else {
										throw new ApiException('Resourse not found.', "001");
									}
								}else{
									//Make subscription payment 
									if (array_key_exists('type', $data) && $data['type'] == 'subscription') {
										$order->total_amount = $data['total_amount'];
										$paymentInfo['amount'] = $data['total_amount'];
									}
									$customer_profiles = CustomerProfile::where('user_id',$user->id)->first();
									$customer_payment_profile = CustomerPaymentProfile::where([['customer_profile_id', $customer_profiles->id],['default',1]])->first();
									if ($customer_payment_profile) {
										$data['last_4'] = substr($customer_payment_profile->last_numbers, strlen($customer_payment_profile->last_numbers) - 4);
										$paymentInfo['profile_id'] = $customer_profiles->customer_profile_id;
										$paymentInfo['payment_profile_id'] = $customer_payment_profile->payment_profile_id;
									} else {
										throw new ApiException('Resourse not found.', "001");
									}
								}
									
							}else {
								throw new ApiException('Unauthenticated user.', "001");
							}
						}
						try {
							if (isset($data['save_card'])) {
								if ($data['save_card'] == true) {
									$paymentProfileinfo = $paymentInfo;
									$paymentProfileinfo['billing_firstname'] = $data['card_holder'];
									$paymentProfileinfo['month'] = explode('/', $data["card_exp_date"])[0];
									$paymentProfileinfo['year'] = explode('/', $data["card_exp_date"])[1];
									$paymentProfileinfo['customer_type'] = $data['customer_type'];
									$paymentProfileinfo['save'] = true;
									$paymentProfileinfo['type'] = $data['type'];
									$paymentProfileinfo['token'] = $data['token'];
									try {
										$_result = $this->cAccount->addPaymentMethod($paymentProfileinfo);
									} catch (\Exception $error) {
										throw new ApiException('The card was successfully verified but it was an error saving it in your user profile. Try to checkout again without save the card and save it later from your user dashboard.', "001", $order);
									}
									$customer_profile = CustomerProfile::with('paymentProfiles')->where([['user_id', \Auth::user()->id], ['payment_processor', strtolower(env('CARD_PROCESSOR'))]])->first();
									$processPayment = $this->processPayment([
										'type' => 'profile',
										'amount' => $paymentInfo['amount'],
										'profile_id' => $customer_profile->customer_profile_id,
										'payment_profile_id' => $_result['payment_profile_id'],
										'shipping_firstname' => (\Auth::check()) ? \Auth::user()->firstname : '',
										'shipping_lastname' => (\Auth::check()) ? \Auth::user()->lastname : '',
										'shipping_address' => (isset($data['shipping_address_1'])) ? $data['shipping_address_1'] : '',
										'shipping_city' => (isset($data['shipping_city'])) ? $data['shipping_city'] : '',
										'shipping_state' => (isset($data['shipping_state'])) ? $data['shipping_state'] : '',
										'shipping_zip' => (isset($data['shipping_zip'])) ? $data['shipping_zip'] : '',
										'shipping_country' => (isset($data['shipping_country'])) ? $data['shipping_country'] : '',
									]);
								} else {
									$processPayment = $this->processPayment($paymentInfo);
	
								}
							} else {
								$processPayment = $this->processPayment($paymentInfo);
							}
							
							$order->paid_amount = $paymentInfo['amount'];
							$order->status = 'paid';
							$order->save();

							if($order->subscription_id > 0){
								$subs = Subscription::where('id', $order->subscription_id)
													->first();
								if($subs){
									// if($subs->total_payments > $subs->payments_made){
										$subs->expires_at = date('Y-m-d H:i:s', strtotime($subs->next_billing_date . '+'.($subs->days_to_pay+$subs->grace_days).' day'));
										$subs->last_payment_date = date('Y-m-d H:i:s');
										$subs->payments_made = $subs->payments_made + 1;
									// }
									
									$last_order = Order::where('subscription_id', $order->subscription_id)->where('status', 'pending')->first();
									if($last_order){
										Subscription::where('id', $order->subscription_id)->update(['next_payment_date' => $last_order->created_at]);
									}else{
										$subs->next_payment_date = $subs->next_billing_date;
									}
                        			$subs->save();
								}
							}
							
							$processPayment['operation'] = 'charge';
							$processPayment['last_4'] = (isset($data['last_4'])) ? $data['last_4'] : null;
							if ($this->registerSuccessPayment($processPayment, $order, $provider = strtolower(env('CARD_PROCESSOR', 'authorize'))) > 0) {
								$result = $order;
							}
						} catch (ApiException $error) {
							// $order->status = 'pay__error';
							// $order->save();
							if(!is_array($error->messages())){
								$message = $error->messages()->messages();
							}else{
								$message = $error->messages();
							}
	
							if ($error = $this->registerErrorPayment($error->message(), $paymentInfo, $order, $provider = 'authorize')) {
								if ($error > 0) {
									$result = $order;
									throw new ApiException($message, "E001", $order);
								}
							}
						}
					} else {
						$url = parse_url(env('APP_URL', 'https://properos.com'));
	
						$options = [
							'url_return' => $url['scheme'] . '://' . $url['host'] . '/api/payment/success',
							'url_cancel' => $url['scheme'] . '://' . $url['host'] . '/api/payment/cancel',
							'custom' => ['store' => env('APP_NAME')]
						];
						if (Auth::check()) {
							$options['custom']['user_id'] = \Auth::user()->id;
						}
	
						$paypal = new PaypalClass($options);
	
						$paypal_data = [
							'item_number' => $order->order_number,
							'item_name' => 'Order',
							'total_amount' => $paymentInfo['amount'],
							'recurring' => 0
						];
						$result = ['type' => 'paypal', 'info' => $paypal->build_form($paypal_data["item_number"], $paypal_data["total_amount"], $paypal_data["recurring"], $paypal_data)];
					}
				}else{
					$order->paid_amount = $order->total_amount;
					$order->status = 'paid';
					$order->save();

					if($order->subscription_id > 0){
						$subs = Subscription::where('id', $order->subscription_id)
											->first();
						if($subs){
							// if($subs->total_payments > $subs->payments_made){
								$subs->expires_at = date('Y-m-d H:i:s', strtotime($subs->next_billing_date . '+'.($subs->days_to_pay+$subs->grace_days).' day'));
								$subs->last_payment_date = date('Y-m-d H:i:s');
								$subs->payments_made = $subs->payments_made + 1;
							// }
							
							$last_order = Order::where('subscription_id', $order->subscription_id)->where('status', 'pending')->first();
							if($last_order){
								Subscription::where('id', $order->subscription_id)->update(['next_payment_date' => $last_order->created_at]);
							}else{
								$subs->next_payment_date = $subs->next_billing_date;
							}
							$subs->save();
						}
					}
					return $order->toArray();				
				}
				$order->save();
				return $result;
			}
		}
	}

	public function processPayment(array $payment_data)
	{
		if (count($payment_data) > 0) {
			$authPayment = new CPayment();
			$paymentActionResult = $authPayment->chargeAccount($payment_data);
			return $paymentActionResult;
		}
	}

	public function getTaxBaseAmount($orderItems)
	{
		if (count($orderItems) > 0) {
			$taxBase = 0;
			foreach ($orderItems as $key => $orderItem) {
				if($orderItem->taxes > 0){
					if($orderItem->discount_percent > 0){
						$taxBase += ($orderItem->price -  ($orderItem->price*$orderItem->discount_percent/100)) * $orderItem->qty;
					}else{
						$taxBase += $orderItem->price * $orderItem->qty;
					}
				}
			}
			return $taxBase;
		}
	}

	public function getTotalAmountWithOutTax($orderItems)
	{
		if (count($orderItems) > 0) {
			$totalAmount = 0;
			foreach ($orderItems as $key => $orderItem) {
				if(isset($orderItem->discount_percent) && $orderItem->discount_percent > 0){
					$totalAmount += ($orderItem->price -  ($orderItem->price*$orderItem->discount_percent/100)) * $orderItem->qty;
				}else{
					$totalAmount += $orderItem->price * $orderItem->qty;
				}
			}
			return $totalAmount;
		}
	}

	public function decreaseItemQtyFromOrder($order, $sync = true)
	{
		if (count($order->orderItems) > 0) {
			foreach ($order->orderItems as $key => $orderItem) {
				$item = $orderItem->item;
				if($item){
					if ($item->total_qty > $orderItem->qty) {
						$item->total_qty -= $orderItem->qty;
					} else if ($item->total_qty == $orderItem->qty) {
						$item->total_qty = 0;
					}
					$item->save();
					if ($sync == true) {
						event(new ProductQtyChanged($item));
					}
				}
			}
		}
	}

	public function registerSuccessPayment($data, $order, $provider)
	{
		$this->decreaseItemQtyFromOrder($order, true);

		if (count($data) > 0) {
			$new_data = [
				'payable_type' => 'orders',
				'payable_id' => $order->id,
				'user_id' => $order->user_id,
				'operation' => $data['operation'],
				'customer_email' => isset($data['customer_email']) ? $data['customer_email'] : $order->customer_email,
				'amount' => $order->paid_amount,
				'fee' => isset($data['transaction_fee']) ? $data['transaction_fee'] : null,
				'last_4' => isset($data['last_4']) ? $data['last_4'] : null,
				'transaction_id' => $data['transaction_id'],
				'auth_code' => (isset($data['authcode'])) ? $data['authcode'] : null,
				'transaction_status' => 'success',
				'provider' => $provider,
				'account_type' => 'local',
				'account_id' => 0,
				'description' => $data['description'],
				'created_at' => date('Y-m-d H:i:s')
			];

			$payment_method = $this->paymentMethod->find($order->payment_method_id);

			if ($payment_method->name == 'credit-card') {
				$new_data['type'] = 'card';
				$new_data['description'] = (isset($data['description']) && $data['description'] != '') ? $data['description'] : 'Card payment operation';
			} else if ($payment_method->name == 'paypal') {
				$new_data['type'] = 'paypal';
				$new_data['description'] = 'A paypal charge operation';
			} else if ($payment_method->name == 'cheque') {
				$new_data['type'] = 'cheque';
				$new_data['description'] = 'Cheque payment operation';
			} else if ($payment_method->name == 'cash') {
				$new_data['type'] = 'cash';
				$new_data['description'] = 'Cash payment operation';
			}

			if ($order->user_id) {
				$new_data['user_id'] = $order->user_id;
			} else {
				$new_data['customer_email'] = $order->customer_email;
			}

			$result = DB::table('payments')->insertGetId($new_data);
			dispatch(new calculateAffiliateGain($new_data));
		}

		return $result;
	}

	public function registerErrorPayment($data, $request, $order, $provider)
	{
		if (count($data) > 0) {
			$new_data = [
				'order_id' => $order->id,
				'user_id' => $order->user_id,
				'provider' => $provider,
			];
			$serialized_array_request = serialize($request);
			$serialized_array_errors = serialize($data);
			$new_data['request_data'] = $serialized_array_request;
			$new_data['error_response'] = $serialized_array_errors;
			if ($order->user_id) {
				$new_data['user_id'] = $order->user_id;
			} else {
				$new_data['customer_email'] = $order->customer_email;
			}
			$result = DB::table('payment_errors')->insertGetId($new_data);
		}
		return $result;
	}

	public function destroy($id)
	{
		$order = $this->order->find($id);
		return $order->delete();
	}

	public function sendOrderStatusEmail($id)
	{
		if ($id > 0) {
			$order = $this->order->with('shippingMethod')->find($id);
			if ($order) {
				// if (isset($order->shippingMethod->name) && $order->shippingMethod->name != 'manual') {
				// if (Auth::check()) {
				// 	$email_data = new OrderCreatedVM(
				// 		env('MAIL_FROM_ADDRESS', 'support@properos.com'),
				// 		$order,
				// 		$order->customer_name,
				// 		$order->customer_email
				// 	);
				// 	Mail::to($order->customer)->queue(new OrderStatusEmail($email_data));
				// } else {
					$email_data = new OrderCreatedVM(
						env('MAIL_FROM_ADDRESS', 'support@properos.com'),
						$order,
						$order->customer_name,
						$order->customer_email
					);
					Mail::to($order->customer_email)->queue(new OrderStatusEmail($email_data));
				// }
				// } 
				return true;
			}
		}
	}

	public function calculateSubtotalTax(array $items, $order){
		$total = [
			'amount' => 0.00,
			'tax' => 0.00,
			'tax_base' => 0.00
		];
		$taxable = 0;
		foreach($items as $item){
			if($item['discount_percent'] > 0){
				$price = ($item['price']  - ($item['price'] * $item['discount_percent']/100)) *$item['qty'];
				$total['amount'] += $price;
			}else{
				$price = $item['price'] * $item['qty'];
				$total['amount'] += $price;
			}
			if($item['taxable'] >0){
				$taxable += $price;
			}
		}
		$total['tax'] = $taxable * $order['tax']/100;
		$total['tax_base'] = $taxable;
		return $total;
	}

	public function sendInvoiceByEmail($id, $email = null, $subject = null)
	{
		if ($id > 0) {
			$order = $this->order->with('shippingMethod')->find($id);
			if ($order) {
				if ($order->user_id > 0) {
					$email_data = new OrderCreatedVM(
						env('MAIL_FROM_ADDRESS', 'support@properos.com'),
						$order,
						$order->customer->firstname.' '.$order->customer->lastname,
						(($email && $email != '') ? $email : $order->customer->email),
						$subject
					);
					Mail::to(($email && $email != '') ? $email : $order->customer->email)->queue(new OrderInvoiceEmail($email_data));
				} else {
					$email_data = new OrderCreatedVM(
						env('MAIL_FROM_ADDRESS', 'support@properos.com'),
						$order,
						$order->customer_name,
						(($email && $email != '') ? $email : $order->customer_email),
						$subject
					);
					Mail::to((($email && $email != '') ? $email : $order->customer_email))->queue(new OrderInvoiceEmail($email_data));
				}
				return true;
			}
		}
	}

	public function sendQuoteByEmail($id, $email = null, $subject = null)
	{
		if ($id > 0) {
			$order = $this->order->with('shippingMethod')->find($id);
			if ($order) {
				if ($order->user_id > 0) {
					$email_data = new OrderCreatedVM(
						env('MAIL_FROM_ADDRESS', 'support@properos.com'),
						$order,
						$order->customer->firstname.' '.$order->customer->lastname,
						(($email && $email != '') ? $email : $order->customer->email),
						$subject
					);
					Mail::to(($email && $email != '') ? $email : $order->customer->email)->queue(new OrderQuoteEmail($email_data));
				} else {
					$email_data = new OrderCreatedVM(
						env('MAIL_FROM_ADDRESS', 'support@properos.com'),
						$order,
						$order->customer_name,
						(($email && $email != '') ? $email : $order->customer_email),
						$subject
					);
					Mail::to((($email && $email != '') ? $email : $order->customer_email))->queue(new OrderQuoteEmail($email_data));
				}
				return true;
			}
		}
	}

	public function getTrackingInfo($data)
	{
		if (isset($data['carrier']) && isset($data['tracking_number'])) {
			switch ($data['carrier']) {
				case 'ups':

					return (new ShippingUPS())->getTrackingInfo($data);
					break;
			}
		}
	}

	public function processInvoicePayment($data)
	{
		$paymentInfo = [];
		$result = [];
		if (isset($data['type'])) {
			$order = $this->order->find($data['order_id']);
			if ($order) {
				// $total_amount_result = $this->getTotalAmountWithOutTax($order->orderItems);
				// $tax_base = $this->getTaxBaseAmount($order->orderItems);
				// $order->total_tax_amount = $tax_base * $order->to;
				// $order->total_amount = $total_amount_result + $total_tax_result + $order->shipping_cost_estimated;
				$order->save();
				if (($order->total_amount) - $order->paid_amount > 0) {
					if (isset($data['partial_amount'])) {
						if ($data['partial_amount'] >= ($order->total_amount) - $order->paid_amount) {
							$paymentInfo['amount'] = ($order->total_amount + $order->total_tax_amount) - $order->paid_amount;
						} else if ($data['partial_amount'] < ($order->total_amount) - $order->paid_amount) {
							if ($data['partial_amount'] < $order->min_payment) {
								throw new ApiException('Minimun payment amount is' . ' ' . $order->min_payment, "001");
							} else {
								$paymentInfo['amount'] = $data['partial_amount'];
							}
						}
					} else {
						$paymentInfo['amount'] = ($order->total_amount) - $order->paid_amount;
					}
					switch ($data['type']) {
						case 'card':
							$order->payment_method_id = ($this->paymentMethod->where('name', 'credit-card')->orWhere('name', 'card')->first())->id;
							$order->save();
							if (isset($data['billing_firstname']))
								$paymentInfo['billing_firstname'] = $data['billing_firstname'];
							if (isset($data['billing_lastname']))
								$paymentInfo['billing_lastname'] = $data['billing_lastname'];
							if (isset($data['card_processor']))
								$paymentInfo['card_processor'] = $data['card_processor'];
							if (isset($data['billing_zip']))
								$paymentInfo['billing_zip'] = $data['billing_zip'];
							if (isset($data['billing_address']))
								$paymentInfo['billing_address'] = $data['billing_address'];

							$paymentInfo['type'] = $data['type'];
							if (array_key_exists('token', $data)) {
								$paymentInfo['token'] = $data['token'];
							}
							if (array_key_exists('description', $data)) {
								$paymentInfo['description'] = $data['description'];
							}
							try {
								$processPayment = $this->processPayment($paymentInfo);

								if ($paymentInfo['card_processor'] == 'authorize') {
									if ($processPayment['response_code'] == 'approved' || $processPayment['response_code'] == 'held_for_review') {
										$payment_data = [
											'payable_type' => 'orders',
											'payable_id' => $order->id,
											'user_id' => $order->user_id,
											'operation' => 'charge',
											'amount' => $order->paid_amount,
											'fee' => $processPayment['transaction_fee'],
											'transaction_id' => $processPayment['transaction_id'],
											'auth_code' => isset($processPayment['authcode']) ? $processPayment['authcode'] : null,
											'transaction_status' => 'success',
											'provider' => $paymentInfo['card_processor'],
											'account_type' => 'local',
											'account_id' => 0,
											'description' => $processPayment['description'],
											'created_at' => date('Y-m-d H:i:s')
										];

									} else if ($processPayment['response_code'] == 'error') {
										throw new ApiException('There was an error processing the payment.', "002");
									} else if ($processPayment['response_code'] == 'declined') {
										throw new ApiException('Your payment has been declined.', "003");
									}
								} else if ($paymentInfo['card_processor'] == 'stripe') {
									if ($processPayment['response_code'] == 'succeeded') {
										$payment_data = [
											'payable_type' => 'orders',
											'payable_id' => $order->id,
											'user_id' => $order->user_id,
											'operation' => $processPayment['outcome']['type'],
											'amount' => $processPayment['amount'],
											'fee' => null,
											'transaction_id' => $processPayment['transaction_id'],
											'auth_code' => isset($processPayment['authcode']) ? $processPayment['authcode'] : null,
											'transaction_status' => $processPayment['response_code'],
											'provider' => $paymentInfo['card_processor'],
											'account_type' => 'local',
											'account_id' => 0,
											'description' => $processPayment['outcome']['seller_message'],
											'created_at' => date('Y-m-d H:i:s')
										];

									} else {
										throw new ApiException('There was an error processing the payment.', "002");
									}
								}
								$order->paid_amount += $paymentInfo['amount'];
								if ((($order->total_amount) - $order->paid_amount) > 0) {
									$order->status = 'pending';
								} else {
									$order->status = 'paid';
									if($order->subscription_id > 0){
										$subscription = Subscription::where('id',$order->subscription_id)->with('plan', 'next_plan')->first();
										$subscription->last_payment = $order->paid_amount;
										$subscription->last_payment_date = date('Y-m-d H:i:s');
										if ($subscription->next_plan_id > 0) {
											$plan = $subscription->next_plan;
										} else {
											$plan = $subscription->plan;
										}
										if($plan){
											switch ($plan->interval) {
												case 'day':
													$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
													break;
												case 'weekly':
													$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
													break;
												case 'month':
													$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' month'));
													break;
												case 'year':
													$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' year'));
													break;
											}
										}else{
											switch ($subscription->interval) {
												case 'day':
													$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
													break;
												case 'weekly':
													$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
													break;
												case 'month':
													$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' month'));
													break;
												case 'year':
													$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' year'));
													break;
											}
										}
										
										if ($subscription->next_plan_id != null && $subscription->next_plan_id > 0) {
											$subscription->last_plan_id = $subscription->current_plan_id;
											$subscription->current_plan_id = $subscription->next_plan_id;
											$subscription->next_plan_id = 0;
										}
										
										if($subscription->status == 'expired'){
											$subscription->status = 'active';
										}
										$subscription->expires_at = date('Y-m-d H:i:s', strtotime($subscription->next_billing_date . '+'.($subscription->days_to_pay+$subscription->grace_days).' day'));

										// if($subscription->total_payments > $subscription->payments_made){
											$subscription->last_payment_date = date('Y-m-d H:i:s');
											$subscription->payments_made = $subscription->payments_made + 1;
										// }
										
										$last_order = Order::where('subscription_id', $order->subscription_id)->where('status', 'pending')->first();
										
										if($last_order){
											Subscription::where('id', $order->subscription_id)->update(['next_payment_date' => $last_order->created_at]);
										}else{
											$subscription->next_payment_date = $subscription->next_billing_date;
										}
										$subscription->save();
									}
								}
								$order->save();
								if ($this->registerSuccessPayment($payment_data, $order, $provider = $paymentInfo['card_processor']) > 1) {
									$result = [
										'type' => 'card',
										'status' => 1,
										'code' => '200',
										'message' => 'Payment processed successfully'
									];
								}
								return $result;

							} catch (ApiException $error) {
								$order->status = 'pay__error';
								$order->save();
								$message = $error->message();
								if ($error = $this->registerErrorPayment($error->messages(), $paymentInfo, $order, $provider = $paymentInfo['card_processor'])) {
									if ($error > 0) {
										throw new ApiException('Error processing payment', "001");
									}
								}
							}
							break;
						case 'paypal':
							$order->payment_method_id = ($this->paymentMethod->where('name', 'paypal')->first())->id;
							$order->save();
							$url = parse_url(env('APP_URL', 'http://yuleah.com'));
							$options = [
								'url_return' => $url['scheme'] . '://' . $url['host'] . '/api/payment/success',
								'url_cancel' => $url['scheme'] . '://' . $url['host'] . '/api/payment/cancel',
								'custom' => ['store' => env('APP_NAME')]
							];
							if (Auth::check()) {
								$options['custom']['user_id'] = \Auth::user()->id;
							}
							$paypal = new PaypalClass($options);

							$paypal_data = [
								'item_number' => $order->order_number,
								'item_name' => 'Order',
								'total_amount' => $paymentInfo['amount'],
								'recurring' => 0
							];
							
							if($order->subscription_id > 0){
								$subscription = Subscription::where('id',$order->subscription_id)->with('plan', 'next_plan')->first();
								$subscription->last_payment = $paymentInfo['amount'];
								$subscription->last_payment_date = date('Y-m-d H:i:s');
								if ($subscription->next_plan_id > 0) {
									$plan = $subscription->next_plan;
								} else {
									$plan = $subscription->plan;
								}
								if($plan){
									switch ($plan->interval) {
										case 'day':
											$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
											break;
										case 'weekly':
											$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
											break;
										case 'month':
											$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' month'));
											break;
										case 'year':
											$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' year'));
											break;
									}
								}else{
									switch ($subscription->interval) {
										case 'day':
											$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
											break;
										case 'weekly':
											$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
											break;
										case 'month':
											$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' month'));
											break;
										case 'year':
											$subscription->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' year'));
											break;
									}
								}
								
								if ($subscription->next_plan_id != null && $subscription->next_plan_id > 0) {
									$subscription->last_plan_id = $subscription->current_plan_id;
									$subscription->current_plan_id = $subscription->next_plan_id;
									$subscription->next_plan_id = 0;
								}
								
								if($subscription->status == 'expired'){
									$subscription->status = 'active';
								}
								$subscription->expires_at = date('Y-m-d H:i:s', strtotime($subscription->next_billing_date . '+'.($subscription->days_to_pay+$subscription->grace_days).' day'));

								// if($subscription->total_payments > $subscription->payments_made){
									$subscription->last_payment_date = date('Y-m-d H:i:s');
									$subscription->payments_made = $subscription->payments_made + 1;
								// }
								
								$last_order = Order::where('subscription_id', $order->subscription_id)->where('status', 'pending')->first();
								
								if($last_order){
									Subscription::where('id', $order->subscription_id)->update(['next_payment_date' => $last_order->created_at]);
								}else{
									$subscription->next_payment_date = $subscription->next_billing_date;
								}
								$subscription->save();
							}
							$result = ['type' => 'paypal', 'info' => $paypal->build_form($paypal_data["item_number"], $paypal_data["total_amount"], $paypal_data["recurring"], $paypal_data)];
							return $result;

							break;
						default:
						# code...
							break;
					}
				} else {
					throw new ApiException('This invoice has been already paid', "004");
				}
			} else {
				abort(404);
			}
		}
	}

	public function getShippingInfo($payload_info)
	{
		$data = [];
		if (isset($payload_info['carrier'])) {
			switch ($payload_info['carrier']) {
				case 'ups':
					$data['service_code'] = (string)$payload_info['service_code'];
					if (\Auth::check()) {
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
							$order = $this->order->with('orderItems.item')->with('customer')->find($payload_info['order_id']);
							if ($order->shipping_tracking != '' || $order->shipping_tracking != null) {
								return [
									'tracking_number' => $order->shipping_tracking,
									'total_charges' => $order->shipping_cost
								];
							}
							if (count($order->orderItems) > 0) {
								$length = $order->orderItems[0]->item->length;
								$width = 0.0;
								$height = $order->orderItems[0]->item->height;
								$weight = 0.0;
							} else {
								throw new ApiException('Order empty', '007');
							}
							foreach ($order->orderItems as $key => $order_item) {
								$width += $order_item->item->width * $order_item->qty;
								$weight += $order_item->item->weight * $order_item->qty;
								if ($order_item->item->length > $length) {
									$length = $order_item->item->length;
								}
								if ($order_item->item->height > $height) {
									$height = $order_item->item->height;
								}
							}
							$data['customer_name'] = $order->customer_name;
							if($order->customer_phone != null){
								$data['customer_phone'] = $order->customer_phone;
							}

							$data['package_type'] = '02';
							$data['package_length'] = (string)$length;
							$data['package_width'] = (string)$width;
							$data['package_height'] = (string)$height;
							$data['package_weight'] = (string)$weight;

							$data['shipper_address'] = config('shipping.ups.shipper_address.address');
							$data['shipper_city'] = config('shipping.ups.shipper_address.city');
							$data['shipper_zip'] = config('shipping.ups.shipper_address.zip');
							$data['shipper_state'] = config('shipping.ups.shipper_address.state');
							$data['shipper_country'] = config('shipping.ups.shipper_address.country');
							
							$result = (new ShippingUPS())->createLabel($data);
							
							$order->shipping_tracking = $result['tracking_number'];
							$order->shipping_cost = $result['total_charges'];

							$upload_result = (new CFile(new File))->uploadFile([
								'owner_type' => 'order',
								'owner_id' => $order->id,
								'picture_base_64' => $result['label']['image']
							]);

							$order->save();
							return $result;
						}
					}
					break;

				default:
					# code...
					break;
			}
		}
	}

	public function duplicate($id){
		$order = Order::where('id', $id)->with(['orderItems'])->first();

		if($order){
			$newOrder = $order->replicate();
			$newOrder->save();
			if(isset($order->orderItems)){
				foreach($order->orderItems as $item){
					\DB::table('order_items')->insert([
						'order_id' => $newOrder->id,
						'item_id' => $item->item_id,
						'marketplace_id' => $item->marketplace_id,
						'marketplace_item_id' => $item->marketplace_item_id,
						'name' => $item->name,
						'description' => $item->description,
						'sku' => $item->sku,
						'qty' => $item->qty,
						'price' => $item->price,
						'taxes' => $item->taxes,
						'discount_percent' => $item->discount_percent,
						'options' => $item->options,
						'created_at' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s'),
					]);
				}
			}
			return Order::where('id', $newOrder->id)->with(['orderItems'])->first();
		}

		throw new ApiException('Order not found', '003', []);
	}
}