<?php

namespace RatePAY\Model\Request\SubModel\Content\ShoppingBasket;

use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Service\Util;

class Discount extends AbstractModel
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
        'UnitPriceGross' => [
            'mandatory' => true,
            'isAttribute' => true
        ],
        'TaxRate' => [
            'mandatory' => true,
            'isAttribute' => true
        ],
        'DescriptionAddition' => [
            'mandatory' => false,
            'isAttribute' => true
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
     * Changes discount to negative value (if necessary)
     *
     * @return array
     */
    public function toArray()
    {
        $unitPrice = Util::changeAmountToFloat($this->admittedFields['UnitPriceGross']['value']);
        $this->admittedFields['UnitPriceGross']['value'] = Util::changeValueToNegative($unitPrice);

        return parent::toArray();
    }

}
