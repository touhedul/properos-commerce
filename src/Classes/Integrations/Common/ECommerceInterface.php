<?php
namespace  Properos\Commerce\Classes\Integrations\Common;

/**
 *
 * @author Properos
 */
interface ECommerceInterface
{

    public function connectionCheck();

    public function getLimit();

    public function setLimit($limit);

    public function setSettings($data);

    public function getSettings();

    public function getOrders($options);

    public function getOrder($id, $options);

    public function getCountOrders($options);

    public function getCustomer($id, $options);

    public function getCustomers($options);

    public function getCountCustomers($options);

    public function getShipments($options);

    public function address($address);
}