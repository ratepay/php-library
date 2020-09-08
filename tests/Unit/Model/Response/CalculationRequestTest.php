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

namespace RatePAY\Tests\Unit\Model\Response;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Response\CalculationRequest;

class CalculationRequestTest extends TestCase
{
    /** @dataProvider provideValidableResponses */
    public function testValidateResponse($statusCode, $resultCode, $reasonCode, $expectedSuccess)
    {
        $xml = $this->getResponseXml($statusCode, $resultCode, $reasonCode);

        $response = new CalculationRequest($xml);
        $response->validateResponse();

        $this->assertEquals($expectedSuccess, $response->isSuccessful());
    }

    public function provideValidableResponses()
    {
        return [
            ['CLOSED', 200, 999, false],
            ['UNKNOWN', 403, 699, false],
            ['OK', 200, 603, false],
            ['OK', 502, 999, false],
            ['OK', 502, 603, true],
            ['OK', 502, 671, true],
            ['OK', 502, 688, true],
            ['OK', 502, 689, true],
            ['OK', 502, 695, true],
            ['OK', 502, 696, true],
            ['OK', 502, 697, true],
            ['OK', 502, 698, true],
            ['OK', 502, 699, true],
        ];
    }

    /**
     * @return \SimpleXMLElement
     */
    protected function getResponseXml($statusCode, $resultCode, $reasonCode)
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>
            <response version="1.0" xmlns="urn://www.ratepay.com/payment/1_0">
                <head>
                    <system-id>foo-bar</system-id>
                    <operation />
                    <response-type />
                    <external />
                    <transaction-id>foo-1234567890</transaction-id>
                    <processing>
                        <timestamp />
                        <status code="__STATUS_CODE__">Foo</status>
                        <reason code="__REASON_CODE__">Bar</reason>
                        <result code="__RESULT_CODE__">Baz</result>
                    </processing>
                </head>
                <content />
            </response>';
        $content = str_replace('__STATUS_CODE__', $statusCode, $content);
        $content = str_replace('__REASON_CODE__', $reasonCode, $content);
        $content = str_replace('__RESULT_CODE__', $resultCode, $content);

        return new \SimpleXMLElement($content);
    }
}
