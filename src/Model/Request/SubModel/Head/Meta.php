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

namespace RatePAY\Model\Request\SubModel\Head;

use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Model\Request\SubModel\Head\Meta\Systems;

/**
 * @method $this   setSystems(Systems $systems)
 * @method Systems getSystems()
 */
class Meta extends AbstractModel
{
    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set systems by using setSystems(var). Get systems by using getSystems(). (Please consider the camel case).
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
        'Systems' => [
            'mandatory' => true,
            'instanceOf' => 'Head\\Meta\\Systems',
        ],
    ];

    /**
     * Manipulates the parent method to set instance of systems if not already set.
     *
     * @return array
     */
    public function toArray()
    {
        if (!key_exists('value', $this->admittedFields['Systems'])) {
            $prototype = $this->admittedFields['Systems']['instanceOf'];
            $this->admittedFields['Systems']['value'] = new $prototype();
        }

        return parent::toArray();
    }
}
