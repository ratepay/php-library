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

use RatePAY\Exception\RuleSetException;
use RatePAY\Model\Request\SubModel\Head;

abstract class AbstractRequest
{
    /**
     * Head model.
     *
     * @var Head
     */
    private $head;

    /**
     * Subtype needed.
     *
     * @var bool
     */
    protected $subtypeRequired = false;

    /**
     * Error message.
     *
     * @var string
     */
    private $errorMsg = '';

    /**
     * Returns the value of $head.
     *
     * @return Head
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * Sets the value for $head.
     */
    public function setHead(Head $head)
    {
        $this->head = $head;
    }

    /**
     * Returns all values as Array.
     *
     * @return array
     */
    public function toArray()
    {
        // Checks whether request rule is passed
        if ($this->rule() !== true) {
            throw new RuleSetException($this->getErrorMsg());
        }

        return [
            'head' => $this->getHead()->toArray(),
        ];
    }

    abstract public function rule();

    /**
     * Returns whether subtype is needed.
     *
     * @return bool
     */
    public function isSubtypeRequired()
    {
        return $this->subtypeRequired;
    }

    /**
     * Returns error message.
     *
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * Sets error message.
     *
     * @param string
     */
    public function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;
    }
}
