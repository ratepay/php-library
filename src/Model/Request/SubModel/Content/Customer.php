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
     *
     * @var array
     */
    public $admittedFields = [
        'Gender' => [
            'mandatory' => true,
            'uppercase' => true
        ],
        'Salutation' => [
            'mandatory' => false
        ],
        'Title' => [
            'mandatory' => false
        ],
        'FirstName' => [
            'mandatory' => true
        ],
        'MiddleName' => [
            'mandatory' => false
        ],
        'LastName' => [
            'mandatory' => true
        ],
        'NameSuffix' => [
            'mandatory' => false
        ],
        'DateOfBirth' => [
            'mandatory' => false
        ],
        'Nationality' => [
            'mandatory' => false,
            'uppercase' => true
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
            'instanceOf' => __NAMESPACE__ . "\\Customer\\Addresses"
        ],
        'Contacts' => [
            'mandatory' => true,
            'instanceOf' => __NAMESPACE__ . "\\Customer\\Contacts"
        ],
        'BankAccount' => [
            'mandatory' => false,
            'instanceOf' => __NAMESPACE__ . "\\Customer\\BankAccount"
        ],
        'CompanyName' => [
            'mandatory' => false
        ],
        'CompanyType' => [
            'optionalByRule' => true,
            'instanceOf' => "CompanyType"
        ],
        'VatId' => [
            'optionalByRule' => true
        ],
        'CompanyId' => [
            'optionalByRule' => true
        ],
        'RegistryLocation' => [
            'optionalByRule' => true
        ],
        'Homepage' => [
            'optionalByRule' => false
        ],
    ];

    /**
     * Company rule : additional company fields will only passed if company name is set
     *
     * @return bool
     */
    protected function rule()
    {
        if (key_exists('value', $this->admittedFields['CompanyName'])) {
            return true;
        } else {
            return false;
        }
    }

}
