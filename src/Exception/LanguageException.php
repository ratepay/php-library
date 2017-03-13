<?php

    namespace RatePAY\Exception;

    class LanguageException extends ExceptionAbstract
    {

        /**
         * @param string $message
         */
        public function __construct($message)
        {
            parent::__construct("Language exception : " . $message);
        }

    }
