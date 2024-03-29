<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Request\SubModel\Head;

use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Model\Request\SubModel\Head\External\Tracking;

/**
 * @method $this    setOrderId(string $orderId)
 * @method string   getOrderId()
 * @method $this    setMerchantConsumerId(string $merchantConsumerId)
 * @method string   getMerchantConsumerId()
 * @method $this    setMerchantConsumerClassification(string $merchantConsumerClassification)
 * @method string   getMerchantConsumerClassification()
 * @method $this    setTracking(Tracking $tracking)
 * @method Tracking getTracking()
 * @method $this    setShopLanguage(string $shopLanguage)
 * @method string   getShopLanguage()
 * @method $this    setReferenceId(string $referenceId)
 * @method string   getReferenceId()
 */
class External extends AbstractModel
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
        'OrderId' => [
            'mandatory' => false,
            'cdata' => true,
        ],
        'MerchantConsumerId' => [
            'mandatory' => false,
            'cdata' => true,
        ],
        'MerchantConsumerClassification' => [
            'mandatory' => false,
        ],
        'Tracking' => [
            'mandatory' => false,
            'instanceOf' => 'Head\\External\\Tracking',
        ],
        'ShopLanguage' => [
            'mandatory' => false,
        ],
        'ReferenceId' => [
            'mandatory' => false,
        ],
    ];
}
