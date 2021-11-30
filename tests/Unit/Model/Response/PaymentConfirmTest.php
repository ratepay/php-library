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
use RatePAY\Model\Response\PaymentConfirm;

class PaymentConfirmTest extends TestCase
{
    /** @dataProvider provideValidableResponses */
    public function testValidateResponse($statusCode, $resultCode, $expectedSuccess)
    {
        $xml = $this->getResponseXml($statusCode, $resultCode);

        $response = new PaymentConfirm($xml);
        $response->validateResponse();

        $this->assertEquals($expectedSuccess, $response->isSuccessful());
    }

    public function provideValidableResponses()
    {
        return [
            ['CLOSED', 200, false],
            ['UNKNOWN', 400, false],
            ['OK', 200, false],
            ['OK', 400, true],
        ];
    }

    /**
     * @return \SimpleXMLElement
     */
    protected function getResponseXml($statusCode, $resultCode)
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
        $content = str_replace('__RESULT_CODE__', $resultCode, $content);

        return new \SimpleXMLElement($content);
    }
}
