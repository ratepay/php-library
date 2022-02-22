<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Request;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Request\CalculationRequest;
use RatePAY\ModelBuilder;

class CalculationRequestTest extends TestCase
{
    public function testInitializationSetsSubtype()
    {
        $request = new CalculationRequest();

        $this->assertTrue($request->isSubtypeRequired());
        $this->assertEquals(['calculation-by-rate', 'calculation-by-time'], $request->getAdmittedSubtypes());
    }

    public function testRuleSucceed()
    {
        $head = new ModelBuilder();

        $request = new CalculationRequest();
        $request->setHead($head->getModel());

        $this->assertTrue($request->rule());
    }

    public function testRuleFailed()
    {
        $head = new ModelBuilder();
        $head->setTransactionId('foo-1234567890');

        $request = new CalculationRequest();
        $request->setHead($head->getModel());

        $this->assertFalse($request->rule());
        $this->assertEquals('Calculation Request does not allow transaction id', $request->getErrorMsg());
    }
}
