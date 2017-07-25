<?php

namespace RatePAY\Model\Request\SubModel\Content\Customer;

use RatePAY\Model\Request\SubModel\AbstractModel;

class Addresses extends AbstractModel
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
        'Address' => [
            'mandatory' => true,
            'instanceOf' => "Content\\Customer\\Addresses\\Address",
            'multiple' => true
        ]
    ];

}
