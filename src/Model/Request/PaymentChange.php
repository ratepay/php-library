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

namespace RatePAY\Model\Request;

class PaymentChange extends AbstractRequest
{
    use TraitRequestContent;
    use TraitRequestSubtype;

    /**
     * PaymentChange constructor.
     * Defines subtype as required and sets admitted subtypes.
     */
    public function __construct()
    {
        $this->setSubtypeAsRequired();
        $this->setAdmittedSubtypes(['cancellation', 'change-order', 'return', 'credit']);
    }

    /**
     * Request rule set.
     *
     * @return bool
     */
    public function rule()
    {
        if (!key_exists('value', $this->getHead()->admittedFields['TransactionId'])) {
            $this->setErrorMsg('Payment Change expects transaction id');

            return false;
        }

        return true;
    }
}
