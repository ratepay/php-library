<?php

/*
 * RatePAY PHP-Library
 *
 * This document contains trade secret data which are the property of
 * RatePAY GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by RatePAY GmbH.
 * All rights reserved by RatePAY GmbH.
 *
 * Copyright (c) 2020 RatePAY GmbH / Berlin / Germany
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
