<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Request\SubModel\Content;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Request\SubModel\Content\ShoppingBasket;
use RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Discount;
use RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Shipping;
use RatePAY\ModelBuilder;

class ShoppingBasketTest extends TestCase
{
    public function testCalculatesAmountIfNotSet()
    {
        $items = new ModelBuilder('Items');
        $items->setArray([
            [
                'Item' => [
                    'Description' => 'Foo',
                    'ArticleNumber' => 'item-foo-1',
                    'Quantity' => 1,
                    'UnitPriceGross' => 7.99,
                    'TaxRate' => 19,
                ],
            ],
            [
                'Item' => [
                    'Description' => 'Bar',
                    'ArticleNumber' => 'item-bar-1',
                    'Quantity' => 2,
                    'UnitPriceGross' => 9.99,
                    'TaxRate' => 19,
                ],
            ],
        ]);

        $basket = new ShoppingBasket();
        $basket->setItems($items->getModel());


        $array = $basket->toArray();

        $this->assertEquals(27.97, $array['attributes']['amount']['value']);
    }

    public function testNoCalculationIfAmountSet()
    {
        $items = new ModelBuilder('Items');
        $items->setArray([
            [
                'Item' => [
                    'Description' => 'Foo',
                    'ArticleNumber' => 'item-foo-1',
                    'Quantity' => 1,
                    'UnitPriceGross' => 27.99,
                    'TaxRate' => 19,
                ],
            ],
        ]);

        $basket = new ShoppingBasket();
        $basket->setAmount(30.99);
        $basket->setItems($items->getModel());


        $array = $basket->toArray();

        $this->assertEquals(30.99, $array['attributes']['amount']['value']);
    }

    public function testHandleItems()
    {
        $items = new ModelBuilder('Items');
        $items->setArray([
            [
                'Item' => [
                    'Description' => 'Foo',
                    'ArticleNumber' => 'item-foo-1',
                    'Quantity' => 1,
                    'UnitPriceGross' => 27.99,
                    'TaxRate' => 19,
                ],
            ],
        ]);

        $basket = new ShoppingBasket();
        $basket->setItems($items->getModel());

        $array = $basket->toArray();

        $expectedItems = [
            "item" => [
                [
                    "cdata" => "Foo",
                    "attributes" => [
                        "article-number" => [
                            "value" => "item-foo-1",
                        ],
                        "quantity" => [
                            "value" => 1,
                        ],
                        "unit-price-gross" => [
                            "value" => 27.99,
                        ],
                        "tax-rate" => [
                            "value" => 19,
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expectedItems, $array['items']);
    }

    public function testHandleShippingItem()
    {
        $items = new ModelBuilder('Items');
        $items->setArray([
            [
                'Item' => [
                    'Description' => 'Foo',
                    'ArticleNumber' => 'item-foo-1',
                    'Quantity' => 1,
                    'UnitPriceGross' => 27.99,
                    'TaxRate' => 19,
                ],
            ],
        ]);

        $shipping = (new Shipping())
            ->setTaxRate(19)
            ->setUnitPriceGross(5.79)
            ->setDescription('Delivery')
            ->setDescriptionAddition('Planet-Express');

        $basket = new ShoppingBasket();
        $basket->setItems($items->getModel());
        $basket->setShipping($shipping);


        $array = $basket->toArray();

        $expectedShipping = [
            "cdata" => "Delivery",
            "attributes" => [
                "unit-price-gross" => [
                    "value" => 5.79,
                ],
                "tax-rate" => [
                    "value" => 19,
                ],
                "description-addition" => [
                    "value" => "Planet-Express",
                ],
            ]
        ];

        $this->assertEquals($expectedShipping, $array['shipping']);
    }

    public function testHandleDiscountItem()
    {
        $items = new ModelBuilder('Items');
        $items->setArray([
            [
                'Item' => [
                    'Description' => 'Foo',
                    'ArticleNumber' => 'item-foo-1',
                    'Quantity' => 1,
                    'UnitPriceGross' => 27.99,
                    'TaxRate' => 19,
                ],
            ],
        ]);

        $discount = (new Discount())
            ->setTaxRate(19)
            ->setUnitPriceGross(-5.79)
            ->setDescription('Trust-Voucher');

        $basket = new ShoppingBasket();
        $basket->setItems($items->getModel());
        $basket->setDiscount($discount);


        $array = $basket->toArray();

        $expectedDiscount = [
            "cdata" => "Trust-Voucher",
            "attributes" => [
                "unit-price-gross" => [
                    "value" => -5.79,
                ],
                "tax-rate" => [
                    "value" => 19,
                ],
            ]
        ];

        $this->assertEquals($expectedDiscount, $array['discount']);
    }
}
