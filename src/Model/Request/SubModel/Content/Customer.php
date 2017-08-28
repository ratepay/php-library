<?php

namespace RatePAY\Model\Request\SubModel\Content;

use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Model\Request\SubModel\Constants as CONSTANTS;

class Customer extends AbstractModel
{

    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set firstname value by using setFirstName(var). Get firstname by using getFirstName(). (Please consider the camel case)
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
     * uppercase            = value will be changed to upper case
     * lowercase            = value will be changed to lower case
     *
     * @var array
     */
    public $admittedFields = [
        'Gender' => [
            'mandatory' => true,
            'uppercase' => true
        ],
        'Salutation' => [
            'mandatory' => false,
            'cdata' => true
        ],
        'Title' => [
            'mandatory' => false,
            'cdata' => true
        ],
        'FirstName' => [
            'mandatory' => true,
            'cdata' => true
        ],
        'MiddleName' => [
            'mandatory' => false,
            'cdata' => true
        ],
        'LastName' => [
            'mandatory' => true,
            'cdata' => true
        ],
        'NameSuffix' => [
            'mandatory' => false,
            'cdata' => true
        ],
        'DateOfBirth' => [
            'mandatory' => false
        ],
        'Nationality' => [
            'mandatory' => false,
            'uppercase' => true
        ],
        'Language' => [
            'mandatory' => false,
            'lowercase' => true
        ],
        'IpAddress' => [
            'mandatory' => true
        ],
        'CustomerAllowCreditInquiry' => [
            'mandatory' => true,
            'default' => CONSTANTS::CUSTOMER_ALLOW_CREDIT_INQUIRY
        ],
        'Addresses' => [
            'mandatory' => true,
            'instanceOf' => "Content\\Customer\\Addresses"
        ],
        'Contacts' => [
            'mandatory' => true,
            'instanceOf' => "Content\\Customer\\Contacts"
        ],
        'BankAccount' => [
            'mandatory' => false,
            'cdata' => true,
            'instanceOf' => "Content\\Customer\\BankAccount"
        ],
        'CompanyName' => [
            'mandatory' => false,
            'cdata' => true
        ],
        'CompanyType' => [
            'optionalByRule' => true,
            'cdata' => true
        ],
        'VatId' => [
            'optionalByRule' => true,
            'cdata' => true
        ],
        'CompanyId' => [
            'optionalByRule' => true,
            'cdata' => true
        ],
        'RegistryLocation' => [
            'optionalByRule' => true,
            'cdata' => true
        ],
        'Homepage' => [
            'optionalByRule' => false,
            'cdata' => true
        ],
    ];

    /**
     * Company rule : additional company fields will only passed if company name is set
     *
     * @return bool
     */
    protected function rule()
    {
        foreach ($this->admittedFields as $fieldName => $value) {
            switch ($fieldName) {
                case "CompanyType":
                case "VatId":
                case "CompanyId":
                case "RegistryLocation":
                case "Homepage":
                    if (!key_exists('value', $this->admittedFields['CompanyName'])) {
                        return false;
                    }
            }
        }

        return true;
    }

}
