<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Service;

/**
 * Class Math static functions that do reusable calculations.
 */
class Math
{
    public static function isZero($number, $precission = 2)
    {
        return round($number, $precission) === 0.0;
    }

    /**
     * @param float     $netPrice
     * @param float|int $taxPercentage
     * @param bool      $round
     *
     * @return float
     */
    public static function netToGross($netPrice, $taxPercentage, $round = false)
    {
        $withTax = $netPrice * (1 + $taxPercentage / 100);

        if (!$round) {
            return floatval(number_format($withTax, 5));
        }

        $rounded = round($withTax, 2);

        return $rounded;
    }

    /**
     * @param float $annualInterestRate annual percentage interest rate
     * @param float $interval           Fraction of a year
     *
     * @return float|int
     */
    public static function interestByInterval($annualInterestRate, $interval)
    {
        return pow((1 + ($annualInterestRate / 100)), $interval) - 1;
    }
}
