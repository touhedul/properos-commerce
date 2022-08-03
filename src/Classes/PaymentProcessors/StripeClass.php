<?php
namespace Properos\Commerce\Classes\PaymentProcessors;

use Properos\Base\Classes\Helper;
use Properos\Base\Exceptions\ApiException;
use Properos\Base\Classes\Api;

class StripeClass
{
    private $secret_key;
    private $public_key;
    private $statement_descriptor;
    private $api_url;
    private $options;
    private $headers;

    public function __construct($options = [])
    {
        $this->setCredentials($options);
    }

    public function setCredentials($options)
    {
        $this->secret_key = array_get($options, 'secret_key', env('STRIPE_SECRET_KEY', ''));
        $this->public_key = array_get($options, 'public_key', env('STRIPE_PUBLIC_KEY', ''));
        $this->statement_descriptor = trim(array_get($options, 'statement_descriptor', env('STRIPE_STATEMENT_DESCRIPTOR', env('APP_NAME', ''))));
        $this->api_url = rtrim(array_get($options, 'api_url', env('STRIPE_API_URL', 'https://api.stripe.com/v1')), '/');

        if (!($this->secret_key != '' && $this->public_key != '' && $this->api_url != '')) {
            throw new ApiException(['Stripe is not configured'], '006');
        }
        
        if (strlen($this->statement_descriptor) > 22) {
            throw new ApiException(['Statement Descriptor is too long. The max size is 22 characters'], '006');
        }
        
        $this->options = [
            'userpwd' => $this->secret_key . ':'
        ];

        $this->headers = ['Content-Type: application/x-www-form-urlencoded'];
    }

    public function getSecretKey()
    {
        $this->secret_key;
    }
    
    public function getPublicKey()
    {
        $this->secret_key;
    }

    public function apiCall($endpoint, $data, $method = 'POST')
    {
        $res = \json_decode(Helper::call($this->api_url . $endpoint, $data, $method, $this->headers, $this->options), true);

        if (isset($res['error'])) {
            return Api::error('000', $res['error'], $data);
        } else {
            return Api::success('', $res);
        }
    }
    // Product Management
    public function searchProductByName($name, $options = ['limit' => 100,'type' => 'service', 'active' => 'true'])
    {
        if ($name != '') {
            $res = $this->apiCall('/products', $options, 'GET');
            if ($res['status'] > 0) {
                foreach ($res['data']['data'] as $product) {
                    if ($product['name'] == $name) {
                        return $product;
                    }
                }
                if ($res['data']['has_more']) {
                    $options['starting_after'] = $product['id'];
                    return $this->searchProductByName($name, $options);
                } else {
                    return [];
                }
            } else {
                throw new ApiException($res['errors'], '003', ['name' => $name, 'options' => $options]);
            }
        } else {
            throw new ApiException('The name index does not found', '003', ['name' => $name, 'options' => $options]);
        }
    }
    
    public function getProduct($name)
    {
        if ($name != '') {
            $res = $this->apiCall('/products/' . $name, [], 'GET');
            if ($res['status'] > 0) {
                $res['data'] = $this->response_transform($res['data']);
                return $res['data'];
            } else {
                throw new ApiException($res['errors'], '003', ['name' => $name, 'response' => $res]);
            }
        } else {
            throw new ApiException('The name index does not found', '003', ['name' => $name]);
        }
    }

    public function createProduct($data)
    {
        $rules = [
            'name' => 'required|string',
            'type' => 'required|string',
        ];

        $validation = \Validator::make($data, $rules);

        if ($validation->passes()) {
            $product = $this->searchProductByName($data['name']);
            if (count($product) > 0) {
                return Api::success('', $product);
            } else {
                return $this->apiCall('/products', $data);
            }
        }
        throw new ApiException($validation->errors(), '006', $data);
    }

    // Plan Management
    public function createPlan($data)
    {
        $rules = [
            'product' => 'required|string',
            'nickname' => 'required|string',
            'interval' => 'required|string',
            'currency' => 'required|string',
            'amount' => 'required'
        ];

        $validation = \Validator::make($data, $rules);

        if ($validation->passes()) {
            $res = $this->apiCall('/plans', $data);
            if ($res['status'] > 0) {
                $res['data'] = $this->response_transform($res['data']);
                return $res['data'];
            } else {
                throw new ApiException($res['errors']['message'], '003', $res);
            }
        }

        throw new ApiException($validation->errors(), '006', $data);
    }
    
    public function updatePlan($data)
    {
        $rules = [
            'plan_id' => 'required|string'
        ];

        $validation = \Validator::make($data, $rules);

        if ($validation->passes()) {
            $params = array_intersect_key($data, ['nickname'=>'', 'interval' => '', 'currency' => '', 'amount' => '']);
            if (count($params) > 0) {
                return $this->apiCall('/plans/' . $data['plan_id'], $params);
            } else {
                throw new ApiException('Invalid Query Format', '006', $data);
            }
        }

        throw new ApiException($validation->errors(), '006', $data);
    }

    public function getPlan($data)
    {
        $rules = [
            'plan_id' => 'required|string'
        ];

        $validation = \Validator::make($data, $rules);

        if ($validation->passes()) {
            $res = $this->apiCall('/plans/' . $data['plan_id'], $data, 'GET');
            if ($res['status'] > 0) {
                $res['data'] = $this->response_transform($res['data']);
                return $res['data'];
            } else {
                throw new ApiException($res['errors']['message'], '003', $res);
            }
        }

        throw new ApiException($validation->errors(), '006', $data);
    }
    
    //Susbcription Management
    public function createSubscription($data)
    {
        $rules = [
            'customer' => 'required|string',
            'plan' => 'required|string'
        ];

        $validation = \Validator::make($data, $rules);

        if ($validation->passes()) {
            $res = $this->apiCall('/subscriptions', [
                'customer'=> $data['customer'],
                'items[0][plan]' => $data['plan'],
            ]);
            if ($res['status'] > 0) {
                $res['data'] = $this->response_transform($res['data']);
                return $res['data'];
            } else {
                throw new ApiException($res['errors']['message'], '003', $res);
            }
        }

        throw new ApiException($validation->errors(), '006', $data);
    }

    public function updateSubscription($data)
    {
        $rules = [
            'subscription_id' => 'required|string',
            'plan' => 'required|string'
        ];

        $validation = \Validator::make($data, $rules);

        if ($validation->passes()) {
            $subscription = $this->getSubscription($data);
            $res = $this->apiCall('/subscriptions/'.$data['subscription_id'], [
                'items[0][id]'=> $subscription['items']['data'][0]['id'],
                'items[0][plan]' => $data['plan'],
                'prorate'=> 'false'
            ]);
            if ($res['status'] > 0) {
                $res['data'] = $this->response_transform($res['data']);
                return $res['data'];
            } else {
                throw new ApiException($res['errors']['message'], '003', $res);
            }
        }

        throw new ApiException($validation->errors(), '006', $data);
    }

    public function getSubscription($data)
    {
        $rules = [
            'subscription_id' => 'required|string',
        ];

        $validation = \Validator::make($data, $rules);

        if ($validation->passes()) {
            $res = $this->apiCall('/subscriptions/'. $data['subscription_id'], [], 'GET');
            if ($res['status'] > 0) {
                $res['data'] = $this->response_transform($res['data']);
                return $res['data'];
            } else {
                throw new ApiException($res['errors']['message'], '003', $res);
            }
        }

        throw new ApiException($validation->errors(), '006', $data);
    }

    public function cancelSubscription($data)
    {
        $rules = [
            'subscription_id' => 'required|string',
        ];

        $validation = \Validator::make($data, $rules);

        if ($validation->passes()) {
            $params = [];
            if (isset($data['at_period_end'])) {
                if (is_bool($data['at_period_end'])) {
                    $params['at_period_end'] =  ($data['at_period_end']) ? 'true' : 'false';
                } else {
                    $params['at_period_end'] =  $data['at_period_end'];
                }
            }
            $res = $this->apiCall('/subscriptions/'. $data['subscription_id'], $params, 'DELETE');
            if ($res['status'] > 0) {
                $res['data'] = $this->response_transform($res['data']);
                return $res['data'];
            } else {
                throw new ApiException($res['errors']['message'], '003', $res);
            }
        }

        throw new ApiException($validation->errors(), '006', $data);
    }

    //Charge Account Management
    public function chargeAccount($data)
    {
        if (!(isset($data['statement_descriptor']) && trim($data['statement_descriptor']) != '')) {
            $data['statement_descriptor'] = $this->statement_descriptor;
        }
        if (!(isset($data['currency']) && trim($data['currency']) != '')) {
            $data['currency'] = 'usd';
        }
        if(isset($data['type'])){
            if($data['type'] == 'profile'){
                $data['customer'] = $data['profile_id'];
                if(isset($data['payment_profile_id'])){
                    $data['source'] = $data['payment_profile_id'];
                }
            }else if($data['type'] == 'card'){
                $data['source'] = $data['token'];
            }
        } 
        
        $rules = [
            'amount' => 'required|numeric',
            'currency' => 'nullable|string',
            'description' => 'nullable|string',
            'capture' => 'nullable|string',
            'statement_descriptor' => 'nullable|string|max:22',
            'source' => 'required_if:customer,null|string',
            'customer' => 'required_if:source,null|string'
        ];

        $data = $this->validator($data,$rules);
        $data['amount'] = round($data['amount'],2) * 100;

        $res = $this->apiCall('/charges', $data);
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }
    }

    public function authorizeAccount($data)
    {
        $data['capture'] = 'false';
        return $this->chargeAccount($data);        
    }

    public function captureAccount($data)
    {
        $rules = [
            'charge' => 'required|string'
        ];
    
        $data = $this->validator($data,$rules);
        $res = $this->apiCall('/charges/' . $data['charge'] . '/capture', []);
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }
    }

    public function getTransaction($data)
    {
        $rules = [
            'charge' => 'required|string'
        ];
    
        $data = $this->validator($data,$rules);
        $res = $this->apiCall('/charges/' . $data['charge'], [], 'GET');
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }
    }

    public function getTransactionHistory($data)
    {
        if (isset($data['profile_id']) && trim($data['profile_id']) != ''){
            $data['customer'] = $data['profile_id'];
        }
        if (isset($data['payment_profile_type']) && trim($data['payment_profile_type']) != ''){
            $data['source'] = $data['payment_profile_type'];
        }else{
            $data['source'] = 'all';
        }

        $rules = [
            'created' => 'nullable|string',
            'starting_after' => 'nullable|string',
            'limit' => 'nullable|numeric|between:1,100',
            'source' => 'nullable|in:all,alipay_account,bank_account,bitcoin_receiver,card',            
            'customer' => 'nullable|string'
        ];

        $data = $this->validator($data,$rules);
        $data['source'] = ['object' => $data['source']];
        $res = $this->apiCall('/charges', $data, 'GET');
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }
    }

    public function voidTransaction($data) 
    {
        if(isset($data['transaction_id'])){
            $data['charge'] = $data['transaction_id'];
        }
        if (isset($data['amount']) && $data['amount'] > 0){
            unset($data['amount']);
        }
        $rules = [
            'charge' => 'required|string',
            'reason' => 'nullable|in:duplicate,fraudulent,requested_by_customer'
        ];
    
        $data = $this->validator($data,$rules);
        $res = $this->apiCall('/refunds', $data);
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            if(isset($res['data']) && isset($res['data']['charge'])){
                $res['data']['ref_transaction_id'] = $res['data']['charge'];
            }
            if(isset($res['data']) && isset($res['data']['balance_transaction'])){
                $res['data']['transaction_id'] = $res['data']['balance_transaction'];
            }
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }
    }

    public function refundTransaction($data)
    {
        if(isset($data['transaction_id'])){
            $data['charge'] = $data['transaction_id'];
        }
        if (isset($data['amount']) && $data['amount'] > 0){
            $data['amount'] = $data['amount'] * 100;
        }
        $rules = [
            'charge' => 'required|string',
            'amount' => 'nullable|numeric',
            'reason' => 'nullable|in:duplicate,fraudulent,requested_by_customer'
        ];
    
        $data = $this->validator($data,$rules);
        $res = $this->apiCall('/refunds', $data);
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            if(isset($res['data']) && isset($res['data']['charge'])){
                $res['data']['ref_transaction_id'] = $res['data']['charge'];
            }
            if(isset($res['data']) && isset($res['data']['balance_transaction'])){
                $res['data']['transaction_id'] = $res['data']['balance_transaction'];
            }
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }
    }

    // Customer Information Management
    public function addCustomerProfile($data)
    {
        
        $data['source'] = $data['token'];
        $rules = [
            'email' => 'required|email|string',
            'description' => 'nullable|string',
            'source' => 'required|string'
        ];
        
        $data = $this->validator($data,$rules);
        $res = $this->apiCall('/customers', $data);
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }
    }
        
    public function updateCustomerProfile($data)
    {
        if (isset($data['profile_id']) && trim($data['profile_id']) != ''){
            $data['customer'] = $data['profile_id'];
        }
        $rules = [
            'customer' => 'required|string',
            'description' => 'nullable|string',
            'email' => 'nullable|email',
            'default_source' => 'nullable|string',
            'source' => 'nullable|string'
        ];
    
        $data = $this->validator($data,$rules);
        
        $res = $this->apiCall('/customers/' . $data['customer'], $data);
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }
        
    }
        
    public function delCustomerProfile($data)
    {
        $rules = [
            'profile_id' => 'required|string',
        ];
    
        $data = $this->validator($data,$rules);

        $res = $this->apiCall('/customers/' . $data['profile_id'], [], 'DELETE');
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }
    }
        
    public function getCustomerProfile($data)
    {
        $rules = [
            'profile_id' => 'required|string',
        ];
    
        $data = $this->validator($data,$rules);
        $res =  $this->apiCall('/customers/' . $data['profile_id'], [], 'GET');
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }
    }

    public function getCustomerProfileIds($data = [])
    {
        $rules = [
            'created' => 'nullable|string',
            'starting_after' => 'nullable|string',
            'limit' => 'nullable|numeric|between:1,100',
        ];

        $data = $this->validator($data,$rules);
        $res =  $this->apiCall('/customers', $data, 'GET');
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }        
    }

    // Payment Profile Management
    public function getPaymentProfileList($data)
    {
        if (isset($data['profile_id']) && trim($data['profile_id']) != ''){
            $data['customer'] = $data['profile_id'];
        }
        $rules = [
            'customer' => 'required|string',
            'created' => 'nullable|string',
            'starting_after' => 'nullable|string',
            'limit' => 'nullable|numeric|between:1,100',
        ];
    
        $params = $this->validator($data,$rules);
        unset($params['customer']);
        $res =  $this->apiCall('/customers/' . $data['customer'] . '/sources', $params, 'GET');
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }        
    }

    public function getPaymentProfile($data)
    {
        $rules = [
            'profile_id' => 'required|string',
            'payment_profile_id' => 'required|string'
        ];
    
        $data = $this->validator($data,$rules);

        $res =  $this->apiCall('/customers/' . $data['profile_id'] . '/sources/' . $data['payment_profile_id'], [], 'GET');
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }
        
    }

    public function addPaymentProfile($data)
    {
        $data['token_id'] = $data['token'];

        $rules = [
            'profile_id' => 'required|string',
            'token_id' => 'required|string',
        ];
    
        $data = $this->validator($data,$rules);
        
        $res =  $this->apiCall('/customers/' . $data['profile_id'] . '/sources', ['source' => $data['token_id']]);
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }
    }
    
    public function delPaymentProfile($data)
    {
        $rules = [
            'profile_id' => 'required|string',
            'payment_profile_id' => 'required|string'
        ];
    
        $data = $this->validator($data,$rules);

        $res =  $this->apiCall('/customers/' . $data['profile_id'] . '/sources/' . $data['payment_profile_id'], [], 'DELETE');
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            throw new ApiException($res['errors']['message'], '003', $res);
        }
        
    }

    public function createCardToken($data)
    {
        $rules = [
            'name' => 'required|string',
            'number' => 'required|string',
            'exp_month' => 'required|string',
            'exp_year' => 'required|string',
            'cvc' => 'required|string',
            'address_line1' => 'nullable|string',
            'address_line2' => 'nullable|string',
            'address_city' => 'nullable|string',
            'address_state' => 'nullable|string',
            'address_zip' => 'nullable|string',
            'address_country' => 'nullable|string',
        ];

        $data = $this->validator($data,$rules);
        
        $params = [];
        foreach ($data as $key => $value) {
            $params['card['.$key.']'] = $value;
        }
        $res = $this->apiCall('/tokens', $params);
        if ($res['status'] > 0) {
            $res['data'] = $this->response_transform($res['data']);
            return $res['data'];
        } else {
            if (isset($res['errors']['param'])) {
                throw new ApiException([
                    $res['errors']['param'] => $res['errors']['message']
                ], '003', $res);
            } else {
                throw new ApiException($res['errors'], '003', $res);
            }
        }
    }

    private function validator($data, $rules){
        
        $data = array_intersect_key($data, $rules);

        $validation = \Validator::make($data, $rules);

        if ($validation->passes()) {
            return $data;
        }            
        
        throw new ApiException($validation->errors(), '006', $data);
    }

    private function response_transform($data)
    {
        $res = [];
        foreach ($data as $key=>$value) {
            switch ($key) {
                case 'id':
                    if(isset($data['customer']) && !isset($data['deleted'])){
                        if(isset($data['object']) && $data['object'] == 'charge'){
                            $res['transaction_id'] = $value;
                        }else{
                            $res['payment_profile_id'] = $value;
                        }
                    }else{
                        if(isset($data['object']) && $data['object'] == 'charge'){
                            $res['transaction_id'] = $value;
                        }else{
                            $res['profile_id'] = $value;
                        }
                    }
                    break;
                case 'customer':
                    $res['profile_id'] = $value;
                    break;
                case 'sources':
                    if(isset($value['data'])){
                        foreach($value['data'] as $source){
                            $res['payment_profile'][] = [
                                'profile_id' => $source['customer'],
                                'payment_profile_id' => $source['id'],
                                'card_number' => $source['last4'],
                                'brand' => $source['last4'],
                                'exp_date' => $source['exp_month'].'-'.$source['exp_year'],
                                'billing_firstname' => $source['name'],
                                'billing_lastname' => null,
                                'billing_address' => $source['address_line1'],
                                'billing_city' => $source['address_city'],
                                'billing_state' => $source['address_state'],
                                'billing_country' => $source['address_country'],
                                'billing_zip' => $source['address_zip'],
                            ];
                        }
                    }
                    break;
                case 'default_source':
                    $res['payment_profile_id'] = $value;
                    break;
                case 'name':
                    $res['billing_firstname'] = $value;
                    break;
                case 'last4':
                    $res['card_number'] = $value;
                    break;
                case 'address_line1':
                    $res['billing_address'] = $value;
                    break;
                case 'address_city':
                    $res['billing_city'] = $value;
                    break;
                case 'address_state':
                    $res['billing_state'] = $value;
                    break;
                case 'address_zip':
                    $res['billing_zip'] = $value;
                    break;
                case 'address_country':
                    $res['billing_country'] = $value;
                    break;
                case 'status':
                    $res['response_code'] = $value;
                    break;
                case 'application_fee':
                    $res['transaction_fee'] = $value;
                    break;
                case 'source':
                    $res['payment_profile'] = [
                        'profile_id' => $value['customer'],
                        'payment_profile_id' => $value['id'],
                        'card_number' => $value['last4'],
                        'brand' => $value['last4'],
                        'exp_date' => $value['exp_month'].'-'.$value['exp_year'],
                        'billing_firstname' => $value['name'],
                        'billing_lastname' => null,
                        'billing_address' => $value['address_line1'],
                        'billing_city' => $value['address_city'],
                        'billing_state' => $value['address_state'],
                        'billing_country' => $value['address_country'],
                        'billing_zip' => $value['address_zip'],
                    ];
                    break;
                case 'amount':
                    $res['amount'] = $value/100;
                    break;
                default:
                    $res[$key] = $value;
                    break;
            }
        }
        return $res;
    }

}
