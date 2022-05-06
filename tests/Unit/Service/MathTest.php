<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

    /** @dataProvider provideZeroValues */
    public function testIsZero($number, $precission, $expectation)
    {
        $isZero = Math::isZero($number, $precission);

        $this->assertEquals($expectation, $isZero);
    }

    public function provideZeroValues()
    {
        return [
            [ -0.000001, 0, true ],
            [ -0.000001, 1, true ],
            [ -0.000001, 2, true ],
            [ -0.000001, 3, true ],
            [ -0.000001, 4, true ],
            [ -0.000001, 5, true ],
            [ -0.000001, 6, false ],
            [ -0.3 + 0.09 + 0.2, 0, true ],
            [ -0.3 + 0.09 + 0.2, 1, true ],
            [ -0.3 + 0.09 + 0.2, 2, false ],
        ];
    }
}
