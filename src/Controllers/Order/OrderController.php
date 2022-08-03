<?php

namespace Properos\Commerce\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Properos\Commerce\Classes\COrder;
use Properos\Commerce\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Properos\Commerce\Mail\OrderCreatedEmail;
use Properos\Commerce\ViewModel\OrderCreatedVM;
use Illuminate\Support\Facades\Auth;
use Properos\Commerce\Classes\CCart;
use Properos\Base\Classes\Theme;

class OrderController extends Controller
{
	private $cOrder;
	private $order;
	private $cCart;

	public function __construct(COrder $cOrder, Order $order, CCart $cCart)
	{
		$this->cOrder = $cOrder;
		$this->order = $order;
		$this->cCart = $cCart;
	}

	public function destroy($id)
	{

	}

	public function getOrderConfirmation($id, $status)
	{
		if ($status == 1) {
			if ($id > 0) {
				$order = $this->order->with(['orderItems' => function ($q) {
					$q->with(['item.files' => function ($q) {
						return $q->where('type', 'image');
					}]);
				}])->with('customer')->find($id);
				if ($order) {
					if (Auth::check()) {
						$email_data = new OrderCreatedVM(
							env('MAIL_FROM_ADDRESS', 'support@properos.com'),
							$order,
							$order->customer_name,
							$order->customer_email
						);
						Mail::to($order->customer)->queue(new OrderCreatedEmail($email_data));
					} else {
						$email_data = new OrderCreatedVM(
							env('MAIL_FROM_ADDRESS', 'support@properos.com'),
							$order,
							$order->customer_name,
							$order->customer_email
						);
						Mail::to($order->customer_email)->queue(new OrderCreatedEmail($email_data));
					}

					$email_to_system = new OrderCreatedVM(
						env('MAIL_FROM_ADDRESS', 'support@properos.com'),
						$order,
						$order->customer_name,
						$order->customer_email,
						true
					);
					Mail::to(env('MAIL_FROM_ADDRESS', 'support@properos.com'))->queue(new OrderCreatedEmail($email_to_system));
					//$cart = new CCart();
					$this->cCart->removeAll();
					return view('themes.'.Theme::get().'.order-confirm')->with(['order' => $order, 'status' => 1])
																						->with(['theme'=>Theme::get()]);
				}
			}
		}
		if ($status == 0) {
			$payment_error = DB::table('payment_errors')->where('order_id', $id)->first();
			if ($payment_error) {
				$order = $this->order->with(['orderItems' => function ($q) {
					$q->with(['item.files' => function ($q) {
						return $q->where('type', 'image');
					}]);
				}])->find($id);
				if ($order) {
					return view('themes.'.Theme::get().'.order-confirm')->with(['order' => $order, 'payment_error' => $payment_error, 'status' => 0])
																						->with(['theme'=>Theme::get()]);
				}
			}
		}
		abort('404');
	}

	public function trackingOrder($number)
	{
		$payload_info['carrier'] = 'ups';
		$payload_info['tracking_number'] = $number;

		return $this->cOrder->getTrackingInfo($payload_info);
	}

	public function openInvoice($number)
	{
		if ($number != '') {
			$order = $this->order->where('token', $number)->first();
			if ($order) {
				$client_key = "";
				$api_key = "";
				$card_processor = env('CARD_PROCESSOR');
				if(strtolower(env('CARD_PROCESSOR')) == 'authorize'){
					$client_key = env('AUTHORIZE_PUBLIC_KEY');
					$api_key = env('AUTHORIZE_API_ID');
				}else if(strtolower(env('CARD_PROCESSOR')) == 'stripe'){
					$client_key = env('STRIPE_PUBLIC_KEY');
					$api_key = env('STRIPE_SECRET_KEY');
				}
				return view('be.commerce.invoice')->with(['order' => $order, 'card_processor' => $card_processor, 'client_key' => $client_key, 'api_key' => $api_key])
																			->with(['theme'=>Theme::get()]);
			} else {
				abort('404');
			}
		}
	}

	public function publicInvoice($number)
	{
		if ($number != '') {
			$order = $this->order->where('token', $number)->first();
			if ($order && $order->status != 'cancelled') {
				$client_key = "";
				$api_key = "";
				$card_processor = env('CARD_PROCESSOR');
				if(strtolower(env('CARD_PROCESSOR')) == 'authorize'){
					$client_key = env('AUTHORIZE_PUBLIC_KEY');
					$api_key = env('AUTHORIZE_API_ID');
				}else if(strtolower(env('CARD_PROCESSOR')) == 'stripe'){
					$client_key = env('STRIPE_PUBLIC_KEY');
					$api_key = env('STRIPE_SECRET_KEY');
				}
				return view('be.commerce.invoice')->with(['order' => $order, 'card_processor' => $card_processor, 'client_key' => $client_key, 'api_key' => $api_key])
																			->with(['theme'=>Theme::get()]);
			} else {
				abort('404');
			}
		}
	}

	public function openQuote($number)
	{
		if ($number != '') {
			$order = $this->order->where('token', $number)->first();
			if ($order) {
				$client_key = "";
				$api_key = "";
				$card_processor = env('CARD_PROCESSOR');
				if(strtolower(env('CARD_PROCESSOR')) == 'authorize'){
					$client_key = env('AUTHORIZE_PUBLIC_KEY');
					$api_key = env('AUTHORIZE_API_ID');
				}else if(strtolower(env('CARD_PROCESSOR')) == 'stripe'){
					$client_key = env('STRIPE_PUBLIC_KEY');
					$api_key = env('STRIPE_SECRET_KEY');
				}
				return view('be.commerce.quote')->with(['order' => $order, 'card_processor' => $card_processor, 'client_key' => $client_key, 'api_key' => $api_key])
																			->with(['theme'=>Theme::get()]);
			} else {
				abort('404');
			}
		}
	}

	public function openPackingSlip($number)
	{
		if ($number != '') {
			$order = $this->order->where('token', $number)->first();
			if ($order) {
				$client_key = "";
				$api_key = "";
				$card_processor = env('CARD_PROCESSOR');
				if(strtolower(env('CARD_PROCESSOR')) == 'authorize'){
					$client_key = env('AUTHORIZE_PUBLIC_KEY');
					$api_key = env('AUTHORIZE_API_ID');
				}else if(strtolower(env('CARD_PROCESSOR')) == 'stripe'){
					$client_key = env('STRIPE_PUBLIC_KEY');
					$api_key = env('STRIPE_SECRET_KEY');
				}
				return view('be.commerce.invoice')->with(['order' => $order, 'card_processor' => $card_processor, 'client_key' => $client_key, 'api_key' => $api_key, 'packing'=> true])
																			->with(['theme'=>Theme::get()]);
			} else {
				abort('404');
			}
		}
	}
}