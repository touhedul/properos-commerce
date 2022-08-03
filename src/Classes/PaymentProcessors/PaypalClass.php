<?php
namespace Properos\Commerce\Classes\PaymentProcessors;

use Properos\Base\Classes\Api;
use Properos\Base\Exceptions\ApiException;

class PaypalClass
{
    private $hack_try = 0;
    private $tx_st = "unverified";
    private $op = "none";
    private $debug_comment = "";
    private $debug_email = "admin@properos.com";

    private $test; 						// Use test = 1 only if using sandbox settings
    private $paypal_link; 				// Test Paypal API LINK
    private $paypal_id;					// Business email ID
    private $paypal_server; 			// It can be paypal or sandbox.paypal
    private $mypaypal_token; 			// Test Token, only usable for retrieve_transaction (PDT validation)
    private $url_return; 				// Test Paypal Success LINK
    private $url_cancel; 				// Test Paypal Cancel LINK
    private $url_notify; 				// Test Paypal Cancel LINK
    private $image_url = '';
    private $custom;

    public function __construct($options = [])
    {
        $this->image_url = env('APP_URL', 'https://properos.com') . '/images/logos/logo.png';

        $this->test = 0;
        if (env('PAYPAL_ENV') == 'PRODUCTION') {
            $this->paypal_link = 'https://www.paypal.com/cgi-bin/webscr'; 			// Test Paypal API LINK
            $this->paypal_server = "paypal";							// It can be paypal or sandbox.paypal
            $this->paypal_api = "https://api.paypal.com";							// It can be paypal or sandbox.paypal
        } else {
            $this->paypal_link = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; 			// Test Paypal API LINK
            $this->paypal_server = "sandbox.paypal";							// It can be paypal or sandbox.paypal
            $this->paypal_api = "https://api.sandbox.paypal.com";	
        }															// Use test = 1 only if using sandbox settings
        $this->paypal_id = env('PAYPAL_ID');							// Business email ID
        $this->mypaypal_token = env('PAYPAL_TOKEN');						// Test Token, only usable for retrieve_transaction (PDT validation)

        if (count($options) > 0) {
            if (isset($options['url_return'])) {
                $this->url_return = $options['url_return'];
            } else {
                $this->url_return = env('APP_URL', 'https://properos.com') . '/api/payment/success'; 	// Test Paypal Success LINK
            }
            if (isset($options['url_cancel'])) {
                $this->url_cancel = $options['url_cancel'];
            } else {
                $this->url_cancel = env('APP_URL', 'https://properos.com') . '/api/payment/cancel';      // Test Paypal Cancel LINK
            }
            if (isset($options['custom'])) {
                $this->custom = $options['custom'];
            } else {
                $this->custom = [];      // Test Paypal Cancel LINK
            }
        } else {
            $this->url_return = env('APP_URL', 'https://properos.com') . '/api/payment/success'; 	// Test Paypal Success LINK
            $this->url_cancel = env('APP_URL', 'https://properos.com') . '/api/payment/cancel'; 	// Test Paypal Cancel LINK
            $this->custom = []; 	// Test Paypal Cancel LINK
        }

        $this->url_notify = env('APP_URL', 'https://properos.com') . '/api/payment/paypal/listener'; 		// Test Paypal Cancel LINK

    }

    public function get_config()
    {
        $result = array();
        $result["test"] = $this->test;
        $result["paypal_id"] = $this->paypal_id;
        $result["paypal_link"] = $this->paypal_link;
        $result["paypal_server"] = $this->paypal_server;
        $result["mypaypal_token"] = $this->mypaypal_token;
        $result["url_return"] = $this->url_return;
        $result["url_cancel"] = $this->url_cancel;
        return $result;
    }

    public function validate_transaction($post)
    {
        $request = "cmd=_notify-validate";
        foreach ($post as $varname => $varvalue) {
            $varvalue = urlencode($varvalue);
            $request .= "&$varname=$varvalue";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.' . $this->paypal_server . '.com/cgi-bin/webscr?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function retrieve_transaction($tx)
    {
        $paypal_url = 'https://www.' . $this->paypal_server . '.com/cgi-bin/webscr?cmd=_notify-synch&tx=' . $tx . '&at=' . $this->mypaypal_token;

        $curl = curl_init($paypal_url);
        $data = array(
            "cmd" => "_notify-synch",
            "tx" => $tx,
            "at" => $this->mypaypal_token
        );

        $data_string = json_encode($data);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 1);
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Host: www.' . $this->paypal_server . '.com',
            'Connection: close'
        );
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        $lines = explode("\n", $response);

        $result = array();

        foreach ($lines as $line) {
            $attrib = explode("=", $line);
            if ((is_array($attrib)) && (count($attrib) > 1)) {
                $aname = array_shift($attrib);
                $result[$aname] = urldecode(implode("=", $attrib));
            } elseif ((is_array($attrib)) && (count($attrib) == 1)) {
                $aname = array_shift($attrib);
                if (trim($aname) != "") {
                    $result[trim($aname)] = "";
                }
            }
        }

        return $result;
    }

    public function build_form($item_number, $total, $recurring = "0", $options = array())
    {
        $result = array();
        $type = "default";
        $cycle_unit = (isset($options["cycle_unit"])) ? $options["cycle_unit"] : "M";
        $cycle_length = (isset($options["cycle_length"])) ? $options["cycle_length"] : "1";
        $currency_code = (isset($options["currency_code"])) ? $options["currency_code"] : "USD";
        //$sender_email = (isset($options["sender_email"])) ? $options["sender_email"] : $this->paypal_id;
        $sender_email = $this->paypal_id;

        if ((isset($options["item_type"]) && ($options["item_type"] == "invoice"))) {
            $type = "invoice";
        }

        $result["url"] = $this->paypal_link;
        if ($recurring == "0") {
            $result["pform"] = ' 
                        <input type="hidden" name="cmd" value="_xclick">
                        <input type="hidden" name="business" value="' . $sender_email . '">
                        <input type="hidden" name="item_name" value="' . $options['item_name'] . '">
                        <input type="hidden" name="item_number" value="' . $item_number . '">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="cpp_header_image" value="' . $this->image_url . '">
                        <input type="hidden" name="image_url" value="' . $this->image_url . '">
                        <input type="hidden" name="no_shipping" value="1">
                        <input type="hidden" name="currency_code" value="' . $currency_code . '">
                        <input type="hidden" name="handling" value="0">
                        <input type="hidden" name="cancel_return" value="' . $this->url_cancel . '">
                        <input type="hidden" name="return" value="' . $this->url_return . '">
                        <input type="hidden" name="notify_url" value="' . $this->url_notify . '"> <!-- billing cycle unit=month -->
                        <input type="hidden" name="on0" value="Service">
                        <input type="hidden" name="os0" value="sale">
                        <input type="hidden" name="custom" value="' . urlencode(json_encode($this->custom)) . '">
                        <input type="hidden" name="amount" value="' . $total . '">
                        ';
        } else {
            $result["pform"] = '
					<input type="hidden" name="cmd" value="_xclick-subscriptions">
					<input type="hidden" name="business" value="' . $sender_email . '">
					<input type="hidden" name="item_name" value="' . $options['item_name'] . '">
					<input type="hidden" name="item_number" value="' . $item_number . '">
					<input type="hidden" name="image_url" value="' . $this->image_url . '">
					<input type="hidden" name="cancel_return" value="' . $this->url_cancel . '">
					<input type="hidden" name="return" value="' . $this->url_return . '">
					<input type="hidden" name="notify_url" value="' . $this->url_notify . '"> <!-- billing cycle unit=month -->
					<input type="hidden" name="currency_code" value="' . $currency_code . '">
					<input type="hidden" name="no_note" value="1"> <!-- required field -->
                    <input type="hidden" name="src" value="1"> <!-- recurring payments -->
					<input type="hidden" name="a3" value="' . $total . '"> <!-- price of subscription -->
					<input type="hidden" name="p3" value="' . $cycle_length . '"> <!-- billing cycle length -->
					<input type="hidden" name="t3" value="' . $cycle_unit . '"> <!-- billing cycle unit=month -->
                    ';
            if ($options["modify"] > 0) {
                $result["pform"] .= '<input type="hidden" name="modify" value="2"> <!-- Let current subscribers modify only. -->';
            }
        }
        return $result;
    }

    public function authPaypal(){
            $url =  $this->paypal_api.'/v1/oauth2/token';
            $options = ['userpwd' => env('PAYPAL_CLIENT_ID'). ':' .env('PAYPAL_SECRET_ID')];
            $headers = ['Accept: application/json','Accept-Language: en_US'];
            $data = ['grant_type' => 'client_credentials'];

            $res = Api::call($url, $data, 'POST', $headers, $options);
            $response = json_decode($res, true);

            if(!isset($response['status']) || $response['status'] == 0){
                if(isset($response['data'])){
                    if(isset($response['data']['message'])){
                        throw new ApiException($response['data']['message'], $response['code']);
                    }
                    throw new ApiException($response['data'], $response['code']);
                }
                throw new ApiException($response, $response['code']);
            }
            return $response['data'];
    }

    public function returnPayment($transaction_id, $invoice='', $amount = 0){
        $auth = $this->authPaypal();
        $access_token = $auth['access_token'];
        
        $url = $this->paypal_api.'/v1/payments/capture/'.$transaction_id;
        $headers = ['Content-Type: application/json','Authorization: Bearer '.$access_token];
        $res = Api::call($url, [], 'GET', $headers, []);
        $response = json_decode($res, true);
        
        if(!isset($response['status']) || $response['status'] == 0){
            if(isset($response['data'])){
                if(isset($response['data']['message'])){
                    throw new ApiException($response['data']['message'], $response['code']);
                }
                throw new ApiException($response['data'], $response['code']);
            }
            throw new ApiException($response, $response['code']);
        }
        
        if(isset($response['data'])){
            if ($response['data']['state'] == 'completed') {
                $data =  [
                    "total" => ($amount > 0 && $amount <= $response['data']['amount']) ? $amount : $response['data']['amount']['total'],
                    // "value" => ($amount > 0 && $amount <= $response['data']['amount']) ? $amount : $response['data']['amount']['value'],
                    "currency" => $response['data']['amount']['currency']
                    // "currency_code" => $response['data']['amount']['currency_code']
                ];
                $refund = false;
                $void = false;

                if (isset($response['data']['links']) && count($response['data']['links']) > 0) {
                    foreach ($response['data']['links'] as $link) {
                        if ($link['rel'] == 'refund') {
                            $refund = true;
                        } elseif ($link['rel'] == 'void') {
                            $void = true;
                        }
                    }
                }
                // if($response['data']['final_capture'] == true){
                // if($response['data']['final_capture'] == true){
                if ($void == true) {
                    $result = $this->voidPayment($transaction_id, $access_token);
                } elseif ($refund == true) {
                    $result = $this->refundPayment($transaction_id, $access_token, $data);
                }

                return $result;
            }else if($response['data']['state'] == 'refunded'){
                throw new ApiException('Transaction has already been refunded', '001');
            }else if($response['data']['state'] == 'voided'){
                throw new ApiException('Transaction has already been voided', '001');
            }
        }
        return false;
    }

    public function voidPayment($transaction_id, $access_token){
        
        $url = $this->paypal_api.'/v1/payments/authorization/'.$transaction_id.'/void';
        $headers = ['Content-Type: application/json','Authorization: Bearer '.$access_token];
        $data = ['authorization_id' => $transaction_id];
        $res = Api::call($url, $data, 'POST', $headers, []);

        $response = json_decode($res, true);
        
        if(!isset($response['status']) || $response['status'] == 0){
            if(isset($response['data'])){
                if(isset($response['data']['message'])){
                    throw new ApiException($response['data']['message'], $response['code']);
                }
                throw new ApiException($response['data'], $response['code']);
            }
            throw new ApiException($response, $response['code']);
        }

        $response['data']['operation'] = 'void';
        $response['data']['description'] = 'A paypal void operation';
        return $response;
        
    }

    public function refundPayment($transaction_id,$access_token, $amount){
        
        $url = $this->paypal_api.'/v1/payments/capture/'.$transaction_id.'/refund';
        $headers = ['Content-Type: application/json','Authorization: Bearer '.$access_token];
        $data['amount'] = $amount;
        // $data['authorization_id'] = $transaction_id;
        // if($invoice != ''){
        //     $data['invoice_id'] = $invoice;
        // }
        $res = Api::call($url, $data, 'POST', $headers, []);

        $response = json_decode($res, true);
        
        if(!isset($response['status']) || $response['status'] == 0){
            if(isset($response['data'])){
                if(isset($response['data']['message'])){
                    throw new ApiException($response['data']['message'], $response['code']);
                }
                throw new ApiException($response['data'], $response['code']);
            }
            throw new ApiException($response, $response['code']);
        }
        $response['data']['operation'] = 'refund';
        $response['data']['description'] = 'A paypal refund operation';
        return $response;
        
    }
}
