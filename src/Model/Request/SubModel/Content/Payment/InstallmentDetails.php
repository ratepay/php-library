<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Request\SubModel\Content\Payment;

use RatePAY\Model\Request\SubModel\AbstractModel;

/**
 * @method $this              setInstallmentNumber(string $installmentNumber)
 * @method string             getInstallmentNumber()
 * @method $this              setInstallmentAmount(float $installmentAmount)
 * @method float              getInstallmentAmount()
 * @method $this              setLastInstallmentAmount(InstallmentDetails $lastInstallmentAmount)
 * @method InstallmentDetails getLastInstallmentAmount()
 * @method $this              setInterestRate(string $interestRate)
 * @method string             getInterestRate()
 * @method $this              setPaymentFirstday(string $paymentFirstday)
 * @method string             getPaymentFirstday()
 */
class InstallmentDetails extends AbstractModel
{
    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set payment method value by using setPaymentMethod(var). Get payment method by using getPaymentMethod(). (Please consider the camel case).
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
        'InstallmentNumber' => [
            'mandatory' => true,
        ],
        'InstallmentAmount' => [
            'mandatory' => true,
        ],
        'LastInstallmentAmount' => [
            'mandatory' => true,
        ],
        'InterestRate' => [
            'mandatory' => true,
        ],
        'PaymentFirstday' => [
            'mandatory' => false,
        ],
    ];
}
