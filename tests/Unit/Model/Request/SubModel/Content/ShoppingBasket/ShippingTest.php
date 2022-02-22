<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Request\SubModel\Content\ShoppingBasket;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Shipping;

class ShippingTest extends TestCase
{
    public function testUsesAutoDelivery()
    {
        $item = new Shipping();
        $item->setTaxRate(19);
        $item->setUnitPriceGross(5.99);
        $item->setDescription('Foo');
        $item->setDescriptionAddition('The Foo');

        $this->assertFalse($item->getAutoDelivery());

        $item->setAutoDelivery(true);

        $this->assertTrue($item->getAutoDelivery());
    }

    public function testToArray()
    {
        $item = new Shipping();
        $item->setTaxRate(19);
        $item->setUnitPriceGross(5.99);
        $item->setDescription('Foo');
        $item->setDescriptionAddition('The Foo');

        $array = $item->toArray();

        $expectedArray = [
            "description" => [
                "cdata" => "Foo",
            ],
            "attributes" => [
                "unit-price-gross" => [
                    "value" => 5.99
                ],
                "tax-rate" => [
                    "value" => 19
                ],
                "description-addition" => [
                    "value" => "The Foo"
                ],
            ],
        ];

        $this->assertEquals(print_r($expectedArray, true), print_r($array, true));
    }
}
