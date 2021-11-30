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

namespace RatePAY\Tests\Support;

use donatj\MockWebServer\MockWebServer;
use PHPUnit\Framework\TestCase;

abstract class IntegrationTestCase extends TestCase
{
    /** @var MockWebServer */
    protected static $gateway;

    /**
     * This method is called before the first test of this test class is run.
     */
    public static function setUpBeforeClass(): void
    {
        self::$gateway = new MockWebServer;
        self::$gateway->start();
    }

    /**
     * This method is called after the last test of this test class is run.
     */
    public static function tearDownAfterClass(): void
    {
        self::$gateway->stop();
    }
}
