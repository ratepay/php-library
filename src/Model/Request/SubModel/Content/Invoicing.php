<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Request\SubModel\Content;

use DateTime;
use RatePAY\Model\Request\SubModel\AbstractModel;

/**
 * @method self   setInvoiceId(string $invoiceId)
 * @method string getInvoiceId()
 * @method string getInvoiceDate()
 * @method string getDeliveryDate()
 * @method string getDueDate()
 */
class Invoicing extends AbstractModel
{
    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set invoice id value by using setInvoiceId(var). Get invoice id by using getInvoiceId(). (Please consider the camel case).
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
        'InvoiceId' => [
            'mandatory' => false,
        ],
        'InvoiceDate' => [
            'mandatory' => false,
        ],
        'DeliveryDate' => [
            'mandatory' => false,
        ],
        'DueDate' => [
            'mandatory' => false,
        ],
    ];

    /**
     * @param string|DateTime $date
     *
     * @return self
     *
     * @throws \RatePAY\Exception\ModelException
     */
    public function setInvoiceDate($date)
    {
        if ($date instanceof DateTime) {
            $date = $this->createDateString($date);
        }

        return $this->__set('InvoiceDate', $date);
    }

    /**
     * @param string|DateTime $date
     *
     * @return self
     *
     * @throws \RatePAY\Exception\ModelException
     */
    public function setDeliveryDate($date)
    {
        if ($date instanceof DateTime) {
            $date = $this->createDateString($date);
        }

        return $this->__set('DeliveryDate', $date);
    }

    /**
     * @param string|DateTime $date
     *
     * @return self
     *
     * @throws \RatePAY\Exception\ModelException
     */
    public function setDueDate($date)
    {
        if ($date instanceof DateTime) {
            $date = $this->createDateString($date);
        }

        return $this->__set('DueDate', $date);
    }

    private function createDateString(DateTime $dateTime)
    {
        return $dateTime->format('Y-m-d') . 'T' . $dateTime->format('H:i:s');
    }
}
