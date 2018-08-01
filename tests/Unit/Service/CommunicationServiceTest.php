<?php
/**
 * Created by PhpStorm.
 * User: eiriarte-mendez
 * Date: 11.06.18
 * Time: 09:33
 */
namespace RatePAY\Service {
    use RatePAY\Tests\Unit\Service\CommunicationServiceTest;

    function curl_init() {
        CommunicationServiceTest::$curlDataMock = [];

        return CommunicationServiceTest::$curlDataMock;
    }

    function curl_setopt($handle, $option, $value) {
        $handle[$option] = $value;
    }

    function curl_exec() {
        CommunicationServiceTest::incrementCurlExcecutions();
        return CommunicationServiceTest::curlShouldFail() ? false : "cURL Executed!";
    }

    function curl_errno() {
        return CommunicationServiceTest::curlShouldFail() ? 2 : 0;
    }

    function curl_close() {
        return true;
    }

    function curl_strerror($errorCode) {
        $messages = [
            0 => "OK",
            1 => "Oh my gosh!",
            2 => "This request is blowing my mind!",
        ];

        return $messages[$errorCode];
    }
}

namespace RatePAY\Tests\Unit\Service {
    use \PHPUnit\Framework\TestCase;
    use \RatePAY\Service\CommunicationService;

    class CommunicationServiceTest extends TestCase
    {
        static $curlDataMock = [];
        static $curlExecutions = 0;
        static $noOfCurlFailures = 0;

        public function setUp()
        {
            self::$curlDataMock = [];
            self::$curlExecutions = 0;
            self::$noOfCurlFailures = 0;
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
            self::$noOfCurlFailures = 1;
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <note>
                  <to>Foo</to>
                  <from>Bar</from>
                  <heading>Reminder</heading>
                  <body>Don\'t forget me this weekend!</body>
                </note>';

            $this->expectException('RatePAY\Exception\CurlException');
            $this->expectExceptionMessage('This request is blowing my mind!');

            $service = new CommunicationService();
            $response = $service->send($xml);
        }

        public function testSendAndRetryCurl()
        {
            self::$noOfCurlFailures = 4;
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <note>
                  <to>Foo</to>
                  <from>Bar</from>
                  <heading>Reminder</heading>
                  <body>Don\'t forget me this weekend!</body>
                </note>';

            $service = new CommunicationService();
            $response = $service->send($xml, 0, 0, 5, 0);

            $this->assertEquals('cURL Executed!', $response);
        }

        public static function incrementCurlExcecutions()
        {
            self::$curlExecutions++;
        }

        public static function curlShouldFail()
        {
            return self::$noOfCurlFailures > 0 ? (self::$curlExecutions >= self::$noOfCurlFailures): false;
        }
    }
}
