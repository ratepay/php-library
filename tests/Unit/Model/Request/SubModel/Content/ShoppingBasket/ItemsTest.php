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
use RatePAY\Exception\RuleSetException;
use RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Items;
use RatePAY\ModelBuilder;

class ItemsTest extends TestCase
{
    public function testRuleSucceedIfNoValuesSet()
    {
        $items = new Items();

        $array = $items->toArray();

        $this->assertEquals([], $array);
    }

    public function testRuleSucceedIfUniqueArticleNumbersUsed()
    {
        $item = new ModelBuilder('Item');
        $item->setArray([
            'Description' => 'Foo',
            'ArticleNumber' => 'item-foo-1',
            'Quantity' => 1,
            'UnitPriceGross' => 7.99,
            'TaxRate' => 19,
        ]);

        $items = new Items();
        $items->setItem($item->getModel());

        $expectedArray = [
            'item' => [
                [
                    'cdata' => 'Foo',
                    "attributes" => [
                        "article-number" => [
                            "value" => "item-foo-1"
                        ],
                        "quantity" => [
                            "value" => 1
                        ],
                        "unit-price-gross" => [
                            "value" => 7.99
                        ],
                        "tax-rate" => [
                            "value" => 19
                        ],
                    ],
                ]
            ],
        ];

        $array = $items->toArray();

        $this->assertEquals(print_r($expectedArray, true), print_r($array, true));
    }

    public function testRuleFailedIfDuplicatedArticleNumbersUsed()
    {
        $item1 = new ModelBuilder('Item');
        $item1->setArray([
            'Description' => 'Foo',
            'ArticleNumber' => 'item-foo-1',
            'Quantity' => 1,
            'UnitPriceGross' => 7.99,
            'TaxRate' => 19,
        ]);

        $item2 = new ModelBuilder('Item');
        $item2->setArray([
            'Description' => 'Bar',
            'ArticleNumber' => 'item-foo-1',
            'Quantity' => 2,
            'UnitPriceGross' => 9.99,
            'TaxRate' => 19,
        ]);

        $items = new Items();
        $items->setItem($item1->getModel());
        $items->setItem($item2->getModel());

        $this->expectException(RuleSetException::class);
        $this->expectExceptionMessage('Rule set exception : Identical article numbers on different items are not allowed. Please specify with UniqueArticleNumber.');

        $array = $items->toArray();
    }

    public function testRuleSucceedWithUniqueIdentifierOnDuplicatedArticleNumbers()
    {
        $item1 = new ModelBuilder('Item');
        $item1->setArray([
            'Description' => 'Foo',
            'ArticleNumber' => 'item-foo-1',
            'UniqueArticleNumber' => '1001',
            'Quantity' => 1,
            'UnitPriceGross' => 7.99,
            'TaxRate' => 19,
        ]);

        $item2 = new ModelBuilder('Item');
        $item2->setArray([
            'Description' => 'Bar',
            'ArticleNumber' => 'item-foo-1',
            'UniqueArticleNumber' => '1002',
            'Quantity' => 2,
            'UnitPriceGross' => 9.99,
            'TaxRate' => 19,
        ]);

        $items = new Items();
        $items->setItem($item1->getModel());
        $items->setItem($item2->getModel());

        $expectedArray = [
            'item' => [
                [
                    'cdata' => 'Foo',
                    "attributes" => [
                        "article-number" => [
                            "value" => "item-foo-1"
                        ],
                        "unique-article-number" => [
                            "value" => "1001"
                        ],
                        "quantity" => [
                            "value" => 1
                        ],
                        "unit-price-gross" => [
                            "value" => 7.99
                        ],
                        "tax-rate" => [
                            "value" => 19
                        ],
                    ],
                ],
                [
                    'cdata' => 'Bar',
                    "attributes" => [
                        "article-number" => [
                            "value" => "item-foo-1"
                        ],
                        "unique-article-number" => [
                            "value" => "1002"
                        ],
                        "quantity" => [
                            "value" => 2
                        ],
                        "unit-price-gross" => [
                            "value" => 9.99
                        ],
                        "tax-rate" => [
                            "value" => 19
                        ],
                    ],
                ],
            ],
        ];

        $array = $items->toArray();

        $this->assertEquals(print_r($expectedArray, true), print_r($array, true));
    }
}
