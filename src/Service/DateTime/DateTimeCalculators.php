<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
