<?php

namespace RatePAY\Tests\Unit\Model\Request\SubModel;

use PHPUnit\Framework\TestCase;
use RatePAY\ModelBuilder;
use RatePAY\Tests\Mocks\SimpleRequestModel;

class AbstractModelTest extends TestCase
{

    public function testRemovingWhiteSpaceMethod_set()
    {
        $baseString = "       \t\n\r       leading empty\t\r\nspace (%s)       \t\n\r       ";
        $model = new SimpleRequestModel();
        $model->__set('FieldDefault', sprintf($baseString, 'FieldDefault'))
            ->__set('FieldRequired', sprintf($baseString, 'FieldRequired'))
            ->__set('FieldMultiple', sprintf($baseString, 'FieldMultiple 1'))
            ->__set('FieldMultiple', sprintf($baseString, 'FieldMultiple 2'));

        $baseCorrectedString = "leading empty\t\r\nspace (%s)";
        $data = $model->toArray();
        self::assertEquals($data['field-default']['value'], sprintf($baseCorrectedString, 'FieldDefault'));
        self::assertEquals($data['field-required']['value'], sprintf($baseCorrectedString, 'FieldRequired'));
        self::assertEquals($data['field-multiple']['value'][0], sprintf($baseCorrectedString, 'FieldMultiple 1'));
        self::assertEquals($data['field-multiple']['value'][1], sprintf($baseCorrectedString, 'FieldMultiple 2'));
    }

    public function testRemovingWhiteSpaceMethodAnnotationMethods()
    {
        $baseString = "       \t\n\r       leading empty\t\r\nspace (%s)       \t\n\r       ";
        $model = new SimpleRequestModel();
        $model->setFieldDefault(sprintf($baseString, 'FieldDefault'))
            ->setFieldRequired(sprintf($baseString, 'FieldRequired'))
            ->setFieldMultiple(sprintf($baseString, 'FieldMultiple 1'))
            ->addFieldMultiple(sprintf($baseString, 'FieldMultiple 2'));

        $baseCorrectedString = "leading empty\t\r\nspace (%s)";
        $data = $model->toArray();
        self::assertEquals($data['field-default']['value'], sprintf($baseCorrectedString, 'FieldDefault'));
        self::assertEquals($data['field-required']['value'], sprintf($baseCorrectedString, 'FieldRequired'));
        self::assertEquals($data['field-multiple']['value'][0], sprintf($baseCorrectedString, 'FieldMultiple 1'));
        self::assertEquals($data['field-multiple']['value'][1], sprintf($baseCorrectedString, 'FieldMultiple 2'));
    }
}
