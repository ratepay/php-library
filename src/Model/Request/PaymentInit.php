<?php

namespace RatePAY\Model\Request;

class PaymentInit extends AbstractRequest
{

    /**
     * Request rule set
     *
     * @return bool
     */
    public function rule()
    {
        if (key_exists('value', $this->getHead()->admittedFields['TransactionId'])) {
            $this->setErrorMsg("Payment Init does not allow transaction id");
            return false;
        }

        return true;
    }

}
