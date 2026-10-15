<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Request\SubModel\Content;

use PHPUnit\Framework\TestCase;
use RatePAY\Exception\ModelException;
use RatePAY\Model\Request\SubModel\Content\Secci;
use RatePAY\ModelBuilder;

class SecciTest extends TestCase
{
    public function testToArrayWithMandatoryFields()
    {
        $secci = (new Secci())
            ->setDeliveryMethod('EMAIL')
            ->setEmail('test@example.com');

        $array = $secci->toArray();

        $expected = [
            'delivery-method' => ['value' => 'EMAIL'],
            'email' => ['value' => 'test@example.com'],
        ];

        $this->assertEquals($expected, $array);
    }

    public function testToArrayWithAllFields()
    {
        $secci = (new Secci())
            ->setDeliveryMethod('EMAIL')
            ->setEmail('test@example.com')
            ->setAction('REQUEST')
            ->setLanguage('DE')
            ->setCountryCode('DE');

        $array = $secci->toArray();

        $expected = [
            'delivery-method' => ['value' => 'EMAIL'],
            'email' => ['value' => 'test@example.com'],
            'action' => ['value' => 'REQUEST'],
            'language' => ['value' => 'DE'],
            'country-code' => ['value' => 'DE'],
        ];

        $this->assertEquals($expected, $array);
    }

    public function testSetterReturnsModel()
    {
        $secci = new Secci();

        $this->assertSame($secci, $secci->setDeliveryMethod('EMAIL'));
    }

    public function testGetters()
    {
        $secci = (new Secci())
            ->setDeliveryMethod('EMAIL')
            ->setEmail('test@example.com')
            ->setAction('REQUEST')
            ->setLanguage('DE')
            ->setCountryCode('DE');

        $this->assertEquals('EMAIL', $secci->getDeliveryMethod());
        $this->assertEquals('test@example.com', $secci->getEmail());
        $this->assertEquals('REQUEST', $secci->getAction());
        $this->assertEquals('DE', $secci->getLanguage());
        $this->assertEquals('DE', $secci->getCountryCode());
    }

    public function testGetterReturnsNullIfNotSet()
    {
        $secci = new Secci();

        $this->assertNull($secci->getEmail());
    }

    public function testThrowErrorIfNoDeliveryMethod()
    {
        $secci = new Secci();

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage('Model exception : Field \'DeliveryMethod\' is required');

        $secci->toArray();
    }

    public function testBuildByModelBuilder()
    {
        $builder = new ModelBuilder('Secci');
        $builder->setArray([
            'DeliveryMethod' => 'EMAIL',
            'Email' => 'test@example.com',
        ]);

        $secci = $builder->getModel();

        $this->assertInstanceOf(Secci::class, $secci);

        $expected = [
            'delivery-method' => ['value' => 'EMAIL'],
            'email' => ['value' => 'test@example.com'],
        ];

        $this->assertEquals($expected, $secci->toArray());
    }
}
