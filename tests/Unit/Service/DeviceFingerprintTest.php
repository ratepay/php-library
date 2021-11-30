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

namespace RatePAY\Service {
    function microtime()
    {
        return 'foobarbaz';
    }
}

namespace RatePAY\Tests\Unit\Service {

    use PHPUnit\Framework\TestCase;
    use RatePAY\Service\DeviceFingerprint;

    /**
     * @requires PHPUnit 7.5
     */
    class DeviceFingerprintTest extends TestCase
    {
        public function testCreateDeviceIdentToken()
        {
            $hash = DeviceFingerprint::createDeviceIdentToken('hello-world-system');

            $expectedHash = md5('hello-world-system_foobarbaz');

            $this->assertEquals($expectedHash, $hash);
        }


        public function testGetDeviceIdentSnippet()
        {
            $deviceFingerprint = new DeviceFingerprint('hello-world-123');

            $snippet = $deviceFingerprint->getDeviceIdentSnippet('secure-token-123');

            $this->assertStringContainsString('//d.ratepay.com/di.css?t=secure-token-123&v=hello-world-123&l=Checkout', $snippet);
            $this->assertStringContainsString('{"t":"secure-token-123","v":"hello-world-123","l":"Checkout"}', $snippet);
            $this->assertStringContainsString('//d.ratepay.com/hello-world-123/di.js', $snippet);
        }
    }
}
