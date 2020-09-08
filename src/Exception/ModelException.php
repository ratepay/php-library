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

namespace RatePAY\Exception;

class ModelException extends ExceptionAbstract
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct('Model exception : ' . $message);
    }
}
