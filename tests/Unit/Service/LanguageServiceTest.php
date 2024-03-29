<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use RatePAY\Service\LanguageService;

class LanguageServiceTest extends TestCase
{
    /**
     * @param $language
     * @param $key
     * @param $expectedMessage
     *
     * @dataProvider provideTranslatedMessages
     */
    public function testGetTranslation($language, $key, $expectedMessage)
    {
        $service = new LanguageService($language);

        $message = $service->{$key}();

        $this->assertEquals($expectedMessage, $message);
    }

    public function provideTranslatedMessages()
    {
        return [
            ['DE', 'rp_mouseover_effective_rate', 'Gesamtkosten des Kredits als j&auml;hrlicher Prozentsatz.'],
            ['AT', 'rp_sepa_link', 'Einwilligungserkl&auml;rung zum SEPA-Mandat lesen'],
            ['CH', 'wcd_sepa_terms_block_3', 'Es gelten dabei die mit dem Kreditinstitut vereinbarten Bedingungen.'],
            ['EN', 'rp_sepa_link', 'Declaration of SEPA mandate'],
            // Unknown locale code will use english language by default:
            ['AU', 'wcd_sepa_terms_block_3', 'My rights are explained in a statement that I can obtain from my bank.'],
            ['FR', 'wcd_sepa_terms_block_3', 'My rights are explained in a statement that I can obtain from my bank.'],
        ];
    }

    public function testTranslationKeyNotFound()
    {
        $service = new LanguageService('DE');

        $this->expectException('RatePAY\Exception\LanguageException');
        $this->expectExceptionMessage('No translation for \'something_very_funny\' available');
        $message = $service->something_very_funny();
    }

    public function testGetMessageList()
    {
        $service = new LanguageService('DE');

        $list = $service->getArray();

        $this->assertNotEmpty($list);
        $this->assertArrayHasKey('rp_sepa_terms_block_2', $list);
        $this->assertArrayHasKey('rp_data_privacy_policy_url', $list);
        $this->assertArrayHasKey('wcd_sepa_terms_please_note', $list);
        $this->assertArrayHasKey('rp_calculation_intro_part1', $list);
    }
}
