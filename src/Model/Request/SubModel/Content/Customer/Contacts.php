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

namespace RatePAY\Model\Request\SubModel\Content\Customer;

use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Model\Request\SubModel\Content\Customer\Contacts\Phone;

/**
 * @method $this  setEmail(string $email)
 * @method string getEmail()
 * @method $this  setMobile(Phone $mobile)
 * @method Phone  getMobile()
 * @method $this  setPhone(Phone $phone)
 * @method Phone  getPhone()
 * @method $this  setFax(Phone $fax)
 * @method Phone  getFax()
 */
class Contacts extends AbstractModel
{
    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set email value by using setEmail(var). Get email by using getEmail(). (Please consider the camel case).
     *
     * Settings:
     * mandatory            = field is mandatory (or optional)
     * mandatoryByRule      = field is mandatory if rule is passed
     * optionalByRule       = field will only returned if rule is passed
     * default              = default value if no different value is set
     * isAttribute          = field is xml attribute to parent object
     * isAttribute          = field is xml attribute to parent object
     * isAttributeTo        = field is xml attribute to field (in value)
     * instanceOf           = value has to be an instance of class (in value)
     * cdata                = value will be wrapped in CDATA tag
     *
     * @var array
     */
    public $admittedFields = [
        'Email' => [
            'mandatory' => true,
            'cdata' => true,
        ],
        'Mobile' => [
            'mandatoryByRule' => true,
            'cdata' => true,
            'instanceOf' => 'Content\\Customer\\Contacts\\Phone',
        ],
        'Phone' => [
            'mandatoryByRule' => true,
            'cdata' => true,
            'instanceOf' => 'Content\\Customer\\Contacts\\Phone',
        ],
        'Fax' => [
            'mandatory' => false,
            'cdata' => true,
            'instanceOf' => 'Content\\Customer\\Contacts\\Phone',
        ],
    ];

    /**
     * Phone rule : at least one phone (or mobile) number must be set.
     *
     * @return bool
     */
    protected function rule()
    {
        if (!key_exists('value', $this->admittedFields['Mobile']) && !key_exists('value', $this->admittedFields['Phone'])) {
            $this->setErrorMsg('At least mobile or phone number are required');

            return false;
        }

        return true;
    }
}
