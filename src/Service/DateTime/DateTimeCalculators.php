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

namespace RatePAY\Service\DateTime;

trait DateTimeCalculators
{
    /**
     * @param DateTime $dateTime related date time to perform calculation
     *
     * @return int|float difference between dates in seconds
     */
    public function diffInSeconds($dateTime)
    {
        return $this->getTimestamp() - $dateTime->getTimestamp();
    }

    /**
     * @param DateTime $dateTime related date time to perform calculation
     *
     * @return int|float difference between dates in minutes
     */
    public function diffInMinutes($dateTime)
    {
        return $this->diffInSeconds($dateTime) / 60;
    }

    /**
     * @param DateTime $dateTime related date time to perform calculation
     *
     * @return int|float difference between dates in minutes
     */
    public function diffInHours($dateTime)
    {
        return $this->diffInMinutes($dateTime) / 60;
    }

    /**
     * @param DateTime $dateTime related date time to perform calculation
     *
     * @return int|float difference between dates in minutes
     */
    public function diffInDays($dateTime)
    {
        return $this->diffInHours($dateTime) / 24;
    }
}
