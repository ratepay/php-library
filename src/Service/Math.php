<?php

namespace RatePAY\Service;


/**
 * Class Math static functions that do reusable calculations.
 * @package RatePAY\Service
 */
class Math
{

    /**
     * @param float $netPrice
     * @param float|int $taxPercentage
     * @return float
     */
    public static function netToGross($netPrice, $taxPercentage)
    {
        $withTax = $netPrice + $netPrice * $taxPercentage / 100;

        $rounded = round($withTax, 2);

        return $rounded;
    }

}