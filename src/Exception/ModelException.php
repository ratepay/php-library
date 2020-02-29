<?php

/*
 * RatePAY PHP-Library
 *
 * This document contains trade secret data which are the property of
 * RatePAY GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by RatePAY GmbH.
 * All rights reserved by RatePAY GmbH.
 *
 * Copyright (c) 2020 RatePAY GmbH / Berlin / Germany
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
