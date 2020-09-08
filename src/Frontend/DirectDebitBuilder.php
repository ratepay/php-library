<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * Ratepay GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by Ratepay GmbH.
 * All rights reserved by Ratepay GmbH.
 *
 * Copyright (c) 2019 Ratepay GmbH / Berlin / Germany
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
