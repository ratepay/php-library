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

namespace RatePAY\Model\Request\SubModel\Content;

use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Model\Request\SubModel\Constants as CONSTANTS;
use RatePAY\Model\Request\SubModel\Content\Customer\Addresses;
use RatePAY\Model\Request\SubModel\Content\Customer\BankAccount;
use RatePAY\Model\Request\SubModel\Content\Customer\Contacts;

/**
 * @method $this       setGender(string $value)
 * @method string      getGender()
 * @method $this       setSalutation(string $value)
 * @method string      getSalutation()
 * @method $this       setTitle(string $value)
 * @method string      getTitle()
 * @method $this       setFirstName(string $value)
 * @method string      getFirstName()
 * @method $this       setMiddleName(string $value)
 * @method string      getMiddleName()
 * @method $this       setLastName(string $value)
 * @method string      getLastName()
 * @method $this       setNameSuffix(string $value)
 * @method string      getNameSuffix()
 * @method $this       setDateOfBirth(string $value)
 * @method string      getDateOfBirth()
 * @method $this       setNationality(string $value)
 * @method string      getNationality()
 * @method $this       setLanguage(string $value)
 * @method string      getLanguage()
 * @method $this       setIpAddress(string $value)
 * @method string      getIpAddress()
 * @method $this       setCustomerAllowCreditInquiry(string $value)
 * @method string      getCustomerAllowCreditInquiry()
 * @method $this       setAddresses(Addresses $value)
 * @method Addresses   getAddresses()
 * @method $this       setContacts(Contacts $value)
 * @method Contacts    getContacts()
 * @method $this       setBankAccount(BankAccount $value)
 * @method BankAccount getBankAccount()
 * @method $this       setCompanyName(string $value)
 * @method string      getCompanyName()
 * @method $this       setCompanyType(string $value)
 * @method string      getCompanyType()
 * @method $this       setVatId(string $value)
 * @method string      getVatId()
 * @method $this       setCompanyId(string $value)
 * @method string      getCompanyId()
 * @method $this       setRegistryLocation(string $value)
 * @method string      getRegistryLocation()
 * @method $this       setHomepage(string $value)
 * @method string      getHomepage()
 */
class Customer extends AbstractModel
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
     * uppercase            = value will be changed to upper case
     * lowercase            = value will be changed to lower case
     *
     * @var array
     */
    public $admittedFields = [
        'Gender' => [
            'mandatory' => true,
            'uppercase' => true,
        ],
        'Salutation' => [
            'mandatory' => false,
            'cdata' => true,
        ],
        'Title' => [
            'mandatory' => false,
            'cdata' => true,
        ],
        'FirstName' => [
            'mandatory' => true,
            'cdata' => true,
        ],
        'MiddleName' => [
            'mandatory' => false,
            'cdata' => true,
        ],
        'LastName' => [
            'mandatory' => true,
            'cdata' => true,
        ],
        'NameSuffix' => [
            'mandatory' => false,
            'cdata' => true,
        ],
        'DateOfBirth' => [
            'mandatory' => false,
        ],
        'Nationality' => [
            'mandatory' => false,
            'uppercase' => true,
        ],
        'Language' => [
            'mandatory' => false,
            'lowercase' => true,
        ],
        'IpAddress' => [
            'mandatory' => true,
        ],
        'CustomerAllowCreditInquiry' => [
            'mandatory' => true,
            'default' => CONSTANTS::CUSTOMER_ALLOW_CREDIT_INQUIRY,
        ],
        'Addresses' => [
            'mandatory' => true,
            'instanceOf' => 'Content\\Customer\\Addresses',
        ],
        'Contacts' => [
            'mandatory' => true,
            'instanceOf' => 'Content\\Customer\\Contacts',
        ],
        'BankAccount' => [
            'mandatory' => false,
            'cdata' => true,
            'instanceOf' => 'Content\\Customer\\BankAccount',
        ],
        'CompanyName' => [
            'mandatory' => false,
            'cdata' => true,
        ],
        'CompanyType' => [
            'optionalByRule' => true,
            'cdata' => true,
        ],
        'VatId' => [
            'optionalByRule' => true,
            'cdata' => true,
        ],
        'CompanyId' => [
            'optionalByRule' => true,
            'cdata' => true,
        ],
        'RegistryLocation' => [
            'optionalByRule' => true,
            'cdata' => true,
        ],
        'Homepage' => [
            'optionalByRule' => false,
            'cdata' => true,
        ],
    ];

    /**
     * Company rule : additional company fields will only passed if company name is set.
     *
     * @return bool
     */
    protected function rule()
    {
        foreach ($this->admittedFields as $fieldName => $value) {
            switch ($fieldName) {
                case 'CompanyType':
                case 'VatId':
                case 'CompanyId':
                case 'RegistryLocation':
                case 'Homepage':
                    if (!key_exists('value', $this->admittedFields['CompanyName'])) {
                        return false;
                    }
            }
        }

        return true;
    }
}
