<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Frontend;

use RatePAY\Exception\LanguageException;
use RatePAY\Service\LanguageService;
use RatePAY\Service\Util;

class DirectDebitBuilder
{
    /**
     * Language object contains translation text blocks.
     *
     * @var LanguageService
     */
    private $lang;

    /**
     * DirectDebitBuilder constructor.
     *
     * @param string $language
     *
     * @throws LanguageException
     */
    public function __construct($language = 'DE')
    {
        $this->lang = new LanguageService($language);
    }

    /**
     * Sets current language.
     *
     * @param $language
     *
     * @throws LanguageException
     */
    public function setLanguage($language)
    {
        $this->lang = new LanguageService($language);
    }

    /**
     * Returns processed html template.
     *
     * @param string $template
     *
     * @return string
     */
    public function getSepaFormByTemplate($template)
    {
        return Util::templateReplace($template, $this->lang->getArray());
    }
}
