<?php

namespace Properos\Commerce\Controllers\Cart; 

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request; 
use Properos\Commerce\Models\Category; 
use Properos\Commerce\Models\Item; 
use Properos\Commerce\Classes\CCart; 
use Properos\Commerce\Models\PaymentMethod; 
use Illuminate\Support\Facades\Auth; 
use Properos\Users\Models\User;
use Properos\Base\Classes\Theme;
use Properos\Commerce\Models\Order;
use Properos\Commerce\Models\Discount;
use Properos\Commerce\Classes\COrder; 

class CartController extends Controller {

	private $mycart; 
	private $user; 
	private $payment_methods; 
	private $cOrder;
	private $order;

	function __construct(CCart $cart, User $user, COrder $cOrder, Order $order) {
		$this->mycart = $cart; 
		$this->user = $user; 
		$this->cOrder = $cOrder;
		$this->order = $order;
		
	}

	public function cartPage(Request $request) {
		$content = []; 
		$cart = $this->mycart->get(); 
		$content["cart"] = $cart["data"]; 
		return view('themes.'.Theme::get().'.cart', ['content' => $content, 'theme'=>Theme::get()]); 
	}

	public function checkout(PaymentMethod $payment_methods) {
		$this->payment_methods = $payment_methods; 

		$content = [];
		$cart = $this->mycart->get();
		$payment_methods = $this->payment_methods->where('active','1')->get();
		
		$content["cart"] = $cart["data"]; 
		if(isset($content['cart']['order_id']) && $content['cart']['order_id'] > 0){
			$order = Order::where('id',$content['cart']['order_id'])->first();
			$result = $this->cOrder->calculateSubtotalTax($content['cart']['cart'], $order);
			if($order){
				if(\Auth::check()){
					$order->total_amount = $result['amount'] + ($result['tax']*$result['tax_base']/100) + $order->shipping_cost_estimated - $order->discount_amount;
					$order->total_tax_amount = $result['tax'];
					$order->subtotal = $result['amount'];
					$order->tax_base = $result['tax_base'];
					$order->save();
				}else{
					$order->total_amount = $result['amount'] + ($result['tax']*$result['tax_base']/100) + $order->shipping_cost_estimated - $order->discount_amount;
					$order->total_tax_amount = $result['tax'];
					$order->subtotal = $result['amount'];
					$order->tax_base = $result['tax_base'];
					$order->save();
				}
				$content['order'] = $order;
				if($order->discount_code != null && $order->discount_code != '' && $order->discount_amount > 0){
					$content['discount'] = Discount::where('code',$order->discount_code)->first();
				}
			}
		}
		
		$content["payment_methods"] = $payment_methods; 

		if (config('shipping.ups.api_integration') == true) {
			$payload_info['carrier'] = 'ups'; 
		}else {
			$payload_info['carrier'] = ''; 
		}
		
		if (config('shipping.ups.api_integration') == true) {
			$shipping_integration = true; 
		}else {
			$shipping_integration = false; 
		}
		$available_methods = $this->mycart->getAvailableShippingMethods($payload_info); 
		$available_shipping_methods = []; 
		if (isset($available_methods['methods'])) {
			$available_shipping_methods = $available_methods['methods']; 
		}else if (isset($available_methods['error_code'])) {
			$available_shipping_methods = []; 
		}
		
		if (Auth::check()) {
			$account = $this->user->with('profile')->with('addresses')->with(['customerProfile' => function($query) {
				$query->where('payment_processor', '=', strtoupper(env('CARD_PROCESSOR'))); 
				$query->with('paymentProfiles'); 
			}])->find(Auth::user()->id); 
			
			if ($account) {
				return view('themes.'.Theme::get().'.checkout', [
					'content' => $content, 
					'payment_methods' => $payment_methods, 
					'account' => $account, 
					'shipping_integration' => $shipping_integration, 
					'available_shipping_methods' =>  ! empty($available_shipping_methods)?$available_shipping_methods:[],
					'theme' => Theme::get()
				]); 
			}
		}
		return view('themes.'.Theme::get().'.checkout', [
			'content' => $content, 
			'payment_methods' => $payment_methods,
			'shipping_integration' => $shipping_integration,  
			'available_shipping_methods' =>  ! empty($available_shipping_methods)?$available_shipping_methods:[],
			'theme' => Theme::get()
		]); 
	}
}