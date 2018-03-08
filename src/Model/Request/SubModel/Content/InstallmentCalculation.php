<?php

namespace RatePAY\Model\Request\SubModel\Content;

use RatePAY\Model\Request\SubModel\AbstractModel;

class InstallmentCalculation extends AbstractModel
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
        ],
        'CalculationRate' => [
            'mandatoryByRule' => true,
            'instanceOf' => "Content\\InstallmentCalculation\\CalculationRate",
        ],
        'CalculationTime' => [
            'mandatoryByRule' => true,
            'instanceOf' => "Content\\InstallmentCalculation\\CalculationTime",
        ],
        'PaymentFirstday' => [
            'mandatory' => false,
        ],
        'Configuration' => [
            'mandatory' => false,
            'instanceOf' => "Content\\Configuration",
        ],
        'CalculationStart' => [
            'mandatory' => false,
        ],
        // Following fields are necessary for offline installment calculation
        'ServiceCharge' => [
            'mandatory' => false,
        ],
        'InterestRate' => [
            'mandatory' => false,
        ]
    ];

    /**
     * Installment calculation rule : time or rate has to be set
     *
     * @return bool
     */
    protected function rule()
    {
        if (!key_exists('value', $this->admittedFields['CalculationTime']) && !key_exists('value', $this->admittedFields['CalculationRate'])) {
            $this->setErrorMsg("Calculation type is missing");
            return false;
        }

        return true;
    }

}
