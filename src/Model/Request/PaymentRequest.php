<?php

namespace RatePAY\Model\Request;

class PaymentRequest extends AbstractRequest
{

    use TraitRequestContent;

    /**
     * Request rule set
     *
     * @return bool
     */
    public function rule()
    {
        if (!key_exists('value', $this->getHead()->admittedFields['TransactionId'])) {
            $this->setErrorMsg("Payment Requests expects transaction id");
            return false;
        }

        if (!key_exists('value', $this->getHead()->admittedFields['CustomerDevice'])) {
            $this->setErrorMsg("Payment Requests expects customer device / device token");
            return false;
        }

        return true;
    }

}
