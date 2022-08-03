<?php

namespace Properos\Commerce\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Commerce\Classes\COrder;
use Properos\Commerce\Models\Order;
use Properos\Base\Classes\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Validation\ValidationException;
use Properos\Commerce\Models\Payment;
use Properos\Commerce\Classes\Payment\CCustomerProfile;
use Properos\Base\Exceptions\ApiException;

class ApiOrderController extends Controller
{
    private $cOrder;
    private $order;

    public function __construct(COrder $cOrder, Order $order)
    {
        $this->cOrder = $cOrder;
        $this->order = $order;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_items' => 'required',
            'total_amount' => 'required',
            'user_id' => 'required'
        ]);

        $order = $this->cOrder->store($request->all());
        if ($order != null) {
            return Api::success('Order created successfully', $order);
        }
        return Api::error('006', 'Error creating the order', []);
    }

    public function placeOrder(Request $request)
    {
        $rules = [
            'id' => 'required|numeric|min:1',
            'total_amount' => 'required',
            'payment_type' => 'required',
            'order_items' => 'required'
        ];

        if(\Auth::guest()){
            $rules['customer_name'] = 'required|max:255';
            $rules['customer_email'] = 'required|max:255';
        }

        try{
            $validatedData = $request->validate($rules);
        }catch(ValidationException $exception){
            return response()->json([
                'status' => 0,
                'msg' => 'Error',
                'errors' => $exception->errors(),
                'code' => '002'
            ], 200);
        }
        $result = $this->cOrder->placeOrder($request->all());
        
        if (is_array($result) && !isset($result['type'])) {
            return Api::success('Order created successfully', ['order_id' => $result['id']]);
        } else {
            return Api::success('Order created successfully', $result);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
          /*  $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required'
        ]); */


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $validatedData = $request->validate([
                'id' => 'required',
                'order_items' => 'required',
                'total_amount' => 'required',
                'user_id' => 'required',
                'order_number' => 'nullable|unique:orders,order_number,'.$id.',id',
            ]);

            $order = $this->cOrder->update($request->all(), $id);
            if ($order != null) {
                return Api::success('Order updated successfully', $order);
            }
            return Api::error('Error updating the order', []);
        }catch (ValidationException $exception) {
            return response()->json([
                'status' => 0,
                'msg' => 'Error',
                'errors' => $exception->errors(),
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id > 0) {
            \DB::table('cart_items')->where('order_id', $id)->delete();
            $result = $this->order->destroy($id);
            if ($result == true) {
                return Api::success('Order deleted successfully', []);
            } else {
                return Api::error('001', 'Error removing the order');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id, $email = null)
    {
        if (!$email) {
            $email = null;
        } else {
            $email = $email;
            $val = [
                'email' => $email
            ];
        }
        if ($id > 0) {
            if (Auth::check()) {
                $order = $this->order->find($id);
                if ($order) {
                    if(date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($order->created_at)))){
                        return Api::error('001', 'Order can\'t be cancelled. Please, contact admin.');
                    }
                    if ($order->user_id == Auth::user()->id) {
                        $payment = Payment::where([['payable_type','orders'],['payable_id',$order->id]])->first();

                        if($payment){
                            $cCustomerProfile = new CCustomerProfile();
                            try {
                                $result = $cCustomerProfile->voidAmount($payment->toArray());
                            }catch(ApiException $error){
                                if (!is_array($error->messages())) {
                                    $message = $error->messages()->messages();
                                } else {
                                    $message = $error->messages();
                                }
                                $message[] = ' Please, contact us.';
                                throw new ApiException($message, $error->code(), ['id' => $id]);
                            }
                        }
                        $order->status = 'cancelled';
                        if ($order->save()) {
                            return Api::success('Order cancelled successfully', []);
                        } else {
                            return Api::error('001', 'Error cancelling the order');
                        }
                    }
                }else {
                    return Api::error('404', 'Order not found');
                }
            } else {
                $validator = Validator::make($val, [
                    'email' => 'required|email'
                ]);
                if($validator->passes()){
                    $order = $this->order->find($id);
                    if ($order) {
                        if(date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($order->created_at)))){
                            return Api::error('001', 'Order can\'t be cancelled. Please, contact admin.');
                        }
                        if ($order->customer_email == $email) {
                            $payment = Payment::where([['payable_type','orders'],['payable_id',$order->id]])->first();

                            if($payment){
                                $cCustomerProfile = new CCustomerProfile();
                                try {
                                    $result = $cCustomerProfile->voidAmount($payment->toArray());
                                }catch(ApiException $error){
                                    if (!is_array($error->messages())) {
                                        $message = $error->messages()->messages();
                                    } else {
                                        $message = $error->messages();
                                    }
                                    $message[] = ' Please, contact us.';
                                    throw new ApiException($message, $error->code(), ['id' => $id]);
                                }
                            }
                            $order->status = 'cancelled';
                            if ($order->save()) {
                                return Api::success('Order cancelled successfully', []);
                            } else {
                                return Api::error('001', 'Error cancelling the order');
                            }
                        }else{
                            return Api::error('404', 'Email not match with customer email.');
                        }
                    }else {
                        return Api::error('404', 'Order not found');
                    }
                }else{
                    return Api::error('404', $validator->errors());
                }
                
            }

        } else {
            return Api::error('404', 'Order not found');
        }
    }

    public function userOrders()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $orders = $this->order->with(['orderItems.item.files' => function ($q) {
                return $q->where('type', 'image');
            }])->with('shippingMethod')->where('user_id', $user->id)->orderBy('created_at', 'desc')->where('status', '!=', 'pay__error')
                ->where('status', '!=', 'cart')->paginate(3)->toJson();

            return $orders;
        }
    }

    public function trackingOrder($carrier, $number)
    {
        $payload_info['carrier'] = $carrier;
        $payload_info['tracking_number'] = $number;
        $tracking_info = $this->cOrder->getTrackingInfo($payload_info);
        
        if (isset($tracking_info['tracking_info'])) {
            return Api::success('Tracking info', $tracking_info['tracking_info']);
        }
    }

    public function getShippingInfo(Request $request, $id)
    {
        $payload_info['order_id'] = $id;
        $payload_info['carrier'] = $request->input('carrier');
        $payload_info['address']['address'] = $request->input('address');
        $payload_info['address']['city'] = $request->input('city');
        $payload_info['address']['zip'] = $request->input('zip');
        $payload_info['address']['state'] = $request->input('state');
        $payload_info['address']['country'] = $request->input('country');
        $payload_info['service_code'] = $request->input('shipping_method_code');
        $shipping_info = $this->cOrder->getShippingInfo($payload_info);
        if (isset($shipping_info['tracking_number']) && isset($shipping_info['total_charges'])) {
            return Api::success('Shipping info', $shipping_info);
        }
    }

    public function sendInvoiceByEmail(Request $request, $id)
    {
        if ($id > 0) {
            $email = $request->input('email', null);
            $subject = $request->input('subject', null);
            $result = $this->cOrder->sendInvoiceByEmail($id, $email, $subject);
            if ($result == true) {
                return Api::success('Invoice email sent successfully', []);
            } else {
                return Api::error('Error sending invoice email', []);
            }
        }
    }

    public function sendQuoteByEmail(Request $request, $id)
    {
        if ($id > 0) {
            $email = $request->input('email', null);
            $subject = $request->input('subject', null);
            $result = $this->cOrder->sendQuoteByEmail($id, $email, $subject);
            if ($result == true) {
                return Api::success('Quote email sent successfully', []);
            } else {
                return Api::error('Error sending quote email', []);
            }
        }
    }

    public function processInvoicePayment(Request $request) 
    {
        $validatedData = $request->validate([
            'type' => 'required',
            'billing_firstname' => 'required_if:type,card',
            'billing_lastname' => 'required_if:type,card',
            'exp_month' => 'required_if:type,card',
            'exp_year' => 'required_if:type,card',
            'token' => 'required_if:type,card',
            'order_id' => 'required',
            'card_processor' => 'required_if:type,card'
        ]);
        $result = $this->cOrder->processInvoicePayment($request->all());
        if ($result['type'] == 'card') {
            if ($result['status'] == 1) {
                return Api::success($result['message'], []);
            }
        } else if ($result['type'] == 'paypal') {
            return Api::success("", $result);
        }
    }

}
