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

namespace RatePAY\Model\Request\SubModel\Head\External\Tracking;

use RatePAY\Model\Request\SubModel\AbstractModel;

/**
 * @method string getId()
 * @method $this  setProvider(string $provider)
 * @method string getProvider()
 */
class Id extends AbstractModel
{
    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set id by using setId(var). Get id by using getId(). (Please consider the camel case).
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
        'Description' => [ // This field will be converted to the inner xml data. this will be identified by the field name "description"
            'mandatory' => false,
            'cdata' => true,
        ],
        'Provider' => [
            'mandatory' => false,
            'isAttribute' => true,
        ],
    ];

    /**
     * @param string $id
     *
     * @return Id
     */
    public function setId($id)
    {
        return $this->__set('Description', $id);
    }
}
