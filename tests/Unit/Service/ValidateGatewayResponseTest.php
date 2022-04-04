<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Service;

use PHPUnit\Framework\TestCase;
use RatePAY\Exception\RequestException;

class ValidateGatewayResponseTest extends TestCase
{

    /**
     * @requires PHPUnit 7.5
     */
    public function testIfExceptionWillThrownIfUnexpectedXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><note><to>Foo</to></note>';

        $this->expectException(RequestException::class);
        $this->expectExceptionMessageMatches('/An error occurred during the processing of the response from the gateway. Error message: (.*)/');

        new ValidateGatewayResponse('PaymentInit', new \SimpleXMLElement($xml));
    }
}
