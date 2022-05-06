<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Response;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Response\ConfigurationRequest;

class ConfigurationRequestTest extends TestCase
{
    /** @dataProvider provideAllowedMonths */
    public function testGetAllowedMonths($amount, $expectedAllowedMonths)
    {
        $response = new ConfigurationRequest();
        $response->setResult([
            'monthAllowed' => [3, 6, 9, 12, 18, 24, 36],
            'rateMinNormal' => 20.0,
            'interestRateDefault' => 13.7,
        ]);

        $allowedMonths = $response->getAllowedMonths($amount);

        $this->assertEquals($expectedAllowedMonths, $allowedMonths);
    }

    public function provideAllowedMonths()
    {
        return [
            [0, [3, 6, 9, 12, 18, 24, 36]],
            [10, []],
            [60, [3]],
            [70, [3]],
            [120, [3, 6]],
            [150, [3, 6]],
        ];
    }

    public function testGetMinRate()
    {
        $response = new ConfigurationRequest();
        $response->setResult(['rateMinNormal' => 20.0]);

        $minRate = $response->getMinRate();

        $this->assertEquals(20, $minRate);
    }

    /** @dataProvider provideMaxRates */
    public function testGetMaxRate($amount, $expectedMaxRate)
    {
        $response = new ConfigurationRequest();
        $response->setResult([
            'monthAllowed' => [3, 6, 9, 12, 18, 24, 36],
        ]);

        $maxRate = $response->getMaxRate($amount);

        $this->assertEquals($expectedMaxRate, $maxRate);
    }

    public function provideMaxRates()
    {
        return [
            [0, 0],
            [60, 20],
            [100, 34],
            [500, 167],
        ];
    }

    public function testGetValidPaymentFirstdays()
    {
        $response = new ConfigurationRequest();
        $response->setResult(['validPaymentFirstdays' => [1, 28]]);

        $firstDays = $response->getValidPaymentFirstdays();

        $this->assertEquals([1, 28], $firstDays);
    }

    /** @dataProvider provideValidableResponses */
    public function testValidateResponse($statusCode, $resultCode, $expectedSuccess)
    {
        $xml = $this->getResponseXml($statusCode, $resultCode);

        $response = new ConfigurationRequest($xml);
        $response->validateResponse();

        $this->assertEquals($expectedSuccess, $response->isSuccessful());
    }

    public function provideValidableResponses()
    {
        return [
            ['CLOSED', 200, false],
            ['UNKNOWN', 500, false],
            ['OK', 200, false],
            ['OK', 500, true],
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
                    <processing>
                        <timestamp />
                        <status code="__STATUS_CODE__">Foo</status>
                        <reason code="__REASON_CODE__">Bar</reason>
                        <result code="__RESULT_CODE__">Baz</result>
                    </processing>
                </head>
                <content>
                    <installment-configuration-result>
                        <interestrate-min />
                        <interestrate-default />
                        <interestrate-max />
                        <interest-rate-merchant-towards-bank />
                        <month-number-min />
                        <month-number-max />
                        <month-longrun />
                        <amount-min-longrun />
                        <month-allowed />
                        <valid-payment-firstdays />
                        <payment-firstday />
                        <payment-amount />
                        <payment-lastrate />
                        <rate-min-normal />
                        <rate-min-longrun />
                        <service-charge />
                        <min-difference-dueday />
                    </installment-configuration-result>
                </content>
            </response>';
        $content = str_replace('__STATUS_CODE__', $statusCode, $content);
        $content = str_replace('__RESULT_CODE__', $resultCode, $content);

        return new \SimpleXMLElement($content);
    }
}
