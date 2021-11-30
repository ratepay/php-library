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

namespace RatePAY\Tests\Unit\Model\Request\SubModel\Content\ShoppingBasket\Items;

use PHPUnit\Framework\TestCase;
use RatePAY\Exception\RuleSetException;
use RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Items\Item;

class ItemTest extends TestCase
{
    public function testChangesDiscountValueToNegative()
    {
        $item = new Item();
        $item->setQuantity(1);
        $item->setTaxRate(19);
        $item->setDiscount(10);
        $item->setUnitPriceGross(99.5);
        $item->setDescription('Foo');
        $item->setArticleNumber('110');

        $array = $item->toArray();

        $this->assertEquals(-10, $array['attributes']['discount']['value']);
    }

    public function testThrowsErrorIfNoQuantitySet()
    {
        $item = new Item();
        $item->setTaxRate(19);
        $item->setUnitPriceGross(99.5);
        $item->setDescription('Foo');
        $item->setArticleNumber('110');

        $this->expectException(RuleSetException::class);
        $this->expectExceptionMessage('Rule set exception : Quantity must be at least 1');

        $array = $item->toArray();
    }

    public function testThrowsErrorIfNegativeQuantitySet()
    {
        $item = new Item();
        $item->setTaxRate(19);
        $item->setQuantity(-3);
        $item->setUnitPriceGross(99.5);
        $item->setDescription('Foo');
        $item->setArticleNumber('110');

        $this->expectException(RuleSetException::class);
        $this->expectExceptionMessage('Rule set exception : Quantity must be at least 1');

        $array = $item->toArray();
    }

    public function testChangesUnitPriceValueToFloat()
    {
        $item = new Item();
        $item->setQuantity(1);
        $item->setTaxRate(19);
        $item->setUnitPriceGross("99.5");
        $item->setDescription('Foo');
        $item->setArticleNumber('110');

        $array = $item->toArray();

        $this->assertEquals(99.5, $array['attributes']['unit-price-gross']['value']);
    }
}
