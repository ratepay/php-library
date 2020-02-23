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

            $expectedSnippet = '<script language="JavaScript">var di = {"t":"secure-token-123","v":"hello-world-123","l":"Checkout"};</script><script type=\"text/javascript\" src=\"//d.ratepay.com/hello-world-123/di.js\"></script>
             <noscript><link rel=\"stylesheet\" type=\"text/css\" href=\"//d.ratepay.com/di.css?t=&v=hello-world-123&l=Checkout\"></noscript>';

            $this->assertEquals($expectedSnippet, $snippet);
        }
    }
}
