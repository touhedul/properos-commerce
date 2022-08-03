<?php
namespace Properos\Commerce\Classes\Integrations\Common;

/**
 * Description of ECommerceClass
 *
 * @author Properos
 */
class ECommerceClass implements ECommerceInterface
{

    public $ecommerce;
    public $name;

    public function __construct($ecommerce, $data)
    {
        $active_marketplaces = [];
        if (config('app.active_amazon') == true) {
            $active_marketplaces[] = 'amazon';
        }
        if (count($active_marketplaces) > 0) {
            $this->name = strtolower($ecommerce);
            if (in_array($this->name, $active_marketplaces)) {
                $ecommerce = '\App\Classes\Integrations\ECommerce\\' . ucfirst($this->name) . '\\' . ucfirst($this->name) . 'Class';
                $this->ecommerce = new $ecommerce($data);
            }
        } else {
            $this->ecommerce = null;
        }
    }

    public function getLimit()
    {
        return $this->ecommerce->getLimit();
    }

    public function setLimit($limit)
    {
        $this->ecommerce->setLimit($limit);
        return $this;
    }

    public function getEcommerce()
    {
        return $this->ecommerce;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setEcommerce($ecommerce)
    {
        $this->ecommerce = $ecommerce;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function connectionCheck()
    {
        return $this->ecommerce->connectionCheck();
    }

    public function setSettings($data)
    {
        $this->ecommerce->setSettings($data);
    }

    public function getSettings()
    {
        return $this->ecommerce->getSettings();
    }

    public function getCustomers($options = [])
    {
        return $this->ecommerce->getCustomers($options);
    }

    public function getOrders($options = [])
    {
        return $this->ecommerce->getOrders($options);
    }

    public function getCustomer($id, $options = [])
    {
        return $this->ecommerce->getCustomer($id, $options);
    }

    public function getOrder($id, $options = [])
    {
        return $this->ecommerce->getOrder($id, $options);
    }

    public function getShipments($options = [])
    {
        return $this->ecommerce->getShipments($options);
    }

    public function getCountCustomers($options = [])
    {
        return $this->ecommerce->getCountCustomers($options);
    }

    public function getCountOrders($options = [])
    {
        return $this->ecommerce->getCountOrders($options);
    }

    public function address($address)
    {
        return $this->ecommerce->address($address);
    }

    public function getProducts($options)
    {
        return $this->ecommerce->getProducts($options);
    }

    public function getProductList($options)
    {
        return $this->ecommerce->getProductList($options);
    }

    public function getOrderItems($id)
    {
        return $this->ecommerce->getOrderItems($id);
    }

    public function getInventory($options)
    {
        return $this->ecommerce->getInventory($options);
    }

    public function updateProductQtyFeed($options)
    {
        return $this->ecommerce->updateProductQtyFeed($options);
    }

    public function updateProductQtyFeedResult($options)
    {
        return $this->ecommerce->updateProductQtyFeedResult($options);
    }
}