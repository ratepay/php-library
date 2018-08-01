<?php

namespace RatePAY\Tests\Unit\Frontend;


use PHPUnit\Framework\TestCase;
use RatePAY\Frontend\DeviceFingerprintBuilder;

class DeviceFingerPrintBuilderTest extends TestCase
{
    /**
     * @param $withSmarty
     * @param $expectedRegExp
     * @throws \RatePAY\Exception\FrontendException
     *
     * @dataProvider provideSnippetCodes
     */
    public function testGetsDfpSnippetCode($withSmarty, $expectedRegExp)
    {
        $builder = new DeviceFingerprintBuilder('test123', 'id-9876');

        $snippetCode = $builder->getDfpSnippetCode($withSmarty);

        //$this->assertEquals($expectedCode, $snippetCode);
        $this->assertRegExp($expectedRegExp, $snippetCode);
    }

    public function provideSnippetCodes()
    {
        return [
            [false, '/<script language\="JavaScript">var di = \{.*\}\;<\/script>/'],
            [true, '/<script language\="JavaScript">\{literal\}var di = \{.*\}\;\{\/literal\}<\/script>/'],
        ];
    }

    /**
     * @param $snippetId
     * @param $uniqueIdentifier
     * @param $exceptionMessage
     * @dataProvider provideExceptionMessages
     * @throws \RatePAY\Exception\FrontendException
     */
    public function testInitializationThrowException($snippetId, $uniqueIdentifier, $exceptionMessage)
    {
        $this->expectException('RatePAY\Exception\FrontendException');
        $this->expectExceptionMessage($exceptionMessage);

        $builder = new DeviceFingerprintBuilder($snippetId, $uniqueIdentifier);
    }

    public function provideExceptionMessages()
    {
        return [
            [null, 'test-123', 'DeviceFingerprintBuilder: Snippet id must be set'],
            ['test-123', null, 'DeviceFingerprintBuilder: Transaction identifier must be set'],
        ];
    }

    public function testTokenManipulation()
    {
        $builder = new DeviceFingerprintBuilder('test123', 'id-9876');

        $token = $builder->getToken();
        $this->assertNotEmpty($token);

        $modifiedToken = md5('hello-world'.microtime());
        $builder->setToken($modifiedToken);

        $this->assertNotEquals($token, $builder->getToken());
        $this->assertEquals($modifiedToken, $builder->getToken());
    }
}
