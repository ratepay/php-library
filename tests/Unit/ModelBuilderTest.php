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
use RatePAY\Tests\Mocks\SimpleRequestModel;

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

    public function testRemovingWhiteSpaceMethodModelBuilderSetter()
    {
        $baseString = "       \t\n\r       leading empty\t\r\nspace (%s)       \t\n\r       ";
        $builder = $this->getModelBuilderWithCustomModel(new SimpleRequestModel());
        $builder->setFieldDefault(sprintf($baseString, 'FieldDefault'))
            ->setFieldRequired(sprintf($baseString, 'FieldRequired'))
            ->setFieldMultiple(sprintf($baseString, 'FieldMultiple 1'))
            ->setFieldMultiple(sprintf($baseString, 'FieldMultiple 2'));

        $baseCorrectedString = "leading empty\t\r\nspace (%s)";
        $data = $builder->getModel()->toArray();
        self::assertEquals($data['field-default']['value'], sprintf($baseCorrectedString, 'FieldDefault'));
        self::assertEquals($data['field-required']['value'], sprintf($baseCorrectedString, 'FieldRequired'));
        self::assertEquals($data['field-multiple']['value'][0], sprintf($baseCorrectedString, 'FieldMultiple 1'));
        self::assertEquals($data['field-multiple']['value'][1], sprintf($baseCorrectedString, 'FieldMultiple 2'));
    }

    public function testRemovingWhiteSpaceMethodModelBuilderArray()
    {
        $baseString = "       \t\n\r       leading empty\t\r\nspace (%s)       \t\n\r       ";
        $builder = $this->getModelBuilderWithCustomModel(new SimpleRequestModel());
        $builder->setArray([
            'FieldDefault' => sprintf($baseString, 'FieldDefault'),
            'FieldRequired' => sprintf($baseString, 'FieldRequired'),
            'FieldMultiple' => sprintf($baseString, 'FieldMultiple 1')
        ]);

        $baseCorrectedString = "leading empty\t\r\nspace (%s)";
        $data = $builder->getModel()->toArray();
        self::assertEquals($data['field-default']['value'], sprintf($baseCorrectedString, 'FieldDefault'));
        self::assertEquals($data['field-required']['value'], sprintf($baseCorrectedString, 'FieldRequired'));
        self::assertEquals($data['field-multiple']['value'][0], sprintf($baseCorrectedString, 'FieldMultiple 1'));
    }

    private function getModelBuilderWithCustomModel($model)
    {
        $builder = new ModelBuilder();
        $refClass = new \ReflectionClass($builder);
        $refProp = $refClass->getProperty('model');
        $refProp->setAccessible(true);
        $refProp->setValue($builder, $model);

        return $builder;
    }
}
