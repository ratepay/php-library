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

class PaymentQuery extends AbstractRequest
{
    use TraitRequestContent;
    use TraitRequestSubtype;

    /**
     * PaymentQuery constructor.
     * Defines subtype as required and sets admitted subtypes.
     */
    public function __construct()
    {
        $this->setSubtypeAsRequired();
        $this->setAdmittedSubtypes(['full']);

        // ToDo: Create rule which sets 'full' automatically
    }

    /**
     * Request rule set.
     *
     * @return bool
     */
    public function rule()
    {
        if (!key_exists('value', $this->getHead()->admittedFields['TransactionId'])) {
            $this->setErrorMsg('Payment Query expects transaction id');

            return false;
        }

        return true;
    }
}
