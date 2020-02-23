<?php
/*
 * RatePAY php-library
 *
 * This document contains trade secret data which are the property of
 * RatePAY GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by RatePAY GmbH.
 * All rights reserved by RatePAY GmbH.
 *
 * Copyright (c) 2020 RatePAY GmbH / Berlin / Germany
 */

namespace RatePAY\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use RatePAY\Service\Math;

class MathTest extends TestCase
{
    /** @dataProvider provideNatPricesWithTaxRates */
    public function testNetToGrossCalculation($netPrice, $taxRate, $expectedGrossPrice)
    {
        $grossPrice = Math::netToGross($netPrice, $taxRate);

        $this->assertEquals($expectedGrossPrice, $grossPrice);
    }

    public function provideNatPricesWithTaxRates()
    {
        return [
            [100, 19, 119],
            [100, 0, 100],
            [29.99, 2.5, 30.73975],
        ];
    }

    /** @dataProvider provideRoundedNatPricesWithTaxRates */
    public function testRoundedNetToGrossCalculation($netPrice, $taxRate, $expectedGrossPrice)
    {
        $grossPrice = Math::netToGross($netPrice, $taxRate, true);

        $this->assertEquals($expectedGrossPrice, $grossPrice);
    }

    public function provideRoundedNatPricesWithTaxRates()
    {
        return [
            [100, 19, 119.00],
            [100, 0, 100.00],
            [29.99, 2.5, 30.74],
        ];
    }
}
