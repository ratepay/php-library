<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * Ratepay GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by Ratepay GmbH.
 * All rights reserved by Ratepay GmbH.
 *
 * Copyright (c) 2022 Ratepay GmbH / Berlin / Germany
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
