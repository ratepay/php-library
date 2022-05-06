<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Exception;

class LanguageException extends ExceptionAbstract
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct('Language exception : ' . $message);
    }
}
