<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace {
    $activateGlobalFunctionMocks = false;

    function should_call_mocked_global_functions()
    {
        global $activateGlobalFunctionMocks;
        return !!$activateGlobalFunctionMocks;
    }
}

namespace RatePAY\Service {
    use RatePAY\Tests\Unit\Service\CommunicationServiceTest;

    function curl_init()
    {
        if (!should_call_mocked_global_functions()) {
            return call_user_func_array('\curl_init', func_get_args());
        }

        CommunicationServiceTest::$curlDataMock = [];
        return CommunicationServiceTest::$curlDataMock;
    }

    function curl_setopt($handle, $option, $value)
    {
        if (!should_call_mocked_global_functions()) {
            return call_user_func_array('\curl_setopt', func_get_args());
        }

        $handle[$option] = $value;
    }

    function curl_exec()
    {
        if (!should_call_mocked_global_functions()) {
            return call_user_func_array('\curl_exec', func_get_args());
        }

        CommunicationServiceTest::incrementCurlExcecutions();
        return CommunicationServiceTest::curlShouldFail() ? false : "cURL Executed!";
    }

    function curl_errno()
    {
        if (!should_call_mocked_global_functions()) {
            return call_user_func_array('\curl_errno', func_get_args());
        }

        return CommunicationServiceTest::curlShouldFail() ? 2 : 0;
    }

    function curl_close()
    {
        if (!should_call_mocked_global_functions()) {
            return call_user_func_array('\curl_close', func_get_args());
        }

        return true;
    }

    function curl_strerror($errorCode)
    {
        if (!should_call_mocked_global_functions()) {
            return call_user_func_array('\curl_strerror', func_get_args());
        }

        $messages = [
            0 => "OK",
            1 => "Oh my gosh!",
            2 => "This request is blowing my mind!",
        ];

        return $messages[$errorCode];
    }

    function curl_getinfo($handle, $optionCode)
    {
        if (!should_call_mocked_global_functions()) {
            return call_user_func_array('\curl_getinfo', func_get_args());
        }

        if ($optionCode === CURLINFO_HTTP_CODE) {
            return CommunicationServiceTest::$forceHttpStatus;
        }

        return null;
    }
}

namespace RatePAY\Tests\Unit\Service {
    use \PHPUnit\Framework\TestCase;
    use RatePAY\Exception\CurlException;
    use \RatePAY\Service\CommunicationService;

    /**
     * @requires PHPUnit 7.5
     */
    class CommunicationServiceTest extends TestCase
    {
        public static $curlDataMock = [];
        public static $curlExecutions = 0;
        public static $expectedCurlFailures = 0;
        public static $forceHttpStatus = 200;

        /**
         * This method is called before the first test of this test class is run.
         */
        public static function setUpBeforeClass(): void
        {
            global $activateGlobalFunctionMocks;

            $activateGlobalFunctionMocks = true;
        }

        /**
         * This method is called after the last test of this test class is run.
         */
        public static function tearDownAfterClass(): void
        {
            global $activateGlobalFunctionMocks;

            $activateGlobalFunctionMocks = false;
        }

        public function setUp(): void
        {
            self::$curlDataMock = [];
            self::$curlExecutions = 0;
            self::$expectedCurlFailures = 0;
        }

        public function testSendCurlRequest()
        {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <note>
                  <to>Foo</to>
                  <from>Bar</from>
                  <heading>Reminder</heading>
                  <body>Don\'t forget me this weekend!</body>
                </note>';

            $service = new CommunicationService();
            $response = $service->send($xml);

            $this->assertEquals('cURL Executed!', $response);
        }

        public function testCurlRequestWillFail()
        {
            self::$expectedCurlFailures = 1;
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <note>
                  <to>Foo</to>
                  <from>Bar</from>
                  <heading>Reminder</heading>
                  <body>Don\'t forget me this weekend!</body>
                </note>';

            $this->expectException(CurlException::class);
            $this->expectExceptionMessage('This request is blowing my mind!');

            $service = new CommunicationService();
            $response = $service->send($xml);
        }

        public function testSendAndRetryCurl()
        {
            self::$expectedCurlFailures = 4;
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <note>
                  <to>Foo</to>
                  <from>Bar</from>
                  <heading>Reminder</heading>
                  <body>Don\'t forget me this weekend!</body>
                </note>';

            $service = new CommunicationService();
            $response = $service->send($xml, 0, 0, 6, 1);

            $this->assertEquals('cURL Executed!', $response);
        }

        public function testCurlRequestWillFailWithHttpError500()
        {
            self::$forceHttpStatus = 500;
            $xml = '<?xml version="1.0" encoding="UTF-8"?><note><to>Foo</to></note>';

            $this->expectException(CurlException::class);
            $this->expectExceptionMessage('There server answered with an invalid status code.');

            $service = new CommunicationService();
            $service->send($xml, 0, 0, 6, 1);
        }

        public static function incrementCurlExcecutions()
        {
            self::$curlExecutions++;
        }

        public static function curlShouldFail()
        {
            if (self::$expectedCurlFailures < 1) {
                return false;
            }

            return self::$expectedCurlFailures >= self::$curlExecutions;
        }
    }
}
