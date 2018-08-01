<?php

namespace RatePAY\Frontend;

use RatePAY\Service\Util;
use RatePAY\Service\LanguageService;

class DirectDebitBuilder
{

    /**
     * Language object contains translation text blocks
     *
     * @var LanguageService
     */
    private $lang;

    /**
     * DirectDebitBuilder constructor.
     * @param string $language
     * @throws \RatePAY\Exception\LanguageException
     */
    public function __construct($language = "DE")
    {
        $this->lang = new LanguageService($language);
    }

    /**
     * Sets current language
     *
     * @param $language
     * @throws \RatePAY\Exception\LanguageException
     */
    public function setLanguage($language)
    {
        $this->lang = new LanguageService($language);
    }

    /**
     * Returns processed html template
     *
     * @param string $template
     * @return string
     */
    public function getSepaFormByTemplate($template)
    {
        return Util::templateReplace($template, $this->lang->getArray());
    }

}
