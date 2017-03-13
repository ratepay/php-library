<?php

namespace RatePAY\Model\Request;

class PaymentChange extends AbstractRequest
{

    use TraitRequestContent;
    use TraitRequestSubtype;

    /**
     * PaymentChange constructor.
     * Defines subtype as required and sets admitted subtypes
     */
    public function __construct()
    {
        $this->setSubtypeAsRequired();
        $this->setAdmittedSubtypes(['cancellation', 'change-order', 'return', 'credit']);
    }

    /**
     * Request rule set
     *
     * @return bool
     */
    public function rule()
    {
        if (!key_exists('value', $this->getHead()->admittedFields['TransactionId'])) {
            $this->setErrorMsg("Payment Change expects transaction id");
            return false;
        }

        return true;
    }

}
