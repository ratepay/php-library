<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Request\SubModel\Head\External\Tracking;

use RatePAY\Model\Request\SubModel\AbstractModel;

/**
 * @method $this  setProvider(string $provider)
 * @method string getProvider()
 * @method string getDescription()
 * @method $this  setDescription(string $description)
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
     * @param string|null $id
     */
    public function __construct($id = null)
    {
        parent::__construct();
        $id && $this->setId($id);
    }

    /**
     * @param string $id
     *
     * @return Id
     */
    public function setId($id)
    {
        return $this->setDescription($id);
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->getDescription();
    }
}
