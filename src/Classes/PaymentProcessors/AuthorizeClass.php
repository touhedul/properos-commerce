<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Properos\Commerce\Classes\PaymentProcessors;

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Properos\Base\Classes\Helper as Helper;
use Properos\Base\Exceptions\ApiException;

/**
 * Description of AuthorizeClass
 *
 * @author Dulce
 */
class AuthorizeClass {
    
    public $url;
    private $credentials;
    private $gateway;
    private $merchantAuthentication;
    private $environment;
    private $interval_units = ["day"=>"days","week"=>"weeks", "month"=>"months"];
	private $errors = ["I00001"=>"A transaction error happened or the credit card number is invalid, please try again or contact us"];

    public function __construct() {
        
        if(!file_exists(storage_path("app/authorizenet/authorizenet.log"))){
            \Storage::disk('local')->makeDirectory('authorizenet');
            //\Storage::disk('local')->put("authorizenet/authorizenet.log", '');              
        }
        if (!defined('AUTHORIZENET_LOG_FILE')) {
            define("AUTHORIZENET_LOG_FILE", storage_path("app/authorizenet/authorizenet.log"));
		}
		
        $this->credentials = array();
        $this->credentials["MerchantID"] = env("AUTHORIZE_API_ID",""); // Test
        $this->credentials["MerchantKey"] = env("AUTHORIZE_PRIVATE_KEY","") ; // Test
		if (strtoupper(env('AUTHORIZE_ENV','SANDBOX')) == 'PRODUCTION') {
			$this->environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;	
			$this->url = " https://api.authorize.net/xml/v1/request.api";
		} else {
			$this->environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
			$this->url = "https://apitest.authorize.net/xml/v1/request.api";
		}
        $this->merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $this->merchantAuthentication->setName($this->credentials["MerchantID"]);
        $this->merchantAuthentication->setTransactionKey($this->credentials["MerchantKey"]);
    }

// This function is ready    
    public function setCredentials($data) {
        if ((isset($data["MerchantID"])) && (isset($data["MerchantKey"]))){
            $this->credentials["MerchantID"] = $data["MerchantID"];
            $this->credentials["MerchantKey"] = $data["MerchantKey"];
            $this->merchantAuthentication->setName($credentials["MerchantID"]);
            $this->merchantAuthentication->setTransactionKey($credentials["MerchantKey"]);
            return true;
        } else {
            return false;
        }
    }
    
    public function setError($code, $message) {
         return json_encode(array());
	}
	/****************************************
	 * **** CUSTOMER PROFILE ***************
	 ****************************************/
	public function getCustomerProfile($data) {

		$rules = [
			'profile_id' => 'required|string',
		];
		
		$data = $this->validator($data,$rules);

		$request = new AnetAPI\GetCustomerProfileRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setCustomerProfileId($data["profile_id"]);
		$controller = new AnetController\GetCustomerProfileController($request);
		$response = $controller->executeWithApiResponse( $this->environment);

		if ($response != null) {
			if($response->getMessages()->getResultCode() == "Ok") {
				$profileSelected = $response->getProfile();
				$paymentProfilesSelected = $profileSelected->getPaymentProfiles();
				$result = [
					"profile_id" => $response->getProfile()->getCustomerProfileId(),
					"description" => $response->getProfile()->getDescription()
				];			// Success
				
				if (count($paymentProfilesSelected)>0) {
					$result["payment_profiles"]=[];
					foreach($paymentProfilesSelected as $paymentProfile ) {
							$row = [
								"payment_profile_id" => $paymentProfile->getCustomerPaymentProfileId(),
								"card_number" => $paymentProfile->getPayment()->getCreditCard()->getCardNumber(),
								"brand" => $paymentProfile->getPayment()->getCreditCard()->getCardType()
							];
							if($paymentProfile->getBillTo() != null) {
								$row["billing_firstname"] = $paymentProfile->getBillTo()->getFirstName();
								$row["billing_lastname"] = $paymentProfile->getBillTo()->getLastName();
								$row['billing_address'] = $paymentProfile->getBillTo()->getAddress();
								$row['billing_zip'] = $paymentProfile->getBillTo()->getZip();
							}else{
								$row["billing_firstname"] = null;
								$row["billing_lastname"] = null;
								$row['billing_address'] = null;
								$row['billing_zip'] = null;
							}
							$result["payment_profiles"][] = $row;
					}
				}
				
				if($response->getSubscriptionIds() != null)	{
					$result["subscriptions"] = [];
					foreach($response->getSubscriptionIds() as $subscriptionid)
						$result["subscriptions"][] = $subscriptionid;
				}
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}
			}
		} else {
			throw new ApiException("Null response returned", "001");
		}
		return $result;
	}

	public function getCustomerProfileIds() {
		// Get all existing customer profile ID's
		$request = new AnetAPI\GetCustomerProfileIdsRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$controller = new AnetController\GetCustomerProfileIdsController($request);
		$response = $controller->executeWithApiResponse($this->environment);

		if ($response != null) {
			if($response->getMessages()->getResultCode() == "Ok"){
				$result = [
					"profile_ids" => $response->getIds(),
					"profile_count" => count($response->getIds())
				];			// Success
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}
			}
		} else {
			throw new ApiException("Null response returned", "001");
		}
		return $result;
	}

	public function addCustomerProfile($data) {
	    // Set the transaction's refId
		$refId = 'ref' . time();
		
		$rules = [
			'email' => 'required|email|string',
            'description' => 'required|string',
			'token' => 'required|string',
			'billing_firstname' => 'required|string',
			'billing_firstname' => 'nullable|string',
			'billing_lastname' => 'nullable|string',
			'billing_company' => 'nullable|string',
			'billing_address' => 'nullable|string',
			'billing_city' => 'nullable|string',
			'billing_state' => 'nullable|string',
			'billing_country' => 'nullable|string',
			'billing_phone' => 'nullable|string',
			'billing_zip' => 'nullable|string',
			'billing_fax' => 'nullable|string',
			'shipping_firstname' => 'nullable|string',
			'shipping_lastname' => 'nullable|string',
			'shipping_address' => 'nullable|string',
			'shipping_city' => 'nullable|string',
			'shipping_state' => 'nullable|string',
			'shipping_zip' => 'nullable|string',
			'shipping_country' => 'nullable|string'
		];
		
		$data = $this->validator($data,$rules);

		// Set information for payment profile
		$opaqueData = new AnetAPI\OpaqueDataType();
		$opaqueData->setDataDescriptor($data["description"]);
		$opaqueData->setDataValue($data["token"]);
		$paymentOpaqueData = new AnetAPI\PaymentType();
		$paymentOpaqueData->setOpaqueData($opaqueData);

		// Create the Bill To info for new payment type
		$billto = new AnetAPI\CustomerAddressType();
		$billto->setFirstName($data["billing_firstname"]);
		if(isset($data["billing_lastname"])) $billto->setLastName($data["billing_lastname"]);
		if (isset($data["billing_company"])) $billto->setCompany($data["billing_company"]);
		if (isset($data["billing_address"])) $billto->setAddress($data["billing_address"]);
		if (isset($data["billing_city"])) $billto->setCity($data["billing_city"]);
		if (isset($data["billing_state"])) $billto->setState($data["billing_state"]);
		if (isset($data["billing_zip"])) $billto->setZip($data["billing_zip"]);
		if (isset($data["billing_country"])) $billto->setCountry($data["billing_country"]);
		if (isset($data["billing_phone"])) $billto->setPhoneNumber($data["billing_phone"]);
		if (isset($data["billing_fax"])) $billto->setfaxNumber($data["billing_fax"]);

		$shipto = new AnetAPI\CustomerAddressType();
		$ship = 0;
		if (isset($data["shipping_firstname"])) { $shipto->setFirstName($data["shipping_firstname"]); $ship++;}
		if (isset($data["shipping_lastname"])) { $shipto->setLastName($data["shipping_lastname"]); $ship++;}
		if (isset($data["shipping_address"])) { $shipto->setAddress($data["shipping_address"]); $ship++;}
		if (isset($data["shipping_city"])) { $shipto->setCity($data["shipping_city"]); $ship++;}
		if (isset($data["shipping_state"])) { $shipto->setState($data["shipping_state"]); $ship++;}
		if (isset($data["shipping_zip"])) { $shipto->setZip($data["shipping_zip"]); $ship++;}
		if (isset($data["shipping_country"])) { $shipto->setCountry($data["shipping_country"]); $ship++;}
		
		$shippingProfiles[] = $shipto;

		// Create a new Customer Payment Profile object
		$paymentprofile = new AnetAPI\CustomerPaymentProfileType();
		if (isset($data["customer_type"])) { $paymentprofile->setCustomerType($data["customer_type"]); } else { $paymentprofile->setCustomerType('individual'); }
		$paymentprofile->setBillTo($billto);
		$paymentprofile->setPayment($paymentOpaqueData);
		$paymentprofile->setDefaultPaymentProfile(true);
		
		$paymentprofiles[] = $paymentprofile;

		// Create a new CustomerProfileType and add the payment profile object
		$customerprofile = new AnetAPI\CustomerProfileType();
		$customerprofile->setDescription($data["description"]);
		$customerprofile->setEmail($data["email"]);
		$customerprofile->setPaymentProfiles($paymentprofiles);
		if($ship > 0){
			$customerprofile->setShipToList($shippingProfiles);
		}

		// Assemble the complete transaction request
		$request = new AnetAPI\CreateCustomerProfileRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setRefId($refId);
		$request->setProfile($customerprofile);

		// Create the controller and get the response
		$controller = new AnetController\CreateCustomerProfileController($request);
		$response = $controller->executeWithApiResponse($this->environment);
		if ($response != null) {
			if($response->getMessages()->getResultCode() == "Ok") {
				$result["profile_id"] = $response->getCustomerProfileId();

				$paymentProfiles = $response->getCustomerPaymentProfileIdList();
				if (count($paymentProfiles)>0) {
					$result["payment_profile_id"]=$paymentProfiles[0];
				}
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}
			}
		} else {
			throw new ApiException("Null response returned", "001");
		}
		return $result;
	}

	public function updateCustomerProfile($data) {
	    // Set the transaction's refId
		$refId = 'ref' . time();
		
		$rules = [
			'profile_id' => 'required|string',
			'email' => 'required|email|string',
            'description' => 'required|string'
		];
		
		$data = $this->validator($data,$rules);

		// Update an existing customer profile
		$updatecustomerprofile = new AnetAPI\CustomerProfileExType();
		$updatecustomerprofile->setCustomerProfileId($data['profile_id']);
		$updatecustomerprofile->setDescription($data['description']);
		$updatecustomerprofile->setEmail($data['email']);

		$request = new AnetAPI\UpdateCustomerProfileRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setProfile($updatecustomerprofile);
		
		// Create the controller and get the response
		$controller = new AnetController\UpdateCustomerProfileController($request);
		$response = $controller->executeWithApiResponse($this->environment);
	  	
		if ($response != null) {
			if($response->getMessages()->getResultCode() == "Ok"){
				// Validate the description and e-mail that was updated
				$request = new AnetAPI\GetCustomerProfileRequest();
				$request->setMerchantAuthentication($this->merchantAuthentication);
				$request->setCustomerProfileId($data['profile_id']);
			
				$controller = new AnetController\GetCustomerProfileController($request);
				$response = $controller->executeWithApiResponse($this->environment);
				
				if ($response != null) {
					if($response->getMessages()->getResultCode() == "Ok"){
						$result['profile_id'] = $response->getProfile()->getCustomerProfileId();
						$result['description'] =  $response->getProfile()->getDescription();
						$result['email'] = $response->getProfile()->getEmail();
					}else {
						$code = $response->getMessages()->getMessage()[0]->getCode();
						if  (isset($this->errors[$code])) {
							throw new ApiException($this->errors[$code], $code);
						} else {
							throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
						}
					}
				} else {
					throw new ApiException("Null response returned", "001");
				}
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}
			}
		} else {
			throw new ApiException("Null response returned", "001");
		}
		return $result;
	}

	public function delCustomerProfile($data) {

		$rules = [
			'profile_id' => 'required|string',
		];
		
		$data = $this->validator($data,$rules);

		// Delete an existing customer profile  
		$request = new AnetAPI\DeleteCustomerProfileRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setCustomerProfileId($data["profile_id"]);

		$controller = new AnetController\DeleteCustomerProfileController($request);
		$response = $controller->executeWithApiResponse( $this->environment);
		if ($response != null) {
			if($response->getMessages()->getResultCode() == "Ok") {
				$result = [
					"profile_id" => $data["profile_id"],
					"deleted" => true
				];			
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}
			}
		} else {
			throw new ApiException("Null response returned", "001");
		}
		return $result;
	}

	/****************************************
	 * **** PAYMENT PROFILE ***************
	 ****************************************/

	public function getPaymentProfile($data) {
	    // Set the transaction's refId
		$refId = 'ref' . time();

		$rules = [
			'profile_id' => 'required|string',
			'payment_profile_id' => 'required|string',
		];
		
		$data = $this->validator($data,$rules);

		//request requires customerProfileId and customerPaymentProfileId
		$request = new AnetAPI\GetCustomerPaymentProfileRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setRefId( $refId);
		$request->setCustomerProfileId($data["profile_id"]);
		$request->setCustomerPaymentProfileId($data["payment_profile_id"]);

		$controller = new AnetController\GetCustomerPaymentProfileController($request);
		$response = $controller->executeWithApiResponse($this->environment);

		if($response != null) {
			if($response->getMessages()->getResultCode() == "Ok"){
				if($response->getPaymentProfile()->getPayment()->getCreditCard()){
					$result = [
						"profile_id" => $response->getPaymentProfile()->getCustomerProfileId(),
						"payment_profile_id" => $response->getPaymentProfile()->getCustomerPaymentProfileId(),
						"card_number" => $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber(),
						"brand" => $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardType(),
					];							
				}else{
					$result = [
						"profile_id" => $response->getPaymentProfile()->getCustomerProfileId(),
						"payment_profile_id" => $response->getPaymentProfile()->getCustomerPaymentProfileId(),
						"card_number" => $response->getPaymentProfile()->getPayment()->getBankAccount()->getAccountNumber(),
						"brand" => $response->getPaymentProfile()->getPayment()->getBankAccount()->getbankName(),
					];	
				}
				if($response->getPaymentProfile()->getBillTo() != null){
					$result['billing_address'] = $response->getPaymentProfile()->getBillTo()->getAddress();
					$result['billing_city'] = $response->getPaymentProfile()->getBillTo()->getCity();
					$result['billing_state'] = $response->getPaymentProfile()->getBillTo()->getState();
					$result['billing_country'] = $response->getPaymentProfile()->getBillTo()->getCountry();
					$result['billing_zip'] = $response->getPaymentProfile()->getBillTo()->getZip();
					$result['billing_firstname'] = $response->getPaymentProfile()->getBillTo()->getFirstName();
					$result['billing_lastname'] = $response->getPaymentProfile()->getBillTo()->getLastName();
				}else{
					$result['billing_address'] = null;
					$result['billing_city'] = null;
					$result['billing_state'] = null;
					$result['billing_country'] = null;
					$result['billing_zip'] = null;
					$result['billing_firstname'] = null;
					$result['billing_lastname'] = null;
				}
				if($response->getPaymentProfile()->getSubscriptionIds() != null) {
					$result["subscriptions"] = [];
					foreach($response->getPaymentProfile()->getSubscriptionIds() as $subscriptionid)
						$result["subscriptions"][] = $subscriptionid;
				}
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}
			}
		} else {
			throw new ApiException("Null response returned", "001");
		}
		return $result;
	}

	public function getPaymentProfileList($data) {
		// Set the transaction's refId
		$refId = 'ref' . time();
		
		$rules = [
			'search_type' => 'required|string',
			'exp_date' => 'required|string',
		];
		
		$data = $this->validator($data,$rules);

		//Setting the paging
		$paging = new AnetAPI\PagingType();
		if(isset($data['limit'])){ $paging->setLimit($data['limit']); } else { $paging->setLimit('10'); }
		if(isset($data['starting_after'])){ $paging->setOffset($data['starting_after']); } else { $paging->setOffset('1'); }
		
		//Setting the sorting
		$sorting = new AnetAPI\CustomerPaymentProfileSortingType();
		if(isset($data['order_by'])){ $sorting->setOrderBy($data['order_by']); } else { $sorting->setOrderBy('id'); }
		if(isset($data['order_descending'])){ $sorting->setOrderDescending($data['order_descending']); } else { $sorting->setOrderDescending('false'); }
		
		//Creating the request with the required parameters
		$request = new AnetAPI\GetCustomerPaymentProfileListRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setRefId($refId);
		$request->setPaging($paging);
		$request->setSorting($sorting);
		
		$request->setSearchType('cardsExpiringInMonth');
		$request->setMonth($data['exp_date']);
		
		// Controller
		$controller = new AnetController\GetCustomerPaymentProfileListController($request);
		// Getting the response
		$response = $controller->executeWithApiResponse( $this->environment);
		
		if($response != null) {
			if($response->getMessages()->getResultCode() == "Ok") {
				$result = [
					"payment_profile_count" => $response->getTotalNumInResultSet(),
					"payment_profiles" => []
				];					
				foreach($response->getPaymentProfiles() as $paymentProfile ) {
					$row = [
						"profile_id" => $paymentProfile->getCustomerProfileId(),
						"payment_profile_id" => $paymentProfile->getCustomerPaymentProfileId(),
						"card_number" => $paymentProfile->getPayment()->getCreditCard()->getCardNumber(),
						"brand" => $paymentProfile->getPayment()->getCreditCard()->getCardType(),
						"exp_date" => $paymentProfile->getPayment()->getCreditCard()->getExpirationDate()
					];
					if($paymentProfile->getBillTo() != null) {
						$row["billing_firstname"] = $paymentProfile->getBillTo()->getFirstName();
						$row["billing_lastname"] = $paymentProfile->getBillTo()->getLastName();
						$row['billing_address'] = $paymentProfile->getBillTo()->getAddress();
						$row['billing_city'] = $paymentProfile->getBillTo()->getCity();
						$row['billing_state'] = $paymentProfile->getBillTo()->getState();
						$row['billing_country'] = $paymentProfile->getBillTo()->getCountry();
						$row['billing_zip'] = $paymentProfile->getBillTo()->getZip();
					}else{
						$row["billing_firstname"] = null;
						$row["billing_lastname"] = null;
						$row['billing_address'] = null;
						$row['billing_city'] = null;
						$row['billing_state'] = null;
						$row['billing_country'] = null;
						$row['billing_zip'] = null;
					}
					$result["payment_profiles"][] = $row;
				}
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}			
			}
		} else {
			throw new ApiException("Null response returned", "001");
		}
			
		return $result;
	}

	public function addPaymentProfile($data) {
		$refId = 'ref' . time();

		$rules = [
			'profile_id' => 'required|string',
            'description' => 'required|string',
			'token' => 'required|string',
			'billing_firstname' => 'required|string',
			'billing_lastname' => 'nullable|string',
			'billing_address' => 'nullable|string',
			'billing_company' => 'nullable|string',
			'billing_city' => 'nullable|string',
			'billing_state' => 'nullable|string',
			'billing_country' => 'nullable|string',
			'billing_zip' => 'nullable|string',
			'billing_phone' => 'nullable|string',
			'billing_fax' => 'nullable|string',
		];
		
		$data = $this->validator($data,$rules);

		// Set information for payment profile
		$opaqueData = new AnetAPI\OpaqueDataType();
		$opaqueData->setDataDescriptor($data["description"]);
		$opaqueData->setDataValue($data["token"]);
		$paymentOpaqueData = new AnetAPI\PaymentType();
		$paymentOpaqueData->setOpaqueData($opaqueData);

		// Create the Bill To info for new payment type
		$billto = new AnetAPI\CustomerAddressType();
		$billto->setFirstName($data["billing_firstname"]);
		if (isset($data["billing_lastname"])) $billto->setLastName($data["billing_lastname"]);
		if (isset($data["billing_company"])) $billto->setCompany($data["billing_company"]);
		if (isset($data["billing_address"])) $billto->setAddress($data["billing_address"]);
		if (isset($data["billing_city"])) $billto->setCity($data["billing_city"]);
		if (isset($data["billing_state"])) $billto->setState($data["billing_state"]);
		if (isset($data["billing_zip"])) $billto->setZip($data["billing_zip"]);
		if (isset($data["billing_country"])) $billto->setCountry($data["billing_country"]);
		if (isset($data["billing_phone"])) $billto->setPhoneNumber($data["billing_phone"]);
		if (isset($data["billing_fax"])) $billto->setfaxNumber($data["billing_fax"]);

		// Create a new Customer Payment Profile object
		$paymentprofile = new AnetAPI\CustomerPaymentProfileType();
		if (isset($data["customer_type"])) { $paymentprofile->setCustomerType($data["customer_type"]); } else { $paymentprofile->setCustomerType('individual'); }
		$paymentprofile->setBillTo($billto);
		$paymentprofile->setPayment($paymentOpaqueData);
		$paymentprofile->setDefaultPaymentProfile(true);
		if(isset($data['default_payment'])){ $paymentprofile->setDefaultPaymentProfile($data['default_payment']); } else { $paymentprofile->setDefaultPaymentProfile(true) ;}
		
		$paymentprofiles[] = $paymentprofile;

		// Assemble the complete transaction request
		$paymentprofilerequest = new AnetAPI\CreateCustomerPaymentProfileRequest();
		$paymentprofilerequest->setMerchantAuthentication($this->merchantAuthentication);

		// Add an existing profile id to the request
		$paymentprofilerequest->setCustomerProfileId($data["profile_id"]);
		$paymentprofilerequest->setPaymentProfile($paymentprofile);
		if(isset($data['mode'])){ $paymentprofilerequest->setValidationMode($data['mode']); } else { $paymentprofilerequest->setValidationMode("testMode") ;}

		// Create the controller and get the response
		$controller = new AnetController\CreateCustomerPaymentProfileController($paymentprofilerequest);
		$response = $controller->executeWithApiResponse($this->environment);

		if($response != null) {
			if($response->getMessages()->getResultCode() == "Ok") {
				$result = [
					"payment_profile_id" => $response->getCustomerPaymentProfileId(),
				];						
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}	
			}
		} else {
			throw new ApiException("Null response returned", "001");
		}
		return $result;
	}

	public function delPaymentProfile($data) {
		
		$rules = [
			'profile_id' => 'required|string',
			'payment_profile_id' => 'required|string',
		];
		
		$data = $this->validator($data,$rules);

		$request = new AnetAPI\DeleteCustomerPaymentProfileRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setCustomerProfileId($data["profile_id"]);
		$request->setCustomerPaymentProfileId($data["payment_profile_id"]);
		
		$controller = new AnetController\DeleteCustomerPaymentProfileController($request);
		$response = $controller->executeWithApiResponse( $this->environment);
		
		if ($response != null) {
			if($response->getMessages()->getResultCode() == "Ok") {
				$result = [
					"profile_id" => $data["profile_id"],
					"payment_profile_id" => $data["payment_profile_id"],
					"deleted" => true
				];			
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}	
			}
		} else {
			throw new ApiException("Null response returned", "001");
		}
		return $result;
	}
  
	/****************************************
	 * ********* CHARGE ***************
	 ****************************************/

    public function chargeAccount($data) {
		// Set the transaction's refId
		$refId = 'ref' . time();

		$rules = [
			'profile_id' => 'nullable|string',
			'payment_profile_id' => 'nullable|string',
			'token' => 'nullable|string',
			'description' => 'nullable|string',
			'amount' => 'required',
			'type' => 'required',
			'billing_firstname' => 'nullable|string',
			'billing_lastname' => 'nullable|string',
			'billing_company' => 'nullable|string',
			'billing_address' => 'nullable|string',
			'billing_city' => 'nullable|string',
			'billing_state' => 'nullable|string',
			'billing_country' => 'nullable|string',
			'billing_zip' => 'nullable|string',
			'billing_phone' => 'nullable|string',
			'billing_fax' => 'nullable|string',
			'shipping_firstname' => 'nullable|string',
			'shipping_lastname' => 'nullable|string',
			'shipping_address' => 'nullable|string',
			'shipping_city' => 'nullable|string',
			'shipping_state' => 'nullable|string',
			'shipping_zip' => 'nullable|string',
			'shipping_country' => 'nullable|string',
		];
		
		$data = $this->validator($data,$rules);

		$billto = new AnetAPI\CustomerAddressType();
		$bill = 0;
		if (isset($data["billing_firstname"])) { $billto->setFirstName($data["billing_firstname"]); $bill++;}
		if (isset($data["billing_lastname"])) { $billto->setLastName($data["billing_lastname"]); $bill++;}
		if (isset($data["billing_company"])) { $billto->setCompany($data["billing_company"]);$bill++;}
		if (isset($data["billing_address"])) { $billto->setAddress($data["billing_address"]); $bill++;}
		if (isset($data["billing_city"])) { $billto->setCity($data["billing_city"]); $bill++;}
		if (isset($data["billing_state"])) { $billto->setState($data["billing_state"]); $bill++;}
		if (isset($data["billing_zip"])) { $billto->setZip($data["billing_zip"]); $bill++;}
		if (isset($data["billing_country"])) { $billto->setCountry($data["billing_country"]); $bill++;}
		if (isset($data["billing_phone"])) { $billto->setPhoneNumber($data["billing_phone"]); $bill++;}
		if (isset($data["billing_fax"])) { $billto->setfaxNumber($data["billing_fax"]); $bill++;}

		$shipto = new AnetAPI\CustomerAddressType();
		$ship = 0;
		if (isset($data["shipping_firstname"])) { $shipto->setFirstName($data["shipping_firstname"]); $ship++;}
		if (isset($data["shipping_lastname"])) { $shipto->setLastName($data["shipping_lastname"]); $ship++;}
		if (isset($data["shipping_address"])) { $shipto->setAddress($data["shipping_address"]); $ship++;}
		if (isset($data["shipping_city"])) { $shipto->setCity($data["shipping_city"]); $ship++;}
		if (isset($data["shipping_state"])) { $shipto->setState($data["shipping_state"]); $ship++;}
		if (isset($data["shipping_zip"])) { $shipto->setZip($data["shipping_zip"]); $ship++;}
		if (isset($data["shipping_country"])) { $shipto->setCountry($data["shipping_country"]); $ship++;}

		$transactionRequestType = new AnetAPI\TransactionRequestType();

		if($data['type'] == 'profile'){
			$profileToCharge = new AnetAPI\CustomerProfilePaymentType();
			$profileToCharge->setCustomerProfileId($data["profile_id"]);
			$paymentProfile = new AnetAPI\PaymentProfileType();
			$paymentProfile->setPaymentProfileId($data["payment_profile_id"]);
			$profileToCharge->setPaymentProfile($paymentProfile);

			$transactionRequestType->setProfile($profileToCharge);
		}else{
			// Set information for payment profile
			$opaqueData = new AnetAPI\OpaqueDataType();
			$opaqueData->setDataDescriptor($data["description"]);
			$opaqueData->setDataValue($data["token"]);
			$paymentOpaqueData = new AnetAPI\PaymentType();
			$paymentOpaqueData->setOpaqueData($opaqueData);

			$transactionRequestType->setPayment($paymentOpaqueData);
		}
		
		$transactionRequestType->setTransactionType("authCaptureTransaction"); 
		$transactionRequestType->setAmount($data["amount"]);
		if($bill > 0) $transactionRequestType->setBillTo($billto);
		if($ship > 0)$transactionRequestType->setShipTo($shipto);
		

		$request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setRefId( $refId);
		$request->setTransactionRequest( $transactionRequestType);
		$controller = new AnetController\CreateTransactionController($request);
		$response = $controller->executeWithApiResponse($this->environment);

		if ($response != null)  {
			if($response->getMessages()->getResultCode() == "Ok")  {
				$tresponse = $response->getTransactionResponse();
				if ($tresponse != null && $tresponse->getMessages() != null) {
					$result = [
						"authcode" => $tresponse->getAuthCode(),
						"transaction_id" => $tresponse->getTransId(),
						"code_text" => $tresponse->getMessages()[0]->getCode(),
						"description" => $tresponse->getMessages()[0]->getDescription(),
						"transaction_fee" => $this->getTransactionFee($data["amount"], ["transaction_id"=>$tresponse->getTransId()])
					];
					switch($tresponse->getResponseCode()){
						case '1':
							$result["response_code"] = 'approved';
							break;
						case '2':
							$result["response_code"] = 'declined';
							break;
						case '3':
							$result["response_code"] = 'error';
							break;
						case '4':
							$result["response_code"] = 'held_for_review';
							break;
					}
				} else {
					if($tresponse->getErrors() != null) {
						$code = $tresponse->getErrors()[0]->getErrorCode();
						if (isset($this->errors[$code])) {
							throw new ApiException($this->errors[$code], $code);
						} else {
							throw new ApiException($tresponse->getErrors()[0]->getErrorText(), $code);
						}
					}
				}
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}
			}      
		} else {
			throw new ApiException("Null response returned", "001");
		}
        
        return $result;
    }

    public function authorizeAccount($data) {
		$refId = 'ref' . time();

		$rules = [
			'profile_id' => 'required|string',
			'payment_profile_id' => 'required|string',
			'amount' => 'required|string',
		];
		
		$data = $this->validator($data,$rules);

		$profileToCharge = new AnetAPI\CustomerProfilePaymentType();
		$profileToCharge->setCustomerProfileId($data["profile_id"]);
		$paymentProfile = new AnetAPI\PaymentProfileType();
		$paymentProfile->setPaymentProfileId($data["payment_profile_id"]);
		$profileToCharge->setPaymentProfile($paymentProfile);

		$transactionRequestType = new AnetAPI\TransactionRequestType();
		$transactionRequestType->setTransactionType("authOnlyTransaction"); 
		$transactionRequestType->setAmount($data["amount"]);
		$transactionRequestType->setProfile($profileToCharge);

		// Add order info
		$use_order = 0;
		$order = new AnetAPI\OrderType();
		if (isset($data["description"])) { $order->setDescription($data["description"]); $use_order++;}
		if (isset($data["invoice_number"])) { $order->setInvoiceNumber($data["invoice_number"]); $use_order++;}
		if ($use_order > 0) $transactionRequestType->setOrder($order);

		$request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setRefId($refId);
		$request->setTransactionRequest( $transactionRequestType);

		$controller = new AnetController\CreateTransactionController($request);
		$response = $controller->executeWithApiResponse($this->environment);
   
		if ($response != null) {
			$tresponse = $response->getTransactionResponse();
			if (($tresponse != null) && ($tresponse->getResponseCode()=="1") ) {
				$result = [
					"authcode" => $tresponse->getAuthCode(),
					"transaction_id" => $tresponse->getTransId(),
					"description" => $tresponse->getMessages()[0]->getDescription(),
					"transaction_fee" => $this->getTransactionFee($data["amount"], ["transaction_id"=>$tresponse->getTransId()])
				];
				switch($tresponse->getResponseCode()){
					case '1':
						$result["response_code"] = 'approved';
						break;
					case '2':
						$result["response_code"] = 'declined';
						break;
					case '3':
						$result["response_code"] = 'error';
						break;
					case '4':
						$result["response_code"] = 'held_for_review';
						break;
				}
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					\Mail::send([],[],function($mail) use ($result){
						$mail->to("debug@properos.com","Properos Debug");
						$mail->subject("New Authorize Error Code");
						$mail->setBody(json_encode($result));
					});
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}
			}
		} else {
			throw new ApiException("Null response returned", "001");
		}
        return $result;
    }
	
	public function captureAccount($data) {
		$refId = 'ref' . time();

		$rules = [
			'transaction_id' => 'required|string',
			'amount' => 'required|string',
		];
		
		$data = $this->validator($data,$rules);

		$transactionRequestType = new AnetAPI\TransactionRequestType();
		$transactionRequestType->setTransactionType("priorAuthCaptureTransaction");
		$transactionRequestType->setRefTransId($data["transaction_id"]);
	
		$request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setRefId($refId);
		$request->setTransactionRequest( $transactionRequestType);
		
		$controller = new AnetController\CreateTransactionController($request);
		$response = $controller->executeWithApiResponse($this->environment);
   
		if ($response != null) {
			if($response->getMessages()->getResultCode() == "Ok") {
				$tresponse = $response->getTransactionResponse();
				if ($tresponse != null && $tresponse->getMessages() != null) {
					$result = [
						"authcode" => $tresponse->getAuthCode(),
						"transaction_id" => $tresponse->getTransId(),
						"code_text" => $tresponse->getMessages()[0]->getCode(),
						"description" => $tresponse->getMessages()[0]->getDescription(),
						"ref_trans_id" => $tresponse->getRefTransId(),
						"transaction_fee" => $this->getTransactionFee($data["amount"], ["transaction_id"=>$tresponse->getTransId()])
					];
					switch($tresponse->getResponseCode()){
						case '1':
							$result["response_code"] = 'approved';
							break;
						case '2':
							$result["response_code"] = 'declined';
							break;
						case '3':
							$result["response_code"] = 'error';
							break;
						case '4':
							$result["response_code"] = 'held_for_review';
							break;
					}
				} else {
					if($tresponse->getErrors() != null) {
						$code = $tresponse->getErrors()[0]->getErrorCode();
						if  (isset($this->errors[$code])) {
							throw new ApiException($this->errors[$code], $code);
						} else {
							throw new ApiException($tresponse->getErrors()[0]->getErrorText(), $code);
						}
					}
				}
			} else {
				$tresponse = $response->getTransactionResponse();
				if($tresponse != null && $tresponse->getErrors() != null) {
					$code = $tresponse->getErrors()[0]->getErrorCode();
					throw new ApiException($tresponse->getErrors()[0]->getErrorText(), $code);
				} else {
					$code = $response->getMessages()->getMessage()[0]->getCode();
					if  (isset($this->errors[$code])) {
						throw new ApiException($this->errors[$code], $code);
					} else {
						\Mail::send([],[],function($mail) use ($result){
							$mail->to("debug@properos.com","Properos Debug");
							$mail->subject("New Authorize Error Code");
							$mail->setBody(json_encode($result));
						});
						throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
					}
				}
			}      
		} else {
			throw new ApiException("Null response returned", "001");
		}
			   
        return $result;
	}
	
	public function getTransaction($data) { 

		$rules = [
			'transaction_id' => 'required|string',
		];
		
		$data = $this->validator($data,$rules);
        
		$request = new AnetAPI\GetTransactionDetailsRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setTransId($data["transaction_id"]);

		$controller = new AnetController\GetTransactionDetailsController($request);

		$response = $controller->executeWithApiResponse($this->environment);

		if ($response != null) 	{
			if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
				$result = [
					"transaction_id" => $response->getTransaction()->getTransId(),
					"amount" => number_format($response->getTransaction()->getAuthAmount(),2),
					"type" => $response->getTransaction()->getTransactionType(),
					"status" => $response->getTransaction()->getTransactionStatus(),
					"payment_profile" => [
						"card_number" => $paymentProfile->getPayment()->getCreditCard()->getCardNumber(),
						"brand" => $paymentProfile->getPayment()->getCreditCard()->getCardType(),
					],
					"subscription" => [
						"name" => $response->getSubscription()->getName(),
					]
				];	
				switch($response->getTransaction()->getResponseCode()){
					case '1':
						$result["response_code"] = 'approved';
						break;
					case '2':
						$result["response_code"] = 'declined';
						break;
					case '3':
						$result["response_code"] = 'error';
						break;
					case '4':
						$result["response_code"] = 'held_for_review';
						break;
				}		

			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}
			}
		} else {
			throw new ApiException("Null response returned", "001");
		}
      
        return $result;

	}
	
	public function getTransactionHistory($data) {
		// Set the transaction's refId
		$refId = 'ref' . time();

		$rules = [
			'profile_id' => 'required|string',
		];
		
		$data = $this->validator($data,$rules);

		$request = new AnetAPI\GetTransactionListForCustomerRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setCustomerProfileId($data['profile_id']);

    	$controller = new AnetController\GetTransactionListForCustomerController($request);
    	$response = $controller->executeWithApiResponse($this->environment);
    
		if ($response != null) {
			if($response->getMessages()->getResultCode() == "Ok"){
				$result = [
					"transactions_count" => $response->getTotalNumInResultSet(),
					"transactions" => []
				];
				if( $response->getTransactions() != null){
					foreach($response->getTransactions() as $transaction ) {
						$row = [
							"profile_id" => $data['profile_id'],
							"payment_profile_id" => $transaction->getProfile()->getCustomerPaymentProfileId(),
							"transaction_id" => $transaction->getTransId(),
							"utc_date" => $transaction->getSubmitTimeUTC(),
							"local_date" => $transaction->getSubmitTimeLocal(),
							"status" => $transaction->getTransactionStatus(),
							"invoice_number" => $transaction->getInvoiceNumber(),
							"card_number" => $transaction->getAccountNumber(),
							"brand" => $transaction->getAccountType(),
							"amount" => $transaction->getSettleAmount(),
						];
						$result["transactions"][] = $row;
					}
				}
			}else{
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}
			}
		}else {
			throw new ApiException("Null response returned", "001");
		}
	
    	return $result;
	}
	
	public function refundTransaction($data) {
		
		$rules = [
			'amount' => 'required|string',
			'card_number' => 'nullable|string',
			'ref_trans_id' => 'required|string',
		];
		// dd($data);
		$refId = 'ref' . time();
		$data = $this->validator($data,$rules);
		// Create the payment data 
		if(isset($data['card_number'])){
			$creditCard = new AnetAPI\CreditCardType();
			$creditCard->setCardNumber($data["card_number"]);
			$creditCard->setExpirationDate("XXXX");
			$paymentOne = new AnetAPI\PaymentType();
			$paymentOne->setCreditCard($creditCard);
		}
		
        //create a transaction
        $transactionRequest = new AnetAPI\TransactionRequestType();
        $transactionRequest->setTransactionType("refundTransaction"); 
		$transactionRequest->setAmount($data["amount"]);
		$transactionRequest->setRefTransId($data["ref_trans_id"]);
		if(isset($data['card_number'])){
			$transactionRequest->setPayment($paymentOne);
		}
        $request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		
        $request->setTransactionRequest($transactionRequest);
        if(isset($data['ref_trans_id'])) {$request->setRefId($refId);}
		
		$controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response != null) {
            if($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();   

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    $result = [
                        "authcode" => $tresponse->getAuthCode(),
                        "transaction_id" => $tresponse->getTransId(),
                        "code_text" => $tresponse->getMessages()[0]->getCode(),
						"description" => $tresponse->getMessages()[0]->getDescription(),
						"object" => 'refund'
					];
					switch($tresponse->getResponseCode()){
						case '1':
							$result["response_code"] = 'approved';
							break;
						case '2':
							$result["response_code"] = 'declined';
							break;
						case '3':
							$result["response_code"] = 'error';
							break;
						case '4':
							$result["response_code"] = 'held_for_review';
							break;
					}		
                } else {
                    if($tresponse->getErrors() != null) {
						throw new ApiException($tresponse->getErrors()[0]->getErrorText(), $tresponse->getErrors()[0]->getErrorCode());
                    }
                }
            } else {
                $tresponse = $response->getTransactionResponse();
				
				if($tresponse != null && $tresponse->getErrors() != null) {
                    $code = $tresponse->getErrors()[0]->getErrorCode();
					if  (isset($this->errors[$code])) {
						throw new ApiException($this->errors[$code], $code);
					} else {
						throw new ApiException($tresponse->getErrors()[0]->getErrorText(), $code);
					}
                } else {
                    $code = $response->getMessages()->getMessage()[0]->getCode();
					if  (isset($this->errors[$result["code"]])) {
						throw new ApiException($this->errors[$code], $code);
					} else {
						throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
					}
                }
            }      
        } else {
            throw new ApiException("Null response returned", "001");
        }
        return $result;
	}
	
	public function voidTransaction($data) {
        $rules = [
			'transaction_id' => 'required|string',
		];
		
		$data = $this->validator($data,$rules);

        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType( "voidTransaction"); 
        $transactionRequestType->setRefTransId($data["transaction_id"]);

        $request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setTransactionRequest( $transactionRequestType);
		
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response != null) {
            if($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();   

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    $result = [
                        "authcode" => $tresponse->getAuthCode(),
                        "transaction_id" => $tresponse->getTransId(),
                        "code_text" => $tresponse->getMessages()[0]->getCode(),
						"description" => $tresponse->getMessages()[0]->getDescription(),
						"object" => 'void'
					];
					switch($tresponse->getResponseCode()){
						case '1':
							$result["response_code"] = 'approved';
							break;
						case '2':
							$result["response_code"] = 'declined';
							break;
						case '3':
							$result["response_code"] = 'error';
							break;
						case '4':
							$result["response_code"] = 'held_for_review';
							break;
					}		
                } else {
                    if($tresponse->getErrors() != null) {
                        $code = $tresponse->getErrors()[0]->getErrorCode();
						if  (isset($this->errors[$code])) {
							throw new ApiException($this->errors[$code], $code);
						} else {
							throw new ApiException($tresponse->getErrors()[0]->getErrorText(), $code);
						}
                    }
                }
            } else {
                $tresponse = $response->getTransactionResponse();
                if($tresponse != null && $tresponse->getErrors() != null) {
                    $code = $tresponse->getErrors()[0]->getErrorCode();
					if  (isset($this->errors[$code])) {
						throw new ApiException($this->errors[$code], $code);
					} else {
						throw new ApiException($tresponse->getErrors()[0]->getErrorText(), $code);
					}

                    $result["errors"] =   [];                            
                } else {
                    $code = $response->getMessages()->getMessage()[0]->getCode();
					if  (isset($this->errors[$code])) {
						throw new ApiException($this->errors[$code], $code);
					} else {
						throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
					}
                }
            }      
        } else {
            throw new ApiException("Null response returned", "001");
        }

        return $result;
	}
	
	/****************************************
	 * ********* SUBSCRIPTIONS ***************
	 ****************************************/
	
    public function createSubscription($data) {
        // Set the transaction's refId
		$refId = 'ref' . time();
		$rules = [
			'subscription_name' => 'required|string',
            'length' => 'required|string',
            'unit' => 'required|string',
            'start_date' => 'required|string',
            'total_occurrences' => 'required|string',
            'amount' => 'required|string',
            'description' => 'required|string',
            'token' => 'required|string'
		];
		
		$data = $this->validator($data,$rules);

		// Subscription Type Info
		$subscription = new AnetAPI\ARBSubscriptionType();
		$subscription->setName($data['sunscription_name']);

		$interval = new AnetAPI\PaymentScheduleType\IntervalAType();
		$interval->setLength($data['length']);
		$interval->setUnit($data['unit']);

		$paymentSchedule = new AnetAPI\PaymentScheduleType();
		$paymentSchedule->setInterval($interval);
		$paymentSchedule->setStartDate(new DateTime($data['start_date']));
		$paymentSchedule->setTotalOccurrences($data['total_occurrences']);
		// $paymentSchedule->setTrialOccurrences("1");

		$subscription->setPaymentSchedule($paymentSchedule);
		$subscription->setAmount($data['amount']);
		// $subscription->setTrialAmount("0.00");
		
		// Set information for payment profile
		$opaqueData = new AnetAPI\OpaqueDataType();
		$opaqueData->setDataDescriptor($data["description"]);
		$opaqueData->setDataValue($data["token"]);
		$paymentOpaqueData = new AnetAPI\PaymentType();
		$paymentOpaqueData->setOpaqueData($opaqueData);
		$subscription->setPayment($paymentOpaqueData);


		$request = new AnetAPI\ARBCreateSubscriptionRequest();
		$request->setmerchantAuthentication($this->merchantAuthentication);
		$request->setRefId($refId);
		$request->setSubscription($subscription);
		$controller = new AnetController\ARBCreateSubscriptionController($request);

		$response = $controller->executeWithApiResponse($this->environment);       

        if ($response != null){
			if($response->getMessages()->getResultCode() == "Ok") {
				$result = [
					"code" => $response->getMessages()->getMessage()[0]->getCode(),
					"message" =>   $response->getMessages()->getMessage()[0]->getText(),
				];
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}
			}    
		} else {
            throw new ApiException("Null response returned", "001");
		}
        return $result;
	}
	
	public function updateSubscription($data) {

		// Set the transaction's refId
		$refId = 'ref' . time();
		$rules = [
			'subscription_id' => 'required|string',
            'length' => 'nullable|string',
            'unit' => 'nullable|string',
            'start_date' => 'nullable|string',
            'total_occurrences' => 'nullable|string',
            'amount' => 'nullable|string',
            'description' => 'nullable|string',
            'token' => 'nullable|string'
		];
		
		$data = $this->validator($data,$rules);

		// Subscription Type Info
		$subscription = new AnetAPI\ARBSubscriptionType();
		$count = 0;
		$interval = new AnetAPI\PaymentScheduleType\IntervalAType();
		if(isset($data['length'])){ $interval->setLength($data['length']); $count++;}
		if(isset($data['unit'])){ $interval->setUnit($data['unit']); $count++;}

		$count1= 0;
		$paymentSchedule = new AnetAPI\PaymentScheduleType();
		if($count >0) { $paymentSchedule->setInterval($interval); $count1++;}
		if(isset($data['start_date'])) { $paymentSchedule->setStartDate(new DateTime($data['start_date'])); $count1++;}
		if(isset($data['total_occurrences'])) { $paymentSchedule->setTotalOccurrences($data['total_occurrences']); $count1++;}
		// $paymentSchedule->setTrialOccurrences("1");

		if($count1 > 0) { $subscription->setPaymentSchedule($paymentSchedule);}
		if(isset($data['amount'])) $subscription->setAmount($data['amount']);
		// $subscription->setTrialAmount("0.00");
		
		// Set information for payment profile
		$count = 0;
		$opaqueData = new AnetAPI\OpaqueDataType();
		if(isset($data['description'])) { $opaqueData->setDataDescriptor($data["description"]); $count++;}
		if(isset($data['description'])) { $opaqueData->setDataValue($data["token"]);$count++;}
		$paymentOpaqueData = new AnetAPI\PaymentType();
		if($count >0){
			$paymentOpaqueData->setOpaqueData($opaqueData);
			$subscription->setPayment($paymentOpaqueData);
		}

		$request = new AnetAPI\ARBUpdateSubscriptionRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setSubscriptionId($data["subscription_id"]);
		$request->setSubscription($subscription);

		$controller = new AnetController\ARBUpdateSubscriptionController($request);
		$response = $controller->executeWithApiResponse($this->environment);

		if ($response != null) {
			if ($response->getMessages()->getResultCode() == "Ok")   {
				$result = [
					"code" => $response->getMessages()->getMessage()[0]->getCode(),
					"message" =>   $response->getMessages()->getMessage()[0]->getText()
				];
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}
			}    
		}else {
            throw new ApiException("Null response returned", "001");
		}
        return $result;
	}
	

    public function cancelSubscription($data) {

		$rules = [
			'subscription_id' => 'required|string',
		];
		
		$data = $this->validator($data,$rules);

        $request = new AnetAPI\ARBCancelSubscriptionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setSubscriptionId($data["subscription_id"]);
		
        $controller = new AnetController\ARBCancelSubscriptionController($request);
        $response = $controller->executeWithApiResponse($this->environment);        

        if ($response != null) {
			if($response->getMessages()->getResultCode() == "Ok"){
				$result = [
					"code" => $response->getMessages()->getMessage()[0]->getCode(),
					"message" =>   $response->getMessages()->getMessage()[0]->getText()
				];
			} else {
				$code = $response->getMessages()->getMessage()[0]->getCode();
				if  (isset($this->errors[$code])) {
					throw new ApiException($this->errors[$code], $code);
				} else {
					throw new ApiException($response->getMessages()->getMessage()[0]->getText(), $code);
				}
			}    
        }else {
            throw new ApiException("Null response returned", "001");
		}
        return $result;
    }
	
    public function getTransactionFee($amount, $options = []) {
		$fee = ($amount * 0.029 ) + 0.30;
        return $fee;
	}

	private function validator($data, $rules){
        
        $data = array_intersect_key($data, $rules);

        $validation = \Validator::make($data, $rules);

        if ($validation->passes()) {
            return $data;
        }            
        
        throw new ApiException($validation->errors(), '006', $data);
    }
}
