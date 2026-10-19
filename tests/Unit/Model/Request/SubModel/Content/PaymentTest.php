<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Request\SubModel\Content;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Request\SubModel\Content\Payment;
use RatePAY\ModelBuilder;

class PaymentTest extends TestCase
{
    public function testToArray()
    {
        $payment = (new Payment())
            ->setMethod('INVOICE')
            ->setAmount(100.00);

        $array = $payment->toArray();

        $expected = [
            'attributes' => [
                'method' => ['value' => 'INVOICE'],
            ],
            'amount' => ['value' => 100.00],
        ];

        $this->assertEquals($expected, $array);
    }

    public function testHandleByModelBuilder()
    {
        $builder = new ModelBuilder('Payment');
        $builder->setArray([
            'Method' => 'INVOICE',
            'Amount' => 100.00,
        ]);

        $array = $builder->getModel()->toArray();

        $this->assertEquals('INVOICE', $array['attributes']['method']['value']);
        $this->assertEquals(100.00, $array['amount']['value']);
    }

    public function testCurrencyIsNotAdmitted()
    {
        // Currency is delivered by ShoppingBasket, not by Payment
        $payment = new Payment();

        $this->assertArrayNotHasKey('Currency', $payment->admittedFields);
    }
}
