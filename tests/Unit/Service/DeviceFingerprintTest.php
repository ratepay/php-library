<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
