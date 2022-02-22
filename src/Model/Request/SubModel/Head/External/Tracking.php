<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Request\SubModel\Head\External;

use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Model\Request\SubModel\Head\External\Tracking\Id;

/**
 * @method Tracking addId(Id $id)
 */
class Tracking extends AbstractModel
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
        'Id' => [
            'mandatory' => true,
            'multiple' => true,
            'instanceOf' => 'Head\\External\\Tracking\\Id',
        ],
        'Provider' => [
            'mandatory' => false,
        ],
    ];

    /**
     * @return Id[]
     */
    public function getIds()
    {
        return $this->__get('Id');
    }

    /**
     * {@inheritDoc}
     */
    public function commonSetter($field, $arguments)
    {
        if ($field === 'Id' && $arguments[0] instanceof Tracking\Id === false) {
            return $this->setId($arguments[0]);
        }
        if ($field === 'Provider') {
            return $this->setProvider($arguments[0]);
        }

        return parent::commonSetter($field, $arguments);
    }

    /**
     * @param $id
     *
     * @return Tracking
     *
     * @deprecated please use the SubModel `\RatePAY\Model\Request\SubModel\Head\External\Tracking\Id` and add it with the method `addId`
     */
    public function setId($id)
    {
        $this->addId((new Id())->setId($id));

        return $this;
    }

    /**
     * @param $provider
     *
     * @return Tracking
     *
     * @deprecated please use the SubModel `\RatePAY\Model\Request\SubModel\Head\External\Tracking\Id` and add it with the method `addId`
     */
    public function setProvider($provider)
    {
        $ids = $this->getIds();
        if (!isset($ids[0]) || $ids[0] === null || count($ids) === 0) {
            throw new \RuntimeException('please set the Id first');
        }
        $id = $ids[count($ids) - 1];
        $id->setProvider($provider);

        return $this;
    }

    /**
     * @return string|null
     *
     * @deprecated please use the function `getIds()` to get the tracking-ids
     */
    public function getId()
    {
        $ids = $this->getIds();

        return isset($ids[0]) ? $ids[0]->getId() : null;
    }

    /**
     * @return string|null
     *
     * @deprecated please use the function `getIds()` to get the provider information
     */
    public function getProvider()
    {
        $ids = $this->getIds();

        return isset($ids[0]) ? $ids[0]->getProvider() : null;
    }
}
