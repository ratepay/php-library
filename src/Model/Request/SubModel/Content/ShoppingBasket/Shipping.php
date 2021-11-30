<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * Ratepay GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by Ratepay GmbH.
 * All rights reserved by Ratepay GmbH.
 *
 * Copyright (c) 2019 Ratepay GmbH / Berlin / Germany
 */

namespace RatePAY\Model\Request\SubModel\Content\ShoppingBasket;

use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Service\Util;

/**
 * @method $this  setDescription(string $description)
 * @method string getDescription()
 * @method $this  setUnitPriceGross(float $unitPriceGross)
 * @method float  getUnitPriceGross()
 * @method $this  setTaxRate(float $taxRate)
 * @method float  getTaxRate()
 * @method $this  setDescriptionAddition(string $amount)
 * @method string getDescriptionAddition()
 */
class Shipping extends AbstractModel
{
    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set payment unit price gross by using setUnitPriceGross(var). Get unit price gross by using getUnitPriceGross(). (Please consider the camel case).
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
            'mandatory' => false,
            'cdata' => true,
        ],
        'UnitPriceGross' => [
            'mandatory' => true,
            'isAttribute' => true,
        ],
        'TaxRate' => [
            'mandatory' => true,
            'isAttribute' => true,
        ],
        'DescriptionAddition' => [
            'mandatory' => false,
            'isAttribute' => true,
        ],
    ];

    /*
     * List of settings.
     * In addition to API fields there are settings possible to control library behavior.
     */
    public $settings = [
        'AutoDelivery' => false,
    ];

    /**
     * @return array
     *
     * @throws \RatePAY\Exception\ModelException
     * @throws \RatePAY\Exception\RuleSetException
     */
    public function toArray()
    {
        $this->admittedFields['UnitPriceGross']['value'] = Util::changeAmountToFloat($this->admittedFields['UnitPriceGross']['value']);

        return parent::toArray();
    }
}
