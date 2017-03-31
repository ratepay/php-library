<?php

namespace RatePAY\Model\Request\SubModel\Content\Customer;

use RatePAY\Model\Request\SubModel\AbstractModel;

class BankAccount extends AbstractModel
{

    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set bank code value by using setBankCode(var). Get bank code by using getBankCode(). (Please consider the camel case)
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
        'Owner' => [
            'mandatory' => true,
            'cdata' => true
        ],
        'BankName' => [
            'mandatory' => false,
            'cdata' => true
        ],
        'BankAccountNumber' => [
            'mandatoryByRule' => true,
            'cdata' => true
        ],
        'BankCode' => [
            'mandatoryByRule' => true,
            'cdata' => true
        ],
        'Iban' => [
            'mandatoryByRule' => true,
            'cdata' => true
        ],
        'BicSwift' => [
            'mandatory' => false, // BicSwift is only for customers with billing address in germany optional
            'cdata' => true
        ]
    ];

    /**
     * Bank data rule : if classic bank account number is used bank code is mandatory
     *
     * @return bool
     */
    protected function rule()
    {
        if (key_exists('value', $this->admittedFields['BankAccountNumber'])) {
            if (!key_exists('value', $this->admittedFields['BankCode'])) {
                $this->setErrorMsg("Bank code is required");
                return false;
            } else {
                return true;
            }
        } elseif (key_exists('value', $this->admittedFields['Iban'])) {
            return true;
        } else {
            $this->setErrorMsg("Bank account number or IBAN are required");
            return false;
        }
    }
}
