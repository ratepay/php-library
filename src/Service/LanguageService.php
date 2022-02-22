<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Service;

use RatePAY\Exception\LanguageException;

class LanguageService
{
    /**
     * Current language.
     *
     * @var string
     */
    private $language;

    /**
     * Translated messages.
     *
     * @var array
     */
    private $messages = [];

    /**
     * LanguageService constructor. Default language is German (deu). Austrian and Swiss-German will be set as German.
     *
     * @param string $language
     *
     * @throws LanguageException
     */
    public function __construct($language = 'EN')
    {
        $language = in_array(strtoupper($language), ['AT', 'CH']) ? 'DE' : $language;

        if (!$this->localeFileExists($language)) {
            $language = 'EN';
        }

        $this->language = strtoupper($language);
        $this->messages[$this->language] = $this->loadTranslatedMessages();
    }

    /**
     * Magic getter returns value to entered language key.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     *
     * @throws LanguageException
     */
    public function __call($name, $arguments)
    {
        $messages = $this->messages[$this->language];

        if (key_exists($name, $messages)) {
            return $messages[$name];
        } else {
            throw new LanguageException("No translation for '" . $name . "' available");
        }
    }

    /**
     * Returns all text blocks array (of current language).
     *
     * @return array
     */
    public function getArray()
    {
        return $this->messages[$this->language];
    }

    /**
     * Returns a boolean true if locale file for given language exists.
     *
     * @return bool
     */
    private function localeFileExists($language)
    {
        return file_exists($this->getLocaleFilePath($language));
    }

    /**
     * Load files with translated messages for frontend.
     *
     * @return array
     */
    private function loadTranslatedMessages()
    {
        return require $this->getLocaleFilePath($this->language);
    }

    /**
     * Returns complete path to requested locale file.
     *
     * @param string $language
     *
     * @return string $path
     */
    private function getLocaleFilePath($language)
    {
        $fileName = '/locales/' . strtolower($language) . '.php';

        return dirname(__DIR__) . $fileName;
    }
}
