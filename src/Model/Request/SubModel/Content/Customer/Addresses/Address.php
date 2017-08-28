<?php

namespace RatePAY\Model\Request\SubModel\Content\Customer\Addresses;

use RatePAY\Model\Request\SubModel\AbstractModel;

class Address extends AbstractModel
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
        'Type' => [
            'mandatory' => true,
            'isAttribute' => true,
            'uppercase' => true
        ],
        'Salutation' => [
            'mandatory' => false
        ],
        'FirstName' => [
            'optionalByRule' => true,
            'cdata' => true
        ],
        'LastName' => [
            'optionalByRule' => true,
            'cdata' => true
        ],
        'Company' => [
            'mandatoryByRule' => true,
            'cdata' => true
        ],
        'Street' => [
            'mandatory' => true,
            'cdata' => true
        ],
        'StreetAdditional' => [
            'mandatory' => false,
            'cdata' => true
        ],
        'StreetNumber' => [
            'mandatory' => false,
            'cdata' => true
        ],
        'ZipCode' => [
            'mandatory' => true,
            'cdata' => true
        ],
        'City' => [
            'mandatory' => true,
            'cdata' => true
        ],
        'CountryCode' => [
            'mandatory' => true,
            'uppercase' => true
        ],
    ];

    /**
     * Address rule : names are only mandatory in billing addresses, company is mandatory in registry addresses
     *
     * @return bool
     */
    protected function rule()
    {
        if (strtoupper($this->admittedFields['Type']['value']) == 'DELIVERY') {
            if (!key_exists('value', $this->admittedFields['FirstName']) || !key_exists('value', $this->admittedFields['LastName'])) {
                $this->setErrorMsg("Delivery address requires firstname and lastname");
                return false;
            }
        }
        if (strtoupper($this->admittedFields['Type']['value']) == 'REGISTRY') {
            if (!key_exists('value', $this->admittedFields['Company'])) {
                $this->setErrorMsg("Registry address requires company");
                return false;
            }
        }
        return true;
    }
}
