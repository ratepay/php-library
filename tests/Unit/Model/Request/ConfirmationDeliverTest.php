<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Request;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Request\ConfirmationDeliver;
use RatePAY\ModelBuilder;

class ConfirmationDeliverTest extends TestCase
{
    public function testRuleSucceed()
    {
        $head = new ModelBuilder();
        $head->setTransactionId('foo-1234567890');

        $request = new ConfirmationDeliver();
        $request->setHead($head->getModel());

        $this->assertTrue($request->rule());
    }

    public function testRuleFailed()
    {
        $head = new ModelBuilder();

        $request = new ConfirmationDeliver();
        $request->setHead($head->getModel());

        $this->assertFalse($request->rule());
        $this->assertEquals('Confirmation Deliver expects transaction id', $request->getErrorMsg());
    }
}
