<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Request\SubModel\Content\Customer;

use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Model\Request\SubModel\Content\Customer\Addresses\Address;

/**
 * @method $this addAddress(Address $address)
 */
class Addresses extends AbstractModel
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
        'Address' => [
            'mandatory' => true,
            'instanceOf' => 'Content\\Customer\\Addresses\\Address',
            'multiple' => true,
        ],
    ];

    /**
     * @return Address[]
     */
    public function getAddresses()
    {
        return $this->__get('Address');
    }

    /**
     * @return Address
     */
    public function getAddress($type)
    {
        foreach ($this->getAddresses() as $address) {
            if (strtolower($address->getType()) === strtolower($type)) {
                return $address;
            }
        }

        return null;
    }
}
