<?php

namespace RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Items;

use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Service\Util;

class Item extends AbstractModel
{

    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set payment unit price gross by using setUnitPriceGross(var). Get unit price gross by using getUnitPriceGross(). (Please consider the camel case)
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
        'Description' => [
            'mandatory' => true,
            'cdata' => true
        ],
        'ArticleNumber' => [
            'mandatory' => true,
            'isAttribute' => true
        ],
        'UniqueArticleNumber' => [
            'mandatory' => false,
            'isAttribute' => true
        ],
        'Quantity' => [
            'mandatoryByRule' => true,
            'isAttribute' => true
        ],
        'UnitPriceGross' => [
            'mandatory' => true,
            'isAttribute' => true,
        ],
        'TaxRate' => [
            'mandatory' => true,
            'isAttribute' => true,
        ],
        'Category' => [
            'mandatory' => false,
            'isAttribute' => true
        ],
        'Discount' => [
            'mandatory' => false,
            'isAttribute' => true,
        ],
        'DescriptionAddition' => [
            'mandatory' => false,
            'isAttribute' => true,
        ],
        'ShopId' => [
            'mandatory' => false,
            'isAttribute' => true,
        ]
    ];

    /*
     * List of settings.
     * In addition to API fields there are settings possible to control library behavior.
     */
    public $settings = [
        'AutoDelivery' => false
    ];

    /**
     * Quantity rule : reject basket if quantity is <= 0
     *
     * @return bool
     */
    protected function rule()
    {
        if (!key_exists('value', $this->admittedFields['Quantity']) || (int) $this->admittedFields['Quantity']['value'] <= 0) {
            $this->setErrorMsg("Quantity must be at least 1");
            return false;
        }

        return true;
    }

    /**
     * Changes discount to negative value (if necessary)
     *
     * @return array
     * @throws \RatePAY\Exception\ModelException
     * @throws \RatePAY\Exception\RuleSetException
     */
    public function toArray()
    {
        if (key_exists('value', $this->admittedFields['Discount'])) {
            $this->admittedFields['Discount']['value'] = Util::changeValueToNegative($this->admittedFields['Discount']['value']);
        }

        $this->admittedFields['UnitPriceGross']['value'] = Util::changeAmountToFloat($this->admittedFields['UnitPriceGross']['value']);

        return parent::toArray();
    }

}
