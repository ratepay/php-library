<?php

namespace RatePAY\Service;

use RatePAY\Exception\CurlException;

class CommunicationService
{

    /**
     * URL of RatePAY production gateway
     */
    const RATEPAY_PRODUCTION_GATEWAY_URL = "https://gateway.ratepay.com/api/xml/1_0";

    /**
     * URL of RatePAY integration gateway
     */
    const RATEPAY_INTEGRATION_GATEWAY_URL = "https://gateway-int.ratepay.com/api/xml/1_0";

    /**
     * Current URL of RatePAY gateway
     *
     * @var null|string
     */
    private $ratepayGatewayUrl = null;

    /**
     * CommunicationService constructor.
     * @param bool $sandbox
     */
    public function __construct($sandbox = false)
    {
        if (!$this->isCurl()) {
            throw new CurlException("Curl function not available");
        }

        if ($sandbox) {
            $this->ratepayGatewayUrl = self::RATEPAY_INTEGRATION_GATEWAY_URL;
        } else {
            $this->ratepayGatewayUrl = self::RATEPAY_PRODUCTION_GATEWAY_URL;
        }
    }

    /**
     * Executes the cURL request
     *
     * @param string $xml
     * @return string
     */
    public function send($xml)
    {
        // send XML over Post
        // the following code requires curl-class
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->ratepayGatewayUrl);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: text/xml; charset=UTF-8",
            "Accept: */*",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Connection: keep-alive"
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // use tls v1.2
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        // send to remote and return data to caller.

        // execute curl request
        $response = curl_exec($ch);

        curl_close($ch);
        // close connection

        return $response;
    }

    /**
     * Checks if cURL is enabled
     *
     * @return bool
     */
    private function isCurl ()
    {
        return function_exists('curl_init');
    }
}