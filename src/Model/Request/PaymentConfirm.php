<?php

namespace RatePAY\Model\Request;

class PaymentConfirm extends AbstractRequest
{

    /**
     * Request rule set
     *
     * @return bool
     */
    public function rule()
    {
        if (!key_exists('value', $this->getHead()->admittedFields['TransactionId'])) {
            $this->setErrorMsg("Payment Confirm expects transaction id");
            return false;
        }

        return true;
    }

}
