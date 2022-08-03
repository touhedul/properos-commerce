<?php

namespace Properos\Commerce\Classes\Payment;

use Illuminate\Http\Request;
use Properos\Commerce\Classes\Payment\CPayment;
use Properos\Base\Exceptions\ApiException;
use Properos\Users\Models\User;
use Properos\Commerce\Models\CustomerProfile;
use Properos\Commerce\Models\CustomerPaymentProfile;
use Properos\Commerce\Models\Order;
use Properos\Commerce\Events\ProductQtyChanged;
use Properos\Commerce\Models\PaymentMethod;
use Properos\Commerce\Models\Payment;
use Properos\Commerce\Classes\PaymentProcessors\PaypalClass;
use Properos\Commerce\ViewModel\OrderCreatedVM;
use Properos\Commerce\Mail\OrderPaymentStatusEmail;
use Properos\Commerce\Models\Subscription;

class CCustomerProfile
{
    private $cPayment;
	private $driverName;

	function __construct()
	{
        $this->cPayment =new CPayment();
		$this->driverName = ucfirst(env('CARD_PROCESSOR'));
	}

	public function addPaymentMethod(array $data)
	{
        if (count($data) > 0) {
            $user = User::where('id',$data['user_id'])->first();
            // if ($data['type'] == 'card') {
                $customer_profile = CustomerProfile::with('paymentProfiles')->where([['user_id', $data['user_id']],['payment_processor',strtoupper(env('CARD_PROCESSOR'))]])->first();
            // } 
            if (!$customer_profile && $data['save']) {
                $data['email'] = $user->email;
                $data['user_id'] = $user->id;
                
                try {
                    $result = $this->cPayment->addCustomerProfile($data);
                    
                    $data['profile_id'] = $result["profile_id"];
                    $data['payment_profile_id'] = $result["payment_profile_id"];
                    
                    try {
                        $paymentProfile = $this->cPayment->getPaymentProfile([
                            "profile_id" => $data["profile_id"],
                            "payment_profile_id" => $data["payment_profile_id"]
                        ]);
                        
                        $_result = \DB::transaction(function () use ($data, $paymentProfile) {
                            if (array_key_exists('profile_id', $data) && array_key_exists('payment_profile_id', $data)) {
                                $customerProfile = new CustomerProfile();
                                $customerProfile->payment_processor = $this->driverName;
                                $customerProfile->customer_profile_id = $paymentProfile["profile_id"];
                                $customerProfile->user_id = $data['user_id'];
                                if ($customerProfile->save()) {
                                    $customerPaymentProfile = new CustomerPaymentProfile();
                                    $customerPaymentProfile->customer_profile_id = $customerProfile->id;
                                    $customerPaymentProfile->payment_profile_id = $paymentProfile["payment_profile_id"];
                                    if(isset($paymentProfile["card_number"]))
                                        $customerPaymentProfile->last_numbers = $paymentProfile["card_number"];
                                    if ($data['type'] == 'card') {
                                        $customerPaymentProfile->brand = $paymentProfile['brand'];
                                        $customerPaymentProfile->expiration_date = $data["month"] . '/' . $data['year'];
                                    }
                                    $customerPaymentProfile->payment_method_type = $data['type'];
                                    $customerPaymentProfile->default = false;
                                    $customerProfile->paymentProfiles()->save($customerPaymentProfile);
                                }
                            }
                            return $customerPaymentProfile;
                        }, 5);
    
                        unset($_result["customer_profile_id"]);
                        unset($_result["payment_profile_id"]);
                        
                        $result['row'] = $_result;
                    } catch (Exception $e) {
                        \DB::rollback();
                        throw $e;
                    }
                } catch (\Exception $error) {
                    $result['status'] = 2;
                    switch ($error->getCode()) {
                        case 'E00039':
                            throw new ApiException("This payment method is already registered with this customer information", "001");
                            break;
                        case 'E00042':
                            throw new ApiException("Maximum number of payment profiles reached", "001");
                            break;

                        default:
                            throw new ApiException($error->message(), "001");
                            break;
                    }
                }
            } elseif ($customer_profile && $data['save']) {
                $data['profile_id'] = $customer_profile->customer_profile_id;
                
                $result = $this->cPayment->addPaymentProfile($data);
                $data['payment_profile_id'] = $result['payment_profile_id'];
                
                try {
                    $paymentProfile = $this->cPayment->getPaymentProfile([
                        "profile_id" => $data["profile_id"],
                        "payment_profile_id" => $data["payment_profile_id"]
                    ]);
                    // if (array_key_exists("defaultPaymentProfile", $result)) {
                    // 	$data["default"] = $post['defaultPaymentProfile'];
                    // } else {
                    // 	$data["default"] = false;
                    // }
                    $_result = \DB::transaction(function () use ($data, $paymentProfile, $customer_profile) {
                        if (array_key_exists('payment_profile_id', $data)) {
                            $customerPaymentProfile = new CustomerPaymentProfile();
                            $customerPaymentProfile->payment_profile_id = $paymentProfile["payment_profile_id"];
                            if(isset($paymentProfile["card_number"])){
                                $customerPaymentProfile->last_numbers = $paymentProfile["card_number"];
                                $data['last_4'] = substr($paymentProfile["card_number"], count($paymentProfile["card_number"])-5);
                            }
                            if ($data['type'] == 'card') {
                                $customerPaymentProfile->brand = $paymentProfile["brand"];
                                $customerPaymentProfile->expiration_date = $data["month"] . '/' . $data['year'];
                            }
                            $customerPaymentProfile->default = false;
                            $customerPaymentProfile->payment_method_type = $data['type'];
                            // $customerPaymentProfile->default = $data["default"];
                            $customer_profile->paymentProfiles()->save($customerPaymentProfile);
                        }

                        return $customerPaymentProfile;
                    }, 5);

                    unset($_result["customer_profile_id"]);
                    unset($_result["payment_profile_id"]);
                    
                } catch (\Exception $e) {
                    \DB::rollback();
                    throw $e;
                }
            }

            // if (array_key_exists('payment_type', $data)) {
            if(array_key_exists('order_id', $data) && $data['order_id'] > 0){
                try {
                    $order = Order::where('id', $data['order_id'])->with('orderItems')->first();

                    $paymentInfo['amount'] = (isset($data['total_amount'])) ? $data['total_amount'] : $order->total_amount;
                    $name = explode(' ', $order->customer_name);
                    $paymentInfo['shipping_firstname'] = ((count($name) == 1 || count($name)==2) ? $name[0] : ((count($name) > 2) ? $name[0] .' '.$name[1] : ''));
                    $paymentInfo['shipping_lastname'] = ((count($name) == 2) ? $name[1] : ((count($name) > 2) ? $name[2] : ''));

                    if (isset($order->shipping_address1)) {
                        $paymentInfo['shipping_address'] = $order->shipping_address1;
                    }
                    if (isset($order->shipping_city)) {
                        $paymentInfo['shipping_city'] = $order->shipping_city;
                    }
                    if (isset($order->shipping_state)) {
                        $paymentInfo['shipping_state'] = $order->shipping_state;
                    }
                    if (isset($order->shipping_zip)) {
                        $paymentInfo['shipping_zip'] = $order->shipping_zip;
                    }
                    if (isset($order->shipping_country)) {
                        $paymentInfo['shipping_country'] = $order->shipping_country;
                    }

                    if ($data['save']) {
                        $paymentInfo['type'] = 'profile';
                        $paymentInfo['profile_id'] = $data['profile_id'];
                        $paymentInfo['payment_profile_id'] = $data['payment_profile_id'];
                        if($data['type'] == 'card'){
                            $payment_method = PaymentMethod::where('name','credit-card')->first();
                        }else{
                            $payment_method = PaymentMethod::where('name',$data['type'])->first();
                        }
                    } else {
                        if ($data['type'] == 'card') {
                            $paymentInfo['billing_firstname'] = $data['billing_firstname'];
                            $paymentInfo['billing_lastname'] = $data['billing_lastname'];

                            // if (isset($data['card_holder'])) $paymentInfo['billing_firstname'] = $data['card_holder'];
                            if (isset($data['billing_address'])) {
                                $paymentInfo['billing_address'] = $data['billing_address'];
                            }
                            if (isset($data['billing_city'])) {
                                $paymentInfo['billing_city'] = $data['billing_city'];
                            }
                            if (isset($data['billing_state'])) {
                                $paymentInfo['billing_state'] = $data['billing_state'];
                            }
                            if (isset($data['billing_zip'])) {
                                $paymentInfo['billing_zip'] = $data['billing_zip'];
                            }
                            if (isset($data['billing_country'])) {
                                $paymentInfo['billing_country'] = $data['billing_country'];
                            }

                            $paymentInfo['type'] = $data['type'];
                            if (array_key_exists('token', $data)) {
                                $paymentInfo['token'] = $data['token'];
                            }
                            if (array_key_exists('description', $data)) {
                                $paymentInfo['description'] = $data['description'];
                            }
                            $payment_method = PaymentMethod::where('name','credit-card')->first();
                        }elseif($data['type'] == 'bank'){
                            $paymentInfo['type'] = $data['type'];
                            if (array_key_exists('token', $data)) {
                                $paymentInfo['token'] = $data['token'];
                            }
                            if (array_key_exists('description', $data)) {
                                $paymentInfo['description'] = $data['description'];
                            }
                            $paymentInfo['billing_firstname'] = $data['billing_firstname'];
                            $paymentInfo['billing_lastname'] = $data['billing_lastname'];
                            $payment_method = PaymentMethod::where('name',$data['type'])->first();
                        }
                    }
                    
                    $processPayment = $this->processPayment($paymentInfo);
                    $order->paid_amount = $paymentInfo['amount'];

                    $order->status = 'paid';
                    $order->payment_method_id = $payment_method->id;
                    $order->save();

                    if($order->subscription_id > 0){
                        $subs = Subscription::where('id', $order->subscription_id)
                                            ->first();
                        if($subs){
                            // if ($subs->total_payments > $subs->payments_made) {
                                $subs->last_payment_date = date('Y-m-d H:i:s');
                                $subs->payments_made = $subs->payments_made + 1;
                                $subs->last_payment = $order->paid_amount;
                            // }

                            $subs->expires_at = date('Y-m-d H:i:s', strtotime($subs->next_billing_date . '+'.($subs->days_to_pay+$subs->grace_days).' day'));
                            
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
                    $processPayment['last_4'] = isset($data['last_4']) ? $data['last_4'] : null;
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

                    if ($error = $this->registerErrorPayment($error->message(), $paymentInfo, $order, $provider = strtolower(env('CARD_PROCESSOR', 'authorize')) > 0)) {
                        if ($error > 0) {
                            $result = $order;
                            throw new ApiException($message, "E001", $order);
                        }
                    }
                }
                return $result;
            }else{
                return $_result;
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
    
    public function registerSuccessPayment($data, $order, $provider)
	{
		$this->decreaseItemQtyFromOrder($order, true);

		if (count($data) > 0) {
			$new_data = [
				'payable_type' => 'orders',
				'payable_id' => $order->id,
				'user_id' => $order->user_id,
				'customer_email' => isset($data['customer_email']) ? $data['customer_email'] : $order->customer_email,
				'operation' => $data['operation'],
				'amount' => isset($data['amount']) ? $data['amount'] : $order->paid_amount,
				'fee' => isset($data['transaction_fee']) ? $data['transaction_fee'] : null,
				'transaction_id' => $data['transaction_id'],
				'ref_transaction_id' => isset($data['ref_transaction_id']) ? $data['ref_transaction_id'] : null,
				'auth_code' => (isset($data['authcode'])) ? $data['authcode'] : null,
				'transaction_status' => isset($data['transaction_status']) ? $data['transaction_status'] : 'success',
				'provider' => $provider,
				'account_type' => isset($data['account_type']) ? $data['account_type'] : 'local',
				'account_id' => isset($data['account_id']) ? $data['account_id'] : 0,
				'description' => isset($data['description']) ? $data['description'] : null,
				'last_4' => isset($data['last_4']) ? $data['last_4'] : null,
				'created_at' => date('Y-m-d H:i:s')
			];

			$payment_method = PaymentMethod::find($order->payment_method_id);

			if ($payment_method->name == 'credit-card' || $payment_method->name == 'card') {
                $new_data['type'] = 'card';
                $new_data['description'] = (isset($data['description']) && $data['description'] != '') ? $data['description'] : 'Card payment operation';
			} else if ($payment_method->name == 'paypal') {
				$new_data['type'] = 'paypal';
				$new_data['description'] = (isset($data['description']) && $data['description'] != '') ? $data['description'] : 'A paypal charge operation';
			} else if ($payment_method->name == 'cheque') {
				$new_data['type'] = 'cheque';
				$new_data['description'] = (isset($data['description']) && $data['description'] != '') ? $data['description'] : 'Check payment operation';
			} else if ($payment_method->name == 'cash') {
				$new_data['type'] = 'cash';
				$new_data['description'] = (isset($data['description']) && $data['description'] != '') ? $data['description'] : 'Cash payment operation';
			} else if ($payment_method->name == 'bank') {
				$new_data['type'] = 'bank';
				$new_data['description'] = (isset($data['description']) && $data['description'] != '') ? $data['description'] : 'Bank Account payment operation';
			}

			// if ($order->user_id) {
			// 	$new_data['user_id'] = $order->user_id;
			// } else {
			// 	$new_data['customer_email'] = $order->customer_email;
			// }

			$result = \DB::table('payments')->insertGetId($new_data);
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
			}
            $new_data['customer_email'] = $order->customer_email;
            $new_data['created_at'] = date('Y-m-d H:i:s');
            $new_data['updated_at'] = date('Y-m-d H:i:s');
			$result = \DB::table('payment_errors')->insertGetId($new_data);
		}
		return $result;
    }
    
    public function decreaseItemQtyFromOrder($order, $sync = true)
	{
		if (count($order->orderItems) > 0) {
			foreach ($order->orderItems as $key => $orderItem) {
                if($orderItem->item_id > 0){
                    $item = $orderItem->item;
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

    public function payWithProfile($data){
		$customer_profile = CustomerProfile::where('user_id',$data['user_id'])->first();
		if ($customer_profile) {
			$paymentInfo['profile_id'] = $customer_profile->customer_profile_id;
			$paymentInfo['payment_profile_id'] = $data['payment_profile_id'];
			$paymentInfo['type'] = 'profile';

			$order = Order::where('id', $data['order_id'])->with('orderItems')->first();

			$paymentInfo['amount'] = (isset($data['total_amount'])) ? $data['total_amount'] : $order->total_amount;
			$name = explode(' ', $order->customer_name);
            $paymentInfo['shipping_firstname'] = ((count($name) == 1 || count($name)==2) ? $name[0] : ((count($name) > 2) ? $name[0] .' '.$name[1] : ''));
            $paymentInfo['shipping_lastname'] = ((count($name) == 2) ? $name[1] : ((count($name) > 2) ? $name[2] : ''));

			if (isset($order->shipping_address1)) {
				$paymentInfo['shipping_address'] = $order->shipping_address1;
			}
			if (isset($order->shipping_city)) {
				$paymentInfo['shipping_city'] = $order->shipping_city;
			}
			if (isset($order->shipping_state)) {
				$paymentInfo['shipping_state'] = $order->shipping_state;
			}
			if (isset($order->shipping_zip)) {
				$paymentInfo['shipping_zip'] = $order->shipping_zip;
			}
			if (isset($order->shipping_country)) {
				$paymentInfo['shipping_country'] = $order->shipping_country;
			}
			$customerPaymentProfile = CustomerPaymentProfile::where('payment_profile_id',$data['payment_profile_id'])->first();
			if($customerPaymentProfile->payment_method_type == 'card'){
				$payment_method = PaymentMethod::where('name','credit-card')->first();
			}else{
				$payment_method = PaymentMethod::where('name',$customerPaymentProfile->payment_method_type)->first();
            }
            
            try{
                $processPayment = $this->processPayment($paymentInfo);
                
                $order->paid_amount = $paymentInfo['amount'];
                
                $order->status = 'paid';
                $order->payment_method_id = $payment_method->id;
                $order->save();

                if($order->subscription_id > 0){
                    $subs = Subscription::where('id', $order->subscription_id)
                                        ->first();

                    // if ($subs->total_payments > $subs->payments_made) {
                        $subs->last_payment_date = date('Y-m-d H:i:s');
                        $subs->payments_made = $subs->payments_made + 1;
                        $subs->last_payment = $order->paid_amount;
                    // }

                    $subs->expires_at = date('Y-m-d H:i:s', strtotime($subs->next_billing_date . '+'.($subs->days_to_pay+$subs->grace_days).' day'));
                    
                    $last_order = Order::where('subscription_id', $order->subscription_id)->where('status', 'pending')->first();
                    
                    if($last_order){
                        Subscription::where('id', $order->subscription_id)->update(['next_payment_date' => $last_order->created_at]);
                    }else{
                        $subs->next_payment_date = $subs->next_billing_date;
                    }
                    $subs->save();
                }

                $processPayment['operation'] = 'charge';
                if($customerPaymentProfile->payment_method_type == 'card'){
                    $processPayment['last_4'] = substr($customerPaymentProfile->last_numbers, count($customerPaymentProfile->last_numbers)-5);
                }
                if ($this->registerSuccessPayment($processPayment, $order, $provider = strtolower(env('CARD_PROCESSOR', 'authorize'))) > 0) {
                    $result = $order;
                }
                return $result;
            }catch (ApiException $error) {
                // $order->status = 'pay__error';
                // $order->save();
                if (!is_array($error->messages())) {
                    $message = $error->messages()->messages();
                } else {
                    $message = $error->messages();
                }

                if ($error = $this->registerErrorPayment($error->message(), $paymentInfo, $order, $provider = strtolower(env('CARD_PROCESSOR', 'authorize')) > 0)) {
                    if ($error > 0) {
                        $result = $order;
                        throw new ApiException($message, "E001", $order);
                    }
                }
            }
		}
		throw new ApiException("There was an inssue with this payment method, please try again with another one.", '002', []);
    }
    
	public function removePaymentMethod($data){
		$customer_profile = CustomerProfile::where('user_id',$data['user_id'])->first();
		
		if ($customer_profile) {
			$data['profile_id'] = $customer_profile->customer_profile_id;
			$data['payment_profile_id'] = $data['payment_profile_id'];
			
			if (isset($data['profile_id']) && isset($data['payment_profile_id'])) {
				try{
					$cPayment = new CPayment();
					$_result = $cPayment->delPaymentProfile($data);
					$payment_profile = CustomerPaymentProfile::where('payment_profile_id', $data['payment_profile_id'])->first();
					if (!$payment_profile->delete()) {
						throw new ApiException('Error removing payment method.', "001");
					}
					return $payment_profile->id;
				}catch(\Exception $error){
					switch ($error->getCode()) {
						case 'E00040':
							throw new ApiException('The resource you are trying to remove is not longer exist in this system.', "001");
							break;
						default:
							throw new ApiException($error->getMessage(), "001");
							break;
					}
				}
			}
		}
		throw new ApiException('Error removing payment method.', "001");
	}

	public function payWithCash($data)
    {
        $validator = \Validator::make($data, [
            'id' => 'required|integer',
            'amount' => 'required',
            'payment_method_type' => 'required'
        ]);
        if ($validator->passes()) {
            $order = Order::find($data['id']);
            if ($order) {
                $payment_info = [];
                $payment_info['operation'] = 'received';
                $payment_info['user_id'] = $order->user_id;
                if (isset($data['description'])) {
                    $payment_info['description'] = $data['description'];
                }
				$payment_info['amount'] = $data['amount'];

                $payment_info['payment_type'] = $data['payment_method_type'];
                $payment_info['response_code'] = 'approved';
                $payment_info['transaction_id'] = 0;
                $payment_info['fee'] = 0;

				$payment_method = PaymentMethod::where('name','cash')->first();

				$order->status = 'paid';
				$order->paid_amount = $payment_info['amount'];
				$order->payment_method_id = $payment_method->id;
				$order->save();

                if($order->subscription_id > 0){
                    $subs = Subscription::where('id', $order->subscription_id)
                                        ->first();
                    // if ($subs->total_payments > $subs->payments_made) {
                        $subs->last_payment_date = date('Y-m-d H:i:s');
                        $subs->payments_made = $subs->payments_made + 1;
                        $subs->last_payment = $order->paid_amount;
                    // }

                    $subs->expires_at = date('Y-m-d H:i:s', strtotime($subs->next_billing_date . '+'.($subs->days_to_pay+$subs->grace_days).' day'));
                    
                    $last_order = Order::where('subscription_id', $order->subscription_id)->where('status', 'pending')->first();
                    
                    if($last_order){
                        Subscription::where('id', $order->subscription_id)->update(['next_payment_date' => $last_order->created_at]);
                    }else{
                        $subs->next_payment_date = $subs->next_billing_date;
                    }
                    $subs->save();
                }
                
				if ($this->registerSuccessPayment($payment_info, $order, "cash")) {
					$result = $order;
				}
				return $result;
            }
            throw new ApiException("Order not found", '404', []);
        } else {
            throw new ApiException($validator->errors(), '004', $data);
        }
	}
	
	public function setDefaultPaymentMethod($data)
    {
        $validator = \Validator::make($data, [
            'payment_profile_id' => 'required',
            'user_id' => 'required'
        ]);
        if ($validator->passes()) {
            $customer_profile = CustomerProfile::where('user_id', $data['user_id'])->first();
            if ($customer_profile) {
                if ($customer_profile->paymentProfiles->count() > 0) {
                    foreach ($customer_profile->paymentProfiles as $key => $payment_profile) {
                        if ($payment_profile->payment_profile_id == $data['payment_profile_id']) {
                            $payment_profile->default = 1;
                        } else {
                            $payment_profile->default = 0;
                        }
                        $payment_profile->save();
                    }
                }
                return $customer_profile->paymentProfiles;
            } else {
                throw new ApiException("Payment profile not found", '404', []);
            }
		}
		throw new ApiException($validator->errors(), '004', $data);
    }

    public function refundAmount($data){
        $payment = Payment::where([['id',$data['id']]])->first();
        // dd($payment);
        if($payment){
            if(($payment->amount - $payment->refund_amount) >= $data['amount']){

                if ($payment->payable_type == 'orders') {
                    $model = Order::where('id', $payment->payable_id)->first();
                }

                if ($payment->account_type != 'paypal') {
                    $refund = [
                        'amount'=> $data['amount'],
                        'transaction_id'=>$payment->transaction_id,
                        'ref_trans_id'=>$payment->transaction_id
                    ];
                    if (!isset($data['manual'])) {
                        if ($payment->type == 'card') {
                            $refund['card_number'] = $payment->last_4;
                            $refund['expiration_date'] = 'XXXX';
                        }
                    } else {
                        $refundProcess['description'] = "Manually Refund";
                    }
                    
                    $cPayment = new CPayment();
                    try {
                        if (!isset($data['manual'])) {
                            $refundProcess = $cPayment->refundTransaction($refund);
                            $refundProcess['ref_transaction_id'] = $payment->transaction_id;
                        } else {
                            $refundProcess['type'] = 'cash';
                            $refundProcess['ref_transaction_id'] = $payment->id;
                            $refundProcess['transaction_id'] = 0;
                        }

                        $refundProcess['operation'] = 'refund';
                        $refundProcess['payable_type'] = $payment->payable_type;
                        $refundProcess['payable_id'] = $payment->payable_id;
                        $refundProcess['amount'] = $data['amount'];
                        $refundProcess['description'] = isset($data['description']) ? $data['description'] : 'Refund Operation';
                        
                        if ($payment->payable_type == 'orders') {
                            $refundProcess['user_id'] = $model->user_id;
                            $refundProcess['customer_email'] = $model->customer_email;
                        }
                        if ($this->registerSuccessPayment($refundProcess, $model, $provider = strtolower(env('CARD_PROCESSOR', 'authorize')))) {
                            $result = $model;
                        }
                        $model->refund_amount = $model->refund_amount + $data['amount'];
                        $model->save();
                        $payment->refund_amount = $payment->refund_amount + $data['amount'];
                        $payment->save();

                        $email_data = new OrderCreatedVM(
                            env('MAIL_FROM_ADDRESS', 'support@properos.com'),
                            $model,
                            $model->customer_name,
                            $model->customer_email
                        );
                        \Mail::to($model->customer_email)->queue(new OrderPaymentStatusEmail($email_data));

                        return $result;
                    } catch (ApiException $error) {
                        // $model->status = 'pay__error';
                        // $model->save();
                        if ($error->code() == 54) {
                            throw new ApiException('Sorry, the refund can\'t be processed. Wait 24 hours to try again', '006', $data);
                        }
                        if (!is_array($error->messages())) {
                            $message = $error->messages()->messages();
                        } else {
                            $message = $error->messages();
                        }

                        if ($error = $this->registerErrorPayment($error->message(), $refund, $model, $provider = strtolower(env('CARD_PROCESSOR', 'authorize')))) {
                            if ($error > 0) {
                                $result = $model;
                                throw new ApiException($message, "E001", $model);
                            }
                        }
                    }
                }else{
                    return $this->returnPaypalPayment($data, $payment, $model);
                }
            }
            throw new ApiException('Amount can\'t be refund', '005', $data);
        }

        throw new ApiException('Payment Not Found', '005', $data);
    }

    public function voidAmount($data){ 
        $payment = Payment::where([['id',$data['id']],['operation','charge']])->first();
        if($payment){
            if($payment->refund_amount == 0){

                if($payment->payable_type == 'orders'){
                    $model = Order::where('id',$payment->payable_id)->first();
                }

                if($payment->account_type != 'paypal'){
                    $void = [ 
                        'transaction_id'=>$payment->transaction_id,
                        'ref_trans_id'=>$payment->transaction_id
                    ];
                    $cPayment = new CPayment();

                    try{
                        $voidProcess = $cPayment->voidTransaction($void);
    
                        $voidProcess['operation'] = 'void';
                        $voidProcess['ref_transaction_id'] = $payment->transaction_id;
                        $voidProcess['payable_type'] = $payment->payable_type;
                        $voidProcess['payable_id'] = $payment->payable_id;
                        $voidProcess['amount'] = $payment->amount;
                        $voidProcess['last_4'] = $payment->last_4;
                        $voidProcess['description'] = isset($data['description']) ? $data['description'] : 'Void Operation';

                        if($payment->payable_type == 'orders'){
                            $voidProcess['user_id'] = $model->user_id;
                            $voidProcess['customer_email'] = $model->customer_email;
                        }
                        
                        if ($this->registerSuccessPayment($voidProcess, $model, $provider = strtolower(env('CARD_PROCESSOR', 'authorize')))) {
                            $result = $model;
                        }
                        $model->refund_amount = $voidProcess['amount'];
                        $model->save();
                        $payment->refund_amount = $payment->refund_amount + $voidProcess['amount'];
                        $payment->save();

                        $email_data = new OrderCreatedVM(
                            env('MAIL_FROM_ADDRESS', 'support@properos.com'),
                            $model,
                            $model->customer_name,
                            $model->customer_email
                        );
                        \Mail::to($model->customer_email)->queue(new OrderPaymentStatusEmail($email_data));

                        return $result;
                    }catch (ApiException $error) {
                        // $model->status = 'pay__error';
                        // $model->save();
                        if($error->code() == 54){
                            throw new ApiException('Sorry, the void can\'t be processed. Wait 24 hours to make a refund','006',$data);
                        }
                        if (!is_array($error->messages())) {
                            $message = $error->messages()->messages();
                        } else {
                            $message = $error->messages();
                        }
    
                        if ($error = $this->registerErrorPayment($error->message(), $void, $model, $provider = strtolower(env('CARD_PROCESSOR', 'authorize')))) {
                            if ($error > 0) {
                                $result = $model;
                                throw new ApiException($message, "E001", $model);
                            }
                        }
                    }
                }else{
                    return $this->returnPaypalPayment($data, $payment, $model);
                }
                
            }
            throw new ApiException('Amount can\'t be voided', '005', $data);
        }

        throw new ApiException('Payment Not Found', '005', $data);
    }

    public function returnPaypalPayment($data, $payment, $model){
        $amount = isset($data['amount']) ? $data['amount'] : $payment->amount;
        
        $cPayment = new PaypalClass();
        try{
            $res = $cPayment->returnPayment($payment->transaction_id, $model->order_number, $amount);
            
            $voidProcess['operation'] = $res['data']['operation'];
            $voidProcess['transaction_status'] = $res['data']['state'];
            $voidProcess['transaction_id'] = $res['data']['id'];
            $voidProcess['description'] = $res['data']['description'];
            $voidProcess['provider'] = 'paypal';
            $voidProcess['ref_transaction_id'] = $payment->transaction_id;
            $voidProcess['payable_type'] = $payment->payable_type;
            $voidProcess['payable_id'] = $payment->payable_id;
            $voidProcess['account_id'] = $payment->account_id;
            $voidProcess['account_type'] = $payment->account_type;
            $voidProcess['amount'] = $amount;
            $voidProcess['customer_email'] = $payment->customer_email;

            if($payment->payable_type == 'orders'){
                $voidProcess['user_id'] = $model->user_id;
            }
            
            
            if ($this->registerSuccessPayment($voidProcess, $model, $provider = 'paypal')) {
                $result = $model;
            }
            $model->refund_amount = $payment->refund_amount + $voidProcess['amount'];
            $model->status = 'refunded';
            $model->save();
            $payment->refund_amount = $payment->refund_amount + $voidProcess['amount'];
            $payment->save();

            $email_data = new OrderCreatedVM(
                env('MAIL_FROM_ADDRESS', 'support@properos.com'),
                $model,
                $model->customer_name,
                $model->customer_email
            );
            \Mail::to($model->customer_email)->queue(new OrderPaymentStatusEmail($email_data));
            
            return $result;
        }catch (ApiException $error) {
            if($error->code() == 54){
                throw new ApiException('Sorry, the void can\'t be processed. Wait 24 hours to make a refund','006',$data);
            }
            if (!is_array($error->messages())) {
                $message = $error->messages()->messages();
            } else {
                $message = $error->messages();
            }

            if ($error = $this->registerErrorPayment($error->message(), ['transaction_id' => $payment->transaction_id,'ref_transaction_id' => $payment->transaction_id], $model, $provider = 'paypal')) {
                if ($error > 0) {
                    $result = $model;
                    throw new ApiException($message, "E001", $model);
                }
            }
        }
    }

}