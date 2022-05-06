<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Request;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Request\PaymentQuery;
use RatePAY\ModelBuilder;

class PaymentQueryTest extends TestCase
{
    public function testInitializationSetsSubtype()
    {
        $request = new PaymentQuery();

        $this->assertTrue($request->isSubtypeRequired());
        $this->assertEquals(['full'], $request->getAdmittedSubtypes());
    }

    public function testRuleSucceed()
    {
        $head = new ModelBuilder();
        $head->setTransactionId('foo-1234567890');

        $request = new PaymentQuery();
        $request->setHead($head->getModel());

        $this->assertTrue($request->rule());
    }

    public function testRuleFailed()
    {
        $head = new ModelBuilder();

        $request = new PaymentQuery();
        $request->setHead($head->getModel());

        $this->assertFalse($request->rule());
        $this->assertEquals('Payment Query expects transaction id', $request->getErrorMsg());
    }
}
