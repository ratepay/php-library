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

namespace RatePAY\Model\Request\SubModel\Content\Customer\Contacts;

use RatePAY\Model\Request\SubModel\AbstractModel;

/**
 * @method $this  setAreaCode(string $areaCode)
 * @method string getAreaCode()
 * @method $this  setDirectDial(string $directDial)
 * @method string getDirectDial()
 */
class Phone extends AbstractModel
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
     * instanceOf           = value has to be an instance of class (in value)
     * cdata                = value will be wrapped in CDATA tag
     *
     * @var array
     */
    public $admittedFields = [
        'AreaCode' => [
            'mandatory' => false,
            'cdata' => true,
        ],
        'DirectDial' => [
            'mandatory' => true,
            'cdata' => true,
        ],
    ];
}
