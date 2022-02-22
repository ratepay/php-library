<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Items;
use RatePAY\Model\Request\SubModel\Head;
use RatePAY\Model\Request\SubModel\Head\Meta;
use RatePAY\ModelBuilder;

class ModelBuilderTest extends TestCase
{
    public function testBuildingHead()
    {
        $builder = new ModelBuilder();
        $builder->setSystemId('HELLO_WORLD');

        /** @var Head $head */
        $head = $builder->getModel();

        $this->assertEquals('HELLO_WORLD', $head->getSystemId());
    }

    public function testBuildModelOnDemand()
    {
        $builder = new ModelBuilder();

        $builder = $builder->meta();
        /** @var Meta $meta */
        $meta = $builder->getModel();

        $this->assertInstanceOf('RatePAY\Model\Request\SubModel\Head\Meta', $meta);
    }

    public function testExceptionThrownOnUnkownField()
    {
        $builder = new ModelBuilder();

        $this->expectException('RatePAY\Exception\ModelException');
        $this->expectExceptionMessage("Field 'FooBar' invalid");

        $builder->setFooBar('HELLO_WORLD');
    }

    public function testGetValueFromCommonGetter()
    {
        $builder = new ModelBuilder();
        $builder->setSystemId('HELLO_WORLD');

        $value = $builder->getSystemId();

        $this->assertEquals('HELLO_WORLD', $value);
    }

    public function testSetArrayToModelBuilder()
    {
        $builder = new ModelBuilder('Items');

        $builder->setArray([
            [
                'Item' => [
                    'Description' => 'Foo',
                    'ArticleNumber' => 'item_0001',
                    'Quantity' => 2,
                    'UnitPriceGross' => 21,
                    'TaxRate' => 19,
                ],
            ],
            [
                'Item' => [
                    'Description' => 'Bar',
                    'ArticleNumber' => 'item_0002',
                    'Quantity' => 1,
                    'UnitPriceGross' => 13,
                    'TaxRate' => 19,
                ],
            ],
        ]);

        /** @var Items $items */
        $items = $builder->getModel();

        $this->assertInstanceOf('RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Items', $items);
        // TODO: check item values...
    }

    public function testSetJsonToModelBuilder()
    {
        $builder = new ModelBuilder('Items');

        $builder->setJson(
            '[{"Item":{"Description":"Foo","ArticleNumber":"item_0001","Quantity":2,"UnitPriceGross":21,"TaxRate":19}},{"Item":{"Description":"Bar","ArticleNumber":"item_0002","Quantity":1,"UnitPriceGross":13,"TaxRate":19}}]'
        );

        /** @var Items $items */
        $items = $builder->getModel();

        $this->assertInstanceOf('RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Items', $items);
        // TODO: check item values...
    }
}
