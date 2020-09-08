<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * RatePAY GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by RatePAY GmbH.
 * All rights reserved by RatePAY GmbH.
 *
 * Copyright (c) 2019 RatePAY GmbH / Berlin / Germany
 */

namespace RatePAY\Model\Request\SubModel\Content\Customer\Addresses;

use RatePAY\Model\Request\SubModel\AbstractModel;

/**
 * @method $this  setType(string $type)
 * @method string getType()
 * @method $this  setSalutation(string $salutation)
 * @method string getSalutation()
 * @method $this  setFirstName(string $firstName)
 * @method string getFirstName()
 * @method $this  setLastName(string $lastName)
 * @method string getLastName()
 * @method $this  setCompany(string $company)
 * @method string getCompany()
 * @method $this  setStreet(string $street)
 * @method string getStreet()
 * @method $this  setStreetAdditional(string $streetAdditional)
 * @method string getStreetAdditional()
 * @method $this  setStreetNumber(string $streetNumber)
 * @method string getStreetNumber()
 * @method $this  setZipCode(string $zipCode)
 * @method string getZipCode()
 * @method $this  setCity(string $city)
 * @method string getCity()
 * @method $this  setCountryCode(string $countryCode)
 * @method string getCountryCode()
 */
class Address extends AbstractModel
{
    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set firstname value by using setFirstName(var). Get firstname by using getFirstName(). (Please consider the camel case).
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
            'uppercase' => true,
        ],
        'Salutation' => [
            'mandatory' => false,
        ],
        'FirstName' => [
            'optionalByRule' => true,
            'cdata' => true,
        ],
        'LastName' => [
            'optionalByRule' => true,
            'cdata' => true,
        ],
        'Company' => [
            'mandatoryByRule' => true,
            'cdata' => true,
        ],
        'Street' => [
            'mandatory' => true,
            'cdata' => true,
        ],
        'StreetAdditional' => [
            'mandatory' => false,
            'cdata' => true,
        ],
        'StreetNumber' => [
            'mandatory' => false,
            'cdata' => true,
        ],
        'ZipCode' => [
            'mandatory' => true,
            'cdata' => true,
        ],
        'City' => [
            'mandatory' => true,
            'cdata' => true,
        ],
        'CountryCode' => [
            'mandatory' => true,
            'uppercase' => true,
        ],
    ];

    /**
     * Address rule : names are only mandatory in billing addresses, company is mandatory in registry addresses.
     *
     * @return bool
     */
    protected function rule()
    {
        if (strtoupper($this->admittedFields['Type']['value']) == 'DELIVERY') {
            if (!key_exists('value', $this->admittedFields['FirstName']) || !key_exists('value', $this->admittedFields['LastName'])) {
                $this->setErrorMsg('Delivery address requires firstname and lastname');

                return false;
            }
        }
        if (strtoupper($this->admittedFields['Type']['value']) == 'REGISTRY') {
            if (!key_exists('value', $this->admittedFields['Company'])) {
                $this->setErrorMsg('Registry address requires company');

                return false;
            }
        }

        return true;
    }
}
