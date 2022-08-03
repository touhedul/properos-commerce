<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Properos\Commerce\Classes\Payment;

use Properos\Commerce\Models\Invoice\Invoice as mInvoice;

/**
 * Description of PaymentCardClass
 *
 * @author dulce
 */
class CPayment
{

    private $credentials;
    private $driver;
    private $driverName;

    public function __construct()
    {
        $driverName = strtolower(env('CARD_PROCESSOR', "authorize"));
        $this->driverName = $driverName;
        $driverClass = "Properos\\Commerce\\Classes\\PaymentProcessors\\" . ucfirst($driverName) . "Class";
        $this->driver = new $driverClass();
    }

    public function setCredentials($credentials)
    {
        return $this->driver->setCredentials($credentials);
    }

    public function getDriver()
    {
        return $this->driverName;
    }

// Transaction Management	

    public function chargeAccount($data)
    {
        return $this->driver->chargeAccount($data);
    }

    public function authorizeAccount($data)
    {
        return $this->driver->authorizeAccount($data);
    }

    public function captureAccount($data)
    {
        return $this->driver->captureAccount($data);
    }

    public function captureCustomerProfile($data)
    {
        return $this->driver->captureCustomerProfile($data);
    }

    public function creditAccount($data)
    {
        return $this->driver->creditAccount($data);
    }

    public function voidTransaction($data)
    {
        return $this->driver->voidTransaction($data);
    }

    public function refundTransaction($data)
    {
        return $this->driver->refundTransaction($data);
    }

    public function updateSubscription($data)
    {
        return $this->driver->updateSubscription($data);
    }

    public function cancelSubscription($data)
    {
        return $this->driver->cancelSubscription($data);
    }

    public function getTransaction($data)
    {
        return $this->driver->getTransaction($data);
    }

    public function getTransactionHistory($data)
    {
        return $this->driver->getTransactionHistory($data);
    }

// Customer Information Management

    public function getCustomerProfileIds($data = array())
    {
        return $this->driver->getCustomerProfileIds($data);
    }

    public function getCustomerProfile($data)
    {
        return $this->driver->getCustomerProfile($data);
    }

    public function getPaymentProfileList($data)
    {
        return $this->driver->getPaymentProfileList($data);
    }

    public function getPaymentProfile($data)
    {
        return $this->driver->getPaymentProfile($data);
    }

    public function addCustomerProfile($data)
    {
        return $this->driver->addCustomerProfile($data);
    }

    public function addPaymentProfile($data)
    {
        return $this->driver->addPaymentProfile($data);
    }

    public function delCustomerProfile($data)
    {
        return $this->driver->delCustomerProfile($data);
    }

    public function delPaymentProfile($data)
    {
        return $this->driver->delPaymentProfile($data);
    }

}
