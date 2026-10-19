<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Request\SubModel\Content;

use RatePAY\Model\Request\SubModel\AbstractModel;

/**
 * @method string getDeliveryMethod()
 * @method string getEmail()
 * @method string getAction()
 * @method string getLanguage()
 * @method string getCountryCode()
 */
class Secci extends AbstractModel
{
    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set delivery method value by using setDeliveryMethod(var). Get delivery method by using getDeliveryMethod(). (Please consider the camel case).
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
     * uppercase            = change value to uppercase
     *
     * @var array
     */
    public $admittedFields = [
        'DeliveryMethod' => [
            'mandatory' => true,
            'uppercase' => true,
        ],
        'Email' => [
            'mandatoryByRule' => true,
        ],
        'Action' => [
            'mandatoryByRule' => true,
            'uppercase' => true,
        ],
        'Language' => [
            'mandatory' => false,
            'uppercase' => true,
        ],
        'CountryCode' => [
            'mandatory' => false,
            'uppercase' => true,
        ],
    ];

    /**
     * Secci rule : if DeliveryMethod is EMAIL, Email parameter must be given. if DeliveryMethod is PDF, Action parameter must be either PRINT or DOWNLOAD.
     *
     * @return bool
     */
    protected function rule()
    {
        if ('EMAIL' == $this->admittedFields['DeliveryMethod']['value'] &&
            (!key_exists('value', $this->admittedFields['Email']))
        ) {
            $this->setErrorMsg('email details missing');

            return false;
        }

        if ('PDF' == $this->admittedFields['DeliveryMethod']['value'] &&
            (!key_exists('value', $this->admittedFields['Action']) || !in_array(strtoupper($this->admittedFields['Action']['value']), ['PRINT', 'DOWNLOAD']))
        ) {
            $this->setErrorMsg('action details missing or incorrect');

            return false;
        }

        return true;
    }

    /**
     * @param string $deliveryMethod
     *
     * @return self
     *
     * @throws \RatePAY\Exception\ModelException
     */
    public function setDeliveryMethod($deliveryMethod)
    {
        return $this->__set('DeliveryMethod', $deliveryMethod);
    }

    /**
     * @param string $email
     *
     * @return self
     *
     * @throws \RatePAY\Exception\ModelException
     */
    public function setEmail($email)
    {
        return $this->__set('Email', $email);
    }

    /**
     * @param string $action
     *
     * @return self
     *
     * @throws \RatePAY\Exception\ModelException
     */
    public function setAction($action)
    {
        return $this->__set('Action', $action);
    }

    /**
     * @param string $language
     *
     * @return self
     *
     * @throws \RatePAY\Exception\ModelException
     */
    public function setLanguage($language)
    {
        return $this->__set('Language', $language);
    }

    /**
     * @param string $countryCode
     *
     * @return self
     *
     * @throws \RatePAY\Exception\ModelException
     */
    public function setCountryCode($countryCode)
    {
        return $this->__set('CountryCode', $countryCode);
    }
}
