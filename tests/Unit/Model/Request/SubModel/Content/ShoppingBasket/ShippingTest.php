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
