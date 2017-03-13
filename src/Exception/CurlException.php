<?php

    namespace RatePAY\Exception;

    class CurlException extends ExceptionAbstract
    {

        /**
         * @param string $message
         */
        public function __construct($message)
        {
            parent::__construct("Curl exception : " . $message);
        }

    }