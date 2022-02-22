<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Request;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Model\Request\SubModel\Content;
use RatePAY\Model\Request\TraitRequestContent;
use RatePAY\ModelBuilder;

class MockForTrait extends AbstractModel
{
    use TraitRequestContent;
}

class TraitRequestContentTest extends TestCase
{
    public function testArrayConversion()
    {
        $content = new Content;

        $mock = new MockForTrait;
        $mock->setContent($content);

        $data = $mock->toArray();

        $this->assertTypeIsArray($data);
        $this->assertArrayHasKey('content', $data);

        $value = $data['content'];

        $this->assertTypeIsArray($value);
        $this->assertEquals([], $value);
    }

    public function testArrayConversionWithValues()
    {
        $builder = new ModelBuilder('Content');
        $builder->setArray([
            'Payment' => [
                'Method' => 'INVOICE',
                'Amount' => 123.45
            ],
        ]);

        $content = $builder->getModel();

        $mock = new MockForTrait;
        $mock->setContent($content);

        $data = $mock->toArray();

        $this->assertTypeIsArray($data);
        $this->assertArrayHasKey('content', $data);

        $value = $data['content'];
        $this->assertTypeIsArray($value);

        $expected = print_r([
            'payment' => [
                'attributes' => [
                    'method' => ['value' => 'INVOICE'],
                ],
                'amount' => ['value' => 123.45],
            ],
        ], true);
        $current = print_r($value, true);
        $this->assertEquals($expected, $current);
    }

    public function assertTypeIsArray($data)
    {
        if (method_exists($this, 'assertIsArray')) {
            $this->assertIsArray($data);
        } else {
            $this->assertInternalType('array', $data);
        }
    }
}
