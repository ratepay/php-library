<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Request\SubModel\Content\ShoppingBasket;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Discount;

class DiscountTest extends TestCase
{
    public function testChangesDiscountValueToNegative()
    {
        $item = new Discount();
        $item->setTaxRate(19);
        $item->setUnitPriceGross(5.99);
        $item->setDescription('Foo');
        $item->setDescriptionAddition('The Foo');

        $array = $item->toArray();

        $this->assertEquals(-5.99, $array['attributes']['unit-price-gross']['value']);
    }

    public function testUsesAutoDelivery()
    {
        $item = new Discount();
        $item->setTaxRate(19);
        $item->setUnitPriceGross(-5.99);
        $item->setDescription('Foo');
        $item->setDescriptionAddition('The Foo');

        $this->assertFalse($item->getAutoDelivery());

        $item->setAutoDelivery(true);

        $this->assertTrue($item->getAutoDelivery());
    }
}
