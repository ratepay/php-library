<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Support;

use donatj\MockWebServer\Response;

trait GatewayResponses
{
    public function getPaymentChangeResponse($params = [])
    {
        $bindings = array_merge(
            [
                'system-id' => 'FooSystem',
                'operation' => 'PaymentChange',
                'subtype' => 'credit',
                'transaction-id' => '00-1234567890',
                'status-code' => 'OK',
                'reason-code' => '100',
                'result-code' => '403',
            ],
            $params
        );

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <response version="1.0" xmlns="urn://www.ratepay.com/payment/1_0">
                <head>
                    <system-id>{{ system-id }}</system-id>
                    <operation>{{ operation }}</operation>#
                    <subtype>{{ operation }}</subtype>
                    <response-type />
                    <external />
                    <transaction-id>{{ transaction-id }}</transaction-id>
                    <processing>
                        <timestamp />
                        <status code="{{ status-code }}" />
                        <reason code="{{ reason-code }}" />
                        <result code="{{ result-code }}" />
                    </processing>
                </head>
                <content />
            </response>';

        return new Response($this->processXmlTemplate($xml, $bindings));
    }

    public function getPaymentInitResponse($params = [])
    {
        $bindings = array_merge(
            [
                'system-id' => 'FooSystem',
                'operation' => 'PaymentInit',
                'transaction-id' => '00-1234567890',
                'status-code' => 'OK',
                'reason-code' => '100',
                'result-code' => '350',
            ],
            $params
        );

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <response version="1.0" xmlns="urn://www.ratepay.com/payment/1_0">
                <head>
                    <system-id>{{ system-id }}</system-id>
                    <operation>{{ operation }}</operation>
                    <response-type />
                    <external />
                    <transaction-id>{{ transaction-id }}</transaction-id>
                    <processing>
                        <timestamp />
                        <status code="{{ status-code }}" />
                        <reason code="{{ reason-code }}" />
                        <result code="{{ result-code }}" />
                    </processing>
                </head>
                <content />
            </response>';

        return new Response($this->processXmlTemplate($xml, $bindings));
    }

    public function getPaymentRequestResponse($params = [])
    {
        $bindings = array_merge(
            [
                'system-id' => 'FooSystem',
                'customer-message' => 'im a customer!',
                'transaction-id' => '00-1234567890',
                'status-code' => 'OK',
                'reason-code' => '100',
                'result-code' => '402',
            ],
            $params
        );

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <response version="1.0" xmlns="urn://www.ratepay.com/payment/1_0">
                <head>
                    <system-id>{{ system-id }}</system-id>
                    <operation>PaymentRequest</operation>
                    <response-type />
                    <external />
                    <transaction-id>{{ transaction-id }}</transaction-id>
                    <processing>
                        <timestamp />
                        <customer-message>{{ customer-message }}</customer-message>
                        <status code="{{ status-code }}" />
                        <reason code="{{ reason-code }}" />
                        <result code="{{ result-code }}" />
                    </processing>
                </head>
                <content>
                    <customer>
                        <addresses/>
                    </customer>
                </content>
            </response>';

        return new Response($this->processXmlTemplate($xml, $bindings));
    }

    public function getAutoConfirmationDeliveryResponse($params = [])
    {
        $bindings = array_merge(
            [
                'system-id' => 'FooSystem',
                'status-code' => 'OK',
                'reason-code' => '100',
                'result-code' => '404',
            ],
            $params
        );

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <response version="1.0" xmlns="urn://www.ratepay.com/payment/1_0">
                <head>
                    <system-id>{{ system-id }}</system-id>
                    <operation>ConfirmationDelivery</operation>
                    <response-type />
                    <external />
                    <transaction-id>{{ transaction-id }}</transaction-id>
                    <processing>
                        <timestamp />
                        <status code="{{ status-code }}" />
                        <reason code="{{ reason-code }}" />
                        <result code="{{ result-code }}" />
                    </processing>
                </head>
                <content />
            </response>';

        return new Response($this->processXmlTemplate($xml, $bindings));
    }

    /**
     * @param string $template
     * @param array $bindings
     * @return string
     */
    private function processXmlTemplate($template, $bindings)
    {
        $processed = $template;
        foreach ($bindings as $key => $value) {
            $processed = str_replace("{{ $key }}", $value, $processed);
        }

        return $processed;
    }
}
