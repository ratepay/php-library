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

trait TraitTransactionId
{
    /**
     * Transaction Id.
     *
     * @var string
     */
    protected $transactionId = '';

    /**
     * Returns transaction id.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Sets transaction id.
     *
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }
}
