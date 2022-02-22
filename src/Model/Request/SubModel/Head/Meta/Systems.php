<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Request\SubModel\Head\Meta;

use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Model\Request\SubModel\Constants as CONSTANTS;
use RatePAY\Model\Request\SubModel\Head\Meta\Systems\System;

/**
 * @method $this  setSystem(System $system)
 * @method System getSystem()
 * @method $this  setApiVersion(string $apiVersion)
 * @method string getApiVersion()
 */
class Systems extends AbstractModel
{
    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set payment profile id by using setProfileId(var). Get profile id by using getProfileId(). (Please consider the camel case).
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
        'System' => [
            'mandatory' => true,
            'instanceOf' => 'Head\\Meta\\Systems\\System',
            'default' => '',
        ],
        'ApiVersion' => [
            'mandatory' => true,
            'default' => CONSTANTS::RATEPAY_API_VERSION,
        ],
    ];

    /**
     * Manipulates the parent method to set instance of systems if not already set.
     *
     * @return array
     *
     * @throws \RatePAY\Exception\ModelException
     * @throws \RatePAY\Exception\RuleSetException
     */
    public function toArray()
    {
        if (!key_exists('value', $this->admittedFields['System'])) {
            $prototype = $this->admittedFields['System']['instanceOf'];
            $this->admittedFields['System']['value'] = new $prototype();
        }

        return parent::toArray();
    }
}
