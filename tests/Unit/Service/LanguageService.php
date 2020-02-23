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

namespace RatePAY\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use RatePAY\Exception\LanguageException;
use RatePAY\Service\LanguageService;

class LanguageServiceTest extends TestCase
{
    /** @dataProvider provideValidLanguages */
    public function testInitializeLanguageService($lang)
    {
        $service = new LanguageService($lang);

        $this->assertInstanceOf(LanguageService::class, $service);
    }

    public function provideValidLanguages()
    {
        return [
            ['DE'],
            ['de'],
            ['AU'],
            ['ch'],
        ];
    }

    public function testInitializeLanguageServiceWithDefaultLanguage()
    {
        $service = new LanguageService();

        $this->assertInstanceOf(LanguageService::class, $service);
    }

    public function testThrowsErrorOnUnknownLanguage()
    {
        $this->expectException(LanguageException::class);
        $this->expectExceptionMessage("No translation table for 'FR' available");

        $service = new LanguageService('FR');
    }

    /** @dataProvider provideTranslationMessages */
    public function testGetTranslatedMessage($key, $expectedMessage)
    {
        $service = new LanguageService();

        $message = $service->{$key}();

        $this->assertEquals($expectedMessage, $message);
    }

    public function provideTranslationMessages()
    {
        return [
            ['rp_calculate_rate', 'Rate berechnen'],
            ['rp_mouseover_service_charge', 'Bei Ratenzahlung pro Bestellung anfallende, einmalige Bearbeitungsgeb&uuml;hr.'],
            ['rp_calculator', 'Ratenrechner'],
        ];
    }

    public function testThrowsErrorOnUnknownMessageKey()
    {
        $service = new LanguageService();

        $this->expectException(LanguageException::class);
        $this->expectExceptionMessage("No translation for 'unknown_message_key' available");

        $message = $service->unknown_message_key();
    }
}
