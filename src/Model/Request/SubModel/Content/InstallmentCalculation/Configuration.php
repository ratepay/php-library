<?php

namespace RatePAY\Model\Request\SubModel\Content\InstallmentCalculation;

use RatePAY\Model\Request\SubModel\AbstractModel;

class Configuration extends AbstractModel
{

    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set payment interest rate by using setInterestRate(var). Get interest rate by using getInterestRate(). (Please consider the camel case)
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
        'InterestRate' => [
            'mandatory' => false,
        ],
    ];

}
