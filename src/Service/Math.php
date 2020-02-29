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

namespace RatePAY\Service;

/**
 * Class Math static functions that do reusable calculations.
 */
class Math
{
    /**
     * @param float     $netPrice
     * @param float|int $taxPercentage
     * @param bool      $round
     *
     * @return float
     */
    public static function netToGross($netPrice, $taxPercentage, $round = false)
    {
        $withTax = $netPrice + $netPrice * $taxPercentage / 100;

        if (!$round) {
            return $withTax;
        }

        $rounded = round($withTax, 2);

        return $rounded;
    }
}
