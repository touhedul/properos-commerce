<?php

namespace  Properos\Commerce\Classes\Integrations\Shipping\UPS;

use Illuminate\Http\File;
use Carbon\Carbon;
use Validator;
use Properos\Base\Classes\Helper;
use Properos\Base\Classes\Api as ApiCall;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use PhpParser\Node\Stmt\Class_;
use Properos\Base\Exceptions\ApiException;
use function GuzzleHttp\json_encode;
use Properos\Commerce\Classes\Integrations\Shipping\Common\IShippingUPS;
use Illuminate\Support\Arr;
/**
 * Description of ShippingUPS
 *
 * @author Joan
 */

class ShippingUPS implements IShippingUPS
{
    private $baseUrl;
    private $rateUrl;
    private $shippingUrl;
    private $trackingUrl;
    private $addressValidationUrl;
    private $labelUrl;
    private $credentials;
    private $services;
    private $services_allowed;
    private $package_types;

    public function __construct()
    {
        if (config('shipping.ups.api_integration') == true) {
            if (config('shipping.ups.api_env') == 'prod') {
                $this->baseUrl = config('shipping.ups.url_prod');
            } else if(config('shipping.ups.api_env') == 'dev') {
                $this->baseUrl = config('shipping.ups.url_dev');
            }
            $this->rateUrl = $this->baseUrl . 'Rate';
            $this->trackingUrl = $this->baseUrl . 'Track';
            $this->shippingUrl = $this->baseUrl . 'Ship';
            $this->addressValidationUrl = $this->baseUrl . 'AV';
            $this->labelUrl = $this->baseUrl . 'LBRecovery';

            if (config('shipping.ups.api_user') != '' && config('shipping.ups.api_password') != ''
                && config('shipping.ups.access_token') && config('shipping.ups.account_number')) {
                $this->credentials['user'] = config('shipping.ups.api_user');
                $this->credentials['password'] = config('shipping.ups.api_password');
                $this->credentials['token'] = config('shipping.ups.access_token');
                $this->credentials['account_number'] = config('shipping.ups.account_number');
            } else {
                throw new ApiException("The credentials are not configured.", '006');
            }

            $this->services = config('shipping.ups.services');
            $this->package_types = config('shipping.ups.package_types');
            $this->services_allowed = config('shipping.ups.services_allowed');
        }
        /* else {
            throw new ApiException("The UPS API is not configured.", '006');
        }  */
    }

    public function buildShippingShopRequest($data)
    {  
        $request_data = [];
        $validator = Validator::make($data, [
            /* 'shipper_number' => 'required|max:255', */
            'shipper_address' => 'required',
            'shipper_city' => 'required',
            'shipper_zip' => 'required',
            'shipper_state' => 'required',
            'shipper_country' => 'required',
            'customer_address' => 'required',
            'customer_city' => 'required',
            'customer_zip' => 'required',
            'customer_state' => 'required',
            'customer_country' => 'required',
            'package_length' => 'required',
            'package_width' => 'required',
            'package_height' => 'required',
            'package_weight' => 'required',
        ]);
        if (!$validator->fails()) {
          
            //$normalized_data = $this->normalizeData($data);
            if (count($this->credentials) > 0) {
                $request_data = [
                    'UPSSecurity' => [
                        'UsernameToken' => [
                            'Username' => $this->credentials['user'],
                            'Password' => $this->credentials['password']
                        ],
                        'ServiceAccessToken' => [
                            'AccessLicenseNumber' => $this->credentials['token']
                        ]
                    ],
                    'RateRequest' => [
                        'Request' => [
                            'RequestAction' => "Rate",
                            'RequestOption' => "Shop",
                            /* 'TransactionReference' => [
                                'CustomerContext' => "Your Customer Context"
                            ] */
                        ],
                        'Shipment' => [
                            'Shipper' => [
                            /*  'Name' => "Properos LLC",
                                'AttentionName' => "Yoandro Gonzalez",
                                'TaxIdentificationNumber' => "123456",
                                'Phone' => [
                                    'Number' => "7863575509",
                                    'Extension' => 1
                                ], */
                                /* 'ShipperNumber' => "9V1483", */
                                /* 'FaxNumber' => "1234567890", */
                                'Address' => [
                                    'AddressLine1' => $data['shipper_address'],
                                    'City' => $data['shipper_city'],
                                    'StateProvinceCode' => $data['shipper_state'],
                                    'PostalCode' => $data['shipper_zip'],
                                    'CountryCode' => $data['shipper_country']
                                ]
                            ],
                            'ShipTo' => [
                                /* 'Name' => "TEST",
                                'AttentionName' => "TEST ONE",
                                'Phone' => [
                                    'Number' => "1234567890"
                                ], */

                                'Address' => [
                                    'AddressLine1' => $data['customer_address'],
                                    'City' => $data['customer_city'],
                                    'StateProvinceCode' => $data['customer_state'],
                                    'PostalCode' => $data['customer_zip'],
                                    'CountryCode' => $data['customer_country'],
                                ]
                            ],
                            /* 'ShipFrom' => [
                                'Name' => "Ship From Name",
                                'AttentionName' => "Ship From Attn Name",
                                'Phone' => [
                                    'Number' => "1234567890"
                                ],
                                'FaxNumber' => "1234567890",
                                'Address' => [
                                    'AddressLine' => "13501 SW 128 St",
                                    'City' => 'MIAMI',
                                    'StateProvinceCode' => "FL",
                                    'PostalCode' => "33186",
                                    'CountryCode' => "US"
                                ]
                            ], */
                            'Package' => [
                                'PackagingType' => [
                                    'Code' => "02",
                                    /* 'Description' => "Rate" */
                                ],
                                'Dimensions' => [
                                    'UnitOfMeasurement' => [
                                        'Code' => "IN",
                                        'Description' => "inches"
                                    ],

                                    'Length' => $data['package_length'],
                                    'Width' => $data['package_width'],
                                    'Height' => $data['package_height'],
                                ],
                                'PackageWeight' => [
                                    'UnitOfMeasurement' => [
                                        'Code' => "Lbs",
                                        'Description' => "pounds"
                                    ],
                                    'Weight' => $data['package_weight']
                                ]
                            ]
                        ]
                    ]
                ];
            }
        }

        return $request_data;
    }

    public function buildTrackingRequest($data)
    {
        $request_data = [];
        $validator = Validator::make($data, []);
        if (!$validator->fails()) {
           
            //$normalized_data = $this->normalizeData($data);
            if (count($this->credentials) > 0) {
                $request_data = [
                    'UPSSecurity' => [
                        'UsernameToken' => [
                            'Username' => $this->credentials['user'],
                            'Password' => $this->credentials['password']
                        ],
                        'ServiceAccessToken' => [
                            'AccessLicenseNumber' => $this->credentials['token']
                        ]
                    ],
                    'TrackRequest' => [
                        'Request' => [
                            'RequestOption' => "1",
                            /* 'TransactionReference' => [
                                'CustomerContext' => "Your Customer Context"
                            ] */
                        ],
                        'InquiryNumber' => $data['tracking_number']
                    ]
                ];
            }
        }

        return $request_data;
    }

    public function normalizeData($data)
    {
        return $data;
    }

    public function mapShippingMethods($data)
    {
        $mapping = [];
        foreach ($data as $key => $method) {
            $description = $this->getService($method['Service']['Code']);
            if (in_array($method['Service']['Code'], $this->services_allowed) && $description != null) {
                $service = new \stdClass();
                $service->cost = $method['TotalCharges']['MonetaryValue'];
                $service->code = $method['Service']['Code'];
                $service->description = $description;
                $mapping[$service->code] = $service;
            }
        }
        return $mapping;
    }

    public function getShippingRates($data)
    {
        $request_data = $this->buildShippingShopRequest($data);
        $_result = [];
        if (array_key_exists('RateRequest', $request_data)) {
            $res = json_decode(ApiCall::call($this->rateUrl, $request_data, 'post', ['Content-Type: application/json']), true);
            
            if($res['status'] > 0){
                $result = $res['data'];
                if (isset($result['RateResponse'])) {
                    $shippingMethods = $result['RateResponse']['RatedShipment'];
                    
                    if (count($shippingMethods) > 0) {
                        $methods = $this->mapShippingMethods($shippingMethods);
                        $_result['methods'] = $methods;
                    }
                } else if (isset($result['Fault'])) {
                    if (count($result['Fault']['detail']['Errors']['ErrorDetail']) > 1) {
                        $_result['error_code'] = $result['Fault']['detail']['Errors']['ErrorDetail'][0]['PrimaryErrorCode']['Code'];
                        $_result['error_msg'] = $result['Fault']['detail']['Errors']['ErrorDetail'][0]['PrimaryErrorCode']['Description'];
                    } else if (count($result['Fault']['detail']['Errors']['ErrorDetail']) == 1) {
                        $_result['error_code'] = $result['Fault']['detail']['Errors']['ErrorDetail']['PrimaryErrorCode']['Code'];
                        $_result['error_msg'] = $result['Fault']['detail']['Errors']['ErrorDetail']['PrimaryErrorCode']['Description'];
                    }
                }
            }
        }
        return $_result;
    }

    public function getTrackingInfo($data)
    {
        $request_data = $this->buildTrackingRequest($data);
        $_result = [];
        $tracking_info = [];
        if (array_key_exists('TrackRequest', $request_data)) {
            $result = json_decode(ApiCall::call($this->trackingUrl, $request_data, 'post', ['Content-Type: application/json']));
            if (isset($result->TrackResponse)) {
                $tracking_info['tracking_number'] = $result->TrackResponse->Shipment->InquiryNumber->Value;
                $tracking_info['shipment_type'] = isset($result->TrackResponse->Shipment->ShipmentType) ? $result->TrackResponse->Shipment->ShipmentType->Description : '';
                $tracking_info['shipment_service'] = $result->TrackResponse->Shipment->Service->Description;
                if (isset($result->TrackResponse->Shipment->PickupDate)) {
                    $tracking_info['pickup_date'] = $result->TrackResponse->Shipment->PickupDate;
                }
                if (isset($result->TrackResponse->Shipment->Package)) {
                    if (isset($result->TrackResponse->Shipment->Package->Activity)) {
                        foreach ($result->TrackResponse->Shipment->Package->Activity as $key => $activity) {
                            $activities[] = $activity;
                        }
                        $tracking_info['activities'] = $activities;
                    }
                }
                if (count($tracking_info) > 0) {
                    $_result['tracking_info'] = $tracking_info;
                }
            } else if (isset($result->Fault)) {
                $_result['error_code'] = $result->Fault->detail->Errors->ErrorDetail->PrimaryErrorCode->Code;
                $_result['error_msg'] = $result->Fault->detail->Errors->ErrorDetail->PrimaryErrorCode->Description;
            }
        }

        return $_result;
    }

    public function shipmentRequest($data)
    {
        $request_data = $this->buildShipmentRequest($data);
        $_result = [];
        // throw new ApiException('', '006',$request_data);
        $result = json_decode(ApiCall::call($this->shippingUrl, $request_data, 'post', ['Content-Type: application/json']), true);
        if (isset($result['ShipmentResponse']) && isset($result['ShipmentResponse']['Response']['ResponseStatus']['Code']) && $result['ShipmentResponse']['Response']['ResponseStatus']['Code'] > 0) {
            $_result['total_charges'] = Arr::get($result, 'ShipmentResponse.ShipmentResults.ShipmentCharges.TotalCharges.MonetaryValue', 0);
            $_result['tracking_number'] = Arr::get($result, 'ShipmentResponse.ShipmentResults.ShipmentIdentificationNumber', '');
            $_result['label'] = [
                'image' => Arr::get($result, 'ShipmentResponse.ShipmentResults.PackageResults.ShippingLabel.GraphicImage', ''),
                'html' => Arr::get($result, 'ShipmentResponse.ShipmentResults.PackageResults.ShippingLabel.HTMLImage', ''),
            ];
        } else if (isset($result['Fault'])) {
            throw new ApiException(Arr::get($result, 'Fault.detail.Errors.ErrorDetail', 'Error'), '006', $result);
        } else {
            throw new ApiException(json_encode($result['VoidShipmentResponse']), '006', $result);
        }

        return $_result;
    }

    public function buildShipmentRequest($data)
    {
        $request_data = [];
        $validator = Validator::make($data, [
            'shipper_address' => 'required',
            'shipper_city' => 'required',
            'shipper_zip' => 'required',
            'shipper_state' => 'required',
            'shipper_country' => 'required',
            'customer_address' => 'required',
            'customer_city' => 'required',
            'customer_zip' => 'required',
            'customer_state' => 'required',
            'customer_country' => 'required',
            'service_code' => 'required',
            'package_type' => 'required',
            'package_length' => 'required',
            'package_width' => 'required',
            'package_height' => 'required',
            'package_weight' => 'required',

        ]);
        if (!$validator->fails()) {
            //$normalized_data = $this->normalizeData($data);
            if (count($this->credentials) > 0) {
                $request_data = [
                    'UPSSecurity' => [
                        'UsernameToken' => [
                            'Username' => $this->credentials['user'],
                            'Password' => $this->credentials['password']
                        ],
                        'ServiceAccessToken' => [
                            'AccessLicenseNumber' => $this->credentials['token']
                        ]
                    ],
                    'ShipmentRequest' => [
                        "Request" => [
                            "RequestOption" => "validate"
                        ],
                        'Shipment' => [
                            'Shipper' => [
                                'Name' => env('APP_NAME'),
                                'AttentionName' => env('APP_NAME'),
                                'ShipperNumber' => $this->credentials['account_number'],
                                'Address' => [
                                    'AddressLine' => $data['shipper_address'],
                                    'City' => $data['shipper_city'],
                                    'StateProvinceCode' => $data['shipper_state'],
                                    'PostalCode' => $data['shipper_zip'],
                                    'CountryCode' => $data['shipper_country']
                                ],
                                'Phone' => [
                                    'Number' => Arr::get($data, 'shipper_phone_number', '0000000000'),
                                ]
                            ],
                            'Description' => [
                                'Description' => "International shipment"
                            ],
                            'ShipTo' => [
                                'Name' => Arr::get($data, 'customer_name', 'Unknown'),
                                'AttentionName' => Arr::get($data, 'customer_name', 'Unknown'),
                                'Phone' => [
                                    'Number' => Arr::get($data, 'customer_phone', '0000000000'),
                                ],
                                'Address' => [
                                    'AddressLine' => $data['customer_address'],
                                    'City' => $data['customer_city'],
                                    'StateProvinceCode' => $data['customer_state'],
                                    'PostalCode' => $data['customer_zip'],
                                    'CountryCode' => $data['customer_country'],
                                ]
                            ],
                            'PaymentInformation' => [
                                'ShipmentCharge' => [
                                    "Type" => "01",
                                    'BillShipper' => [
                                        'AccountNumber' => $this->credentials['account_number']
                                    ]
                                ]
                            ],
                            'Service' => [
                                'Code' => $data['service_code'],
                                'Description' => $this->getService($data['service_code'])
                            ],
                            'Package' => [
                                'Description' => 'Description',
                                'Packaging' => [
                                    'Code' => $data['package_type'],
                                    'Description' => $this->getPackageType($data['package_type'])
                                ],
                                'Dimensions' => [
                                    'UnitOfMeasurement' => [
                                        'Code' => "IN",
                                        'Description' => "Inches"
                                    ],

                                    'Length' => $data['package_length'],
                                    'Width' => $data['package_width'],
                                    'Height' => $data['package_height'],
                                ],
                                'PackageWeight' => [
                                    'UnitOfMeasurement' => [
                                        'Code' => "LBS",
                                        'Description' => "Pounds"
                                    ],
                                    'Weight' => $data['package_weight']
                                ]
                            ]
                        ],
                        'LabelSpecification' => [
                            'LabelImageFormat' => [
                                'Code' => 'GIF',
                                'Description' => 'GIF'
                            ],
                            'HTTPUserAgent' => 'Mozilla/4.5'
                        ],
                    ]
                ];
            }
        } else {
            throw new ApiException($validator->errors(), '006');
        }

        return $request_data;
    }

    public function createLabel($data)
    {
        $shipmentResponse = $this->shipmentRequest($data);
        return $shipmentResponse;
    }

    public function getLabel($data)
    {
        $request_data = $this->buildLabelRecoveryRequest($data);
        $_result = [];
        $result = json_decode(ApiCall::call($this->labelUrl, $request_data, 'POST', ['Content-Type: application/json']), true);
        if (isset($result['LabelRecoveryResponse']) && isset($result['LabelRecoveryResponse']['Response']['ResponseStatus']['Code']) && $result['LabelRecoveryResponse']['Response']['ResponseStatus']['Code'] > 0) {
            $_result['tracking_number'] = Arr::get($result, 'LabelRecoveryResponse.LabelResults.TrackingNumber', '');
            $_result['label'] = [
                'image' => Arr::get($result, 'LabelRecoveryResponse.LabelResults.LabelImage.GraphicImage', ''),
                'html' => Arr::get($result, 'LabelRecoveryResponse.LabelResults.LabelImage.HTMLImage', '')
            ];
        } elseif (isset($result['Fault'])) {
            throw new ApiException(Arr::get($result, 'Fault.detail.Errors.ErrorDetail', 'Error'), '006', $result);
        } else {
            throw new ApiException(json_encode($result['VoidShipmentResponse']), '006', $result);
        }

        return $_result;
    }

    public function buildLabelRecoveryRequest($data)
    {
        $request_data = [];
        $validator = Validator::make($data, [
            'tracking_number' => 'required',
        ]);
        if (!$validator->fails()) {
            //$normalized_data = $this->normalizeData($data);
            if (count($this->credentials) > 0) {
                $request_data = [
                    'UPSSecurity' => [
                        'UsernameToken' => [
                            'Username' => $this->credentials['user'],
                            'Password' => $this->credentials['password']
                        ],
                        'ServiceAccessToken' => [
                            'AccessLicenseNumber' => $this->credentials['token']
                        ]
                    ],
                    'LabelRecoveryRequest' => [
                        "Request" => [
                            "RequestAction" => "LabelRecovery",
                        ],
                        'LabelSpecification' => [
                            'LabelImageFormat' => [
                                'Code' => 'GIF'
                            ],
                        ],
                        'TrackingNumber' => $data['tracking_number']
                    ]
                ];
            }
        } else {
            throw new ApiException($validator->errors(), '006');
        }

        return $request_data;
    }

    public function voidLabel($data)
    {
        $request_data = $this->buildVoidLabelRequest($data);
        $_result = [];

        $result = json_decode(ApiCall::call($this->baseUrl . 'Void', $request_data, 'POST', ['Content-Type: application/json']), true);
        if (isset($result['VoidShipmentResponse']) && isset($result['VoidShipmentResponse']['Response']['ResponseStatus']['Code']) && $result['VoidShipmentResponse']['Response']['ResponseStatus']['Code'] > 0) {
            $_result['status'] = 1;
            $_result['status'] = Arr::get($result, 'VoidShipmentResponse.SummaryResult.Status.Code', 0);
            $_result['message'] = Arr::get($result, 'VoidShipmentResponse.SummaryResult.Status.Description', '');

        } elseif (isset($result['Fault'])) {
            throw new ApiException(Arr::get($result, 'Fault.detail.Errors.ErrorDetail', 'Error'), '006', $result);
        } else {
            throw new ApiException(json_encode($result['VoidShipmentResponse']), '006', $result);
        }

        return $_result;
    }

    public function buildVoidLabelRequest($data)
    {
        $request_data = [];
        $validator = Validator::make($data, [
            'tracking_number' => 'required',
        ]);
        if (!$validator->fails()) {
           
            //$normalized_data = $this->normalizeData($data);
            if (count($this->credentials) > 0) {
                $request_data = [
                    'UPSSecurity' => [
                        'UsernameToken' => [
                            'Username' => $this->credentials['user'],
                            'Password' => $this->credentials['password']
                        ],
                        'ServiceAccessToken' => [
                            'AccessLicenseNumber' => $this->credentials['token']
                        ]
                    ],
                    'VoidShipmentRequest' => [
                        "Request" => [
                            "RequestAction" => "1",
                        ],
                        "VoidShipment" => [
                            'ShipmentIdentificationNumber' => $data['tracking_number']
                        ]
                    ]
                ];
            }
        } else {
            throw new ApiException($validator->errors(), '006');
        }

        return $request_data;
    }

    public function getService($code)
    {
        return isset($this->services[$code]) ? $this->services[$code] : null;
    }

    public function getServices()
    {
        return $this->services;
    }

    public function getAllowedServices()
    {
        $result = [];
        foreach ($this->services_allowed as $allowed) {
            if (isset($this->services[$allowed])) {
                $result[$allowed] = $this->services[$allowed];
            }
        }
        return $result;
    }

    public function getPackageType($code)
    {
        return isset($this->package_types[$code]) ? $this->package_types[$code] : null;
    }

    public function getPackageTypes()
    {
        return $this->package_types;
    }
}
