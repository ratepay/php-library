<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * Ratepay GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by Ratepay GmbH.
 * All rights reserved by Ratepay GmbH.
 *
 * Copyright (c) 2019 Ratepay GmbH / Berlin / Germany
 */

namespace RatePAY\Model\Response;

class PaymentInit extends AbstractResponse
{
    use TraitTransactionId;

    /**
     * Validates response.
     */
    public function validateResponse()
    {
        if ($this->getStatusCode() == 'OK' && $this->getResultCode() == 350) {
            $this->setTransactionId((string) $this->getResponse()->head->{'transaction-id'});
            $this->setSuccessful();
        }
    }
}
