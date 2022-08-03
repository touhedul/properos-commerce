<?php

namespace Properos\Commerce\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Commerce\Models\Order;
use Properos\Commerce\Classes\PaymentProcessors\PaypalClass;
use Properos\Commerce\Models\Payment;
use Properos\Commerce\Models\PaymentError;
use Properos\Commerce\Classes\Payment\CCustomerProfile;
use Properos\Base\Classes\Api;
use Properos\Base\Exceptions\ApiException;
use Properos\Commerce\Classes\CPayments;
use Properos\Base\Classes\Theme;
use Properos\Commerce\Jobs\calculateAffiliateGain;
use Properos\Commerce\Models\PaymentMethod;
use Properos\Commerce\Models\CustomerProfile;

class ApiPaymentController extends Controller
{
    private $billing_email 	 = "billing@properos.com";

    public function payed() {

		$tx = $_REQUEST;
		$paypal = new PaypalClass();
		if (isset($_REQUEST["tx"])) {
            
            $order = Order::where([['order_number', $tx['item_number']],['status','<>','paid']])->first();
            
            $trans_verify = $paypal->retrieve_transaction($_REQUEST["tx"]);
            $tx["verification"] = $trans_verify;
            
			if ((is_array($trans_verify)) && (isset($trans_verify["SUCCESS"])))  {
				$r_id    = $trans_verify["item_number"];
				$r_total = $trans_verify["payment_gross"];

                if($order){
                    $order->status = 'paid';
                    $order->paid_amount = $r_total;
                    $order->save();
                    $custom = json_decode(urldecode($trans_verify['custom']));
                    
                    $payment = new Payment();
                    $payment->payable_type = 'orders';
                    $payment->payable_id = $order->id;
                    $payment->type = 'paypal';
                    $payment->user_id = 0;
                    if(isset($custom->user_id)){
                        $payment->user_id = $custom->user_id;
                    }
                    $payment->customer_email = $trans_verify["payer_email"];
                    $payment->operation = 'charge';
                    $payment->amount = $r_total;
                    $payment->fee = $trans_verify["mc_fee"];
                    $payment->account_type = 'paypal';
                    $payment->account_id = $trans_verify['payer_id'];
                    $payment->description = 'A paypal charge operation';
                    $payment->transaction_id = $trans_verify['txn_id'];
                    $payment->transaction_status = 'success';
                    $payment->provider = 'paypal';
                    $payment->save();
                    
                    \Mail::raw(json_encode($tx), function ($message) {
                        $message->to(env('MAIL_FROM_ADDRESS', 'support@properos.com')); 
                        $message->subject('IPN Verified - INSIDE');
                    });
                    dispatch(new calculateAffiliateGain($payment->toArray()));
                    return redirect('orders/order-confirmation/'.$order->id.'/1');
                    // return redirect()->action(
                    //     'Properos\Commerce\Controllers\Order\OrderController@getOrderConfirmation', ['id' => $order->id, 'status'=>1]
                    // );
                }
                return redirect('orders/order-confirmation/'.$r_id.'/1');
                // return redirect()->action(
                //     'Properos\Commerce\Controllers\Order\OrderController@getOrderConfirmation', ['id' => $r_id, 'status'=>1]
                // );
                // return redirect()->route('orders/order-confirmation', ['id' => $r_id, 'status'=>1]);
			} else {
                if($order){
                    $order->status = 'declined';
                    $order->save();
                    $custom = json_decode(urldecode($trans_verify['custom']));
                    
                    $payment_error = new PaymentError();
                    $payment_error->user_id = 0;
                    if(isset($custom->user_id)){
                        $payment_error->user_id = $custom->user_id;
                    }
                    $payment_error->customer_email = $trans_verify["payer_email"];
                    $payment_error->order_id = $order->id;
                    $payment_error->provider = 'paypal';
                    $payment_error->error_response = json_encode($tx);
                    $payment_error->save();
                    
                }
                \Mail::raw(json_encode($tx), function ($message) {
                    $message->to(env('MAIL_FROM_ADDRESS', 'support@properos.com'));
                    $message->subject('UNVERIFIED Paypal Transaction');
                });
                return redirect('orders/order-confirmation/'.$order->id.'/0');
                // return redirect()->action(
                //     'Properos\Commerce\Controllers\Order\OrderController@getOrderConfirmation', ['id' => $order->id, 'status'=>0]
                // );
			}
		} else {
            \Mail::raw(json_encode($tx), function ($message) {
                $message->to(env('MAIL_FROM_ADDRESS', 'support@properos.com'));
                $message->subject('Payment URL Call Error');
            });
            return redirect('orders/order-confirmation/0/0');
			// return redirect()->action(
            //     'Properos\Commerce\Controllers\Order\OrderController@getOrderConfirmation', ['id' => 0, 'status'=>0]
            // );
		}
    }
    
    public function cancelPayed()
    {
        if(Theme::get() == 'properos'){
            return \Redirect::back();
        }
        return redirect('cart/checkout');
    }

    public function addPaymentMethod(Request $request)
    {
        $type  = $request->input('type', '');

        if($type == 'card'){
            $validatedData = $request->validate([
                // 'user_id' => 'required',
                'card_info.token' => 'required|string',
                // 'card_info.description' => 'required|string',
                'card_info.card_holder' => 'required',
                'card_info.exp_month' => 'required',
                'card_info.exp_year' => 'required',
                'card_info.billing_zip' => 'required',
                'customer_type' => 'required',
                'profile' => 'required',
                'type' => 'required',
                'save' => 'required',
                // 'order_id' => 'required',
                'last_4' => 'required',
            ]);
            $name = explode(' ', $request->input('card_info.card_holder'));
            $data['billing_firstname'] = ((count($name) == 1 || count($name)==2) ? $name[0] : ((count($name) > 2) ? $name[0] .' '.$name[1] : ''));
            $data['billing_lastname'] = ((count($name) == 2) ? $name[1] : ((count($name) > 2) ? $name[2] : ''));
            $data['month'] = $request->input('card_info.exp_month');
            $data['year'] = $request->input('card_info.exp_year');
            $data['billing_address'] = $request->input('card_info.billing_address', '');
            $data['billing_city'] = $request->input('card_info.billing_city', '');
            $data['billing_zip'] = $request->input('card_info.billing_zip');
            $data['billing_state'] = $request->input('card_info.billing_state', '');
            $data['billing_country'] = $request->input('card_info.billing_country', '');
    
            $data['token'] = $request->input('card_info.token');
            $data['description'] = $request->input('card_info.description' , env('APP_NAME', ''));
            $data['customer_type'] = $request->input('customer_type');
            $data['profile'] = $request->input('profile');
            $data['last_4'] = $request->input('last_4');

        }elseif($type == 'bank'){
            $validatedData = $request->validate([
                // 'user_id' => 'required',
                'payment_token' => 'required|string',
                'payment_descriptor' => 'required',
                'customer_name' => 'required',
                'recurring_payments' => 'required',
                'type' => 'required',
                'save' => 'required',
                // 'order_id' => 'required'
            ]);
            $name = explode(' ', $request->input('customer_name'));
            $data['billing_firstname'] = ((count($name) == 1 || count($name)==2) ? $name[0] : ((count($name) > 2) ? $name[0] .' '.$name[1] : ''));
            $data['billing_lastname'] = ((count($name) == 2) ? $name[1] : ((count($name) > 2) ? $name[2] : ''));
            $data['token'] = $request->input('payment_token');
            $data['description'] = $request->input('payment_descriptor' , env('APP_NAME', ''));
        }


        $data['user_id'] = $request->input('user_id', 0);
        $data['order_id'] = $request->input('order_id', null);
        $data['type'] = $request->input('type');
        
       
        $data['save'] = $request->input('save');

        // if($request->input('delete') != null && $request->input('delete') > 0){
        //     $this->removePaymentMethod($request->input('delete'));
        // }
        $cCustomerProfile = new CCustomerProfile(); 
        $result = $cCustomerProfile->addPaymentMethod($data);
        return Api::success('Payment method registered successfully.', $result);
    }

    public function removePaymentMethod(Request $request){
        $data = $request->all();

        $rules = [
            'user_id' => 'required',
            'payment_profile_id' => 'required',
        ];

        $validator = \Validator::make($data, $rules);

        if($validator->passes()){
            $cCustomerProfile = new CCustomerProfile();
            $result = $cCustomerProfile->removePaymentMethod($data);
            return Api::success('Payment remove successfully', $result);
        }

        throw new ApiException($validator->errors(), '002', $data);
    }

    public function payWithProfile(Request $request){
        $data = $request->all();

        $rules = [
            'user_id' => 'required',
            'order_id' => 'required',
            'payment_profile_id' => 'required',
        ];

        $validator = \Validator::make($data, $rules);

        if($validator->passes()){
            $cCustomerProfile = new CCustomerProfile();
            $result = $cCustomerProfile->payWithProfile($data);
            return Api::success('Payment received successfully', $result);
        }

        throw new ApiException($validator->errors(), '002', $data);
    }

    public function payWithCash(Request $request){
        $data = $request->all();

        $cCustomerProfile = new CCustomerProfile();
        $result = $cCustomerProfile->payWithCash($data);
        return Api::success('Payment received successfully', $result);
    }

    public function setDefaultPayment(Request $request){
        $data = $request->all();

        $cCustomerProfile = new CCustomerProfile();
        $result = $cCustomerProfile->setDefaultPaymentMethod($data);
        return Api::success('Payment received successfully', $result);
    }
    public function refundPayment(Request $request){
        $data = $request->all();

        $rules = [
            'id' => 'required',
            'amount' => 'required'
        ];

        $validator = \Validator::make($data, $rules);

        if($validator->passes()){
            $cCustomerProfile = new CCustomerProfile();
            $result = $cCustomerProfile->refundAmount($data);
            return Api::success('Amount refunded successfully', $result);
        }
        return Api::error('006',$validator->errors(),$data);
    }
    public function voidPayment(Request $request){
        $data = $request->all();

        $rules = [
            'id' => 'required',
        ];

        $validator = \Validator::make($data, $rules);

        if($validator->passes()){
            $cCustomerProfile = new CCustomerProfile();
            $result = $cCustomerProfile->voidAmount($data);
            return Api::success('Amount voided successfully', $result);
        }
        return Api::error('006',$validator->errors(),$data);
    }

    public function search(Request $request){
        $cPayments = new CPayments();
        $options = $cPayments->standardize_search($request);
        $payments = $cPayments->find($options);
        return response()->json(Api::success('Payments search result.', $payments, $cPayments->get_paginator()->toArray()));
    }

    public function getPaymentsMethods(){
        $payment_methods = CustomerProfile::where('user_id',\Auth::user()->id)->with('paymentProfiles')->first();
        if($payment_methods){
            if(isset($payment_methods->paymentProfiles) && count($payment_methods->paymentProfiles) > 0){
                return Api::success('Payment method', ['customer_payment' => true, 'payment_profile_id' => $payment_methods->paymentProfiles[0]->payment_profile_id]);
            }
        }
        return Api::success('Payment method', ['customer_payment' => false]);
    }
}
