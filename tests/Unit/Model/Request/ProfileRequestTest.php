<?php
/*
 * RatePAY php-library
 *
 * This document contains trade secret data which are the property of
 * RatePAY GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by RatePAY GmbH.
 * All rights reserved by RatePAY GmbH.
 *
 * Copyright (c) 2020 RatePAY GmbH / Berlin / Germany
 */

namespace RatePAY\Tests\Unit\Model\Request;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Request\ProfileRequest;
use RatePAY\ModelBuilder;

class ProfileRequestTest extends TestCase
{
    public function testRuleSucceed()
    {
        $head = new ModelBuilder();

        $request = new ProfileRequest();
        $request->setHead($head->getModel());

        $this->assertTrue($request->rule());
    }

    public function testRuleFailed()
    {
        $head = new ModelBuilder();
        $head->setTransactionId('foo-1234567890');

        $request = new ProfileRequest();
        $request->setHead($head->getModel());

        $this->assertFalse($request->rule());
        $this->assertEquals('Profile Request does not allow transaction id', $request->getErrorMsg());
    }
}
