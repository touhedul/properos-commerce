<?php

namespace Properos\Commerce\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Properos\Commerce\Classes\CAccount;
use Properos\Base\Classes\Api;
use Properos\Users\Models\User;
use Properos\Commerce\Models\Order;
use Illuminate\Support\Facades\Auth;
use Properos\Commerce\Models\CustomerProfile;
use Properos\Commerce\Models\CustomerPaymentProfile;
use Properos\Commerce\Classes\Payment\CPayment;

class ApiAccountController extends Controller
{
    private $cAccount;
    private $user;

    public function __construct(CAccount $cAccount, User $user)
    {
        $this->cAccount = $cAccount;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
       /*  $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required'
        ]); */


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
        /* $validatedData = $request->validate([
            'id' => 'required',
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required'
        ]); */


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getBasicUserInfo($id)
    {
        if ($id > 0) {
            $user = $this->user->with('customerProfile.paymentProfiles')->find($id);
        }
        if ($user) {
            return Api::success('User info', $user);
        } else {
            return Api::error('001', 'Error getting user info');
        }
    }

    public function addAddress(Request $request)
    {
        $validatedData = $request->validate([
            'address1' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'state' => 'required',
            'country' => 'required'
        ]);

        $address = $this->cAccount->addAddress($request->all());
        if ($address) {
            return Api::success('Address added successfully', $address);
        }
        return Api::error('001', 'Error adding the address. Check if this address already exists');
    }

    public function updateAddress(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'address1' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'state' => 'required',
            'country' => 'required'
        ]);

        $address = $this->cAccount->updateAddress($request->all(), $id);
        if ($address) {
            return Api::success('Address updated successfully', $id);
        }
        return Api::error('001', 'Error updating the address. Check if this address already exists');
    }

    public function setDefaultAddress($id)
    {
        if ($id > 0) {
            if (Auth::check()) {
                $user = Auth::user();
                if (count($user->addresses) > 0) {
                    foreach ($user->addresses as $key => $address) {
                        if ($address->id == $id) {
                            $address->default = true;
                        } else {
                            $address->default = false;
                        }
                        $result = $address->save();
                    }
                }
            }
        }
        if ($result) {
            return Api::success('Address updated successfully', $id);
        }
        return Api::error('001', 'Error updating the address');
    }

    public function removeAddress($id)
    {
        if ($id > 0) {
            if (Auth::check()) {
                $user = Auth::user();
                if (count($user->addresses) > 0) {
                    foreach ($user->addresses as $key => $address) {
                        if ($address->id == $id) {
                            $address->default = false;
                            $address->save();
                            $result = $address->delete();
                        }
                    }
                }
            }
        }
        if ($result) {
            return Api::success('Address removed successfully', $id);
        }
        return Api::error('001', 'Error removing the address');
    }

    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'old_pass' => 'required',
            'new_pass' => 'required',
            'new_pass_conf' => 'required',
        ]);

        $result = $this->cAccount->changePassword($request->all());
        if ($result['status'] == 1) {
            return Api::success($result['message'], []);
        } else if ($result['status'] == 2) {
            return Api::error('001', $result['message']);
        }
    }

    public function addPaymentMethod(Request $request)
    {
        $validatedData = $request->validate([
            'card_info.token' => 'required|string',
            // 'card_info.description' => 'required|string',
            'card_info.card_holder' => 'required',
            'card_info.exp_month' => 'required',
            'card_info.exp_year' => 'required',
            'card_info.billing_address' => 'required',
            'card_info.billing_city' => 'required',
            'card_info.billing_zip' => 'required',
            'card_info.billing_state' => 'required',
            'customer_type' => 'required',
            'profile' => 'required',
            'type' => 'required',
            'save' => 'required'
        ]);

        $name = explode(' ', $request->input('card_info.card_holder'));
        $data['billing_firstname'] = ((count($name) == 1 || count($name)==2) ? $name[0] : ((count($name) > 2) ? $name[0] .' '.$name[1] : ''));
        $data['billing_lastname'] = ((count($name) == 2) ? $name[1] : ((count($name) > 2) ? $name[2] : ''));
        $data['month'] = $request->input('card_info.exp_month');
        $data['year'] = $request->input('card_info.exp_year');
        $data['billing_address'] = $request->input('card_info.billing_address');
        $data['billing_city'] = $request->input('card_info.billing_city');
        $data['billing_zip'] = $request->input('card_info.billing_zip');
        $data['billing_state'] = $request->input('card_info.billing_state');
        $data['billing_country'] = $request->input('card_info.billing_country', '');

        $data['token'] = $request->input('card_info.token');
        $data['description'] = $request->input('card_info.description' , env('APP_NAME', ''));

        $data['type'] = $request->input('type');
        $data['customer_type'] = $request->input('customer_type');
        $data['profile'] = $request->input('profile');
        $data['save'] = $request->input('save');

        if($request->input('delete') != null && $request->input('delete') > 0){
            $this->removePaymentMethod($request->input('delete'));
        }
            
        $result = $this->cAccount->addPaymentMethod($data);
        return Api::success('Payment method registered successfully.', $result);
    }

    public function removePaymentMethod($id)
    {
        if ($id > 0) {
            $result = $this->cAccount->removePaymentMethod($id);
            return Api::success('Payment method deleted successfully', [$result]);
        }
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();

        $rules = [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users,email,'.\Auth::user()->id.',id',
        ];
        $validation = \Validator::make($data, $rules);

        if($validation->passes()){
            $result = User::where('id', \Auth::user()->id)->update([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'company' => $data['company'],
            ]);
            return Api::success('Profile updated successfully', $result);
        }
        return Api::error('001',$validation->errors(),$data);
    }

    public function getPaymentMethod($id){
        if($id > 0){
            $card = CustomerPaymentProfile::where('id', $id)->with('customerProfile')->first();
            if($card){
                $cPayment = new CPayment();
                $paymentProfile = $cPayment->getPaymentProfile([
                    "profile_id" => $card->customerProfile->customer_profile_id,
                    "payment_profile_id" => $card->payment_profile_id
                ]);
                $paymentProfile['expiration_date'] = $card['expiration_date'];
                return Api::success('Payment info', $paymentProfile);
            }
            return Api::error('001','Payment not found',$id);
        }
        return Api::error('001','Invalid query request',$id);
    }
}


