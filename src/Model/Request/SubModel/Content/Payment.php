<?php

namespace RatePAY\Model\Request\SubModel\Content;

use RatePAY\Model\Request\SubModel\AbstractModel;

class Payment extends AbstractModel
{

    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set payment method value by using setPaymentMethod(var). Get payment method by using getPaymentMethod(). (Please consider the camel case)
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
        'Method' => [
            'mandatoryByRule' => true,
            'isAttribute' => true,
            'uppercase' => true
        ],
        'Amount' => [
            'mandatory' => true
        ],
        'InstallmentDetails' => [
            'mandatoryByRule' => true,
            'instanceOf' => "Content\\Payment\\InstallmentDetails"
        ],
        'DebitPayType' => [
            'mandatoryByRule' => true
        ]
    ];

    /*
     *  All available RatePAY payment methods
     *  @ToDo: find better place to save (but stay compatible with PHP 5.4 (now array within constant))
     */
    private $ratepayPaymentMethods = [
        "INVOICE",
        "INSTALLMENT",
        "ELV",
        "PREPAYMENT"
    ];

    /**
     * Installment details rule : if payment method is installment, InstallmentDetails are mandatory
     *
     * @return bool
     */
    protected function rule()
    {

        if (!in_array(strtoupper($this->admittedFields['Method']['value']), $this->ratepayPaymentMethods)) {
            $this->setErrorMsg("Payment method invalid");
            return false;
        }

        if ('INSTALLMENT' == $this->admittedFields['Method']['value'] &&
            (!key_exists('value', $this->admittedFields['InstallmentDetails']) || !key_exists('value', $this->admittedFields['DebitPayType']))
        ) {
            $this->setErrorMsg("installment details missing");
            return false;
        }

        return true;
    }

}
