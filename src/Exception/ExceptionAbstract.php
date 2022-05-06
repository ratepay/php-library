<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Exception;

abstract class ExceptionAbstract extends \Exception
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        //return __CLASS__ . ":" . $this->message;
        return $this->message;
    }
}
