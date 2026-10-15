<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Request;

use PHPUnit\Framework\TestCase;
use RatePAY\Exception\RuleSetException;
use RatePAY\Model\Request\SecciRequest;
use RatePAY\ModelBuilder;

class SecciRequestTest extends TestCase
{
    public function testRuleSucceed()
    {
        $head = new ModelBuilder();

        $request = new SecciRequest();
        $request->setHead($head->getModel());

        $this->assertTrue($request->rule());
    }

    public function testRuleFailed()
    {
        $head = new ModelBuilder();
        $head->setTransactionId('foo-1234567890');

        $request = new SecciRequest();
        $request->setHead($head->getModel());

        $this->assertFalse($request->rule());
        $this->assertEquals('Secci Request does not allow transaction id', $request->getErrorMsg());
    }

    public function testArrayConversion()
    {
        $head = new ModelBuilder();
        $head->setArray([
            'SystemId' => 'SuperSystem',
            'Operation' => 'SECCI_REQUEST',
            'Credential' => [
                'ProfileId' => 'MY_AMAZING_PROFILE',
                'Securitycode' => 'super-secure-code',
            ],
        ]);

        $content = new ModelBuilder('Content');
        $content->setArray([
            'Secci' => [
                'DeliveryMethod' => 'EMAIL',
                'Email' => 'test@example.com',
            ],
        ]);

        $request = new SecciRequest();
        $request->setHead($head->getModel());
        $request->setContent($content->getModel());

        $data = $request->toArray();

        $this->assertTypeIsArray($data);
        $this->assertArrayHasKey('head', $data);
        $this->assertArrayHasKey('content', $data);

        $expected = print_r([
            'secci' => [
                'delivery-method' => ['value' => 'EMAIL'],
                'email' => ['value' => 'test@example.com'],
            ],
        ], true);
        $current = print_r($data['content'], true);
        $this->assertEquals($expected, $current);
    }

    public function testArrayConversionFailsOnTransactionId()
    {
        $head = new ModelBuilder();
        $head->setArray([
            'SystemId' => 'SuperSystem',
            'Operation' => 'SECCI_REQUEST',
            'TransactionId' => 'foo-1234567890',
            'Credential' => [
                'ProfileId' => 'MY_AMAZING_PROFILE',
                'Securitycode' => 'super-secure-code',
            ],
        ]);

        $content = new ModelBuilder('Content');
        $content->setArray([
            'Secci' => [
                'DeliveryMethod' => 'EMAIL',
                'Email' => 'test@example.com',
            ],
        ]);

        $request = new SecciRequest();
        $request->setHead($head->getModel());
        $request->setContent($content->getModel());

        $this->expectException(RuleSetException::class);
        $this->expectExceptionMessage('Secci Request does not allow transaction id');

        $request->toArray();
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
