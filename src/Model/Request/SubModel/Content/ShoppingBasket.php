<?php

namespace RatePAY\Model\Request\SubModel\Content;

use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Service\Util;

class ShoppingBasket extends AbstractModel
{

    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set payment items by using setItems(var). Get items by using getItems(). (Please consider the camel case)
     *
     * Settings:
     * mandatory            = field is mandatory (or optional)
     * mandatoryByRule      = field is mandatory if rule is passed
     * optionalByRule       = field will only returned if rule is passed
     * default              = default value if no different value is set
     * isAttribute          = field is xml attribute to parent object
     * isAttributeTo        = field is xml attribute to field (in value)
     * instanceOf           = value has to be an instance of class (in value)
     * cdata                = value will be wrapped in CDATA tag
     *
     * @var array
     */
    public $admittedFields = [
        'Amount' => [
            'mandatory' => true,
            'isAttribute' => true
        ],
        'Currency' => [
            'mandatory' => true,
            'isAttribute' => true,
            'default' => "EUR",
            'uppercase' => true
        ],
        'Items' => [
            'mandatory' => false,
            'instanceOf' => "Content\\ShoppingBasket\\Items"
        ],
        'Shipping' => [
            'mandatory' => false,
            'instanceOf' => "Content\\ShoppingBasket\\Shipping"
        ],
        'Discount' => [
            'mandatory' => false,
            'instanceOf' => "Content\\ShoppingBasket\\Discount"
        ],
    ];

    /*
     * List of settings.
     * In addition to API fields there are settings possible to control library behavior.
     */
    public $settings = [
        'AutoDelivery' => false
    ];

    /**
     * Totalizes item amounts and sets shopping basket amount.
     *
     * @return array
     * @throws \RatePAY\Exception\ModelException
     * @throws \RatePAY\Exception\RuleSetException
     */
    public function toArray()
    {
        if (!key_exists('value', $this->admittedFields['Amount'])) {
            $amount = 0;

            if (key_exists('value', $this->admittedFields['Items'])) {
                $items = $this->admittedFields['Items']['value']->toArray();
                if (key_exists('item', $items)) { // If item list is not empty
                    foreach ($items['item'] as $item) {
                        $unitPrice = Util::changeAmountToFloat($item['attributes']['unit-price-gross']['value']);
                        $unitPriceGross = floatval($unitPrice);
                        $quantity = intval($item['attributes']['quantity']['value']);
                        $discount = (key_exists('discount', $item['attributes'])) ? floatval($item['attributes']['discount']['value']) : 0;
                        $amount += ($unitPriceGross + $discount) * $quantity;
                    }
                }
            }
            
            if (key_exists('value', $this->admittedFields['Shipping'])) {
                $shipping = $this->admittedFields['Shipping']['value']->toArray();
                $unitPrice = Util::changeAmountToFloat($shipping['attributes']['unit-price-gross']['value']);
                $amount += floatval($unitPrice);
            }
            
            if (key_exists('value', $this->admittedFields['Discount'])) {
                $discount = $this->admittedFields['Discount']['value']->toArray();
                $unitPrice = Util::changeAmountToFloat($discount['attributes']['unit-price-gross']['value']);
                $amount += floatval($unitPrice);
            }
            
            $this->admittedFields['Amount']['value'] = $amount;
        }

        return parent::toArray();
    }

}
