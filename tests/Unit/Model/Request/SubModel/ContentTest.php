<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Request\SubModel;

use PHPUnit\Framework\TestCase;
use RatePAY\Exception\ModelException;
use RatePAY\Model\Request\SubModel\Content;
use RatePAY\Model\Request\SubModel\Content\Invoicing;
use RatePAY\Model\Request\SubModel\Content\Secci;
use RatePAY\ModelBuilder;

class ContentTest extends TestCase
{
    public function testEmptyContent()
    {
        $content = new Content();

        $this->assertEquals([], $content->toArray());
    }

    public function testHandleSecci()
    {
        $secci = (new Secci())
            ->setDeliveryMethod('EMAIL')
            ->setEmail('test@example.com');

        $content = new Content();
        $content->setSecci($secci);

        $array = $content->toArray();

        $expectedSecci = [
            'delivery-method' => ['value' => 'EMAIL'],
            'email' => ['value' => 'test@example.com'],
        ];

        $this->assertEquals($expectedSecci, $array['secci']);
    }

    public function testGetSecci()
    {
        $secci = (new Secci())
            ->setDeliveryMethod('EMAIL');

        $content = new Content();
        $content->setSecci($secci);

        $this->assertSame($secci, $content->getSecci());
    }

    public function testSecciIsOptional()
    {
        $content = new Content();

        $this->assertNull($content->getSecci());
        $this->assertArrayNotHasKey('secci', $content->toArray());
    }

    public function testThrowErrorOnWrongSecciInstance()
    {
        $content = new Content();

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage('Model exception : Wrong class instance set to \'Secci\'');

        $content->setSecci(new Invoicing());
    }

    public function testHandleSecciByModelBuilder()
    {
        $builder = new ModelBuilder('Content');
        $builder->setArray([
            'Secci' => [
                'DeliveryMethod' => 'EMAIL',
                'Email' => 'test@example.com',
            ],
        ]);

        $content = $builder->getModel();

        $this->assertInstanceOf(Secci::class, $content->getSecci());

        $expectedSecci = [
            'delivery-method' => ['value' => 'EMAIL'],
            'email' => ['value' => 'test@example.com'],
        ];

        $this->assertEquals($expectedSecci, $content->toArray()['secci']);
    }
}
