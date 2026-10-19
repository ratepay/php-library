<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Response;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Response\SecciRequest;

class SecciRequestTest extends TestCase
{
    /** @dataProvider provideValidableResponses */
    public function testValidateResponse($statusCode, $resultCode, $expectedSuccess)
    {
        $xml = $this->getResponseXml($statusCode, $resultCode);

        $response = new SecciRequest($xml);
        $response->validateResponse();

        $this->assertEquals($expectedSuccess, $response->isSuccessful());
    }

    public function provideValidableResponses()
    {
        return [
            ['CLOSED', 200, false],
            ['UNKNOWN', 504, false],
            ['OK', 200, false],
            ['OK', 504, true],
        ];
    }

    public function testGetsSecciResult()
    {
        $xml = $this->getResponseXml('OK', 504);

        $response = new SecciRequest($xml);
        $response->validateResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('foo-attestation-token', $response->getAttestationToken());
        $this->assertEquals('SCE2QIAE-10092026', $response->getDocumentId());
        $this->assertNull($response->getDocument());
        $this->assertNull($response->getContentType());
    }

    public function testGetsSecciResultWithDocument()
    {
        $document = '<document>JVBERi0xLjQK</document>
                        <content-type>application/pdf</content-type>';
        $xml = $this->getResponseXml('OK', 504, $document);

        $response = new SecciRequest($xml);
        $response->validateResponse();

        $this->assertTrue($response->isSuccessful());
        // Document is delivered base64 encoded and returned decoded
        $this->assertSame("%PDF-1.4\n", $response->getDocument());
        $this->assertEquals('application/pdf', $response->getContentType());
    }

    /** @dataProvider provideEmptySecciResultFields */
    public function testIsNotSuccessfulOnEmptySecciResultField($field)
    {
        $xml = $this->getResponseXml('OK', 504);
        $xml->content->{'secci-result'}->{$field} = '  ';

        $response = new SecciRequest($xml);
        $response->validateResponse();

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getAttestationToken());
        $this->assertNull($response->getDocumentId());
    }

    public function provideEmptySecciResultFields()
    {
        return [
            ['attestation-token'],
            ['document-id'],
        ];
    }

    /** @dataProvider provideMissingSecciResultFields */
    public function testIsNotSuccessfulOnMissingSecciResultField($field)
    {
        $xml = $this->getResponseXml('OK', 504);
        unset($xml->content->{'secci-result'}->{$field});

        $response = new SecciRequest($xml);
        $response->validateResponse();

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getAttestationToken());
        $this->assertNull($response->getDocumentId());
    }

    public function provideMissingSecciResultFields()
    {
        return [
            ['attestation-token'],
            ['document-id'],
        ];
    }

    public function testIsNotSuccessfulOnMissingSecciResult()
    {
        $xml = $this->getResponseXml('OK', 504);
        unset($xml->content->{'secci-result'});

        $response = new SecciRequest($xml);
        $response->validateResponse();

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getAttestationToken());
        $this->assertNull($response->getDocumentId());
    }

    /** @dataProvider provideInvalidDocuments */
    public function testIsNotSuccessfulOnInvalidDocument($document)
    {
        $xml = $this->getResponseXml('OK', 504, $document);

        $response = new SecciRequest($xml);
        $response->validateResponse();

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getDocument());
        $this->assertNull($response->getContentType());
    }

    public function provideInvalidDocuments()
    {
        return [
            'empty document' => ['<document></document><content-type>application/pdf</content-type>'],
            'invalid base64' => ['<document>!!!no-base64!!!</document><content-type>application/pdf</content-type>'],
            'missing content type' => ['<document>JVBERi0xLjQK</document>'],
            'empty content type' => ['<document>JVBERi0xLjQK</document><content-type></content-type>'],
        ];
    }

    public function testGettersReturnNullIfResponseIsNotSuccessful()
    {
        $xml = $this->getResponseXml('CLOSED', 200);

        $response = new SecciRequest($xml);
        $response->validateResponse();

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getAttestationToken());
        $this->assertNull($response->getDocumentId());
        $this->assertNull($response->getDocument());
        $this->assertNull($response->getContentType());
    }

    /**
     * @return \SimpleXMLElement
     */
    protected function getResponseXml($statusCode, $resultCode, $document = '')
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>
            <response version="1.0" xmlns="urn://www.ratepay.com/payment/1_0">
                <head>
                    <system-id>Example</system-id>
                    <operation>SECCI_REQUEST</operation>
                    <response-type>SECCI_DOCUMENT</response-type>
                    <external />
                    <processing>
                        <timestamp>2026-09-10T11:03:58.000</timestamp>
                        <status code="__STATUS_CODE__">Successfully</status>
                        <reason code="__REASON_CODE__">SECCI document generated successfully</reason>
                        <result code="__RESULT_CODE__">SECCI generation successful</result>
                    </processing>
                </head>
                <content>
                    <secci-result>
                        <attestation-token>foo-attestation-token</attestation-token>
                        <document-id>SCE2QIAE-10092026</document-id>
                        __DOCUMENT__
                    </secci-result>
                </content>
            </response>';
        $content = str_replace('__STATUS_CODE__', $statusCode, $content);
        $content = str_replace('__REASON_CODE__', '308', $content);
        $content = str_replace('__RESULT_CODE__', $resultCode, $content);
        $content = str_replace('__DOCUMENT__', $document, $content);

        return new \SimpleXMLElement($content);
    }
}
