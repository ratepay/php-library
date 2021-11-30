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
