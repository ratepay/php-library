<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * RatePAY GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by RatePAY GmbH.
 * All rights reserved by RatePAY GmbH.
 *
 * Copyright (c) 2019 RatePAY GmbH / Berlin / Germany
 */

namespace RatePAY\Model\Request;

class PaymentInit extends AbstractRequest
{
    /**
     * Request rule set.
     *
     * @return bool
     */
    public function rule()
    {
        if (key_exists('value', $this->getHead()->admittedFields['TransactionId'])) {
            $this->setErrorMsg('Payment Init does not allow transaction id');

            return false;
        }

        return true;
    }
}
