<?php
namespace  Properos\Commerce\Classes\Integrations\Shipping\Common;

/**
 *
 * @author Joan
 */
interface IShipping
{
    public function getShippingRates($data);
    public function getTrackingInfo($data);
}