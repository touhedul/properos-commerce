<?php

namespace Properos\Commerce\Classes;

use Illuminate\Http\Request;
use Properos\Commerce\Models\Order;
use Properos\Commerce\Models\OrderItem;
use Properos\Users\Models\User;
use Properos\Users\Models\UserProfile;
use Properos\Commerce\Models\CustomerProfile;
use Properos\Commerce\Models\CustomerPaymentProfile;
use Properos\Users\Models\UserAddress;
use Properos\Commerce\Classes\Payment\CPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Properos\Base\Exceptions\ApiException;


class CAccount
{
	private $cPaymentEloquent;
	private $cPayment;
	private $driverName;

	function __construct(CPayment $cPayment)
	{
		$this->user = new User();
		$this->user_profile = new UserProfile();
		$this->user_address = new UserAddress();

		$this->cPayment = $cPayment;
		$this->driverName = ucfirst(env('CARD_PROCESSOR'));
	}

	public function store(array $data)
	{

	}

	public function update(array $data, $id)
	{

	}

	public function destroy($id)
	{

	}

	public function addAddress(array $data)
	{
		if (Auth::check()) {
			$user = Auth::user();
			$addresses = $this->user_address->where('user_id', $user->id)->get();
			if (count($addresses) > 0) {
				foreach ($addresses as $key => $address) {
					if ($address->address1 == $data['address1']
						&& $address->city == $data['city']
						&& $address->zip == $data['zip']
						&& $address->state == $data['state']
						&& $address->country == $data['country']) {
						if (array_key_exists('address2', $data)) {
							if ($address->address2 == $data['address2']) {
								return null;
							}
						}
						return null;
					}
				}
			}
			$new_address = new UserAddress();
			$new_address->address1 = $data['address1'];
			if (array_key_exists('address2', $data)) {
				$new_address->address2 = $data['address2'];
			}
			$new_address->city = $data['city'];
			$new_address->zip = $data['zip'];
			$new_address->state = $data['state'];
			$new_address->country = $data['country'];
			if (count($this->user_address->where('user_id', $user->id)->get()) > 0) {
				$new_address->default = false;
			} else {
				$new_address->default = true;
			}
			return $user->addresses()->save($new_address);
		}

	}

	public function updateAddress(array $data, $id)
	{
		if (Auth::check()) {
			$address = $this->user_address->find($id);
			if ($address) {
				$address->address1 = $data['address1'];
				$address->address2 = $data['address2'];
				$address->city = $data['city'];
				$address->zip = $data['zip'];
				$address->state = $data['state'];
				$address->country = $data['country'];
			}
			return $address->save();
		}
	}
	
	public function changePassword(array $data)
	{
		if (Auth::check()) {
			$result = [];
			if (strcmp($data['new_pass'], $data['new_pass_conf']) == 0) {
				$user = Auth::user();
				$current_pass_hash = $user->password;
				if (Hash::check($data['old_pass'], $current_pass_hash)) {
					$user->password = Hash::make($data['new_pass']);
					if ($user->save()) {
						$result['status'] = 1;
						$result['message'] = 'Password changed successfully';
					}
				} else {
					$result['status'] = 2;
					$result['message'] = 'The current password is incorrect';
				}
			} else {
				$result['status'] = 2;
				$result['message'] = 'Password and password confirmation are not equals';
			}
		}

		return $result;
	}

	public function addPaymentMethod(array $data)
	{
		if (Auth::check()) {
			$user = Auth::user();
			if (count($data) > 0) {
				if($data['type'] == 'card'){
					$customer_profile = CustomerProfile::with('paymentProfiles')->where([['user_id', $user->id],['payment_processor',strtoupper(env('CARD_PROCESSOR'))]])->first();
				}
				if (!$customer_profile && $data['save']) {
					$data['email'] = $user->email;
					$data['user_id'] = $user->id;
					try{
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
									$customerProfile->user()->associate(\Auth::user());
									if ($customerProfile->save()) {
										$customerPaymentProfile = new CustomerPaymentProfile();
										$customerPaymentProfile->customer_profile_id = $customerProfile->id;
										$customerPaymentProfile->payment_profile_id = $paymentProfile["payment_profile_id"];
										$customerPaymentProfile->last_numbers = $paymentProfile["card_number"];
										$customerPaymentProfile->brand = $paymentProfile['brand'];
										$customerPaymentProfile->expiration_date = $data["month"] . '/' . $data['year'];
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
							DB::rollback();
							throw $e;
						}

					}catch(\Exception $error){
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
				}else if($customer_profile && $data['save']){
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
                                $customerPaymentProfile->last_numbers = $paymentProfile["card_number"];
                                $customerPaymentProfile->brand = $paymentProfile["brand"];
                                $customerPaymentProfile->expiration_date = $data["month"] . '/' . $data['year'];
                                $customerPaymentProfile->default = false;
                                // $customerPaymentProfile->default = $data["default"];
                                $customer_profile->paymentProfiles()->save($customerPaymentProfile);
                            }

                            return $customerPaymentProfile;
                        }, 5);
	
						unset($_result["customer_profile_id"]);
						unset($_result["payment_profile_id"]);
						
						$result['row'] = $_result;
					} catch (\Exception $e) {
						\DB::rollback();
						throw $e;
					}
				}
				return $result;
			}
		} else {
			throw new ApiException('Unauthenticated user', "001");
		}
	}

	public function removePaymentMethod($id)
	{
		if (Auth::check()) {
			$user = Auth::user();
			$payment_profile = CustomerPaymentProfile::with('customerProfile')->find($id);
			if ($payment_profile->customerProfile->user_id == $user->id) {
				$data['profile_id'] = $payment_profile->customerProfile->customer_profile_id;
				$data['payment_profile_id'] = $payment_profile->payment_profile_id;
				
				if (isset($data['profile_id']) && isset($data['payment_profile_id'])) {
					try{
						$_result = $this->cPayment->delPaymentProfile($data);
						if (!$payment_profile->delete()) {
							throw new ApiException('Error removing payment method.', "001");
						}
						return $id;
					}catch(\Exception $error){
						switch ($error->getCode()) {
							case 'E00040':
								throw new ApiException('The resource you are trying to remove is not longer exist in this system.', "001");
								break;
							default:
								throw new ApiException($error->getMessage(), "001");
								break;
						}
						throw new ApiException('Error removing payment method.', "001");
					}
				}
			} 
		} 
		throw new ApiException('Unauthenticated user.', "001");
	}
}