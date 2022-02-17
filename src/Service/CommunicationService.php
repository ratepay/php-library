<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * Ratepay GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by Ratepay GmbH.
 * All rights reserved by Ratepay GmbH.
 *
 * Copyright (c) 2019 Ratepay GmbH / Berlin / Germany
 */

namespace RatePAY\Service;

use RatePAY\Exception\CurlException;

class CommunicationService
{
    /**
     * URL of RatePAY production gateway.
     */
    const RATEPAY_PRODUCTION_GATEWAY_URL = 'https://gateway.ratepay.com/api/xml/1_0';

    /**
     * URL of RatePAY integration gateway.
     */
    const RATEPAY_INTEGRATION_GATEWAY_URL = 'https://gateway-int.ratepay.com/api/xml/1_0';

    /**
     * Current URL of RatePAY gateway.
     *
     * @var string|null
     */
    private $ratepayGatewayUrl = null;

    /**
     * CommunicationService constructor.
     *
     * @param bool $sandbox
     * @param null $gatewayUrl
     *
     * @throws CurlException
     */
    public function __construct($sandbox = false, $gatewayUrl = null)
    {
        if (!$this->isCurl()) {
            throw new CurlException('Curl function not available');
        }

        $this->ratepayGatewayUrl = empty($gatewayUrl) ? $this->getStandardRatepayGateway($sandbox) : $gatewayUrl;
    }

    /**
     * Executes the cURL request.
     *
     * @param string $xml
     *
     * @return string
     *
     * @throws CurlException
     */
    public function send($xml, $connectionTimeout = 0, $executionTimeout = 0, $retries = 0, $retryDelay = 0)
    {
        // send XML over Post
        // the following code requires curl-class
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->ratepayGatewayUrl);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: text/xml; charset=UTF-8',
            'Accept: */*',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            'Connection: keep-alive',
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // the number of milliseconds to wait while trying to connect
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $connectionTimeout);
        // the maximum number of milliseconds to allow cURL functions to execute
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $executionTimeout);
        // use tls v1.2
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        // send to remote and return data to caller.

        // execute curl request
        $response = curl_exec($ch);

        $errno = curl_errno($ch);

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // close connection
        curl_close($ch);

        // status codes 3xx and 5xx are not processable by the SDK (we assume that the body will be invalid too)
        $invalidStatusCode = $statusCode < 200 || ($statusCode >= 300 && ($statusCode < 400 || $statusCode >= 500));

        if ($invalidStatusCode || $response === false) {
            if ($retries > 0) {
                if ($retryDelay > 0) {
                    // halt time in milliseconds (entered microseconds * 1000)
                    usleep((int) $retryDelay * 1000);
                }

                return $this->send($xml, $connectionTimeout, $executionTimeout, $retries - 1, $retryDelay);
            }

            if ($errno > 0) {
                throw new CurlException(curl_strerror($errno));
            }

            if ($invalidStatusCode) {
                throw new CurlException('There server answered with an invalid status code.');
            }
        }

        return $response;
    }

    /**
     * Checks if cURL is enabled.
     *
     * @return bool
     */
    private function isCurl()
    {
        return function_exists('curl_init');
    }

    /**
     * Gets the standard Gateway URL-Address.
     *
     * @param bool $sandbox
     *
     * @return string
     */
    private function getStandardRatepayGateway($sandbox)
    {
        return ((bool) $sandbox) ? self::RATEPAY_INTEGRATION_GATEWAY_URL : self::RATEPAY_PRODUCTION_GATEWAY_URL;
    }
}
